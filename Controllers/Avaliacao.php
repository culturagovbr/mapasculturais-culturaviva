<?php

namespace CulturaViva\Controllers;

use CulturaViva\Entities\Avaliacao as AvaliacaoEntity;
use CulturaViva\Entities\AvaliacaoCriterio as AvaliacaoCriterioEntity;
use CulturaViva\Entities\Uf;
use CulturaViva\Util\NativeQueryUtil;
use MapasCulturais\App;
use MapasCulturais\Controller;

/**
 * Api base do processo de certificação
 */
class Avaliacao extends Controller
{
    // @todo
    protected $_user = 3;

    const MINERVA_DILIGENCE = 'M';

    function getUser()
    {
        return $this->_user;
    }

    function GET_total()
    {
        $this->requireAuthentication();
        $app = App::i();

        $usuario = $app->user;

        $agenteIdFiltro = $usuario->profile->id;
        if ($usuario->is('rcv_agente_area')) {
            // Agente da área pode ver os totais de todos os certificadores
            $agenteIdFiltro = 0;
        }

        $sql = "
            SELECT
                count(CASE WHEN avl.estado = 'P' THEN 1 END) as pendentes,
                count(CASE WHEN avl.estado = 'A' THEN 1 END) as em_analise,
                count(CASE WHEN avl.estado = ANY(ARRAY['D','I']) THEN 1 END) as finalizadas
            FROM culturaviva.avaliacao avl
            JOIN culturaviva.certificador cert ON cert.id = avl.certificador_id
            WHERE avl.estado <> 'C'
            AND (:agenteId = 0 OR cert.agente_id = :agenteId)";

        $campos = [
            'pendentes',
            'em_analise',
            'finalizadas'
        ];

        $parametros = [
            'agenteId' => $agenteIdFiltro
        ];

        $this->json((new NativeQueryUtil($sql, $campos, $parametros))->getSingleResult());
    }

