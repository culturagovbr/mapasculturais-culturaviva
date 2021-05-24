/*

Registro de todas as alterções ocorridas na base do culturaviva para fins de auditoria

Para cada tabela do culturaviva, será criada uma entidade contendo os mesmos campos acrescido dos campos abaixo

log_ts      Data de gravação do registro de auditoria, ou seja, quando a modificação foi realizada na tabela auditada.
log_tp      Tipo da alteração realizada, podendo ser I (INSERT), U (UPDATE) ou D (DELETE).
log_client  Host (IP:porta) de onde se originou o comando de alteração.
log_user    Nome do usuário (banco de dados) que realizou a alteração.
log_spid    Process ID do processo do servidor anexado à sessão atual. (Você pode utilizar as tabelas de log para
                    recuperar informações para a sessão atual, util para depuração de erros)

 */


------------------------------------------------------------------------------------------------------------------------
-- Schema
------------------------------------------------------------------------------------------------------------------------
-- DROP SCHEMA IF EXISTS culturaviva_log CASCADE;

CREATE SCHEMA IF NOT EXISTS culturaviva_log;
------------------------------------------------------------------------------------------------------------------------


------------------------------------------------------------------------------------------------------------------------
-- Critérios de Avaliação
------------------------------------------------------------------------------------------------------------------------
-- table
CREATE TABLE culturaviva_log.criterio (
    log_ts      TIMESTAMP without time zone,
    log_tp      CHAR,
    log_client  VARCHAR(50),
    log_user    VARCHAR(50),
    log_spid    int4,
    id          INTEGER NOT NULL,
    ordem       INTEGER,
    ativo       BOOLEAN,
    descricao   TEXT,
    ts_criacao  TIMESTAMP without time zone
);
COMMENT ON TABLE culturaviva_log.criterio IS 'Log de Auditoria da tabela culturaviva.criterio';
COMMENT ON COLUMN culturaviva_log.criterio.log_ts       IS 'Data de gravação do registro de auditoria, ou seja, quando a modificação foi realizada na tabela auditada.';
COMMENT ON COLUMN culturaviva_log.criterio.log_tp       IS 'Tipo da alteração realizada, podendo ser I (INSERT), U (UPDATE) ou D (DELETE).';
COMMENT ON COLUMN culturaviva_log.criterio.log_client   IS 'Host (IP:porta) de onde se originou o comando de alteração.';
COMMENT ON COLUMN culturaviva_log.criterio.log_user     IS 'Nome do usuário (banco de dados) que realizou a alteração.';
COMMENT ON COLUMN culturaviva_log.criterio.log_spid     IS 'Process ID do processo do servidor anexado à sessão atual. (Você pode utilizar as tabelas de log para recuperar informações para a sessão atual, util para depuração de erros)';

-- function
CREATE OR REPLACE FUNCTION culturaviva_log.criterio_fn_tg() RETURNS TRIGGER AS $BODY$
    DECLARE
        vlog_client VARCHAR(50);
        vRecord     RECORD;
        vAction     CHAR;
    BEGIN
        IF inet_client_addr() IS NULL THEN
            vlog_client := 'localhost';
        ELSE
            vlog_client := inet_client_addr()::varchar || ':' || inet_client_port();
        END IF;

        IF TG_OP = 'INSERT' THEN
            vRecord := NEW;
            vAction := 'I';
        ELSIF TG_OP = 'UPDATE' then
            vRecord := NEW;
            vAction := 'U';
        ELSIF TG_OP = 'DELETE' THEN
            vRecord := OLD;
            vAction := 'D';
        END IF;

        INSERT INTO culturaviva_log.criterio(
            log_ts, log_tp, log_client, log_user, log_spid,
            id, ordem, ativo, descricao, ts_criacao
        )
        VALUES (
            clock_timestamp(), vAction, vlog_client, session_user, pg_backend_pid(),
            vRecord.id, vRecord.ordem, vRecord.ativo, vRecord.descricao, vRecord.ts_criacao
        );

        RETURN vRecord;
    END;
