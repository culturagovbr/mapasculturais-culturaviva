<?php

require_once __DIR__ . '/../../../../../../protected/application/bootstrap.php';

// Remove timeout de execução do script
set_time_limit(0);

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
    return file_get_contents(__DIR__ . "/importar/$file");
}

function importar()
{
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
    $conn->executeQuery(loadScript('1-registrar-inscricoes.sql'));

    print("Registra as ressubmissões dos pontos de cultura\n");
    $conn->executeQuery(loadScript('11-registrar-ressubmissoes.sql'));

    print("Remover critérios inativos de inscrições não finalizadas\n");
    $conn->executeQuery(loadScript('2-remover-criterios-inscricoes_A.sql'));
    $conn->executeQuery(loadScript('2-remover-criterios-inscricoes_B.sql'));

    print("Registrar os critérios das inscrições\n");
    $conn->executeQuery(loadScript('3-incluir-criterios-inscricoes.sql'));


    // 3º Passo: DISTRIBUIR AVALIAÇÕES
    print("Remover avaliações avaliadores inativos:\n");
    $conn->executeQuery(loadScript('4-remover-avaliacoes-avaliador-inativo.sql'));

    print("Distribuir avaliações para Representantes da Sociedade Civil\n");
    inserirAvaliacaoCertificador($conn, ['tipo' => 'C']);

    print("Distribuir avaliações para Representantes do Poder Publico\n");
    inserirAvaliacaoCertificador($conn, ['tipo' => 'P']);


    // 4º Passo: DISTRIBUIR VOTOS DE MINERVA
    print("Distribuir avaliações para Certificadores com Voto de Minerva\n");
    inserirAvaliacaoMinerva($conn);


    print("Notificando via e-mail as entidades com inscrições finalizadas (Deferidas e Indeferidas)\n");
    notificarCertificacoesDeferidas($app, $conn);
    notificarCertificacoesIndeferidas($app, $conn);
}

/**
 * Associa avaliações para certificadores da sociedade civil para inscrições que ainda não possuem
 *
 * @param type $conn
 * @param type $filtro
 * @return type
 */
function inserirAvaliacaoCertificador($conn, $filtro)
{
    $inscricoes = $conn->fetchAll(loadScript('5-obter-inscricoes-para-distribuir.sql'), $filtro);
    if (!isset($inscricoes) || empty($inscricoes)) {
        // Nao existem INSCRICOES para distribuir
        return;
    }

    $certificadores = $conn->fetchAll(loadScript('6-obter-certificadores-por-tipo.sql'), $filtro);
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
                    AND aval.inscricao_id = ?
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

importar1();
