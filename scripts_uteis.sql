
-- ACESSAR BANCO DE DADOS
-- sudo -u postgres psql mapas

--------------------------------------------------------------------------------
-- CRIAÇÃO DE USUÁRIOS DE TESTE
--------------------------------------------------------------------------------

--------------------------------------------------------------------------------
-- Agente Area (Gestor)

INSERT INTO usr(auth_provider, auth_uid, email, last_login_timestamp, create_timestamp, status)
    VALUES (1, 1, 'AgenteArea@local', current_date, current_date, 1);

INSERT INTO agent(user_id, type, name, create_timestamp, status)
	SELECT id, 1, email, current_date, 1 FROM usr WHERE usr.email = 'AgenteArea@local';

UPDATE usr SET profile_id = (SELECT id FROM agent WHERE name = 'AgenteArea@local')
    WHERE email = 'AgenteArea@local';

INSERT INTO role(id, usr_id, name)
	SELECT nextval('role_id_seq'), id, 'rcv_agente_area'
        FROM usr WHERE usr.email = 'AgenteArea@local';

-- Agente area deve ser admin
INSERT INTO role(id, usr_id, name)
	SELECT nextval('role_id_seq'), id, 'admin'
        FROM usr WHERE usr.email = 'AgenteArea@local';


--------------------------------------------------------------------------------
-- Agente Certificador da Sociedade Civil

INSERT INTO usr(auth_provider, auth_uid, email, last_login_timestamp, create_timestamp, status)
    VALUES (1, 1, 'AgenteCivil@local', current_date, current_date, 1);

INSERT INTO agent(user_id, type, name, create_timestamp, status)
	SELECT id, 1, email, current_date, 1 FROM usr WHERE usr.email = 'AgenteCivil@local';

UPDATE usr SET profile_id = (SELECT id FROM agent WHERE name = 'AgenteCivil@local')
    WHERE email = 'AgenteCivil@local';

-- INSERT INTO role(usr_id, name)
-- 	SELECT id, 'rcv_certificador_civil' FROM usr WHERE usr.email = 'AgenteCivil@local';


--------------------------------------------------------------------------------
-- Agente Certificador do Poder Publico

INSERT INTO usr(auth_provider, auth_uid, email, last_login_timestamp, create_timestamp, status)
    VALUES (1, 1, 'AgentePublico@local', current_date, current_date, 1);

INSERT INTO agent(user_id, type, name, create_timestamp, status)
	SELECT id, 1, email, current_date, 1 FROM usr WHERE usr.email = 'AgentePublico@local';

UPDATE usr SET profile_id = (SELECT id FROM agent WHERE name = 'AgentePublico@local')
    WHERE email = 'AgentePublico@local';

-- INSERT INTO role(usr_id, name)
-- 	SELECT id, 'rcv_certificador_publico' FROM usr WHERE usr.email = 'AgentePublico@local';


--------------------------------------------------------------------------------
-- Agente Certificador do Poder Publico Com Voto de Minerva

INSERT INTO usr(auth_provider, auth_uid, email, last_login_timestamp, create_timestamp, status)
    VALUES (1, 1, 'AgenteMinerva@local', current_date, current_date, 1);

INSERT INTO agent(user_id, type, name, create_timestamp, status)
	SELECT id, 1, email, current_date, 1 FROM usr WHERE usr.email = 'AgenteMinerva@local';

UPDATE usr SET profile_id = (SELECT id FROM agent WHERE name = 'AgenteMinerva@local')
    WHERE email = 'AgenteMinerva@local';

-- Possui duas roles, publico e minerva
-- INSERT INTO role(usr_id, name) SELECT id, 'rcv_certificador_publico' FROM usr
--     WHERE usr.email = 'AgenteMinerva@local';
-- INSERT INTO role(usr_id, name) SELECT id, 'rcv_certificador_minerva' FROM usr
--     WHERE usr.email = 'AgenteMinerva@local';



--------------------------------------------------------------------------------
-- Ponto de Cultura

INSERT INTO usr(auth_provider, auth_uid, email, last_login_timestamp, create_timestamp, status)
    VALUES (1, 1, 'PontoCultura@local', current_date, current_date, 1);

