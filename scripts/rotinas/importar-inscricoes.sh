#!/bin/bash

echo "EXECUTANDO IMPORTACAO DE INSCRICOES...";


if [[ $1 ]]; then
    DOMAIN=$1;
else
    DOMAIN="localhost";
fi

MAPASCULTURAIS_CONFIG_FILE='config.php' HTTP_HOST=$DOMAIN REQUEST_METHOD='CLI' REMOTE_ADDR='127.0.0.1' REQUEST_URI='/' SERVER_NAME=127.0.0.1 SERVER_PORT="8000" php ./importar-inscricoes.php
