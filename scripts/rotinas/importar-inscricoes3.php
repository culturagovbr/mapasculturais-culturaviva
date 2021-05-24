<?php

require_once __DIR__ . '/../../../../../../protected/application/bootstrap.php';

// Remove timeout de execução do script
set_time_limit(-1);

/**
 * Rotina para importação das inscrições cadastradas
 *
 * O fluxo de certificação se inicia quando a rotina de importação de inscrições é acionada, esta tem
 * a responsabilidade de verificar quais inscrições de pontos de cultura estão concluídas e ainda não estão
 * participando do processo de certificação (novos cadastros).
 *
 * A rotina irá criar o registro para cada inscrição (culturaviva.inscricao) atribuindo o estado "[P] Pendente".
 *
 * Neste momento, deve registrar também os critérios de avaliação da inscrição (culturaviva.inscricao_criterio).
 *
 * Estados da inscrição
 *
 * P - Pendente
 * C - Certificado
 * N - Não Certificado
 * R - Re Submissão - Inscrição rejeitada pelos certificadores, cadastro alterado pelo Ponto de Cultura e nova Inscrição criada para reavaliação
 *
 *
 * ALGORITMO
 *
 * 1 - Buscar todos os cadastros finalizados que não possui inscrição com estado [P], [C] OU [R]
 * 2 - Para cada registro:
 *  2.1 - Criar registro de inscrição culturaviva.inscricao
 *  2.2 - Registrar os critérios da avaliação (culturaviva.inscricao_criterio)
 */
function loadScript($file)
{
    set_time_limit(-1);
    return file_get_contents(__DIR__ . "/importar/$file");
}

