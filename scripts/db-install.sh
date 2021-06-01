#!/bin/bash


PGUSER=postgres
DBNAME=mapas

sudo -u ${PGUSER} psql -d ${DBNAME} -f ./db/culturaviva-schema.sql
sudo -u ${PGUSER} psql -d ${DBNAME} -f ./db/culturaviva_log-schema.sql
sudo -u ${PGUSER} psql -d ${DBNAME} -f ./db/culturaviva-initial-data.sql

