<?php
    $this->bodyProperties['ng-app'] = "culturaviva";
    $this->layout = 'cadastro';
    $this->cadastroTitle = '5. Projetos Financiados';
    $this->cadastroText = 'O Min. Cidadania possui diversas formas de financiamento. Você já recebeu algum prêmio ou participou de algum edital?';
    $this->cadastroIcon = 'icon-dollar';
    $this->cadastroPageClass = 'contato-entidade page-base-form';
    $this->cadastroLinkContinuar = 'articulacao';
?>


<form name="form_entityContact" ng-controller="EntityContactCtrl">
    <?php $this->part('messages'); ?>
    <div class="form">
        <h4>Informações Opcionais</h4>

        <div class="row">
            <div class="colunm-50">
                <span class="destaque" ng-show="agent.tipoOrganizacao==='entidade' || !agent.tipoOrganizacao">A Entidade já foi financiada pelo Min. Cidadania <i class='hltip' title='Marque caso você tenha recebido algum recurso direto do Min. Cidadania'>?</i></span>
                <span class="destaque" ng-show="agent.tipoOrganizacao==='coletivo'">O Coletivo já foi financiada pelo Min. Cidadania <i class='hltip' title='Marque SIM se o coletivo cultural ja recebeu algum recurso do MiniC'>?</i></span>
                <label class="label-radio">
                    <input type="radio"
                           name="foiFomentado"
                           ng-value="1"
                           ng-change="save_field('foiFomentado')"
                           ng-model="agent.foiFomentado"> Sim
                </label>
                <label class="label-radio">
                    <input type="radio"
                           name="foiFomentado"
                           ng-value="0"
                           ng-change="save_field('foiFomentado')"
                           ng-model="agent.foiFomentado"> Não
                </label>
            </div>
        </div>

        <div ng-show="agent.foiFomentado">
            <div class="row">
                <label class="colunm-1">
                    <span class="destaque" ng-show="agent.tipoOrganizacao==='entidade'">Qual o principal financiamento que a Entidade recebe ou recebeu?</span>
                    <span class="destaque" ng-show="agent.tipoOrganizacao==='coletivo'">Qual o principal financiamento que o Coletivo recebe ou recebeu?</span>
                    <label class="label-radio"><input type="radio"
                                                     name="tipoFomento"
                                                     value="tcc"
                                                     ng-change="save_field('tipoFomento')"
                                                     ng-model="agent.tipoFomento" > TCC - Celebração de Termo de Compromisso Cultural como resultado de seleção em edital público do Ministério da Cidadania ou de entes federados parceiros nas Redes Estaduais/Municipais/Intermunicipais de Pontos e Pontões de Cultura.</label>
                    <label class="label-radio"><input type="radio"
                                                     name="tipoFomento"
                                                     value="premio"
                                                     ng-change="save_field('tipoFomento')"
                                                     ng-model="agent.tipoFomento" > Prêmio - Recebimento de Premiação em recursos financeiros como resultado de seleção em edital público do Ministério da Cidadania ou de entes federados parceiros nas Redes Estaduais/Municipais/Intermunicipais de Pontos e Pontões de Cultura.</label>
                    <label class="label-radio"><input type="radio"
                                                     name="tipoFomento"
                                                     value="bolsa"
                                                     ng-change="save_field('tipoFomento')"
                                                     ng-model="agent.tipoFomento" > Bolsa - Recebimento de Bolsa como resultado de seleção em edital público do Ministério da Cidadania ou de entes federados parceiros nas Redes Estaduais/Municipais/Intermunicipais de Pontos e Pontões de Cultura.</label>
                    <label class="label-radio"><input type="radio"
                                                     name="tipoFomento"
                                                     value="convenio"
                                                     ng-change="save_field('tipoFomento')"
                                                     ng-model="agent.tipoFomento" > Convênio - Celebração de Convênio com o Ministério da Cidadania ou com entes federados parceiros nas Redes Estaduais/Municipais/Intermunicipais de Pontos e Pontões de Cultura.para realização de projeto cultural.</label>
                    <label class="label-radio"><input type="radio"
                                                     name="tipoFomento"
                                                     value="rouanet"
                                                     ng-change="save_field('tipoFomento')"
                                                     ng-model="agent.tipoFomento" > Lei Rouanet - Projeto Aprovado e Captação de Recursos realizada por meio do mecanismo de Incentivo Fiscal previsto na Lei n 8.313, de 23 de dezembro de 1991 (Lei Rouanet).</label>
                    <label class="label-radio"><input type="radio"
                                                     name="tipoFomento"
                                                     value="outros"
                                                     ng-change="save_field('tipoFomento')"
                                                     ng-model="agent.tipoFomento" > Outros</label>
                    <input type="text"
                           ng-show="agent.tipoFomento==='outros'"
                           ng-blur="save_field('tipoFomentoOutros')"
                           ng-model="agent.tipoFomentoOutros">
                </label>
            </div>
            <div class="clear"></div>
            <div class="row">
                <div class="colunm-full">
                    <span class="destaque">Tipo de Reconhecimento <i>?</i></span>
                    <label class="label-radio"><input type="radio"
                                                     name="fomento_tipoReconhecimento"
                                                     value="minc"
                                                     ng-change="save_field('tipoReconhecimento')"
                                                     ng-model="agent.tipoReconhecimento" > Direto com o Min. Cidadania</label>
                    <label class="label-radio"><input type="radio"
                                                     name="fomento_tipoReconhecimento"
                                                     value="estadual"
                                                     ng-change="save_field('tipoReconhecimento')"
                                                     ng-model="agent.tipoReconhecimento" > Estadual</label>
                    <label class="label-radio"><input type="radio"
                                                     name="fomento_tipoReconhecimento"
                                                     value="municipal"
                                                     ng-change="save_field('tipoReconhecimento')"
                                                     ng-model="agent.tipoReconhecimento" > Municipal</label>
                    <label class="label-radio"><input type="radio"
                                                     name="fomento_tipoReconhecimento"
                                                     value="intermunicipal"
                                                     ng-change="save_field('tipoReconhecimento')"
                                                     ng-model="agent.tipoReconhecimento" > Intermunicipal</label>
                </div>
            </div>
            <div class="clear"></div>
            <div ng-if="agent.rcv_Ds_Edital" class="row">
                <label class="colunm-50">
                    <span class="destaque"> Edital encontrado na importação pelo CNPJ <i>?</i></span> {{agent.rcv_Ds_Edital}}
                </label>
            </div>
            <div class="row">
                <label class="colunm-50">
                    <span class="destaque">Número do Edital de Seleção</span>
                    <input name="edital_num" type="text" ng-blur="save_field('edital_num')" ng-model="agent.edital_num" >
                </label>
                <label class="colunm-50">
                    <span class="destaque">Ano do Edital de Seleção</span>
                    <input name="edital_ano" type="text" ng-blur="save_field('edital_ano')" ng-model="agent.edital_ano" ui-mask="9999" >
                </label>

            </div>
            <div class="clear"></div>
            <div class="row">
                <label class="colunm-50">
                    <span class="destaque">Título do Projeto</span>
                    <input name="edital_projeto_nome" type="text" ng-blur="save_field('edital_projeto_nome')" ng-model="agent.edital_projeto_nome" >
                </label>

                <label class="colunm-50">

                    <span class="destaque">Local de Realização <i class='hltip' title='Local onde o projeto foi realizado'>?</i></span>
                    <input name="edital_localRealizacao" type="text" ng-blur="save_field('edital_localRealizacao')" ng-model="agent.edital_localRealizacao" >
    <!--                <select ng-blur="save_field('')" ng-model="agent.locationrealization">
                        <option value="AC">Acre</option>
                        <option value="AL">Alagoas</option>
                        <option value="AP">Amapá</option>
                    </select>-->
                </label>
            </div>
            <div class="clear"></div>
            <div class="row">
                <label class="colunm-50">
                    <span class="destaque">Proponente </span>
                    <input name="edital_proponente" type="text" ng-blur="save_field('edital_proponente')" ng-model="agent.edital_proponente" >
                </label>
            </div>
            <div class="clear"></div>
            <div class="row">
                <label class="colunm-full">
                    <span class="destaque">Resumo do projeto (objeto) <i class='hltip' title='Um breve resumo do que foi realizado no projeto (como foi feito, qual o objetivo,etc..)'>?</i></span>
                    <textarea name="edital_projeto_resumo" ng-blur="save_field('edital_projeto_resumo')" ng-model="agent.edital_projeto_resumo"> </textarea>
                </label>
            </div>
            <div class="clear"></div>
    <!--        <div class="row">
                <label class="colunm-full recursos-projeto">
                    <span class="destaque">Recursos do Projeto selecionado* <i>?</i></span>
                    <table>
                        <tr>
                            <td width="200"></td>
                            <td width="150" class="cinza">Custeio*</td>
                            <td width="150" class="cinza">Capital*</td>
                            <td width="150" class="cinza">Total*</td>
                        </tr>
                        <tr>
                            <td class="cinza">Valor Total(R$)*</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr class="cinza">
                            <td >Ação*</td>
                            <td colspan="3">Valor das Parcelas(R$)*</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="cinza">Total das Parcelas (R$)*</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                </label>
            </div>
            <div class="clear"></div>-->
            <div class="row">
                <div class="colunm-50">
                    <span class="destaque">Etapa do Projeto <i class='hltip' title='Se o projeto mencionado ainda estiver em andamento marque Em Execução'>?</i></span>
                    <label class="label-radio"><input type="radio"
                                                     name="etapaprojeto"
                                                     value="emexecucao"
                                                     ng-change="save_field('edital_projeto_etapa')"
                                                     ng-model="agent.edital_projeto_etapa"> Em Execução</label>
                    <label class="label-radio"><input type="radio"
                                                     name="etapaprojeto"
                                                     value="executado"
                                                     ng-change="save_field('edital_projeto_etapa')"
                                                     ng-model="agent.edital_projeto_etapa"> Já executado</label>
                </div>
            </div>
            <div class="clear"></div>
            <div ng-show="agent.edital_projeto_etapa==='executado'">
                <div class="row">
                    <div class="colunm-50">
                        <span class="destaque">Prestação de Contas <i class='hltip' title=''>?</i></span>
                        <label class="label-radio"><input type="radio"
                                                         name="edital_prestacaoContas_envio"
                                                         value="enviada"
                                                         ng-blur="save_field('edital_prestacaoContas_envio')"
                                                         ng-model="agent.edital_prestacaoContas_envio" > Enviada</label>
                        <label class="label-radio"><input type="radio"
                                                         name="edital_prestacaoContas_envio"
                                                         value="naoEnviada"
                                                         ng-blur="save_field('edital_prestacaoContas_envio')"
                                                         ng-model="agent.edital_prestacaoContas_envio"  > Não Enviada</label>
                        <label class="label-radio"><input type="radio"
                                                         name="edital_prestacaoContas_envio"
                                                         value="premiado"
                                                         ng-blur="save_field('edital_prestacaoContas_envio')"
                                                         ng-model="agent.edital_prestacaoContas_envio" > Ponto de Cultura Premiado</label>
                    </div>
                </div>
                <div class="row" ng-show="agent.edital_prestacaoContas_envio==='enviada'">
                    <div class="colunm-50">
                        <label class="label-radio"><input type="radio"
                                                         name="edital_prestacaoContas_status"
                                                         value="aprovada"
                                                         ng-blur="save_field('edital_prestacaoContas_status')"
                                                         ng-model="agent.edital_prestacaoContas_status"  > Aprovada</label>
                        <label class="label-radio"><input type="radio"
                                                         name="edital_prestacaoContas_status"
                                                         value="naoaprovada"
                                                         ng-blur="save_field('edital_prestacaoContas_status')"
                                                         ng-model="agent.edital_prestacaoContas_status" > Não Aprovada</label>
                        <label class="label-radio"><input type="radio"
                                                         name="edital_prestacaoContas_status"
                                                         value="analise"
                                                         ng-blur="save_field('edital_prestacaoContas_status')"
                                                         ng-model="agent.edital_prestacaoContas_status"  > Em Análise</label>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <div class="row">

                <label class="colunm-full vigencia">
                    <span class="destaque">Vigência:    </span>
                    <span class="vigencia-box vigiencia-de" style="width:120px; display:inline-block;">
                        de <input name="edital_projeto_vigencia_inicio" class="vigencia-box vigiencia-de" ui-date ui-date-format="yy-mm-dd" ng-change="save_field('edital_projeto_vigencia_inicio')" ng-model="agent.edital_projeto_vigencia_inicio">
                    </span>
                    <span class="vigencia-box vigiencia-ate" style="width:120px; display:inline-block; margin-left:15px">
                        até  <input name="edital_projeto_vigencia_fim" class="vigencia-box vigiencia-de" ui-date ui-date-format="yy-mm-dd" ng-change="save_field('edital_projeto_vigencia_fim')" ng-model="agent.edital_projeto_vigencia_fim">
                    </span>

                </label>
            </div>
            <div class="clear"></div>
            <div class="row">
                <div class="colunm-full">
                   <span class="destaque">Recebe ou recebeu outros financiamentos? (apoios, patrocínios, prêmios, bolsas, convênios, etc) <i class='hltip' title='Se recebeu mais algum outro financiamento do Minc, seja ele de qualquer tipo marque sim'>?</i></span>
                    <label class="label-radio">
                        <input type="radio"
                               name="financiamentos"
                               ng-value="1"
                               ng-change="save_field('outrosFinanciamentos')"
                               ng-model="agent.outrosFinanciamentos"> Sim</label>
                    <label class="label-radio">
                        <input type="radio"
                               name="financiamentos"
                               ng-value="0"
                               ng-change="save_field('outrosFinanciamentos')"
                               ng-model="agent.outrosFinanciamentos"> Não</label>
                </div>
            </div>
            <div class="row" ng-show="agent.outrosFinanciamentos">
                <label class="colunm-50">
                    <span class="destaque">Quais ?</span>
                    <input name="outrosFinanciamentos_descricao" type="text" ng-blur="save_field('outrosFinanciamentos_descricao')" ng-model="agent.outrosFinanciamentos_descricao" >
                </label>
            </div>
            <div class="clear"></div>

            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
</form>