$BODY$ LANGUAGE plpgsql VOLATILE SECURITY DEFINER COST 100;
COMMENT ON FUNCTION culturaviva_log.criterio_fn_tg() IS 'Função para Log de Auditoria da tabela culturaviva.criterio';

-- trigger
CREATE TRIGGER culturaviva_criterio_log_tg
    BEFORE INSERT OR UPDATE OR DELETE ON culturaviva.criterio
    FOR EACH ROW EXECUTE PROCEDURE culturaviva_log.criterio_fn_tg();
COMMENT ON TRIGGER culturaviva_criterio_log_tg ON culturaviva.criterio IS 'Trigger para Log de Auditoria em culturaviva_log.criterio';
------------------------------------------------------------------------------------------------------------------------


------------------------------------------------------------------------------------------------------------------------
-- Inscrições
------------------------------------------------------------------------------------------------------------------------
-- table
CREATE TABLE culturaviva_log.inscricao (
    log_ts          TIMESTAMP without time zone,
    log_tp          CHAR,
    log_client      VARCHAR(50),
    log_user        VARCHAR(50),
    log_spid        int4,
    id              INTEGER,
    agente_id       INTEGER,
    estado          CHAR,
    ts_criacao      TIMESTAMP without time zone,
    ts_finalizacao  TIMESTAMP without time zone
);
COMMENT ON TABLE culturaviva_log.inscricao IS 'Log de Auditoria da tabela culturaviva.criterio';
COMMENT ON COLUMN culturaviva_log.inscricao.log_ts       IS 'Data de gravação do registro de auditoria, ou seja, quando a modificação foi realizada na tabela auditada.';
COMMENT ON COLUMN culturaviva_log.inscricao.log_tp       IS 'Tipo da alteração realizada, podendo ser I (INSERT), U (UPDATE) ou D (DELETE).';
COMMENT ON COLUMN culturaviva_log.inscricao.log_client   IS 'Host (IP:porta) de onde se originou o comando de alteração.';
COMMENT ON COLUMN culturaviva_log.inscricao.log_user     IS 'Nome do usuário (banco de dados) que realizou a alteração.';
COMMENT ON COLUMN culturaviva_log.inscricao.log_spid     IS 'Process ID do processo do servidor anexado à sessão atual. (Você pode utilizar as tabelas de log para recuperar informações para a sessão atual, util para depuração de erros)';

-- function
CREATE OR REPLACE FUNCTION culturaviva_log.inscricao_fn_tg() RETURNS TRIGGER AS $BODY$
    DECLARE
        vlog_client VARCHAR(50);
        vRecord     RECORD;
        vAction     CHAR;
    BEGIN
        IF inet_client_addr() IS NULL THEN
            vlog_client := 'localhost';
        ELSE
            vlog_client := inet_client_addr()::varchar || ':' || inet_client_port();
        END IF;

        IF TG_OP = 'INSERT' THEN
            vRecord := NEW;
            vAction := 'I';
        ELSIF TG_OP = 'UPDATE' then
            vRecord := NEW;
            vAction := 'U';
        ELSIF TG_OP = 'DELETE' THEN
            vRecord := OLD;
            vAction := 'D';
        END IF;

        INSERT INTO culturaviva_log.inscricao(
            log_ts, log_tp, log_client, log_user, log_spid,
            id, agente_id, estado, ts_criacao, ts_finalizacao
        )
        VALUES (
            clock_timestamp(), vAction, vlog_client, session_user, pg_backend_pid(),
            vRecord.id, vRecord.agente_id, vRecord.estado, vRecord.ts_criacao, vRecord.ts_finalizacao
        );

        RETURN vRecord;
    END;
$BODY$ LANGUAGE plpgsql VOLATILE SECURITY DEFINER COST 100;
COMMENT ON FUNCTION culturaviva_log.inscricao_fn_tg() IS 'Função para Log de Auditoria da tabela culturaviva.inscricao';

