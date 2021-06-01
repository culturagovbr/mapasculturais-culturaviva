/**
 * Remover critérios inativos de inscrições/avaliações não finaliadas 
 */

DELETE FROM culturaviva.avaliacao_criterio 
WHERE (criterio_id, inscricao_id) IN (
	SELECT
		crit.id,
		insc.id
	FROM culturaviva.inscricao insc
	JOIN culturaviva.inscricao_criterio incrit
		ON incrit.inscricao_id = insc.id
	JOIN culturaviva.criterio crit 
		ON crit.id = incrit.criterio_id
		AND crit.ativo = FALSE
	WHERE insc.estado = ANY(ARRAY['P'::text, 'R'::text])
);