    /**
     * Lista as avaliações de um certificador (ou de todos, caso usuario seja agente area)
     *
     * @param string $nome Permite filtrar a entidade da inscrição ou o agente responsável
     * @param string $estado Permite filtrar o estado da avaliação
     * @param string $pagina Paginação do resultado
     */
    function GET_listar()
    {
        $this->requireAuthentication();
        $app = App::i();

        $agenteId = $app->user->profile->id;
        if ($app->user->is('rcv_agente_area')) {
            // Agente da área pode ver avaliações de todos os certificadores
            $agenteId = 0;
        }

        $sql = "
            WITH avaliacoes AS (
                SELECT
                    avl.id,
                    avl.inscricao_id,
                    avl.certificador_id,
                    cert.tipo AS certificador_tipo,
                    cert.agente_id,                    
                    cert.uf AS certificador_uf,
                    agt.name AS certificador_nome,
                    avl.estado
                FROM culturaviva.avaliacao avl
                JOIN culturaviva.certificador cert ON cert.id = avl.certificador_id
                JOIN agent agt ON agt.id = cert.agente_id
                WHERE estado <> 'C'
            )
            SELECT
                insc.id,
                insc.agente_id,
                insc.estado,
                usuario.id              AS usuario_id,
                ponto.name              AS ponto_nome,
                ponto.id                AS ponto_id,
                entidade.name           AS entidade_nome,
                entidade.id             AS entidade_id,
                tp.value                AS tipo_ponto_desejado,
                avl_c.id                AS avaliacao_civil_id,
                avl_c.estado            AS avaliacao_civil_estado,
                avl_c.certificador_nome AS avaliacao_civil_certificador,
                avl_c.agente_id         AS avaliacao_civil_certificador_id,
                avl_p.id                AS avaliacao_publica_id,
                avl_p.estado            AS avaliacao_publica_estado,
                avl_p.certificador_nome AS avaliacao_publica_certificador,
                avl_p.agente_id         AS avaliacao_publica_certificador_id,
                avl_m.id                AS avaliacao_minerva_id,
                avl_m.estado            AS avaliacao_minerva_estado,
                avl_m.certificador_nome AS avaliacao_minerva_certificador,
                avl_m.agente_id         AS avaliacao_minerva_certificador_id
            FROM culturaviva.inscricao insc
            JOIN agent agente ON agente.id = insc.agente_id
            JOIN usr usuario ON usuario.id = agente.user_id
            JOIN registration reg
                on reg.agent_id = insc.agente_id
                AND reg.opportunity_id = 1
                AND reg.status = 1
            JOIN agent_relation rel_entidade
                ON rel_entidade.object_id = reg.id
                AND rel_entidade.type = 'entidade'
                AND rel_entidade.object_type = 'MapasCulturais\Entities\Registration'
            JOIN agent_relation rel_ponto
                ON rel_ponto.object_id = reg.id
                AND rel_ponto.type = 'ponto'
                AND rel_ponto.object_type = 'MapasCulturais\Entities\Registration'
            JOIN agent entidade ON entidade.id = rel_entidade.agent_id
            JOIN agent_meta ent_meta_uf
                ON  ent_meta_uf.object_id = entidade.id
                AND ent_meta_uf.key = 'En_Estado'
            LEFT JOIN agent_meta ent_meta_municipio
                ON  ent_meta_municipio.object_id = entidade.id
                AND ent_meta_municipio.key = 'En_Municipio'
            JOIN agent ponto ON ponto.id = rel_ponto.agent_id
            JOIN agent_meta tp
                ON tp.key = 'tipoPontoCulturaDesejado'
                AND tp.object_id = entidade.id
            LEFT JOIN avaliacoes avl_c
                ON insc.id = avl_c.inscricao_id
                AND avl_c.certificador_tipo = 'C'
            LEFT JOIN avaliacoes avl_p
                ON insc.id = avl_p.inscricao_id
                AND avl_p.certificador_tipo = 'P'
            LEFT JOIN avaliacoes avl_m
                ON insc.id = avl_m.inscricao_id
                AND avl_m.certificador_tipo = 'M'
            WHERE 1=1
            AND (:agenteId = 0 OR avl_c.agente_id = :agenteId OR avl_p.agente_id = :agenteId OR avl_m.agente_id = :agenteId)
            AND (
                :estado = ''
                OR :estado = ANY(ARRAY[avl_c.estado, avl_p.estado, avl_m.estado])
                OR (:estado = 'F' AND (ARRAY['D', 'I']::varchar[] && ARRAY[avl_c.estado, avl_p.estado, avl_m.estado]::varchar[]))
            )
            AND (
                :nome = ''
                OR unaccent(lower(ponto.name)) LIKE unaccent(lower(:nome))
                OR unaccent(lower(entidade.name)) LIKE unaccent(lower(:nome))
                OR unaccent(lower(avl_c.certificador_nome)) LIKE unaccent(lower(:nome))
                OR unaccent(lower(avl_p.certificador_nome)) LIKE unaccent(lower(:nome))
                OR unaccent(lower(avl_m.certificador_nome)) LIKE unaccent(lower(:nome))
            )
            AND (:municipio = ''
                OR unaccent(lower(ent_meta_municipio.value)) ILIKE unaccent(lower(:municipio)))
            ORDER BY insc.agente_id, ts_criacao DESC
           ";


        $campos = [
            'id',
            'agente_id',
            'usuario_id',
            'estado',
            'ponto_nome',
            'ponto_id',
            'entidade_nome',
            'entidade_id',
            'tipo_ponto_desejado',
            'avaliacao_civil_id',
            'avaliacao_civil_estado',
            'avaliacao_civil_certificador',
            'avaliacao_civil_certificador_id',
            'avaliacao_publica_id',
            'avaliacao_publica_estado',
            'avaliacao_publica_certificador',
            'avaliacao_publica_certificador_id',
            'avaliacao_minerva_id',
            'avaliacao_minerva_estado',
            'avaliacao_minerva_certificador',
            'avaliacao_minerva_certificador_id',
        ];
        $parametros = [
            'agenteId' => $agenteId,
            'estado' => isset($this->data['estado']) ? $this->data['estado'] : '',
            'nome' => isset($this->data['nome']) ? "%{$this->data['nome']}%" : '',
            'uf' => isset($this->data['uf']) ? $this->data['uf'] : '',
            'municipio' => isset($this->data['municipio']) ? "%{$this->data['municipio']}%" : ''
        ];
        $pagina = isset($this->data['pagina']) ? intval($this->data['pagina']) : 1;
        $this->json((new NativeQueryUtil($sql, $campos, $parametros))->paginate($pagina));
    }

