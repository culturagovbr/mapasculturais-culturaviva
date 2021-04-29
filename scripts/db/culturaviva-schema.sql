------------------------------------------------------------------------------------------------------------------------
-- Schema
------------------------------------------------------------------------------------------------------------------------
-- DROP SCHEMA culturaviva CASCADE;

CREATE SCHEMA IF NOT EXISTS culturaviva;
------------------------------------------------------------------------------------------------------------------------


------------------------------------------------------------------------------------------------------------------------
-- Critérios de Avaliação
------------------------------------------------------------------------------------------------------------------------
CREATE SEQUENCE  culturaviva.criterio_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE  IF NOT EXISTS culturaviva.criterio (
    id          INTEGER DEFAULT nextval('culturaviva.criterio_id_seq'::regclass) NOT NULL,
    ordem       INTEGER NOT NULL,
    ativo       BOOLEAN NOT NULL,
    descricao   TEXT NOT NULL,
    ts_criacao  TIMESTAMP without time zone DEFAULT now() NOT NULL,
    CONSTRAINT criterio_pk PRIMARY KEY (id)
);

COMMENT ON TABLE culturaviva.criterio IS 'Registra os Critérios usados para avaliação de uma Inscrição

Um registro de critério não pode sofrer alteração, deve ser inserido um novo registro sempre que sofrer alterção,
pois a avaliação das inscrições serão feitos pelos critérios existentes na epoca da finalização do cadastro pela
entidade';
COMMENT ON COLUMN culturaviva.criterio.id           IS 'Identificador do critério';
COMMENT ON COLUMN culturaviva.criterio.ordem        IS 'Informa a ordem de exibição deste critério';
COMMENT ON COLUMN culturaviva.criterio.ativo        IS 'Informa se este critério está ativo';
COMMENT ON COLUMN culturaviva.criterio.descricao    IS 'Texto descritivo do critério';
COMMENT ON COLUMN culturaviva.criterio.ts_criacao   IS 'Quando o registro foi criado';
------------------------------------------------------------------------------------------------------------------------