-- trigger
CREATE TRIGGER culturaviva_inscricao_log_tg
    BEFORE INSERT OR UPDATE OR DELETE ON culturaviva.inscricao
    FOR EACH ROW EXECUTE PROCEDURE culturaviva_log.inscricao_fn_tg();
COMMENT ON TRIGGER culturaviva_inscricao_log_tg ON culturaviva.inscricao IS 'Trigger para Log de Auditoria em culturaviva_log.inscricao';
------------------------------------------------------------------------------------------------------------------------


------------------------------------------------------------------------------------------------------------------------
-- Critérios de Avaliação da Inscrição
------------------------------------------------------------------------------------------------------------------------
-- table
CREATE TABLE culturaviva_log.inscricao_criterio (
    log_ts          TIMESTAMP without time zone,
    log_tp          CHAR,
    log_client      VARCHAR(50),
    log_user        VARCHAR(50),
    log_spid        int4,
    inscricao_id    INTEGER,
    criterio_id     INTEGER,
    ts_criacao      TIMESTAMP without time zone
);
COMMENT ON TABLE culturaviva_log.inscricao_criterio IS 'Log de Auditoria da tabela culturaviva.criterio';
COMMENT ON COLUMN culturaviva_log.inscricao_criterio.log_ts       IS 'Data de gravação do registro de auditoria, ou seja, quando a modificação foi realizada na tabela auditada.';
COMMENT ON COLUMN culturaviva_log.inscricao_criterio.log_tp       IS 'Tipo da alteração realizada, podendo ser I (INSERT), U (UPDATE) ou D (DELETE).';
COMMENT ON COLUMN culturaviva_log.inscricao_criterio.log_client   IS 'Host (IP:porta) de onde se originou o comando de alteração.';
COMMENT ON COLUMN culturaviva_log.inscricao_criterio.log_user     IS 'Nome do usuário (banco de dados) que realizou a alteração.';
COMMENT ON COLUMN culturaviva_log.inscricao_criterio.log_spid     IS 'Process ID do processo do servidor anexado à sessão atual. (Você pode utilizar as tabelas de log para recuperar informações para a sessão atual, util para depuração de erros)';

-- function
CREATE OR REPLACE FUNCTION culturaviva_log.inscricao_criterio_fn_tg() RETURNS TRIGGER AS $BODY$
    DECLARE
        vlog_client VARCHAR(50);
        vRecord     RECORD;
        vAction     CHAR;
    BEGIN
        IF inet_client_addr() IS NULL THEN
            vlog_client := 'localhost';
        ELSE
            vlog_client := inet_client_addr()::varchar || ':' || inet_client_port();
        END IF;

        IF TG_OP = 'INSERT' THEN
            vRecord := NEW;
            vAction := 'I';
        ELSIF TG_OP = 'UPDATE' then
            vRecord := NEW;
            vAction := 'U';
        ELSIF TG_OP = 'DELETE' THEN
            vRecord := OLD;
            vAction := 'D';
        END IF;

        INSERT INTO culturaviva_log.inscricao_criterio(
            log_ts, log_tp, log_client, log_user, log_spid,
            inscricao_id, criterio_id, ts_criacao
        )
        VALUES (
            clock_timestamp(), vAction, vlog_client, session_user, pg_backend_pid(),
            vRecord.inscricao_id, vRecord.criterio_id, vRecord.ts_criacao
        );

        RETURN vRecord;
    END;
$BODY$ LANGUAGE plpgsql VOLATILE SECURITY DEFINER COST 100;
COMMENT ON FUNCTION culturaviva_log.inscricao_criterio_fn_tg() IS 'Função para Log de Auditoria da tabela culturaviva.inscricao_criterio';

-- trigger
CREATE TRIGGER culturaviva_inscricao_criterio_log_tg
    BEFORE INSERT OR UPDATE OR DELETE ON culturaviva.inscricao_criterio
    FOR EACH ROW EXECUTE PROCEDURE culturaviva_log.inscricao_criterio_fn_tg();
