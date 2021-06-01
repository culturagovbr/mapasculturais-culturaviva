/**
 * Remover avaliações de avaliadores inativos ou suplentes
 */

UPDATE culturaviva.avaliacao SET estado = 'C'
WHERE certificador_id IN (
	SELECT
	    id
	FROM culturaviva.certificador cert
	WHERE cert.ativo = FALSE OR cert.titular = FALSE
)
AND culturaviva.avaliacao.estado = ANY (ARRAY['P'::text, 'A'::text])
;
