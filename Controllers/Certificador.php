<?php

namespace CulturaViva\Controllers;

use CulturaViva\Entities\Certificador as CertificadorEntity;
use CulturaViva\Util\NativeQueryUtil;
use MapasCulturais\App;

/**
 * API usado no gerenciamento de agentes de certificação
 */
class Certificador extends \MapasCulturais\Controller {

    /**
     * Obtém um certificador por id
     */
    function GET_obter() {
        $this->requireAuthentication();
        $app = App::i();
        $id = $this->getUrlData()['id'];
        $certificador = $app->repo('\CulturaViva\Entities\Certificador')->find($id);

        $app = App::i();

        // Agente, diferente do usuario atual
        $query = 'SELECT
                    c.id,
                    c.agenteId,
                    c.ativo,
                    c.tipo,
                    c.titular,
                    c.tsCriacao,
                    c.tsAtualizacao,
                    a.name AS agenteNome
                FROM \CulturaViva\Entities\Certificador c
                JOIN \MapasCulturais\Entities\Agent a
                WHERE a.id = c.agenteId
                AND c.id = :id';

        $agents = $app->em->createQuery($query)
                ->setParameters([
                    'id' => $id
                ])
                ->getSingleResult();

        $this->json($agents);

        if ($certificador) {
            $this->json($certificador);
        } else {
            $this->json(['message' => 'Agente Certificador não encontrado'], 404);
        }
    }

    /**
     * Lista todos os certificadores cadastrados, com informações sobre o
     * status dos processos
     */
    function GET_listar() {
        $this->requireAuthentication();

        $sql = "
            WITH avaliacoes AS (
                SELECT
                    f.certificador_id,
                    f.estado,
                    count(*) AS qtd
                FROM (
                    SELECT
                        certificador_id,
                        CASE
                            WHEN estado = ANY(ARRAY['D','I']) THEN 'F'
                            ELSE estado
                        END AS estado
                    FROM culturaviva.avaliacao
                    WHERE estado <> 'C'
                ) f
                GROUP BY f.certificador_id, f.estado
            )
            SELECT
                c.*,
                a.name AS agente_nome,
                COALESCE(ap.qtd, 0) AS avaliacoes_pendentes,
                COALESCE(aa.qtd, 0) AS avaliacoes_em_analise,
                COALESCE(af.qtd, 0) AS avaliacoes_finalizadas
            FROM culturaviva.certificador c
            JOIN agent a ON a.id = c.agente_id
            LEFT JOIN avaliacoes ap ON ap.certificador_id = c.id AND ap.estado = 'P'
            LEFT JOIN avaliacoes aa ON aa.certificador_id = c.id AND aa.estado = 'A'
            LEFT JOIN avaliacoes af ON af.certificador_id = c.id AND af.estado = 'F'";

        $campos = [
            'id',
            'agente_id',
            'ativo',
            'tipo',
            'titular',
            'ts_criacao',
            'ts_atualizacao',
            'agente_nome',
            'avaliacoes_pendentes',
            'avaliacoes_em_analise',
            'avaliacoes_finalizadas',
        ];

        $this->json((new NativeQueryUtil($sql, $campos, null))->getResult());
    }

    /**
     * Permite salvar certificadores.
     *
     * Somente usuários com perfil AGENTE DA AREA pode fazer alterações nos certificadores
     *
     * @see CulturaViva\Entities\Certifier
     */
    function POST_salvar() {
        $this->requireAuthentication();
        $app = App::i();

        $data = json_decode($app->request()->getBody());

        // Salva detalhes do certificador
        $certificador = null;
        if (isset($data->id)) {
            $certificador = App::i()->repo('\CulturaViva\Entities\Certificador')->find($data->id);
        }

        if ($certificador) {
            $certificador->tsAtualizacao = date('Y-m-d H:i:s');
        } else {
            $certificador = new CertificadorEntity();
            $certificador->id = null;

            // Dados estáticos, nao recebem atualização
            $certificador->tsCriacao = date('Y-m-d H:i:s');
            $certificador->agenteId = $data->agenteId;
            $certificador->tipo = $data->tipo;
        }

        // Permite alterar apenas status e grupo do certificador
        $certificador->ativo = $data->ativo ? 't' : 'f';
        $certificador->titular = $data->titular ? 't' : 'f';

        // Validação de consistencia
        $tiposValidos = [CertificadorEntity::TP_PUBLICO, CertificadorEntity::TP_CIVIL, CertificadorEntity::TP_MINERVA];
        if (!in_array($certificador->tipo, $tiposValidos)) {
            return $this->json([ "message" => 'O tipo do Agente Certificador informado é inválido'], 400);
        }

        // Verifica se já existe cadastro do mesmo agente como certificador do mesmo tipo
        $salvos = App::i()->repo('\CulturaViva\Entities\Certificador')->findBy(['agenteId' => $certificador->agenteId]);
        if ($salvos) {
            $tiposPC = [CertificadorEntity::TP_PUBLICO, CertificadorEntity::TP_CIVIL];
            foreach ($salvos as $salvo) {
                if ($salvo->id == $certificador->id) {
                    continue;
                }

                // Impedir registrar o mesmo agente para o mesmo tipo (independente se ativo ou nao)
                if ($salvo->tipo === $certificador->tipo) {
                    return $this->json([ "message" => 'Agente Certificador já registrado com o Tipo informado'], 400);
                }

                if ($salvo->ativo) {
                    // Certificador não pode ser PUBLICO e CIVIL ao mesmo tempo
                    if (in_array($certificador->tipo, $tiposPC) && in_array($salvo->tipo, $tiposPC)) {
                        return $this->json([ "message" => 'Agente Certificador não pode ser "Publico" e "Civil" simultaneamente'], 400);
                    }
                }
            }
        }

        $certificador->save(true);

        //-------------------------------------------
        // Faz a manutenção das permissões do usuario

        /**
         * @var \MapasCulturais\Entities\User
         */
        $agent = $app->repo('Agent')->find($certificador->agenteId);

        $perfilUsuario = null;
        if ($certificador->tipo == CertificadorEntity::TP_PUBLICO) {
            $perfilUsuario = CertificadorEntity::ROLE_PUBLICO;
        } else if ($certificador->tipo == CertificadorEntity::TP_CIVIL) {
            $perfilUsuario = CertificadorEntity::ROLE_CIVIL;
        } else {
            $perfilUsuario = CertificadorEntity::ROLE_MINERVA;
        }
//        if ($certificador->ativo) {
//            $agent->user->addRole($perfilUsuario);
//            $agent->user->addRole("admin"); //adiciona também o perfil de administrador
//        } else {
//            $agent->user->removeRole($perfilUsuario);
//        }

        $app->em->flush();

        $this->json($certificador);
    }

    /**
     * Pesquisa de agentes por nome, para cadastro de certificador
     *
     * @param string $nome O nome do agente sendo buscado
     */
    function GET_buscarAgente() {
        $this->requireAuthentication();
        $app = App::i();

        $searchQuery = trim($app->request()->get('nome', ''));
        if ($searchQuery !== '') {
            $searchQuery = '%' . $searchQuery . '%';
        }

        $users = $app->em->getConnection()->fetchAll("
                SELECT
                    a.id,
                    u.id as \"userId\",
                    a.name
                FROM
                    usr u
                    JOIN agent a ON a.id = u.profile_id
                WHERE (? = '' OR u.email ILIKE ? OR a.name ILIKE ?)
                ORDER BY email
                LIMIT 10", [
            $searchQuery, $searchQuery, $searchQuery
        ]);

        $this->json($users);
    }

}