COMMENT ON TRIGGER culturaviva_inscricao_criterio_log_tg ON culturaviva.inscricao_criterio IS 'Trigger para Log de Auditoria em culturaviva_log.inscricao_criterio';
------------------------------------------------------------------------------------------------------------------------


------------------------------------------------------------------------------------------------------------------------
-- Certificadores
------------------------------------------------------------------------------------------------------------------------
-- table
CREATE TABLE culturaviva_log.certificador (
    log_ts          TIMESTAMP without time zone,
    log_tp          CHAR,
    log_client      VARCHAR(50),
    log_user        VARCHAR(50),
    log_spid        int4,
    id              INTEGER,
    agente_id       INTEGER,
    ativo           BOOLEAN,
    tipo            CHAR,
    titular         BOOLEAN,
    ts_criacao      TIMESTAMP without time zone,
    ts_atualizacao  TIMESTAMP without time zone
);
COMMENT ON TABLE culturaviva_log.certificador IS 'Log de Auditoria da tabela culturaviva.criterio';
COMMENT ON COLUMN culturaviva_log.certificador.log_ts       IS 'Data de gravação do registro de auditoria, ou seja, quando a modificação foi realizada na tabela auditada.';
COMMENT ON COLUMN culturaviva_log.certificador.log_tp       IS 'Tipo da alteração realizada, podendo ser I (INSERT), U (UPDATE) ou D (DELETE).';
COMMENT ON COLUMN culturaviva_log.certificador.log_client   IS 'Host (IP:porta) de onde se originou o comando de alteração.';
COMMENT ON COLUMN culturaviva_log.certificador.log_user     IS 'Nome do usuário (banco de dados) que realizou a alteração.';
COMMENT ON COLUMN culturaviva_log.certificador.log_spid     IS 'Process ID do processo do servidor anexado à sessão atual. (Você pode utilizar as tabelas de log para recuperar informações para a sessão atual, util para depuração de erros)';

-- function
CREATE OR REPLACE FUNCTION culturaviva_log.certificador_fn_tg() RETURNS TRIGGER AS $BODY$
    DECLARE
        vlog_client VARCHAR(50);
        vRecord     RECORD;
        vAction     CHAR;
    BEGIN
        IF inet_client_addr() IS NULL THEN
            vlog_client := 'localhost';
        ELSE
            vlog_client := inet_client_addr()::varchar || ':' || inet_client_port();
        END IF;

        IF TG_OP = 'INSERT' THEN
            vRecord := NEW;
            vAction := 'I';
        ELSIF TG_OP = 'UPDATE' then
            vRecord := NEW;
            vAction := 'U';
        ELSIF TG_OP = 'DELETE' THEN
            vRecord := OLD;
            vAction := 'D';
        END IF;

        INSERT INTO culturaviva_log.certificador(
            log_ts, log_tp, log_client, log_user, log_spid,
            id, agente_id, ativo, tipo, titular, ts_criacao, ts_atualizacao
        )
        VALUES (
            clock_timestamp(), vAction, vlog_client, session_user, pg_backend_pid(),
            vRecord.id, vRecord.agente_id, vRecord.ativo, vRecord.tipo, vRecord.titular, vRecord.ts_criacao, vRecord.ts_atualizacao
        );

        RETURN vRecord;
    END;
$BODY$ LANGUAGE plpgsql VOLATILE SECURITY DEFINER COST 100;
COMMENT ON FUNCTION culturaviva_log.certificador_fn_tg() IS 'Função para Log de Auditoria da tabela culturaviva.certificador';

-- trigger
CREATE TRIGGER culturaviva_certificador_log_tg
    BEFORE INSERT OR UPDATE OR DELETE ON culturaviva.certificador
    FOR EACH ROW EXECUTE PROCEDURE culturaviva_log.certificador_fn_tg();
COMMENT ON TRIGGER culturaviva_certificador_log_tg ON culturaviva.certificador IS 'Trigger para Log de Auditoria em culturaviva_log.certificador';
------------------------------------------------------------------------------------------------------------------------


