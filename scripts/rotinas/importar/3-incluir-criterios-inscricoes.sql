/**
 * Registra os criterios das inscricoes
 */
INSERT INTO culturaviva.inscricao_criterio (criterio_id, inscricao_id)
SELECT
        crit.id,
        insc.id
FROM culturaviva.inscricao insc
JOIN culturaviva.criterio crit
	ON  crit.ativo = TRUE
LEFT JOIN culturaviva.inscricao_criterio incrit
        ON incrit.inscricao_id = insc.id
	AND incrit.criterio_id = crit.id
WHERE insc.estado = ANY(ARRAY['P'::text, 'R'::text])
AND incrit.inscricao_id IS NULL;
