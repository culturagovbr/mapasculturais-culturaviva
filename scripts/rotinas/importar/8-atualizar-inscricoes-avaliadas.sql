/**
 * Atualiza o estado da incrição
 */
SELECT
	    insc.id AS id,
        insc.agente_id AS agente,
        insc.estado AS insc_estado,
        'C'::CHAR AS estado
INTO TEMP TABLE deferidas
FROM culturaviva.inscricao insc
WHERE insc.estado = ANY(ARRAY['P','R'])
AND (
	SELECT COUNT(0)
        FROM culturaviva.avaliacao av
        WHERE av.inscricao_id = insc.id
        AND av.estado = 'D'
    ) = 2;

SELECT
        insc.id AS id,
        insc.agente_id AS agente,
        insc.estado AS insc_estado,
        'N'::CHAR AS estado
INTO TEMP TABLE indeferidas
FROM culturaviva.inscricao insc
WHERE insc.estado = ANY(ARRAY['P','R'])
AND (
        SELECT COUNT(0)
        FROM culturaviva.avaliacao av
        WHERE av.inscricao_id = insc.id
        AND av.estado = 'I'
    ) = 2;

SELECT * INTO TEMP TABLE todas FROM (
    SELECT  * FROM deferidas
    UNION ALL
    SELECT * FROM indeferidas
) AS tmp;

UPDATE culturaviva.inscricao SET
    estado = e.estado,
    ts_finalizacao = CURRENT_TIMESTAMP
FROM ( SELECT id, estado FROM todas ) AS e
WHERE culturaviva.inscricao.id = e.id;

UPDATE registration SET
    status = (
        CASE
            WHEN e.estado = 'C' THEN 10
	        WHEN e.estado = 'N' THEN 3
	    END
    )
FROM ( SELECT agente, estado FROM todas ) AS e
WHERE registration.agent_id = e.agente AND registration.status = 1 AND opportunity_id = 1;
DROP TABLE IF EXISTS deferidas, indeferidas, todas;