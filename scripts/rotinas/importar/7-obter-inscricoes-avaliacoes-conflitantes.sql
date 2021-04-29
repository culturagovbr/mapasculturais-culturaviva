/** 
 * Inscrições conflitantes (Deferido e Indeferido), para voto de minerva
 */

SELECT DISTINCT insc.id
FROM culturaviva.inscricao insc
JOIN culturaviva.avaliacao avalp
    ON avalp.inscricao_id = insc.id
    AND avalp.estado = ANY(ARRAY['D','I'])
JOIN culturaviva.avaliacao avalc
    ON avalc.inscricao_id = insc.id
    AND avalc.estado = ANY(ARRAY['D','I'])
WHERE insc.estado = ANY(ARRAY['P','R'])
AND avalp.estado <> avalc.estado
AND not exists (
    /* Não deve existir certificador de minerva para a inscriçao */
    SELECT aval.id
    FROM culturaviva.avaliacao aval
    JOIN culturaviva.certificador cert
        ON cert.id = aval.certificador_id
        AND cert.tipo = 'M'           
    WHERE aval.estado <> 'C'
    AND aval.inscricao_id = insc.id
)

