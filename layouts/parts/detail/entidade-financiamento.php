<div  >
    <div class="form">
        <!-- <h4>Informações Opcionais</h4> -->

        <div class="row">
            <div class="colunm-50">
                <span class="destaque" ng-show="entidade.tipoOrganizacao==='entidade' || !entidade.tipoOrganizacao">A Entidade já foi financiada pelo MinC <i class='hltip' title='Marque caso você tenha recebido algum recurso direto do MinC'>?</i></span>
                <span class="destaque" ng-show="entidade.tipoOrganizacao==='coletivo'">O Coletivo já foi financiada pelo MinC <i class='hltip' title='Marque SIM se o coletivo cultural ja recebeu algum recurso do MiniC'>?</i></span>
                <span ng-if="entidade.foiFomentado"><b>Sim</b></span>
                <span ng-if="entidade.foiFomentado === 0"><b>Não</b></span>
                <span ng-if="!entidade.foiFomentado"><b>Não informado</b></span>
            </div>
        </div>

        <div ng-show="entidade.foiFomentado">
            <div class="row">
                <label class="colunm-1">
                    <span class="destaque" ng-show="entidade.tipoOrganizacao==='entidade'">Qual o principal financiamento que a Entidade recebe ou recebeu?</span>
                    <span class="destaque" ng-show="entidade.tipoOrganizacao==='coletivo'">Qual o principal financiamento que o Coletivo recebe ou recebeu?</span>
                    <span><b>{{entidade.tipoFomento}}</b></span>
                    <span ng-if="!entidade.tipoFomento"><b>Não informado</b></span>
                </label>
            </div>
            <div class="clear"></div>
            <div class="row">
                <div class="colunm-full">
                    <span class="destaque">Tipo de Reconhecimento <i>?</i></span>
                    <span><b>{{entidade.fomento_tipoReconhecimento}}</b></span>
                    <span ng-if="!entidade.fomento_tipoReconhecimento"><b>Não informado</b></span>
                </div>
            </div>
            <div class="clear"></div>
            <div ng-if="entidade.rcv_Ds_Edital" class="row">
                <label class="colunm-50">
                    <span class="destaque"> Edital encontrado na importação pelo CNPJ <i>?</i></span> {{entidade.rcv_Ds_Edital}}
                </label>
            </div>
            <div class="row">
                <label class="colunm-50">
                    <span class="destaque">Número do Edital de Seleção</span>
                    <span><b>{{entidade.edital_num}}</b></span>
                    <span ng-if="!entidade.edital_num"><b>Não informado</b></span>
                </label>
                <label class="colunm-50">
                    <span class="destaque">Ano do Edital de Seleção</span>
                    <span><b>{{entidade.edital_ano}}</b></span>
                    <span ng-if="!entidade.edital_ano"><b>Não informado</b></span>
                </label>

            </div>
            <div class="clear"></div>
            <div class="row">
                <label class="colunm-50">
                    <span class="destaque">Título do Projeto</span>
                    <span><b>{{entidade.edital_projeto_nome}}</b></span>
                    <span ng-if="!entidade.edital_projeto_nome"><b>Não informado</b></span>
                </label>

                <label class="colunm-50">

                    <span class="destaque">Local de Realização <i class='hltip' title='Local onde o projeto foi realizado'>?</i></span>
                    <span><b>{{entidade.edital_localRealizacao}}</b></span>
                    <span ng-if="!entidade.edital_localRealizacao"><b>Não informado</b></span>
                </label>
            </div>
            <div class="clear"></div>
            <div class="row">
                <label class="colunm-50">
                    <span class="destaque">Proponente </span>
                    <span><b>{{entidade.edital_proponente}}</b></span>
                    <span ng-if="!entidade.edital_proponente"><b>Não informado</b></span>
                </label>
            </div>
            <div class="clear"></div>
            <div class="row">
                <label class="colunm-full">
                    <span class="destaque">Resumo do projeto (objeto)</span>
                    <span><b>{{entidade.edital_projeto_resumo}}</b></span>
                    <span ng-if="!entidade.edital_projeto_resumo"><b>Não informado</b></span>
                </label>
            </div>
            <div class="clear"></div>
            <div class="row">
                <div class="colunm-50">
                    <span class="destaque">Etapa do Projeto</span>
                    <span ng-if="entidade.edital_projeto_etapa === 'emexecucao'"><b>Em Execução</b></span>
                    <span ng-if="entidade.edital_projeto_etapa === 'executado'"><b>Já executado</b></span>
                </div>
            </div>
            <div class="clear"></div>
            <div ng-show="entidade.edital_projeto_etapa==='executado'">
                <div class="row">
                    <div class="colunm-50">
                        <span class="destaque">Prestação de Contas</span>
                        <span ng-if="entidade.edital_prestacaoContas_envio === enviada"><b>Enviada</b></span>
                        <span ng-if="entidade.edital_prestacaoContas_envio === naoEnviada"><b>Não enviada</b></span>
                        <span ng-if="entidade.edital_prestacaoContas_envio === premiado"><b>Ponto de Cultura Premiado</b></span>
                    </div>
                </div>
                <div class="row" ng-show="entidade.edital_prestacaoContas_envio==='enviada'">
                    <div class="colunm-50">
                        <span ng-if="entidade.edital_prestacaoContas_status === 'aprovada'"><b>Aprovada</b></span>
                        <span ng-if="entidade.edital_prestacaoContas_status === 'naoaprovada'"><b>Não aprovada</b></span>
                        <span ng-if="entidade.edital_prestacaoContas_status === 'analise'"><b>Em Análise</b></span>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <div class="row">

                <label class="colunm-full vigencia">
                    <span class="destaque">Vigência:    </span>
                    <span><b>De {{entidade.edital_projeto_vigencia_inicio}} até {{entidade.edital_projeto_vigencia_fim}}</b></span>
                    <span ng-if="!entidade.edital_projeto_vigencia_inicio"><b>Não informado</b></span>
                </label>
            </div>
            <div class="clear"></div>
            <div class="row">
                <div class="colunm-full">
                   <span class="destaque">Recebe ou recebeu outros financiamentos? (apoios, patrocínios, prêmios, bolsas, convênios, etc)</span>
                   <span ng-if="entidade.outrosFinanciamentos === 1"><b>Sim</b></span>
                   <span ng-if="entidade.outrosFinanciamentos === 0"><b>Não</b></span>
                </div>
            </div>
            <div class="row" ng-show="entidade.outrosFinanciamentos">
                <label class="colunm-50">
                    <span class="destaque">Quais ?</span>
                    <span><b>{{entidade.outrosFinanciamentos_descricao}}</b></span>
                    <span ng-if="!entidade.outrosFinanciamentos_descricao"><b>Não informado</b></span>
                </label>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>
