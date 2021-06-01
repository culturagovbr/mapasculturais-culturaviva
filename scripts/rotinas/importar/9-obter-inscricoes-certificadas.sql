/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  Alex Rodin <contato@alexrodin.info>
 * Created: Apr 7, 2017
 */

SELECT
    r.agents_data
FROM registration r
JOIN culturaviva.inscricao insc
    on insc.agente_id = r.agent_id
WHERE r.opportunity_id = 1
AND insc.estado = 'C'
/* Inscrições finalizadas nos ultimos 10 minutos apenas */
AND insc.ts_finalizacao > current_timestamp - interval '10 minutes'
