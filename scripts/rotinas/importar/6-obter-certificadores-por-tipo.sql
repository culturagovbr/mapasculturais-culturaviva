/**
 * Obtém avaliadores ativos e titulares, por tipo

 * Usado na distribuição de avaliações
 */
SELECT
    cert.id
FROM culturaviva.certificador cert
WHERE cert.ativo = TRUE
AND cert.titular = TRUE
AND cert.tipo = :tipo
ORDER BY cert.id;