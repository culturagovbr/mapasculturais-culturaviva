/**
 * Registra as inscricoes dos pontos de cultura
 */
INSERT INTO culturaviva.inscricao(agente_id, estado)
SELECT
    r.agent_id,
    'P'
FROM registration r
LEFT JOIN culturaviva.inscricao insc
    ON insc.agente_id = r.agent_id
WHERE r.opportunity_id = 1
AND r.status = 1
AND insc.id IS NULL
AND (insc.estado = 'P' OR insc.estado is null);