    /**
     * Obtém as informações de uma avaliação específica
     */
    function GET_obter()
    {
        $this->requireAuthentication();
        $app = App::i();

        $agenteId = $app->user->profile->id;
        if ($app->user->is('rcv_agente_area')) {
            // Agente da área pode ver avaliações de todos os certificadores
            $agenteId = 0;
        }

        $avaliacaoId = $this->getUrlData()['id'];

        $sql = "
            SELECT
                avl.*,
                usuario.id          AS usuario_id,
                cert.agente_id      AS certificador_agente_id,
                (cert.agente_id = :agenteId AND avl.estado = ANY(ARRAY['P','A'])) AS autoriza_edicao,
                cert.tipo           AS certificador_tipo,
                certificador.name   AS certificador_nome,
                insc.estado         AS inscricao_estado,
                insc.ts_criacao     AS inscricao_ts_criacao,
                insc.ts_finalizacao AS inscricao_ts_finalizacao,
                insc.agente_id      AS responsavel_id,
                entidade.id         AS entidade_id,
                entidade.name       AS entidade_nome,
                ponto.id            AS ponto_id,
                ponto.name          AS ponto_nome,
                tp.value            AS ponto_cultura_desejado,
                dsc.value           AS ponto_descricao
            FROM culturaviva.avaliacao avl
            JOIN culturaviva.certificador cert
                ON cert.id = avl.certificador_id
            JOIN agent certificador ON certificador.id = cert.agente_id
            JOIN culturaviva.inscricao insc
                ON insc.id = avl.inscricao_id
            JOIN agent agente ON agente.id = insc.agente_id
            JOIN usr usuario ON usuario.id = agente.user_id
            JOIN registration reg
                on reg.agent_id = insc.agente_id
                AND reg.opportunity_id = 1
            join agent_relation rel_entidade
                ON rel_entidade.object_id = reg.id
                AND (rel_entidade.type = 'entidade' or rel_entidade.type = 'coletivo')
                AND rel_entidade.object_type = 'MapasCulturais\Entities\Registration'
            join agent_relation rel_ponto
                ON rel_ponto.object_id = reg.id
                AND rel_ponto.type = 'ponto'
                AND rel_ponto.object_type = 'MapasCulturais\Entities\Registration'
            JOIN agent ponto ON ponto.id = rel_ponto.agent_id
            JOIN agent entidade ON entidade.id = rel_entidade.agent_id
            JOIN agent_meta tp
                ON tp.key = 'tipoPontoCulturaDesejado'
                AND tp.object_id = entidade.id
            LEFT JOIN agent_meta dsc
                ON dsc.key = 'shortDescription'
                AND dsc.object_id = ponto.id
            WHERE 1=1
            AND avl.id = :id";

        $parametros = [
            'id' => $avaliacaoId,
            'agenteId' => $agenteId
        ];

        $campos = [
            'id',
            'inscricao_id',
            'usuario_id',
            'certificador_id',
            'estado',
            'observacoes',
            'ts_finalizacao',
            'ts_criacao',
            'ts_atualizacao',
            'certificador_agente_id',
            'certificador_tipo',
            'certificador_nome',
            'inscricao_estado',
            'inscricao_ts_criacao',
            'inscricao_ts_finalizacao',
            'responsavel_id',
            'entidade_id',
            'entidade_nome',
            'ponto_id',
            'ponto_nome',
            'ponto_cultura_desejado',
            'ponto_descricao',
            'autoriza_edicao',
        ];

        $out = (new NativeQueryUtil($sql, $campos, $parametros))->getSingleResult();
        if ($out != null) {
            $out['criterios'] = $this->obterCriteriosAvaliacao($avaliacaoId);
        }

        $this->json($out);
    }

