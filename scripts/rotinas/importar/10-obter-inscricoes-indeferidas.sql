SELECT
    r.agents_data,insc.id
FROM registration r
JOIN culturaviva.inscricao insc
    on insc.agente_id = r.agent_id
WHERE r.opportunity_id = 1
AND insc.estado = 'N'
/* Inscrições finalizadas nos ultimos 10 minutos apenas */
AND insc.ts_finalizacao > current_timestamp - interval '10 minutes'