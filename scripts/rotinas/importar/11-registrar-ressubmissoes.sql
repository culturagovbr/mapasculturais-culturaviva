/*
Registra as ressubmissões dos pontos de cultura que não foram certificados
*/
INSERT INTO culturaviva.inscricao(agente_id, estado)
SELECT DISTINCT ON (r.agent_id)
    r.agent_id,
    'R'
FROM registration r
LEFT JOIN culturaviva.inscricao insc
    ON insc.agente_id = r.agent_id
WHERE r.opportunity_id = 1
AND r.status = 1
AND insc.estado = 'N'
AND NOT EXISTS (
	SELECT id
	FROM culturaviva.inscricao
	WHERE agente_id = r.agent_id AND (estado = 'R' OR estado = 'C')
)
ORDER BY r.agent_id, insc.ts_finalizacao DESC;