function importar()
{
    set_time_limit(-1);
    $app = MapasCulturais\App::i();
    $conn = $app->em->getConnection();

    // 1º Passo: DEFERIMENTO E INDEFERIMENTO DE INSCRIÇÕES (CERTIFICAÇÃO)
    print("Atualizando inscrições avaliadas\n");
    $conn->exec(loadScript('8-atualizar-inscricoes-avaliadas.sql'));

    print("Atualizando inscrições certificadas\n");

    // Marca agentes como verificados
    $agent_id = $app->config['rcv.admin'];
    $seal_id = $conn->fetchColumn("SELECT id FROM seal WHERE agent_id = $agent_id and name = 'Ponto de Cultura'");

    $conn->executeQuery("
    INSERT INTO agent_meta (id,object_id,key,value)
    SELECT
        nextval('agent_meta_id_seq'),
        ponto.id,
        'homologado_rcv',
        1
    FROM culturaviva.inscricao insc
    JOIN registration reg
        ON reg.agent_id = insc.agente_id
        AND reg.opportunity_id = 1
        AND reg.status = 10
    JOIN agent_relation rel_ponto
        ON rel_ponto.object_id = reg.id
        AND rel_ponto.type = 'ponto'
        AND rel_ponto.object_type = 'MapasCulturais\Entities\Registration'
    JOIN agent ponto
        ON ponto.id = rel_ponto.agent_id
    WHERE insc.estado = 'C'
        AND not exists (
            SELECT
                    *
            FROM seal_relation
            WHERE seal_id = $seal_id
            AND agent_id = ponto.id
        )
        AND not exists (
            SELECT
                    *
            FROM agent_meta am
            JOIN agent a
                ON a.id = am.object_id
                AND key = 'homologado_rcv'
            WHERE a.user_id = ponto.user_id
        )
    ");

    $conn->executeQuery("
        INSERT INTO seal_relation
        SELECT
            nextval('seal_relation_id_seq'),
            $seal_id,
            a.id,
            CURRENT_TIMESTAMP,
            1,
            'MapasCulturais\Entities\Agent',
            $agent_id,
            $agent_id,
            CURRENT_TIMESTAMP
        FROM agent a
        JOIN agent_meta am
            ON am.object_id = a.id
            AND am.key = 'rcv_tipo'
            AND am.value = 'ponto'
        WHERE EXISTS (
                SELECT * FROM agent_meta
                WHERE object_id = am.object_id
                AND key = 'homologado_rcv'
            ) AND
            NOT EXISTS (
                SELECT * FROM seal_relation
                WHERE object_id = a.id
                AND seal_id = $seal_id
        )");


    // 2º Passo: REGISTRO DE INSCRIÇÕES
    print("Registra as inscricoes dos pontos de cultura\n");
    $conn->executeQuery("INSERT INTO culturaviva.inscricao(agente_id, estado)
                                SELECT
                                    r.agent_id,
                                    'P'
                                FROM registration r
                                LEFT JOIN culturaviva.inscricao insc
                                    ON insc.agente_id = r.agent_id
                                WHERE r.opportunity_id = 1
                                AND r.status = 1
                                AND insc.id IS NULL
                                AND (insc.estado = 'P' OR insc.estado is null);");

    print("Registra as ressubmissões dos pontos de cultura\n");
    $conn->executeQuery(loadScript('11-registrar-ressubmissoes.sql'));

    print("Remover critérios inativos de inscrições não finalizadas\n");
    $conn->executeQuery(loadScript('2-remover-criterios-inscricoes_A.sql'));
    $conn->executeQuery(loadScript('2-remover-criterios-inscricoes_B.sql'));

    print("Registrar os critérios das inscrições\n");
    $conn->executeQuery("INSERT INTO culturaviva.inscricao_criterio (criterio_id, inscricao_id)
                                SELECT
                                        crit.id,
                                        insc.id
                                FROM culturaviva.inscricao insc
                                JOIN culturaviva.criterio crit
                                    ON  crit.ativo = TRUE
                                LEFT JOIN culturaviva.inscricao_criterio incrit
                                    ON incrit.inscricao_id = insc.id
                                    AND incrit.criterio_id = crit.id
                                WHERE insc.estado = ANY(ARRAY['P'::text, 'R'::text])
                                AND incrit.inscricao_id IS NULL;");


    // 3º Passo: DISTRIBUIR AVALIAÇÕES
    print("Remover avaliações avaliadores inativos:\n");
    $conn->executeQuery("UPDATE culturaviva.avaliacao SET estado = 'C'
                                WHERE certificador_id IN (
                                    SELECT
                                        id
                                    FROM culturaviva.certificador cert
                                    WHERE cert.ativo = FALSE OR cert.titular = FALSE
                                )
                                AND culturaviva.avaliacao.estado = ANY (ARRAY['P'::text, 'A'::text])");

    //Procura estados selecionados para redistribuição
    $ufs = $conn->fetchAll("SELECT * FROM culturaviva.uf;");

    foreach ($ufs as $uf) {
        if ($uf['redistribuicao'] === true) {
            print("Distribuir avaliações para Representantes da Sociedade Civil Estadual\n");
            inserirAvaliacaoCertificador($conn, ['tipo' => 'S'], $uf);

            print("Distribuir avaliações para Representantes do Poder Público Estadual\n");
            inserirAvaliacaoCertificador($conn, ['tipo' => 'E'], $uf);
//
//            if ($uf['redistribuicao'] == true) {
//                print("Distribuir avaliações para Representantes do Poder Civil Federal\n");
//                inserirAvaliacaoCertificador($conn, ['tipo' => 'C'], $uf);
//
//                print("Distribuir avaliações para Representantes do Poder Público Federal\n");
//                inserirAvaliacaoCertificador($conn, ['tipo' => 'P'], $uf);
//            }
        }
    }

    // 4º Passo: DISTRIBUIR VOTOS DE MINERVA
    print("Distribuir avaliações para Certificadores com Voto de Minerva\n");
    inserirAvaliacaoMinerva($conn);


    print("Notificando via e-mail as entidades com inscrições finalizadas (Deferidas e Indeferidas)\n");
    notificarCertificacoesDeferidas($app, $conn);
    notificarCertificacoesIndeferidas($app, $conn);
    print_r($conn);
    print_r($app);
}

/**
 * Associa avaliações para certificadores da sociedade civil para inscrições que ainda não possuem
 *
 * @param type $conn
 * @param type $filtro
 * @return type
 */
function inserirAvaliacaoCertificador($conn, $filtro, $uf)
{
    set_time_limit(-1);
    $filtro['uf'] = $uf['sigla'];

    $inscricoes = $conn->fetchAll("SELECT
                                        insc.id
                                    FROM culturaviva.inscricao insc
                                    LEFT JOIN agent agente ON agente.id = insc.agente_id
                                    LEFT JOIN usr usuario ON usuario.id = agente.user_id
                                    LEFT JOIN registration reg
                                        on reg.agent_id = insc.agente_id
                                        AND reg.opportunity_id = 1
                                        AND reg.status = 1
                                    LEFT JOIN agent_relation rel_entidade
                                        ON rel_entidade.object_id = reg.id
                                        AND rel_entidade.type = 'entidade'
                                        AND rel_entidade.object_type = 'MapasCulturais\Entities\Registration'
                                    LEFT JOIN agent_relation rel_ponto
                                        ON rel_ponto.object_id = reg.id
                                        AND rel_ponto.type = 'ponto'
                                        AND rel_ponto.object_type = 'MapasCulturais\Entities\Registration'
                                    LEFT JOIN agent entidade ON entidade.id = rel_entidade.agent_id
                                    LEFT JOIN agent_meta ent_meta_uf
                                        ON  ent_meta_uf.object_id = entidade.id
                                        AND ent_meta_uf.key = 'En_Estado'
                                    WHERE insc.estado = ANY(ARRAY['P','R'])
                                      AND ent_meta_uf.value = :uf
                                    AND (
                                        not exists (
                                            SELECT aval.id
                                            FROM culturaviva.avaliacao aval
                                            JOIN culturaviva.certificador cert
                                                    on cert.id = aval.certificador_id
                                                    AND cert.tipo = :tipo
                                            WHERE aval.estado <> 'C'
                                            AND aval.inscricao_id = insc.id
                                        )
                                        OR exists (
                                            SELECT aval.id
                                            FROM culturaviva.avaliacao aval
                                            JOIN culturaviva.certificador cert
                                                    on cert.id = aval.certificador_id
                                                    AND cert.tipo = :tipo
                                            WHERE aval.estado = 'P'
                                            AND aval.inscricao_id = insc.id
                                        )
                                    )
                                    ORDER BY insc.id", $filtro);
    if (!isset($inscricoes) || empty($inscricoes)) {
        // Nao existem INSCRICOES para distribuir
        return;
    }

    $certificadores = $conn->fetchAll("SELECT
                                            *
                                        FROM culturaviva.certificador cert
                                        WHERE cert.ativo = TRUE
                                        AND cert.titular = TRUE
                                        AND cert.tipo = :tipo
                                        AND cert.uf = :uf
                                        ORDER BY cert.id", $filtro);
    if (!isset($certificadores) || empty($certificadores)) {
        // Nao existem AVALIADORES para o tipo
        return;
    }

    $totalCertificadores = count($certificadores);

    // Relação para novos certificadores e inscrições
    $certInscric = [];
    foreach ($inscricoes as $index => $inscricao) {

        $idx = $index % $totalCertificadores;
        if (!isset($certInscric[$idx])) {
            $certInscric[$idx] = [];
        }
        array_push($certInscric[$idx], $inscricao['id']);
    }
    foreach ($certificadores as $index => $certificador) {
        $idCertificador = $certificador['id'];
        if (!isset($certInscric[$index])) {
            continue;
        }

        foreach ($certInscric[$index] as $idInscricao) {

            // Já possui avaliação deste certificador com o perfil informado para a inscrição?
            $existe = $conn->fetchColumn(
                "SELECT count(0)
                    FROM culturaviva.avaliacao aval
                    JOIN culturaviva.certificador cert
                        ON cert.id = aval.certificador_id
                        AND cert.tipo = '{$filtro['tipo']}'
                    WHERE aval.estado <> 'C'
                    AND aval.inscricao_id =  ?
                    AND aval.certificador_id = ?
                    ", [$idInscricao, $idCertificador]);
            if ($existe > 0) {
                continue;
            }

            // Cancela as avaliações atuais associados a outro certificador
            $conn->executeQuery(
                "UPDATE culturaviva.avaliacao SET estado = 'C'
                    WHERE id IN(
                        SELECT aval.id
                        FROM culturaviva.avaliacao aval
                        JOIN culturaviva.certificador cert
                            ON cert.id = aval.certificador_id
                            AND cert.tipo = '{$filtro['tipo']}'
                        WHERE aval.estado <> 'C'
                        AND aval.inscricao_id = ?
                    )
                    ", [$idInscricao]);
            var_dump($conn);
            die();


            // Registra avaliação com o certificador atual
            $conn->executeQuery(
                "INSERT INTO culturaviva.avaliacao (inscricao_id, certificador_id, estado)
                    SELECT $idInscricao, $idCertificador, 'P'");
        }
    }
}

/**
 * @param type $conn
 * @return type
 * @todo Executar mesmo processo anterior
 *
 */
function inserirAvaliacaoMinerva($conn)
{
    set_time_limit(-1);
    $inscricoes = $conn->fetchAll(loadScript('7-obter-inscricoes-avaliacoes-conflitantes.sql'));
    if (!isset($inscricoes) || empty($inscricoes)) {
        // Nao existem INSCRICOES para distribuir
        return;
    }

    $certificadores = $conn->fetchAll(loadScript('6-obter-certificadores-por-tipo.sql'), ['tipo' => 'M']);
    if (!isset($certificadores) || empty($certificadores)) {
        // Nao existem AVALIADORES para o tipo
        return;
    }

    $inscricao = current($inscricoes);
    while (true) {
        if ($inscricao === false) {
            break;
        }
        foreach ($certificadores as $certificador) {

            $conn->executeQuery("INSERT INTO culturaviva.avaliacao (inscricao_id, certificador_id, estado) VALUES (?, ?, ?)", [
                $inscricao['id'],
                $certificador['id'],
                'P'
            ]);

            $inscricao = next($inscricoes);
            if ($inscricao === false) {
                break;
            }
        }
    }
}

function notificarCertificacoesDeferidas($app, $conn)
{
    set_time_limit(-1);
    print("Notificando via e-mail as inscrições Deferidas\n");

    $registros = $conn->fetchAll(loadScript('9-obter-inscricoes-certificadas.sql'));
    if (!isset($registros) || empty($registros)) {
        // Nao existem INSCRICOES para distribuir
        return;
    }

    $registro = current($registros);
    while (true) {
        if ($registro === false) {
            break;
        }

        try {
            $json = json_decode($registro['agents_data']);
            $emailEntidade = $json->entidade->emailPrivado;
            $emailResponsavel = $json->owner->emailPrivado;

            $message = $app->renderMailerTemplate('certificacao_deferido', [
                'name' => 'x'
            ]);
            $dadosEmail = [
                'from' => $app->config['mailer.from'],
                'to' => $emailResponsavel,
                'cc' => $emailEntidade,
                'subject' => $message['title'],
                'body' => $message['body']
            ];
            $app->createAndSendMailMessage($dadosEmail);
        } catch (Exception $ex) {
            // faz nada
            print($ex);
        }

        $registro = next($registros);
        if ($registro === false) {
            break;
        }
    }
}

function notificarCertificacoesIndeferidas($app, $conn)
{
    set_time_limit(-1);
    print("Notificando via e-mail as inscrições Indeferidas\n");

    $registros = $conn->fetchAll(loadScript('10-obter-inscricoes-indeferidas.sql'));
    if (!isset($registros) || empty($registros)) {
        // Nao existem INSCRICOES para distribuir
        return;
    }

    $registro = current($registros);
    while (true) {
        if ($registro === false) {
            break;
        }

        try {
            $json = json_decode($registro['agents_data']);
            $emailEntidade = $json->entidade->emailPrivado;
            $emailResponsavel = $json->owner->emailPrivado;

            $avaliacoes = $conn->fetchAll(
                "SELECT id,certificador_id,estado,observacoes
                FROM culturaviva.avaliacao
                WHERE estado='I' AND inscricao_id=?"
                , [$registro['id']]);

            foreach ($avaliacoes as &$avaliacao) {
                $avaliacao['criterios'] = $conn->fetchAll(
                    "SELECT ac.aprovado,c.descricao
                    FROM culturaviva.avaliacao_criterio ac
                    JOIN culturaviva.criterio c ON ac.criterio_id = c.id
                    WHERE ac.avaliacao_id=?"
                    , [$avaliacao['id']]
                );
            }

            $message = $app->renderMailerTemplate('certificacao_indeferido', [
                'avaliacoes' => $avaliacoes
            ]);
            $dadosEmail = [
                'from' => $app->config['mailer.from'],
                'to' => $emailResponsavel,
                'cc' => $emailEntidade,
                'subject' => $message['title'],
                'body' => $message['body']
            ];
            $app->createAndSendMailMessage($dadosEmail);
        } catch (Exception $ex) {
            // faz nada
            print($ex);
        }

        $registro = next($registros);
        if ($registro === false) {
            break;
        }
    }
}

importar();