------------------------------------------------------------------------------------------------------------------------
-- Avaliações Pelos Certificadores
------------------------------------------------------------------------------------------------------------------------
-- table
CREATE TABLE culturaviva_log.avaliacao (
    log_ts          TIMESTAMP without time zone,
    log_tp          CHAR,
    log_client      VARCHAR(50),
    log_user        VARCHAR(50),
    log_spid        int4,
    id              INTEGER,
    inscricao_id    INTEGER,
    certificador_id INTEGER,
    estado          CHAR,
    observacoes     TEXT,
    ts_finalizacao  TIMESTAMP without time zone,
    ts_criacao      TIMESTAMP without time zone,
    ts_atualizacao  TIMESTAMP without time zone
);
COMMENT ON TABLE culturaviva_log.avaliacao IS 'Log de Auditoria da tabela culturaviva.criterio';
COMMENT ON COLUMN culturaviva_log.avaliacao.log_ts       IS 'Data de gravação do registro de auditoria, ou seja, quando a modificação foi realizada na tabela auditada.';
COMMENT ON COLUMN culturaviva_log.avaliacao.log_tp       IS 'Tipo da alteração realizada, podendo ser I (INSERT), U (UPDATE) ou D (DELETE).';
COMMENT ON COLUMN culturaviva_log.avaliacao.log_client   IS 'Host (IP:porta) de onde se originou o comando de alteração.';
COMMENT ON COLUMN culturaviva_log.avaliacao.log_user     IS 'Nome do usuário (banco de dados) que realizou a alteração.';
COMMENT ON COLUMN culturaviva_log.avaliacao.log_spid     IS 'Process ID do processo do servidor anexado à sessão atual. (Você pode utilizar as tabelas de log para recuperar informações para a sessão atual, util para depuração de erros)';

-- function
CREATE OR REPLACE FUNCTION culturaviva_log.avaliacao_fn_tg() RETURNS TRIGGER AS $BODY$
    DECLARE
        vlog_client VARCHAR(50);
        vRecord     RECORD;
        vAction     CHAR;
    BEGIN
        IF inet_client_addr() IS NULL THEN
            vlog_client := 'localhost';
        ELSE
            vlog_client := inet_client_addr()::varchar || ':' || inet_client_port();
        END IF;

        IF TG_OP = 'INSERT' THEN
            vRecord := NEW;
            vAction := 'I';
        ELSIF TG_OP = 'UPDATE' then
            vRecord := NEW;
            vAction := 'U';
        ELSIF TG_OP = 'DELETE' THEN
            vRecord := OLD;
            vAction := 'D';
        END IF;

        INSERT INTO culturaviva_log.avaliacao(
            log_ts, log_tp, log_client, log_user, log_spid,
            id, inscricao_id, certificador_id, estado, observacoes, ts_finalizacao, ts_criacao, ts_atualizacao
        )
        VALUES (
            clock_timestamp(), vAction, vlog_client, session_user, pg_backend_pid(),
            vRecord.id, vRecord.inscricao_id, vRecord.certificador_id, vRecord.estado, vRecord.observacoes, vRecord.ts_finalizacao, vRecord.ts_criacao, vRecord.ts_atualizacao
        );

        RETURN vRecord;
    END;
$BODY$ LANGUAGE plpgsql VOLATILE SECURITY DEFINER COST 100;
COMMENT ON FUNCTION culturaviva_log.avaliacao_fn_tg() IS 'Função para Log de Auditoria da tabela culturaviva.avaliacao';

-- trigger
CREATE TRIGGER culturaviva_avaliacao_log_tg
    BEFORE INSERT OR UPDATE OR DELETE ON culturaviva.avaliacao
    FOR EACH ROW EXECUTE PROCEDURE culturaviva_log.avaliacao_fn_tg();
COMMENT ON TRIGGER culturaviva_avaliacao_log_tg ON culturaviva.avaliacao IS 'Trigger para Log de Auditoria em culturaviva_log.avaliacao';
------------------------------------------------------------------------------------------------------------------------