    /**
     * Permite salvar avaliações
     *
     * Somente usuários com perfil AGENTE DA AREA pode fazer alterações nos certificadores
     *
     * @see CulturaViva\Entities\Certifier
     */
    function POST_salvar()
    {
        $this->requireAuthentication();
        $app = App::i();

        $data = json_decode($app->request()->getBody());

        $avaliacao = App::i()->repo('\CulturaViva\Entities\Avaliacao')->find($data->id);
        if (!isset($avaliacao) || empty($avaliacao)) {
            return $this->json(["message" => 'Avaliação não encontrada'], 400);
        }

        // Validação de consistencia
        $estadosPermiteEdicao = [AvaliacaoEntity::ST_PENDENTE, AvaliacaoEntity::ST_EM_ANALISE];
        if (!in_array($avaliacao->estado, $estadosPermiteEdicao)) {
            return $this->json(["message" => 'O estado da avaliação não permite alterações'], 400);
        }

        // Atualiza avaliação
        $avaliacao->tsAtualizacao = date('Y-m-d H:i:s');
        $avaliacao->estado = isset($data->estado) ? $data->estado : AvaliacaoEntity::ST_EM_ANALISE;
        if (isset($data->observacoes) && !empty($data->observacoes)) {
            $avaliacao->observacoes = $data->observacoes;
        }

        $aPersistir = array();
        if (isset($data->criterios) && !empty($data->criterios)) {
            foreach ($data->criterios as $criterio) {
                // Salva detalhes do certificador
                $criterioEntity = null;
                if (isset($data->id)) {
                    $criterioEntity = App::i()
                        ->repo('\CulturaViva\Entities\AvaliacaoCriterio')
                        ->find([
                            'criterioId' => $criterio->id,
                            'avaliacaoId' => $avaliacao->id,
                            'inscricaoId' => $avaliacao->inscricaoId,
                        ]);
                }

                if (!$criterioEntity) {
                    $criterioEntity = new AvaliacaoCriterioEntity();
                    $criterioEntity->criterioId = $criterio->id;
                    $criterioEntity->avaliacaoId = $avaliacao->id;
                    $criterioEntity->inscricaoId = $avaliacao->inscricaoId;
                }

                // Permite alterar apenas status e grupo do certificador
                $criterioEntity->aprovado = $criterio->aprovado ? 't' : 'f';
                array_push($aPersistir, $criterioEntity);
            }
        }


        $app->getEm()->transactional(function ($em) use ($aPersistir, $avaliacao) {
            // Salva os itens
            foreach ($aPersistir as $entity) {
                $em->persist($entity);
            }
            $em->persist($avaliacao);
        });

        $this->json(null);
    }

    /**
     * Executa a rotina de distribuição e certificação de pontos de cultura
     */
    function GET_distribuir()
    {
        $this->requireAuthentication();
        $app = App::i();
        if ($app->user->is('rcv_agente_area')) {
            include(__DIR__ . "/../scripts/rotinas/importar-inscricoes.php");

            importar();
        } else {
            return $this->json(["message" => 'Você não tem permissão para realizar essa ação'], 403);
        }

        $this->json(null);
    }

    /**
     * Obtém todos os critérios de uma avaliação
     *
     * @param type $avaliacaoId
     * @return type
     */
    private function obterCriteriosAvaliacao($avaliacaoId)
    {
        $sql = "
            SELECT
                crtr.id,
                crtr.ordem,
                crtr.descricao,
                avct.aprovado
            FROM culturaviva.avaliacao avl
            JOIN culturaviva.inscricao_criterio insct
                ON insct.inscricao_id = avl.inscricao_id
            JOIN culturaviva.criterio crtr
                ON crtr.id = insct.criterio_id
            LEFT JOIN culturaviva.avaliacao_criterio avct
                ON avct.avaliacao_id = avl.id
                AND avct.inscricao_id = avl.inscricao_id
                AND avct.criterio_id = insct.criterio_id
            WHERE avl.id = :avaliacao";

        $parametros = ['avaliacao' => $avaliacaoId];


        $campos = [
            'id',
            'ordem',
            'descricao',
            'aprovado'
        ];

        return (new NativeQueryUtil($sql, $campos, $parametros))->getResult();
    }

}
