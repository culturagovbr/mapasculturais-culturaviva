<div  >
    <div class="form">
        <div class="row">
            <h4>Rede Colaborativa</h4>
            <span class="destaque">O que o Ponto/Pontão de Cultura pode oferecer para a rede?</span>

            <div class="colunm-full">
                <span class="destaque">Infra-Estrutura</span>
                <span style="height:auto" ng-repeat="termo in ponto.terms.ponto_infra_estrutura"><b>{{termo}}</b></span>
                <span ng-if="!ponto.terms.ponto_infra_estrutura.length"><b>Não informado</b></span>
            </div>

            <div class="colunm-full">
                <span class="destaque">Equipamentos</span>
                <span style="height:auto" ng-repeat="termo in ponto.terms.ponto_equipamentos"><b>{{termo}}</b></span>
                <span ng-if="!ponto.terms.ponto_equipamentos.length"><b>Não informado</b></span>
            </div>

            <div class="colunm-full">
                <span class="destaque">Recursos Humanos</span>
                <span style="height:auto" ng-repeat="termo in ponto.terms.ponto_recursos_humanos"><b>{{termo}}</b></span>
                <span ng-if="!ponto.terms.ponto_recursos_humanos.length"><b>Não informado</b></span>
            </div>

            <div class="colunm-full">
                <span class="destaque">Hospedagem</span>
                <span style="height:auto" ng-repeat="termo in ponto.terms.ponto_hospedagem"><b>{{termo}}</b></span>
                <span ng-if="!ponto.terms.ponto_hospedagem.length"><b>Não informado</b></span>
            </div>

            <div class="colunm-full">
                <span class="destaque">Deslocamento/Transportes</span>
                <span style="height:auto" ng-repeat="termo in ponto.terms.ponto_deslocamento"><b>{{termo}}</b></span>
                <span ng-if="!ponto.terms.ponto_deslocamento.length"><b>Não informado</b></span>
            </div>

            <div class="colunm-full">
                <span class="destaque">Serviços de Comunicação</span>
                <span style="height:auto" ng-repeat="termo in ponto.terms.ponto_comunicacao"><b>{{termo}}</b></span>
                <span ng-if="!ponto.terms.ponto_comunicacao.length"><b>Não informado</b></span>
            </div>

            <label class="colunm-full">
                <span class="destaque">Outros recursos (descreva outros itens que o Ponto/Pontão de Cultura tem disponível e não estavam especificados acima):</span>
                <textarea ng-model="ponto.pontoOutrosRecursosRede" disabled="true"></textarea>
            </label>
        </div>
        <div class="row">
            <h4>Economia Viva</h4>
            <div class="colunm-full">
                <span class="destaque">Quantas pessoas fazem parte do Ponto/Pontão de Cultura? (indique o número de pessoas em cada categoria)</span>
            </div>
            <label class="colunm-full">
                <input type="text" size="10" maxlength="3" class="inputqtd" disabled="true"
                       ng-model="ponto.pontoNumPessoasNucleo"> Núcleo principal (pessoa dedicada exclusivamente/prioritariamente às ações desenvolvidas pelo Ponto/Pontão de Cultura)
            </label>
            <label class="colunm-full">

                <input type="text" size="10" maxlength="3" class="inputqtd" disabled="true"
                       ng-model="ponto.pontoNumPessoasColaboradores">  Colaborador (pessoa que participa de ações específicas, de maneira pontual, mas mantêm um vínculo com o Ponto/Pontão de Cultura)
            </label>
            <div class="colunm-full">
                <span class="destaque">Quantas pessoas participam indiretamente do Ponto/Pontão de Cultura? (indique o número de pessoas em cada categoria)</span>
            </div>

            <label class="colunm-full">
                <input type="text" size="10" maxlength="3" class="inputqtd" disabled="true"
                       ng-model="ponto.pontoNumPessoasParceiros">  Parceiro (participa pontualmente de ações do Ponto/Pontão de Cultura fornecendo serviços, recursos ou estrutura)
            </label>
            <label class="colunm-full">
                <input type="text" size="10" maxlength="3" class="inputqtd" disabled="true"
                       ng-model="ponto.pontoNumPessoasApoiadores">  Apoiador (financia ou fomenta de alguma forma as atividades do Ponto/Pontão de Cultura)
            </label>
            <label class="colunm-full">
                <input type="text" size="10" maxlength="3" class="inputqtd" disabled="true"
                       ng-model="ponto.pontoNumRedes">  Redes (outras redes que se relacionam com o Ponto/Pontão de Cultura)<br />
                <span class="destaque">Descreva: </span>
                <textarea ng-model="ponto.pontoRedesDescricao" disabled="true"></textarea>
            </label>
            <label class="colunm-full">
                <input type="text" size="10" maxlength="3" class="inputqtd" disabled="true"
                       ng-model="ponto.pontoMovimentos"> Movimentos (movimentos sociais, culturais, ambientais e outros que se relacionem com o Ponto/Pontão de Cultura)
            </label>

            <div class="colunm-full">
                <span class="destaque">Quais são as formas de sustentabilidade do Ponto/Pontão de Cultura?</span>
                <span style="height:auto" ng-repeat="termo in ponto.terms.ponto_sustentabilidade"><b>{{termo}}</b></span>
                <span ng-if="!ponto.terms.ponto_sustentabilidade.length"><b>Não informado</b></span>
            </div>

            <div class="colunm-full">
                <span class="destaque">O Ponto/Pontão de Cultura pratica Economia Solidária? Como?</span>
            </div>
            <label class="colunm1">
                <input type="radio"
                       disabled="true"
                       name="pontoeconomia"
                       value="sim"
                       ng-model="ponto.pontoEconomiaSolidaria"> Sim
                <div ng-show="ponto.pontoEconomiaSolidaria==='sim'">
                    <span> Como? </span>
                    <textarea ng-model="ponto.pontoEconomiaSolidariaDescricao" disabled="true"></textarea>
                </div>
            </label>
            <label class="colunm2">
                <input type="radio"
                       disabled="true"
                       name="pontoeconomia"
                       value="nao"
                       ng-model="ponto.pontoEconomiaSolidaria"> Não
            </label>
            <label class="colunm3">
                <input type="radio"
                       disabled="true"
                       name="pontoeconomia"
                       value="gostaria"
                       ng-model="ponto.pontoEconomiaSolidaria">  Não, mas gostaria
            </label>


            <div class="colunm-full">
                <span class="destaque">O Ponto/Pontão de Cultura pratica Economia da Cultura?</span>
            </div>
            <label class="colunm1">
                <input type="radio"
                       disabled="true"
                       name="pontoeconomiacultura"
                       value="sim"
                       ng-model="ponto.pontoEconomiaCultura"> Sim
                <div ng-show="ponto.pontoEconomiaCultura==='sim'">
                    <span> Como? </span>
                    <textarea ng-model="ponto.pontoEconomiaCulturaDescricao" disabled="true"></textarea>
                </div>
            </label>
            <label class="colunm2">
                <input type="radio"
                       disabled="true"
                       name="pontoeconomiacultura"
                       value="nao"
                       ng-model="ponto.pontoEconomiaCultura"> Não
            </label>
            <label class="colunm3">
                <input type="radio"
                       disabled="true"
                       name="pontoeconomiacultura"
                       value="gostaria"
                       ng-model="ponto.pontoEconomiaCultura"> Não, mas gostaria
            </label>

            <?php /*
            <div class="colunm-full">
                <span class="destaque">O Ponto/Pontão de Cultura tem moeda complementar (social)?</span>
            </div>
            <label class="colunm1">
                <input type="radio"
                       disabled="true"
                       name="pontoeconomiasocial"
                       value="sim_fisica"
                       ng-model="ponto.pontoMoedaSocial"> Sim, física
            </label>
            <label class="colunm2">
                <input type="radio"
                       disabled="true"
                       name="pontoeconomiasocial"
                       value="sim_digital"
                       ng-model="ponto.pontoMoedaSocial"
                       > Sim, digital
            </label>
            <label class="colunm3">
                <input type="radio"
                       disabled="true"
                       name="pontoeconomiasocial"
                       value="nao_planejado"
                       ng-model="ponto.pontoMoedaSocial"
                       > Não, mas está planejando seu lançamento
            </label>
            <label class="colunm1">
               <input type="radio"
                      disabled="true"
                      name="pontoeconomiasocial"
                       value="nao_pretende"
                       ng-model="ponto.pontoMoedaSocial"
                      > Não, mas pretende ter
            </label>
            <label class="colunm2">
                <input type="radio"
                       disabled="true"
                       name="pontoeconomiasocial"
                       value="nao_entende"
                       ng-model="ponto.pontoMoedaSocial"
                       > Não, porque não sabe o que é nem como funciona
            </label>
            <label class="colunm3">
                <input type="radio"
                       disabled="true"
                       name="pontoeconomiasocial"
                       value="nao_pretende_ter"
                       ng-model="ponto.pontoMoedaSocial"
                       > Não, e não pretende ter
            </label>
            <div class="colunm-full" ng-show="ponto.pontoMoedaSocial==='sim_fisica' || ponto.pontoMoedaSocial==='sim_digital'">
                <span class="destaque">Conte em um parágrafo a definição e o funcionamento da sua moeda, seja física ou digital.</span>
                <textarea ng-model="ponto.pontoMoedaSocialDescricao" disabled="true"></textarea>
            </div>
            */ ?>

            <div class="colunm-full">
                <span class="destaque">O Ponto/Pontão de Cultura está disponível para as trocas de serviços ou produtos?</span>
            </div>
            <label class="colunm1">
                <input type="radio"
                       disabled="true"
                       name="culturaprodutos"
                       value="sim_parcial"
                       ng-model="ponto.pontoTrocasServicos"> Sim, parcialmente
            </label>
            <label class="colunm2">
                <input type="radio"
                       disabled="true"
                       name="culturaprodutos"
                       value="sim_integral"
                       ng-model="ponto.pontoTrocasServicos"> Sim, integralmente
            </label>
            <label class="colunm3">
                <input type="radio"
                       disabled="true"
                       name="culturaprodutos"
                       value="nao"
                       ng-model="ponto.pontoTrocasServicos"> Não
            </label>
            <label class="colunm1">
                <input type="radio"
                       disabled="true"
                       name="culturaprodutos"
                       value="depende"
                       ng-model="ponto.pontoTrocasServicos"> Depende de quem estará envolvido na troca
            </label>
            <label class="colunm2">
                <input type="radio"
                       disabled="true"
                       name="culturaprodutos"
                       value="outros"
                       ng-model="ponto.pontoTrocasServicos"> Outros
                <textarea ng-show="ponto.pontoTrocasServicos==='outros'" disabled="true"
                          ng-model="ponto.pontoTrocasServicosOutros"></textarea>
            </label>

            <div class="colunm-full">
                <span class="destaque">O Ponto/Pontão de Cultura contrata serviços e/ou produtos de outros Pontos/Pontões de Cultura?</span>
            </div>
            <label class="colunm1">
                <input type="radio"
                       disabled="true"
                       name="servicoprodutos"
                       value="sim"
                       ng-model="ponto.pontoContrataServicos">  Sim
                <div ng-show="ponto.pontoContrataServicos==='sim'">
                    <span>Que tipo de serviços e/ou produtos?</span>
                    <textarea ng-model="ponto.pontoContrataServicosOutros" disabled="true"></textarea>
                </div>
            </label>
            <label class="colunm2">
                <input type="radio"
                       disabled="true"
                       name="servicoprodutos"
                       value="nao"
                       ng-model="ponto.pontoContrataServicos"> Não
            </label>
            <div class="colunm-full">
                <span class="destaque">O Ponto/Pontão de Cultura já apoiou, investiu ou emprestou algum recurso para projetos de outros coletivos, grupos, movimentos, redes, Pontos ou Pontões de Cultura?</span>
            </div>
            <label class="colunm1">
                <input type="radio"
                       disabled="true"
                       name="projetosapoiou"
                       value="sim"
                       ng-model="ponto.pontoInvestimentosColetivos"> Sim
                 <div ng-show="ponto.pontoInvestimentosColetivos==='sim'">
                    <span>Quanto e para quem?</span>
                    <textarea ng-model="ponto.pontoInvestColetivosOutros" disabled="true"></textarea>
                </div>
            </label>
            <label class="colunm2">
                <input type="radio"
                       disabled="true"
                       name="projetosapoiou"
                       value="nao"
                       ng-model="ponto.pontoInvestimentosColetivos"> Não
            </label>


            <label class="colunm-full">
                <span class="destaque">Quanto custa por ano o Ponto/Pontão de Cultura? (valore todas as atividades realizadas, pagamento de pessoal envolvido, aluguel e manutenção de sede e equipamentos, entre outros - custeados ou não com recursos do Ministério da Cultura).</span>
                <textarea ng-model="ponto.pontoCustoAnual" disabled="true"></textarea>
            </label>
        </div>
    </div>
</div>