------------------------------------------------------------------------------------------------------------------------
-- Valores para os critérios de uma Avaliação
------------------------------------------------------------------------------------------------------------------------
-- table
CREATE TABLE culturaviva_log.avaliacao_criterio (
    log_ts          TIMESTAMP without time zone,
    log_tp          CHAR,
    log_client      VARCHAR(50),
    log_user        VARCHAR(50),
    log_spid        int4,
    avaliacao_id    INTEGER,
    inscricao_id    INTEGER,
    criterio_id     INTEGER,
    aprovado        BOOLEAN
);
COMMENT ON TABLE culturaviva_log.avaliacao_criterio IS 'Log de Auditoria da tabela culturaviva.criterio';
COMMENT ON COLUMN culturaviva_log.avaliacao_criterio.log_ts       IS 'Data de gravação do registro de auditoria, ou seja, quando a modificação foi realizada na tabela auditada.';
COMMENT ON COLUMN culturaviva_log.avaliacao_criterio.log_tp       IS 'Tipo da alteração realizada, podendo ser I (INSERT), U (UPDATE) ou D (DELETE).';
COMMENT ON COLUMN culturaviva_log.avaliacao_criterio.log_client   IS 'Host (IP:porta) de onde se originou o comando de alteração.';
COMMENT ON COLUMN culturaviva_log.avaliacao_criterio.log_user     IS 'Nome do usuário (banco de dados) que realizou a alteração.';
COMMENT ON COLUMN culturaviva_log.avaliacao_criterio.log_spid     IS 'Process ID do processo do servidor anexado à sessão atual. (Você pode utilizar as tabelas de log para recuperar informações para a sessão atual, util para depuração de erros)';

-- function
CREATE OR REPLACE FUNCTION culturaviva_log.avaliacao_criterio_fn_tg() RETURNS TRIGGER AS $BODY$
    DECLARE
        vlog_client VARCHAR(50);
        vRecord     RECORD;
        vAction     CHAR;
    BEGIN
        IF inet_client_addr() IS NULL THEN
            vlog_client := 'localhost';
        ELSE
            vlog_client := inet_client_addr()::varchar || ':' || inet_client_port();
        END IF;

        IF TG_OP = 'INSERT' THEN
            vRecord := NEW;
            vAction := 'I';
        ELSIF TG_OP = 'UPDATE' then
            vRecord := NEW;
            vAction := 'U';
        ELSIF TG_OP = 'DELETE' THEN
            vRecord := OLD;
            vAction := 'D';
        END IF;

        INSERT INTO culturaviva_log.avaliacao_criterio(
            log_ts, log_tp, log_client, log_user, log_spid,
            avaliacao_id, inscricao_id, criterio_id, aprovado
        )
        VALUES (
            clock_timestamp(), vAction, vlog_client, session_user, pg_backend_pid(),
            vRecord.avaliacao_id, vRecord.inscricao_id, vRecord.criterio_id, vRecord.aprovado
        );

        RETURN vRecord;
    END;
$BODY$ LANGUAGE plpgsql VOLATILE SECURITY DEFINER COST 100;
COMMENT ON FUNCTION culturaviva_log.avaliacao_criterio_fn_tg() IS 'Função para Log de Auditoria da tabela culturaviva.avaliacao_criterio';

-- trigger
CREATE TRIGGER culturaviva_avaliacao_criterio_log_tg
    BEFORE INSERT OR UPDATE OR DELETE ON culturaviva.avaliacao_criterio
    FOR EACH ROW EXECUTE PROCEDURE culturaviva_log.avaliacao_criterio_fn_tg();
COMMENT ON TRIGGER culturaviva_avaliacao_criterio_log_tg ON culturaviva.avaliacao_criterio IS 'Trigger para Log de Auditoria em culturaviva_log.avaliacao_criterio';
------------------------------------------------------------------------------------------------------------------------