INSERT INTO agent(user_id, type, name, create_timestamp, status)
	SELECT id, 1, email, current_date, 1 FROM usr WHERE usr.email = 'PontoCultura@local';

UPDATE usr SET profile_id = (SELECT id FROM agent WHERE name = 'PontoCultura@local')
    WHERE email = 'PontoCultura@local';

-- Ponto de cultura
INSERT INTO user_meta(id, object_id, key, value)
    SELECT nextval('user_meta_id_seq'), usr.id, 'redeCulturaViva', agt.id
    FROM usr
    JOIN agent agt ON agt.user_id = usr.id
    WHERE usr.email = 'PontoCultura@local'
;
INSERT INTO user_meta(id, object_id, key, value)
    SELECT nextval('user_meta_id_seq'), usr.id, 'tipoPontoCulturaDesejado', 'ponto'
    FROM usr WHERE usr.email = 'PontoCultura@local'
;

-- Registra as inscrições dos pontos de cultura
INSERT INTO culturaviva.inscricao(agente_id, estado)
    SELECT
        r.agent_id, 'P'
    FROM registration r
    LEFT JOIN culturaviva.inscricao insc
            on insc.agente_id = r.agent_id
    WHERE r.opportunity_id = 1
    AND r.status = 1
    AND insc.id IS NULL AND (insc.estado = 'P' OR insc.estado is null);

-- Registra os criterios das inscrições
INSERT INTO culturaviva.inscricao_criterio (criterio_id, inscricao_id)
    SELECT
            crit.id,
            insc.id
    FROM culturaviva.inscricao insc
    JOIN culturaviva.criterio crit ON  crit.ativo = TRUE 
    LEFT JOIN culturaviva.inscricao_criterio incrit
            on incrit.inscricao_id = insc.id
    WHERE insc.estado = 'P'
    AND incrit.inscricao_id IS NULL;


-- Associar avaliação a certificador
-- Algoritmo
    -- Obter quantidade de inscrições por certificador/TIPO
    -- Inscrições que não possuem avaliação
    SELECT
        count(0) as total
    FROM culturaviva.inscricao insc
    LEFT JOIN culturaviva.avaliacao aval 
        on aval.inscricao_id = insc.id
    WHERE aval.inscricao_id IS NULL
    AND insc.estado = ANY(ARRAY['P','R']);

    -- Totais por tipo certificador
    SELECT
        count(CASE WHEN cert.tipo = 'C' THEN 1 ELSE 0 END) as total_civil,
        count(CASE WHEN cert.tipo = 'P' THEN 1 ELSE 0 END) as total_publico
    FROM culturaviva.certificador cert
    WHERE cert.ativo = TRUE 
    AND cert.titular = TRUE
    AND cert.tipo = ANY(ARRAY['C','P']);

    -- Obter totais de cada certificador ()
    WITH cteTotalInscricoes AS (
        SELECT
            cert.id,
            count(aval.inscricao_id) as total_inscricao
        FROM culturaviva.certificador cert
        JOIN culturaviva.avaliacao aval
            ON aval.certificador_id = cert.id
        WHERE cert.ativo = TRUE 
        AND cert.titular = TRUE
        AND cert.tipo = ANY(ARRAY['C','P'])
        GROUP BY cert.id, aval.inscricao_id
    )
    SELECT 
        * 
    FROM cteTotalInscricoes e
    WHERE e.total_inscricao < 4 --numeroMagico

    -- Sortear as inscricoes entre os certificadores
    -- for certificacoes 


SELECT
        insc.*
FROM culturaviva.inscricao insc
LEFT JOIN culturaviva.inscricao_criterio crit
        on crit.inscricao_id = insc.id
WHERE insc.estado = 'P'
AND crit.inscricao_id IS NULL
;


-- Registra a inscrição do ponto de cultura
INSERT INTO culturaviva.inscricao(agente_id, estado)
    SELECT
        r.agent_id, 'P'
FROM registration r
LEFT JOIN culturaviva.inscricao insc
        on insc.agente_id = r.agent_id
WHERE r.opportunity_id = 1
AND r.status = 1
AND insc.id IS NULL AND (insc.estado = 'P' OR insc.estado is null);

DELETE FROM culturaviva.inscricao;

