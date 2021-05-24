/**
 * Obtém inscrições na situações P e R que não possui avaliação para o tipo de certificador informado
 * ou possuem avaliação PENDENTE para o avaliador informado
 */
SELECT
    insc.id
FROM culturaviva.inscricao insc
WHERE insc.estado = ANY(ARRAY['P','R'])
AND (
    not exists (
        SELECT aval.id
        FROM culturaviva.avaliacao aval
        JOIN culturaviva.certificador cert
                on cert.id = aval.certificador_id
                AND cert.tipo = :tipo
        WHERE aval.estado <> 'C'
        AND aval.inscricao_id = insc.id
    )
    OR exists (
        SELECT aval.id
        FROM culturaviva.avaliacao aval
        JOIN culturaviva.certificador cert
                on cert.id = aval.certificador_id
                AND cert.tipo = :tipo
        WHERE aval.estado = 'P'
        AND aval.inscricao_id = insc.id
    )
)
ORDER BY insc.id