------------------------------------------------------------------------------------------------------------------------
-- Inscrições
------------------------------------------------------------------------------------------------------------------------
CREATE SEQUENCE  culturaviva.inscricao_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE  IF NOT EXISTS culturaviva.inscricao (
    id              INTEGER DEFAULT nextval('culturaviva.inscricao_id_seq'::regclass) NOT NULL,
    agente_id       INTEGER NOT NULL,
    estado          CHAR NOT NULL DEFAULT 'P',
    ts_criacao      TIMESTAMP without time zone DEFAULT now() NOT NULL,
    ts_finalizacao  TIMESTAMP without time zone NULL,
    CONSTRAINT inscricao_pk PRIMARY KEY (id),
    CONSTRAINT inscricao_estado_ck CHECK (estado = ANY(ARRAY['P','C','N','R'])),
    CONSTRAINT inscricao_agente_id_fk FOREIGN KEY (agente_id)
        REFERENCES agent (id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

COMMENT ON TABLE culturaviva.inscricao IS 'Registra as Inscrições originadas pelo cadastro feito pelo Pontão/Ponto de Cultura';
COMMENT ON COLUMN culturaviva.inscricao.id              IS 'Identificador da Inscrição';
COMMENT ON COLUMN culturaviva.inscricao.agente_id       IS 'Referencia para o Pontão/Ponto de Cultura solicitante';
COMMENT ON COLUMN culturaviva.inscricao.estado          IS 'Estados da inscrição

P - Pendente
C - Certificado
N - Não Certificado
R - Re Submissão - Inscrição rejeitada pelos certificadores, cadastro alterado pelo Ponto de Cultura e
nova Inscrição criada para reavaliação';
COMMENT ON COLUMN culturaviva.inscricao.ts_criacao      IS 'Quando o registro foi criado';
COMMENT ON COLUMN culturaviva.inscricao.ts_finalizacao  IS 'Quando a avaliação da inscrição foi finalizada, alterando
o estado da inscrição para "C - Certificado" ou "N - Não Certificado"';
------------------------------------------------------------------------------------------------------------------------


------------------------------------------------------------------------------------------------------------------------
-- Critérios de Avaliação da Inscrição
------------------------------------------------------------------------------------------------------------------------
CREATE TABLE  IF NOT EXISTS culturaviva.inscricao_criterio (
    inscricao_id    INTEGER NOT NULL,
    criterio_id     INTEGER NOT NULL,
    ts_criacao      TIMESTAMP without time zone DEFAULT now() NOT NULL,
    CONSTRAINT inscricao_criterio_pk PRIMARY KEY (inscricao_id, criterio_id),
    CONSTRAINT inscricao_criterio_inscricao_id_fk FOREIGN KEY (inscricao_id)
        REFERENCES culturaviva.inscricao (id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT inscricao_criterio_criterio_id_fk FOREIGN KEY (criterio_id)
        REFERENCES culturaviva.criterio (id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

COMMENT ON TABLE culturaviva.inscricao_criterio IS 'Registra os critérios de avaliação de uma inscrição';
COMMENT ON COLUMN culturaviva.inscricao_criterio.inscricao_id   IS 'Referencia para a Incrição do Pontao/Ponto de Cultura';
COMMENT ON COLUMN culturaviva.inscricao_criterio.criterio_id    IS 'Referencia para o Critério de Avaliação';
COMMENT ON COLUMN culturaviva.inscricao_criterio.ts_criacao     IS 'Quando o registro foi criado';
------------------------------------------------------------------------------------------------------------------------


------------------------------------------------------------------------------------------------------------------------
-- Certificadores
------------------------------------------------------------------------------------------------------------------------
CREATE SEQUENCE  culturaviva.certificador_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


CREATE TABLE  IF NOT EXISTS culturaviva.certificador (
    id              INTEGER DEFAULT nextval('culturaviva.certificador_id_seq'::regclass) NOT NULL,
    agente_id       INTEGER NOT NULL,
    ativo           BOOLEAN NOT NULL DEFAULT TRUE,
    tipo            CHAR NOT NULL,
    titular         BOOLEAN NOT NULL DEFAULT TRUE,
    ts_criacao      TIMESTAMP without time zone DEFAULT now() NOT NULL,
    ts_atualizacao  TIMESTAMP without time zone,
    CONSTRAINT certificador_pk PRIMARY KEY (id),
    CONSTRAINT certificador_agente_tipe_uk UNIQUE (agente_id, tipo),
    CONSTRAINT certificador_tipo_ck CHECK (tipo = ANY(ARRAY['C','P','M'])),
    CONSTRAINT certificador_agente_id_fk FOREIGN KEY (agente_id)
        REFERENCES agent (id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

COMMENT ON TABLE culturaviva.certificador IS 'Registra os Agentes Certificadores do sistema';
COMMENT ON COLUMN culturaviva.certificador.id               IS 'Identificador do certificador';
COMMENT ON COLUMN culturaviva.certificador.agente_id
    IS 'Referencia para o usuário AGENT cadastrado no schema do MapasCulturais';
COMMENT ON COLUMN culturaviva.certificador.ativo            IS 'Informa se este certificadro está ativo';
COMMENT ON COLUMN culturaviva.certificador.tipo             IS 'Identifica o Tipo de Certificador

C - Pessoa da Sociedade Civil
P - Membro do Poder Publico
M - Certificador com Voto de Minerva';
COMMENT ON COLUMN culturaviva.certificador.titular          IS 'Informa se este certificador é TITULAR ou SUPLENTE';
COMMENT ON COLUMN culturaviva.certificador.ts_criacao       IS 'Quando o registro foi criado';
COMMENT ON COLUMN culturaviva.certificador.ts_atualizacao   IS 'Quando o registro foi atualizado';
------------------------------------------------------------------------------------------------------------------------


------------------------------------------------------------------------------------------------------------------------
-- Avaliações Pelos Certificadores
------------------------------------------------------------------------------------------------------------------------
CREATE SEQUENCE  culturaviva.avaliacao_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE  IF NOT EXISTS culturaviva.avaliacao (
    id              INTEGER DEFAULT nextval('culturaviva.avaliacao_id_seq'::regclass) NOT NULL,
    inscricao_id    INTEGER NOT NULL,
    certificador_id INTEGER NOT NULL,
    estado          CHAR NOT NULL,
    observacoes     TEXT,
    ts_finalizacao  TIMESTAMP without time zone NULL,
    ts_criacao      TIMESTAMP without time zone DEFAULT now() NOT NULL,
    ts_atualizacao  TIMESTAMP without time zone NULL,
    CONSTRAINT avaliacao_pk PRIMARY KEY (id),
    CONSTRAINT avaliacao_uk UNIQUE (inscricao_id, certificador_id),
    CONSTRAINT avaliacao_estado_ck CHECK (estado = ANY(ARRAY['P','A','D','I','C'])),
    CONSTRAINT avaliacao_inscricao_id_fk FOREIGN KEY (inscricao_id)
        REFERENCES culturaviva.inscricao (id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT avaliacao_certificador_id_fk FOREIGN KEY (certificador_id)
        REFERENCES culturaviva.certificador (id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

COMMENT ON TABLE culturaviva.avaliacao IS 'Registra as avaliações feitas pelos certificadores sobre as Inscrições';
COMMENT ON COLUMN culturaviva.avaliacao.id              IS 'Identificador da avaliação';
COMMENT ON COLUMN culturaviva.avaliacao.inscricao_id    IS 'Referencia para a Incrição do Pontao/Ponto de Cultura';
COMMENT ON COLUMN culturaviva.avaliacao.certificador_id IS 'Referência para o Certificador responsável';
COMMENT ON COLUMN culturaviva.avaliacao.estado          IS 'Estado da Avaliação.

P - Pendente
A - Em Analise
D - Deferido
I - Indeferido
C - Cancelado - Se um certificador for inativado, as avaliações com estado "Pendente" e "Em Análise" deste certificador
serão cancelados e redistribuidos para outro certificador ativo';
COMMENT ON COLUMN culturaviva.avaliacao.observacoes     IS 'Comentários adicionados pelo Certificador';
COMMENT ON COLUMN culturaviva.avaliacao.ts_finalizacao  IS 'Quando a Avaliação foi Finalizada pelo Certificador';
COMMENT ON COLUMN culturaviva.avaliacao.ts_criacao      IS 'Quando o registro foi criado';
COMMENT ON COLUMN culturaviva.avaliacao.ts_atualizacao  IS 'Quando o registro sofreu atualização';


-- Validação de consistencia dos estados da avaliação
DROP FUNCTION IF EXISTS culturaviva_avaliacao_fn_validacoes();

CREATE FUNCTION culturaviva_avaliacao_fn_validacoes() RETURNS TRIGGER AS $BODY$
    BEGIN

        IF (TG_OP = 'INSERT' AND NEW.estado <> 'P') THEN
            RAISE EXCEPTION 'Avaliações devem ser criadas com estado "P - Pendente"';
        ELSIF (TG_OP = 'UPDATE' AND NEW.estado <> 'C') THEN
            -- NÃO PERMITIR CANCELAR AVALIAÇÕES FINALIZADAS
            -- Só permite alterar para o estado "C - Cancelado" se a avaliação
            -- estiver nos estados "P - Pendente" ou "A - Em Analise"
            IF (OLD.estado <> 'P' AND OLD.estado <> 'A') THEN
                RAISE EXCEPTION 'Não é permitido Cancelar uma avaliação já Finalizada';
            END IF;
        END IF;

        RETURN NEW;
    END;
$BODY$ LANGUAGE plpgsql;

CREATE TRIGGER culturaviva_avaliacao_tg_validacoes
    BEFORE INSERT OR UPDATE ON culturaviva.avaliacao
    FOR EACH ROW EXECUTE PROCEDURE culturaviva_avaliacao_fn_validacoes();
------------------------------------------------------------------------------------------------------------------------


------------------------------------------------------------------------------------------------------------------------
-- Valores para os critérios de uma Avaliação
------------------------------------------------------------------------------------------------------------------------
CREATE TABLE  IF NOT EXISTS culturaviva.avaliacao_criterio (
    avaliacao_id    INTEGER NOT NULL,
    inscricao_id    INTEGER NOT NULL,
    criterio_id     INTEGER NOT NULL,
    aprovado        BOOLEAN NULL,
    CONSTRAINT avaliacao_criterio_pk PRIMARY KEY (avaliacao_id, inscricao_id, criterio_id),
    CONSTRAINT avaliacao_criterio_avaliacao_id_fk FOREIGN KEY (avaliacao_id)
        REFERENCES culturaviva.avaliacao (id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT avaliacao_criterio_inscricao_criterio_fk FOREIGN KEY (inscricao_id, criterio_id)
        REFERENCES culturaviva.inscricao_criterio (inscricao_id, criterio_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

COMMENT ON TABLE culturaviva.avaliacao_criterio IS 'Registra os valores para os Critérios de uma Inscrição Avaliados por um Certificador';
COMMENT ON COLUMN culturaviva.avaliacao_criterio.avaliacao_id   IS 'Referencia para a Avaliação da Inscrição';
COMMENT ON COLUMN culturaviva.avaliacao_criterio.inscricao_id   IS 'Referencia para a Incrição do Pontao/Ponto de Cultura';
COMMENT ON COLUMN culturaviva.avaliacao_criterio.criterio_id    IS 'Referencia para o Critério de Avaliação';
COMMENT ON COLUMN culturaviva.avaliacao_criterio.aprovado       IS 'Informa se o critério foi marcado como APROVADO pelo
Avaliador';
------------------------------------------------------------------------------------------------------------------------