-- Atribuição de critérios das inscrições
INSERT INTO culturaviva.inscricao_criterio(inscricao_id, criterio_id)    
    SELECT
        i.id,
        c.id 
    FROM culturaviva.inscricao i
    JOIN culturaviva.criterio  c
        ON c.ativo = true
    LEFT JOIN culturaviva.inscricao_criterio ic
        ON ic.inscricao_id = i.id
        AND ic.criterio_id = c.id
    WHERE ic.inscricao_id IS NULL
    ;

-- Atribuição de critérios das avaliações
INSERT INTO culturaviva.avaliacao_criterio(inscricao_id, avaliacao_id, criterio_id)    
    SELECT
        ic.inscricao_id,
        a.id,
        ic.criterio_id
    FROM culturaviva.inscricao_criterio ic
    JOIN culturaviva.avaliacao a 
        ON a.inscricao_id = ic.inscricao_id
    LEFT JOIN culturaviva.avaliacao_criterio ac
        ON ac.inscricao_id = ic.inscricao_id
        AND ac.criterio_id = ic.criterio_id
        AND ac.avaliacao_id = a.id
    WHERE ac.inscricao_id IS NULL;


-- Atribui avaliação para agente certificador (registrar pela interface)
INSERT INTO culturaviva.avaliacao(inscricao_id, certificador_id, estado)
    VALUES (
        (
            SELECT id
            FROM culturaviva.inscricao
            WHERE agente_id = (SELECT id FROM agent WHERE name = 'PontoCultura@local')
        ),
        (
            SELECT id
            FROM culturaviva.certificador
            WHERE agente_id = (SELECT id FROM agent WHERE name = 'AgenteCivil@local')
            AND ativo = true
            AND tipo = 'C'
        ),
        'P'
    );

INSERT INTO culturaviva.avaliacao(inscricao_id, certificador_id, estado)
    VALUES (
        (
            SELECT id
            FROM culturaviva.inscricao
            WHERE agente_id = (SELECT id FROM agent WHERE name = 'PontoCultura@local')
        ),
        (
            SELECT id
            FROM culturaviva.certificador
            WHERE agente_id = (SELECT id FROM agent WHERE name = 'AgentePublico@local')
            AND ativo = true
            AND tipo = 'P'
        ),
        'P'
    );













--------------------------------------------------------------------------------
-- CONSULTAS GENERICAS
--------------------------------------------------------------------------------

-- Status possiveis de avaliações (culturaviva.avaliacao.estado)
-- [P] Pendente
-- [A] Em Análise
-- [D] Deferido
-- [I] Indeferido
-- [C] Cancelado


-- Quantidade de processos por certificador agrupado por status
SELECT
    d.certifier_id,
    d.status,
    count(*) AS qtd
FROM culturaviva.diligence d
GROUP BY d.certifier_id, d.status;


-- Quantidade de processos por certificador agrupado por status
-- Considerando os status "[D] Deferido" e "[I] Indeferido" como "[F] Finalizado"
-- Ignora processos cancelados
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
GROUP BY f.certificador_id, f.estado;



-- Listar certificadores com informações sobre as avaliações
-- Considerando os status "[D] Deferido" e "[I] Indeferido" como "[F] Finalizado"
-- Ignora processos cancelados
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
LEFT JOIN avaliacoes af ON af.certificador_id = c.id AND af.estado = 'F'
;



-- Totalizadores de avaliações por AGENTE
-- Considerando os status "[D] Deferido" e "[I] Indeferido" como "[F] Finalizado"
-- Ignora processos cancelados
SELECT
    count(CASE WHEN avl.estado = 'P' THEN 1 END) as pendentes,
    count(CASE WHEN avl.estado = 'A' THEN 1 END) as em_analise,
    count(CASE WHEN avl.estado = ANY(ARRAY['D','I']) THEN 1 END) as finalizadas
FROM culturaviva.avaliacao avl
JOIN culturaviva.certificador cert ON cert.id = avl.certificador_id
WHERE avl.estado <> 'C'
AND cert.agente_id = :agenteId
;


