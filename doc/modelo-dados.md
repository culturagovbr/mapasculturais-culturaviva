## Modelo de Dados, Log e Dicionário

- [Logs de Auditoria](#log-de-auditoria)
- [Modelo Físico](#modelo-fisico)
- [Dicionário de Dados](#dicionario-de-dados)
    - [Tabela culturaviva.criterio](#tabela-culturaviva-criterio)
        - [Campos](#tabela-culturaviva-criterio-campos)
    - [Tabela culturaviva.inscricao](#tabela-culturaviva-inscricao)
        - [Campos](#tabela-culturaviva-inscricao-campos)
        - [Constraints](#tabela-culturaviva-inscricao-constraints)
    - [Tabela culturaviva.inscricao_criterio](#tabela-culturaviva-inscricao-criterio)
        - [Campos](#tabela-culturaviva-inscricao-criterio-campos)
        - [Constraints](#tabela-culturaviva-inscricao-criterio-constraints)
    - [Tabela culturaviva.certificador](#tabela-culturaviva-certificador)
        - [Campos](#tabela-culturaviva-certificador-campos)
        - [Constraints](#tabela-culturaviva-certificador-constraints)
    - [Tabela culturaviva.avaliacao](#tabela-culturaviva-avaliacao)
        - [Campos](#tabela-culturaviva-avaliacao-campos)
        - [Constraints](#tabela-culturaviva-avaliacao-constraints)
        - [Triggers](#tabela-culturaviva-avaliacao-triggers)
            - [culturaviva_avaliacao_tg_validacoes](#tabela-culturaviva-avaliacao-triggers-tg-validacoes)
    - [Tabela culturaviva.avaliacao_criterio](#tabela-culturaviva-avaliacao-criterio)
      - [Campos](#tabela-culturaviva-avaliacao-criterio-campos)
      - [Constraints](#tabela-culturaviva-avaliacao-criterio-constraints)

<a name="log-de-auditoria"></a>
## Logs de Auditoria

[Log de Dados é](https://pt.wikipedia.org/wiki/Log_de_dados) uma expressão utilizada para descrever o processo de registro de eventos relevantes num sistema computacional. Esse registro pode ser utilizado para restabelecer o estado original de um sistema ou para que um administrador conheça o seu comportamento no passado. Um arquivo de log pode ser utilizado para auditoria e diagnóstico de problemas em sistemas computacionais.

Na **Rede Cultura Viva**, o log de alterações são registrados no *schema* **culturaviva_log**.


Para cada tabela do *schema* **culturaviva**, existem uma entidade relacionada contendo os mesmos campos da entidade principal acrescido dos campos abaixo.


| Coluna      |      Descrição  |
|-------------|--------------------------------|
| log_ts      | Data de gravação do registro de auditoria, ou seja, quando a modificação foi realizada na tabela auditada|
| log_tp      | Tipo da alteração realizada, podendo ser [**I**] *INSERT*, [**U**] *UPDATE* ou [**D**] *DELETE*|
| log_client  | Host (IP:porta) de onde se originou o comando de alteração|
| log_user    | Nome do usuário (banco de dados) que realizou a alteração|
| log_spid    | ID do processo do servidor anexado à sessão atual. (Você pode utilizar as tabelas de log para recuperar informações para a sessão atual, util para depuração de erros)|

<a name="modelo-fisico"></a>
## Modelo Físico

[![Modelo de Dados da Rede Cultura Viva](img/modelo-dados.png)](img/modelo-dados.png)



<a name="dicionario-de-dados"></a>
## Dicionário de Dados


<a name="tabela-culturaviva-criterio"></a>
### Tabela culturaviva.criterio

Registra os Critérios usados para avaliação de uma Inscrição.

Um registro de critério não pode sofrer alteração, deve ser inserido um novo registro sempre que sofrer alterção, pois a avaliação das inscrições serão feitos pelos critérios existentes na epoca da finalização do cadastro pela entidade

<a name="tabela-culturaviva-criterio-campos"></a>
#### Campos
 
| PK |       Campo     |    Tipo   | FK | Descrição |
|:--:|-----------------|-----------|----|-----------|
| PK | id              | INTEGER   |    | Identificador do critério |
|    | ordem           | INTEGER   |    | Informa a ordem de exibição deste critério |
|    | ativo           | BOOLEAN   |    | Informa se este critério está ativo |
|    | descricao       | TEXT      |    | Texto descritivo do critério |
|    | ts_criacao      | TIMESTAMP |    | Quando o registro foi criado |





<a name="tabela-culturaviva-inscricao"></a>
### Tabela culturaviva.inscricao

Registra as Inscrições originadas pelo cadastro feito pelo Pontão/Ponto de Cultura


<a name="tabela-culturaviva-inscricao-campos"></a>
#### Campos
 
| PK |       Campo     |    Tipo   | FK | Descrição |
|:--:|-----------------|-----------|----|-----------|
| PK | id              | INTEGER   |    | Identificador da Inscrição |
|    | agente_id       | INTEGER   | FK | Referencia para o Pontão/Ponto de Cultura solicitante |
|    | estado          | CHAR      |    | Estados da inscrição: [**P**] *Pendente*, [**C**] *Certificado*, [**N**] *Não Certificado*  e [**R**] *ReSubmissão*. (*ReSubmissão = Inscrição rejeitada pelos certificadores, cadastro alterado pelo Ponto de Cultura e nova Inscrição criada para reavaliação*) |
|    | ts_criacao      | TIMESTAMP |    | Quando o registro foi criado |
|    | ts_finalizacao  | TIMESTAMP |    | Quando a avaliação da inscrição foi finalizada, alterando o estado da inscrição para "C - Certificado" ou "N - Não Certificado |

<a name="tabela-culturaviva-inscricao-constraints"></a>
#### Constraints

| Tipo | Nome | Detalhes | Descrição |
|:----:|------|----------|-----------|
| CHECK | inscricao_estado_ck | estado = ANY(['P','C','N','R']) | Validação de consistência para o campo *culturaviva.inscricao.estado* |
|   FK  | inscricao_agente_id_fk | REFERENCES mapas.agent(id) | Chave estrangeira para o agente na base do Mapas Culturais |





<a name="tabela-culturaviva-inscricao-criterio"></a>
### Tabela culturaviva.inscricao_criterio

Registra os critérios de avaliação de uma Inscrição específica.


<a name="tabela-culturaviva-inscricao-criterio-campos"></a>
#### Campos
 
| PK |       Campo     |    Tipo   | FK | Descrição |
|:--:|-----------------|-----------|----|-----------|
| PK | inscricao_id    | INTEGER   | FK | Referencia para a Incrição do Pontao/Ponto de Cultura |
| PK | criterio_id     | INTEGER   | FK | Referencia para o Critério de Avaliação |
|    | ts_criacao      | TIMESTAMP |    | Quando o registro foi criado |

<a name="tabela-culturaviva-inscricao-criterio-constraints"></a>
#### Constraints

| Tipo | Nome | Detalhes | Descrição |
|:----:|------|----------|-----------|
|   FK  | inscricao_criterio_inscricao_id_fk | REFERENCES culturaviva.inscricao(id) | Chave estrangeira para a Incrição do Pontao/Ponto de Cultura |
|   FK  | inscricao_criterio_criterio_id_fk | REFERENCES culturaviva.criterio(id) | Chave estrangeira para o Critério de Avaliação |




<a name="tabela-culturaviva-certificador"></a>
### Tabela culturaviva.certificador

Registra os Agentes Certificadores do sistema.


<a name="tabela-culturaviva-certificador-campos"></a>
#### Campos
 
| PK |       Campo     |    Tipo   | FK | Descrição |
|:--:|-----------------|-----------|----|-----------|
| PK | id              | INTEGER   |    | Identificador do certificador |
|    | agente_id       | INTEGER   | FK | Referencia para o usuário AGENT cadastrado no schema do MapasCulturais |
|    | ativo           | BOOLEAN   |    | Informa se este certificadro está ativo |
|    | tipo            | CHAR      |    | Identifica o Tipo de Certificador: [**C**] *Pessoa da Sociedade Civil*, [**P**] *Membro do Poder Publico* e [**M**] *Certificador com Voto de Minerva* |
|    | titular         | BOOLEAN   |    | Informa se este certificador é TITULAR ou SUPLENTE |
|    | ts_criacao      | TIMESTAMP |    | Quando o registro foi criado |
|    | ts_atualizacao  | TIMESTAMP |    | Quando o registro foi atualizado |


<a name="tabela-culturaviva-certificador-constraints"></a>
#### Constraints

| Tipo | Nome | Detalhes | Descrição |
|:----:|------|----------|-----------|
|UNIQUE | certificador_agente_tipe_uk | UNIQUE (agente_id, tipo) | Só pode existir um registro por agente/tipo |
| CHECK | certificador_tipo_ck | tipo = ANY(['C','P','M']) | Validação de consistência para o campo *culturaviva.certificador.tipo*  |
|   FK  | certificador_agente_id_fk | REFERENCES  mapas.agent(id) | Chave estrangeira para o agente na base do Mapas Culturais |





<a name="tabela-culturaviva-avaliacao"></a>
### Tabela culturaviva.avaliacao

Registra as avaliações feitas pelos Certificadores sobre as Inscrições.


<a name="tabela-culturaviva-avaliacao-campos"></a>
#### Campos
 
| PK |       Campo     |    Tipo   | FK | Descrição |
|:--:|-----------------|-----------|----|-----------|
| PK | id              | INTEGER   |    | Identificador da avaliação |
|    | inscricao_id    | INTEGER   | FK | Referencia para a Incrição do Pontao/Ponto de Cultura |
|    | certificador_id | INTEGER   | FK | Referência para o Certificador responsável |
|    | estado          | CHAR      |    | Estado da Avaliação: [**P**] *Pendente*, [**A**] *Em Analise*, [**D**] *Deferido*, [**I**] *Indeferido* e [**C**] *Cancelado*. (*Cancelado: Se um certificador for inativado, as Avaliações com estado "Pendente" e "Em Análise" deste Certificador serão cancelados e redistribuidos para outro certificador ativo*) |
|    | observacoes     | TEXT      |    | Comentários adicionados pelo Certificador |
|    | ts_finalizacao  | TIMESTAMP |    | Quando a Avaliação foi Finalizada pelo Certificador |
|    | ts_criacao      | TIMESTAMP |    | Quando o registro foi criado |
|    | ts_atualizacao  | TIMESTAMP |    | Quando o registro sofreu atualização |


<a name="tabela-culturaviva-avaliacao-constraints"></a>
#### Constraints

| Tipo | Nome | Detalhes | Descrição |
|:----:|------|----------|-----------|
|UNIQUE | avaliacao_uk | UNIQUE (inscricao_id, certificador_id) | Um Certificador não pode fazer mais de uma Avaliação da mesma Inscrição |
| CHECK | avaliacao_estado_ck | estado = ANY(['P','A','D','I','C']) | Validação de consistência para o campo *culturaviva.avaliacao.estado*  |
|   FK  | avaliacao_inscricao_id_fk | REFERENCES  culturaviva.inscricao(id) | Chave estrangeira para a Incrição do Pontao/Ponto de Cultura |
|   FK  | avaliacao_certificador_id_fk | REFERENCES culturaviva.certificador(id) | Chave estrangeira para o Certificador da Avaliação |

<a name="tabela-culturaviva-avaliacao-triggers"></a>
#### Triggers


<a name="tabela-culturaviva-avaliacao-triggers-tg-validacoes"></a>
##### culturaviva_avaliacao_tg_validacoes

Validação de consistência de Avaliações: 

 - [**1**] *Avaliações devem ser criadas com estado "P - Pendente"*
 - [**2**] *Não é permitido Cancelar uma avaliação já Finalizada*

**Quando**: *BEFORE INSERT OR UPDATE*
**Detalhes**: *FOR EACH ROW EXECUTE PROCEDURE culturaviva_avaliacao_fn_validacoes()*



<a name="tabela-culturaviva-avaliacao-criterio"></a>
### Tabela culturaviva.avaliacao_criterio

Registra os valores para os Critérios de uma Inscrição Avaliados por um Certificador.


<a name="tabela-culturaviva-avaliacao-criterio-campos"></a>
#### Campos
 
| PK |       Campo     |    Tipo   | FK | Descrição |
|:--:|-----------------|-----------|----|-----------|
| PK | avaliacao_id    | INTEGER   | FK | Referencia para a Avaliação da Inscrição |
| PK | inscricao_id    | INTEGER   | FK | Referencia para a Incrição do Pontao/Ponto de Cultura |
| PK | criterio_id     | INTEGER   | FK | Referencia para o Critério de Avaliação |
|    | aprovado        | BOOLEAN   |    | Informa se o critério foi marcado como APROVADO pelo Avaliador |


<a name="tabela-culturaviva-avaliacao-criterio-constraints"></a>
#### Constraints

| Tipo | Nome | Detalhes | Descrição |
|:----:|------|----------|-----------|
|   FK  | avaliacao_criterio_avaliacao_id_fk | REFERENCES culturaviva.avaliacao(id) | Chave estrangeira para a Avaliação da Inscrição |
|   FK  | avaliacao_criterio_inscricao_criterio_fk | REFERENCES culturaviva.inscricao_criterio (inscricao_id, criterio_id) | Chave estrangeira para o Critério de Avaliação da Inscrição|