-- Listagem/Filtro de avaliações por status
-- Ordena por atualizações mais recentes e inscrições mais antigas
WITH avaliacoes AS (
    SELECT
        avl.id,
        avl.inscricao_id,
        avl.certificador_id,
        cert.tipo AS certificador_tipo,
        cert.agente_id,
        agt.name AS certificador_nome,
        CASE
            WHEN avl.estado = ANY(ARRAY['D','I']) THEN 'F' ELSE estado
        END AS estado
    FROM culturaviva.avaliacao avl
    JOIN culturaviva.certificador cert ON cert.id = avl.certificador_id
    JOIN agent agt ON agt.id = cert.agente_id
    WHERE estado <> 'C'
)
SELECT
    insc.id,
    insc.estado,
    pnt.name                AS ponto_nome,
    tp.value                AS ponto_tipo,
    avl_c.id                AS avaliacao_civil_id,
    avl_c.estado            AS avaliacao_civil_estado,
    avl_c.certificador_nome AS avaliacao_civil_certificador,
    avl_p.id                AS avaliacao_publica_id,
    avl_p.estado            AS avaliacao_publica_estado,
    avl_p.certificador_nome AS avaliacao_publica_certificador,
    avl_m.id                AS avaliacao_minerva_id,
    avl_m.estado            AS avaliacao_minerva_estado,
    avl_m.certificador_nome AS avaliacao_minerva_certificador
FROM culturaviva.inscricao insc
LEFT JOIN avaliacoes avl_c ON insc.id = avl_c.inscricao_id AND avl_c.certificador_tipo = 'C'
LEFT JOIN avaliacoes avl_p ON insc.id = avl_p.inscricao_id AND avl_p.certificador_tipo = 'P'
LEFT JOIN avaliacoes avl_m ON insc.id = avl_m.inscricao_id AND avl_m.certificador_tipo = 'm'
JOIN agent pnt ON pnt.id = insc.agente_id
JOIN user_meta tp ON tp.key = 'tipoPontoCulturaDesejado' AND tp.object_id = pnt.user_id
WHERE insc.estado <> 'C'
/*AND (:agenteId = 0 OR (COALESCE(avl_c.agente_id, avl_p.agente_id, avl_m.agente_id, 0) = :agenteId)*/
/*AND (
    :estado = ''
    OR :estado = ANY(ARRAY[avl_c.estado,avl_p.estado, avl_m.estado])
)*/
/*AND (:nome == '' OR unaccent(lower(pnt.name)) LIKE unaccent(lower(:nome)))*/
ORDER BY avl.ts_criacao ASC



-- Obter detalhes de uma avaliação
SELECT
    avl.*,
    cert.agente_id,
    cert.tipo           AS certificador_tipo,
    agt.name            AS certificador_nome,
    insc.estado         AS inscricao_estado,
    insc.ts_criacao     AS inscricao_ts_criacao,
    insc.ts_finalizacao AS inscricao_ts_finalizacao,
    pnt.name            AS ponto_nome,
    tp.value            AS ponto_tipo,
    dsc.value           AS ponto_descricao
FROM culturaviva.avaliacao avl
JOIN culturaviva.certificador cert
    ON cert.id = avl.certificador_id
JOIN agent agt ON agt.id = cert.agente_id
JOIN culturaviva.inscricao insc
    ON insc.id = avl.inscricao_id
JOIN agent pnt
    ON pnt.id = insc.agente_id
JOIN user_meta tp
    ON tp.key = 'tipoPontoCulturaDesejado'
    AND tp.object_id = pnt.user_id
LEFT JOIN user_meta dsc
    ON dsc.key = 'shortDescription'
    AND tp.object_id = pnt.user_id
WHERE insc.estado <> 'C'
AND avl.id = 4;



-- Obter os critérios de uma avaliação
SELECT
    crtr.id,
    crtr.ordem,
    crtr.descricao,
    avct.aprovado
FROM culturaviva.avaliacao avl
JOIN culturaviva.avaliacao_criterio avct
    ON avct.avaliacao_id = avl.id
    AND avct.inscricao_id = avl.inscricao_id
JOIN culturaviva.inscricao_criterio insct
    ON insct.criterio_id = avct.criterio_id
    AND insct.inscricao_id = avct.inscricao_id
JOIN culturaviva.criterio crtr
    ON crtr.id = insct.criterio_id
WHERE avl.id = 4
