<?php
    $this->bodyProperties['ng-app'] = "culturaviva";
    $this->layout = 'cadastro';
    $this->cadastroTitle = '5. Selos Rede Viva (Opcional)';
    $this->cadastroText = 'Fale mais sobre os recursos que o seu ponto tem para trocar com outros pontos de cultura';
    $this->cadastroIcon = 'icon-dollar';
    $this->cadastroPageClass = 'economia-viva page-base-form';
    $this->cadastroLinkContinuar = '';
    $this->cadastroLinkBack = 'articulacao';

?>

<form name="form_pontoEconomia" ng-controller="PontoEconomiaVivaCtrl">
    <?php $this->part('messages'); ?>
    <div class="form">
        <div class="row">
            <h4>Rede Colaborativa</h4>
            <p>O Ponto/Pontão de Cultura só se realiza plenamente quando se articula em rede. Agir em rede é interagir em um universo de troca e colaboração mútua. Espaços, serviços, equipamentos, atividades, conexão, aquilo que o Ponto/Pontão tem, somado ao que o outro pode oferecer, multiplicam as possibilidades da rede e gera uma outra economia viva,  colaborativa e transformadora </p>
            <span class="destaque">O que o Ponto/Pontão de Cultura pode oferecer para a rede?</span>

            <div class="colunm-full">
                <span class="destaque">Infra-Estrutura</span>
            </div>
            <taxonomy-checkboxes taxonomy="ponto_infra_estrutura" entity="agent" terms="termos.ponto_infra_estrutura"></taxonomy-checkboxes>

            <div class="colunm-full">
                <span class="destaque">Equipamentos</span>
            </div>
            <taxonomy-checkboxes taxonomy="ponto_equipamentos" entity="agent" terms="termos.ponto_equipamentos"></taxonomy-checkboxes>

            <div class="colunm-full">
             <span class="destaque">Recursos Humanos</span>
            </div>
            <taxonomy-checkboxes taxonomy="ponto_recursos_humanos" entity="agent" terms="termos.ponto_recursos_humanos"></taxonomy-checkboxes>

            <div class="colunm-full">
                <span class="destaque">Hospedagem</span>
            </div>
            <taxonomy-checkboxes taxonomy="ponto_hospedagem" entity="agent" terms="termos.ponto_hospedagem"></taxonomy-checkboxes>

            <div class="colunm-full">
                <span class="destaque">Deslocamento/Transportes</span>
            </div>
            <taxonomy-checkboxes taxonomy="ponto_deslocamento" entity="agent" terms="termos.ponto_deslocamento"></taxonomy-checkboxes>

            <div class="colunm-full">
                <span class="destaque">Serviços de Comunicação</span>
            </div>
            <taxonomy-checkboxes taxonomy="ponto_comunicacao" entity="agent" terms="termos.ponto_comunicacao"></taxonomy-checkboxes>

            <label class="colunm-full">
                <span class="destaque">Outros recursos (descreva outros itens que o Ponto/Pontão de Cultura tem disponível e não estavam especificados acima):</span>
                <textarea ng-model="agent.pontoOutrosRecursosRede" ng-blur="save_field('pontoOutrosRecursosRede')"></textarea>
            </label>
        </div>
        <div class="row">
            <h4>Economia Viva</h4>
            <div class="colunm-full">
                <span class="destaque">Quantas pessoas fazem parte do Ponto/Pontão de Cultura? (indique o número de pessoas em cada categoria)</span>
            </div>
            <label class="colunm-full">
                <input type="text" size="10" maxlength="3" class="inputqtd"
                       ng-model="agent.pontoNumPessoasNucleo" ng-blur="save_field('pontoNumPessoasNucleo')"> Núcleo principal (pessoa dedicada exclusivamente/prioritariamente às ações desenvolvidas pelo Ponto/Pontão de Cultura)
            </label>
            <label class="colunm-full">

                <input type="text" size="10" maxlength="3" class="inputqtd"
                       ng-model="agent.pontoNumPessoasColaboradores" ng-blur="save_field('pontoNumPessoasColaboradores')">  Colaborador (pessoa que participa de ações específicas, de maneira pontual, mas mantêm um vínculo com o Ponto/Pontão de Cultura)
            </label>
            <div class="colunm-full">
                <span class="destaque">Quantas pessoas participam indiretamente do Ponto/Pontão de Cultura? (indique o número de pessoas em cada categoria)</span>
            </div>

            <label class="colunm-full">
                <input type="text" size="10" maxlength="3" class="inputqtd"
                       ng-model="agent.pontoNumPessoasParceiros" ng-blur="save_field('pontoNumPessoasParceiros')">  Parceiro (participa pontualmente de ações do Ponto/Pontão de Cultura fornecendo serviços, recursos ou estrutura)
            </label>
            <label class="colunm-full">
                <input type="text" size="10" maxlength="3" class="inputqtd"
                       ng-model="agent.pontoNumPessoasApoiadores" ng-blur="save_field('pontoNumPessoasApoiadores')">  Apoiador (financia ou fomenta de alguma forma as atividades do Ponto/Pontão de Cultura)
            </label>
            <label class="colunm-full">
                <input type="text" size="10" maxlength="3" class="inputqtd"
                       ng-model="agent.pontoNumRedes" ng-blur="save_field('pontoNumRedes')">  Redes (outras redes que se relacionam com o Ponto/Pontão de Cultura)<br />
                <span class="destaque">Descreva: </span>
                <textarea ng-model="agent.pontoRedesDescricao" ng-blur="save_field('pontoRedesDescricao')"></textarea>
            </label>
            <label class="colunm-full">
                <input type="text" size="10" maxlength="3" class="inputqtd"
                       ng-model="agent.pontoMovimentos" ng-blur="save_field('pontoMovimentos')"> Movimentos (movimentos sociais, culturais, ambientais e outros que se relacionem com o Ponto/Pontão de Cultura)
            </label>

            <div class="colunm-full">
                <span class="destaque">Quais são as formas de sustentabilidade do Ponto/Pontão de Cultura?</span>
            </div>
            <taxonomy-checkboxes taxonomy="ponto_sustentabilidade" entity="agent" terms="termos.ponto_sustentabilidade"></taxonomy-checkboxes>

            <div class="colunm-full">
                <span class="destaque">O Ponto/Pontão de Cultura pratica Economia Solidária? Como?<i class='hltip' title='Entende-se por economia solidária, uma forma diferente de produzir, vender, comprar e trocar o que é preciso para viver. Sem explorar os outros, sem querer levar vantagem, sem destruir o ambiente. Cooperando, fortalecendo o grupo, cada um pensando no bem de todos e no próprio bem. Compreende-se por economia solidária o conjunto de atividades econômicas de produção, distribuição, consumo, poupança e crédito, organizadas sob a forma de autogestão.'>?</i></span>
            </div>
            <label class="colunm1">
                <input type="radio"
                       name="pontoeconomia"
                       value="sim"
                       ng-change="save_field('pontoEconomiaSolidaria')"
                       ng-model="agent.pontoEconomiaSolidaria"> Sim
                <div ng-show="agent.pontoEconomiaSolidaria==='sim'">
                    <span class="destaque"> Como? </span>
                    <textarea ng-model="agent.pontoEconomiaSolidariaDescricao" ng-blur="save_field('pontoEconomiaSolidariaDescricao')"></textarea>
                </div>
            </label>
            <label class="colunm2">
                <input type="radio"
                       name="pontoeconomia"
                       value="nao"
                       ng-change="save_field('pontoEconomiaSolidaria')"
                       ng-model="agent.pontoEconomiaSolidaria"> Não
            </label>
            <label class="colunm3">
                <input type="radio"
                       name="pontoeconomia"
                       value="gostaria"
                       ng-change="save_field('pontoEconomiaSolidaria')"
                       ng-model="agent.pontoEconomiaSolidaria">  Não, mas gostaria
            </label>

            <div class="colunm-full">
                <span class="destaque">O Ponto/Pontão de Cultura pratica Economia da Cultura? <i class='hltip' title='Entende-se por economia criativa, um conceito em construção, mas é sabido que sua prática volta-se à economia do intangível, do simbólico. Essa concepção da economia prevê os ciclos de criação, produção, difusão, circulação/distribuição e consumo/fruição de bens e serviços caracterizados pela prevalência de sua dimensão simbólica originada por setores cujas atividades econômicas têm como processo principal o ato criativo, gerador de valor simbólico, elemento central da formação do preço, e que resulta em produção de riqueza cultural.'>?</i></span>
            </div>
            <label class="colunm1">
                <input type="radio"
                       name="pontoeconomiacultura"
                       value="sim"
                       ng-change="save_field('pontoEconomiaCultura')"
                       ng-model="agent.pontoEconomiaCultura"> Sim
                <div ng-show="agent.pontoEconomiaCultura==='sim'">
                    <span class="destaque"> Como? </span>
                    <textarea ng-model="agent.pontoEconomiaCulturaDescricao" ng-blur="save_field('pontoEconomiaCulturaDescricao')"></textarea>
                </div>
            </label>
            <label class="colunm2">
                <input type="radio"
                       name="pontoeconomiacultura"
                       value="nao"
                       ng-change="save_field('pontoEconomiaCultura')"
                       ng-model="agent.pontoEconomiaCultura"> Não
            </label>
            <label class="colunm3">
                <input type="radio"
                       name="pontoeconomiacultura"
                       value="gostaria"
                       ng-change="save_field('pontoEconomiaCultura')"
                       ng-model="agent.pontoEconomiaCultura"> Não, mas gostaria
            </label>
            <div class="colunm-full" ng-show="agent.pontoMoedaSocial==='sim_fisica' || agent.pontoMoedaSocial==='sim_digital'">
                <span class="destaque">Conte em um parágrafo a definição e o funcionamento da sua moeda, seja física ou digital.</span>
                <textarea ng-model="agent.pontoMoedaSocialDescricao" ng-blur="save_field('pontoMoedaSocialDescricao')"></textarea>
            </div>

            <div class="colunm-full">
                <span class="destaque">O Ponto/Pontão de Cultura está disponível para as trocas de serviços ou produtos?</span>
            </div>
            <label class="colunm1">
                <input type="radio"
                       name="culturaprodutos"
                       value="sim_parcial"
                       ng-change="save_field('pontoTrocasServicos')"
                       ng-model="agent.pontoTrocasServicos"> Sim, parcialmente
            </label>
            <label class="colunm2">
                <input type="radio"
                       name="culturaprodutos"
                       value="sim_integral"
                       ng-change="save_field('pontoTrocasServicos')"
                       ng-model="agent.pontoTrocasServicos"> Sim, integralmente
            </label>
            <label class="colunm3">
                <input type="radio"
                       name="culturaprodutos"
                       value="nao"
                       ng-change="save_field('pontoTrocasServicos')"
                       ng-model="agent.pontoTrocasServicos"> Não
            </label>
            <label class="colunm1">
                <input type="radio"
                       name="culturaprodutos"
                       value="depende"
                       ng-change="save_field('pontoTrocasServicos')"
                       ng-model="agent.pontoTrocasServicos"> Depende de quem estará envolvido na troca
            </label>
            <label class="colunm2">
                <input type="radio"
                       name="culturaprodutos"
                       value="outros"
                       ng-change="save_field('pontoTrocasServicos')"
                       ng-model="agent.pontoTrocasServicos"> Outros
                <textarea ng-show="agent.pontoTrocasServicos==='outros'"
                          ng-model="agent.pontoTrocasServicosOutros"
                          ng-blur="save_field('pontoTrocasServicosOutros')"></textarea>
            </label>

            <div class="colunm-full">
                <span class="destaque">O Ponto/Pontão de Cultura contrata serviços e/ou produtos de outros Pontos/Pontões de Cultura?</span>
            </div>
            <label class="colunm1">
                <input type="radio"
                       name="servicoprodutos"
                       value="sim"
                       ng-change="save_field('pontoContrataServicos')"
                       ng-model="agent.pontoContrataServicos">  Sim
                <div ng-show="agent.pontoContrataServicos==='sim'">
                    <span class="destaque">Que tipo de serviços e/ou produtos?</span>
                    <textarea ng-model="agent.pontoContrataServicosOutros"
                              ng-blur="save_field('pontoContrataServicosOutros')"></textarea>
                </div>
            </label>
            <label class="colunm2">
                <input type="radio"
                       name="servicoprodutos"
                       value="nao"
                       ng-change="save_field('pontoContrataServicos')"
                       ng-model="agent.pontoContrataServicos"> Não
            </label>

            <label class="colunm-full">
                <span class="destaque">Quanto custa por ano o Ponto/Pontão de Cultura? (valore todas as atividades realizadas, pagamento de pessoal envolvido, aluguel e manutenção de sede e equipamentos, entre outros - custeados ou não com recursos do Ministério da Cultura).</span>
                <input type="number" min="0" placeholder="Informe o valor total" 
                       ng-model="agent.pontoCustoAnual" ng-blur="save_field('pontoCustoAnual')">
            </label>
        </div>
    </div>
</form>

<!-- Formação -->
<form name="form_pontoFormacao" ng-controller="PontoFormacaoCtrl">
    <?php $this->part('messages'); ?>
    <div class="form">
        <h4>Conhecimento em Rede</h4>
        <div class="row">
            <p>Pontos e Pontões de cultura também são formadores e multiplicadores de cultura. Esta parte do cadastro visa conectar conhecimentos e metodologias educativas,  de formação e aprendizagem desenvolvidas pelos Pontos e Pontões de Cultura, gerando uma grande Rede de Formação Livre Cultura Viva.</p>
            <p>O objetivo é mapear e sistematizar iniciativas de formação - formais e não formais, empíricas e teóricas, ancestrais e contemporâneas, urbanas e rurais - a fim de facilitar e ampliar as trocas de conhecimento na rede.</p>
            <p>As informações registradas neste cadastro farão parte de um banco de dados acessível a todos os Pontos e Pontões de Cultura, com o objetivo de estimular o intercâmbio de saberes.</p>
            <p class="destaque subtitle">Este  mapeamento prioriza as experiências produtivas no campo cultural e está dividido em 03 categorias:</p>
            <span class="destaque subtitle">1) Formadores: </span>
            <p>Formadores, professores, pesquisadores, mestres e mestras das culturas populare e tradicionais, arte-educadores e  investigadores que atuem no campo da cultura. </p>
            <span class="destaque subtitle">2) Espaços de Aprendizagem: </span>
            <p>Espaços culturais, sedes, eventos de formação e plataformas que possam ser consideradas espaços de aprendizagem.</p>
            <span class="destaque subtitle">3) Metodologias:</span>
            <p>Experiências de formação e aprendizagem, vivências, oficinas, cursos, palestras, dinâmicas de troca de conhecimento, entre outras metodologias.</p>
            <p>Para esta primeira etapa você poderá inscrever apenas 1 Formador, 1 Espaço de Aprendizagem e 1 Metodologia. Em breve, a segunda etapa estará pronta e você poderá complementar, inscrevendo mais formadores, espaços e metodologias referentes ao seu Ponto de Cultura.</p>

            <h4> Formadores</h4>
            <label class="colunm1">
                <span class="destaque">Nome Completo:</span>
                <input type="text" ng-blur="save_field('formador1_nome')" ng-model="agent.formador1_nome">
            </label>

            <label class="colunm1">
                <span class="destaque">Email: </span>
                <input type="email" ng-blur="save_field('formador1_email')" ng-model="agent.formador1_email">
            </label>

            <label class="colunm1">
                <span class="destaque">Áreas de atuação (oficinas/atividades ministradas):</span>
                <input type="text" ng-blur="save_field('formador1_areaAtuacao')" ng-model="agent.formador1_areaAtuacao">
            </label>

            <label class="colunm-full">
                <span class="destaque">Descrição/Mini-bio:  </span>
                <textarea ng-blur="save_field('formador1_bio')" ng-model="agent.formador1_bio"></textarea>
            </label>

            <h4> Metodologias</h4>

            <label class="colunm-full">
                <span class="destaque">Nome da metodologia: </span>
                <input type="text" ng-blur="save_field('metodologia1_nome')" ng-model="agent.metodologia1_nome">
            </label>
            <label class="colunm-full">
                <span class="destaque">Descrição:  </span>
                <textarea type="text" ng-blur="save_field('metodologia1_desc')" ng-model="agent.metodologia1_desc"></textarea>
            </label>
            <label class="colunm-full">
                <span class="destaque">Necessidades técnicas:  </span>
                <textarea type="text" ng-blur="save_field('metodologia1_necessidades')" ng-model="agent.metodologia1_necessidades"></textarea>
            </label>
            <label class="colunm1">
                <span class="destaque">Capacidade de público:  </span>
                <input type="text" ng-blur="save_field('metodologia1_capacidade')" ng-model="agent.metodologia1_capacidade">
            </label>
            <label class="colunm1">
                <span class="destaque">Carga horária:  </span>
                <input type="text" ng-blur="save_field('metodologia1_cargaHoraria')" ng-model="agent.metodologia1_cargaHoraria">
            </label>
            <label class="colunm-full">
                <span class="destaque">Possui certificação?</span>

            </label>
            <div class="colunm-50">
                <label class="label-radio">
                    <input type="radio"
                           name="certificacao"
                           ng-value="1"ng-change="save_field('metodologia1_certificacao')"
                           ng-model="agent.metodologia1_certificacao"> Sim</label>
                <label class="label-radio">
                    <input type="radio"
                           name="certificacao"
                           ng-value="0"ng-change="save_field('metodologia1_certificacao')"
                           ng-model="agent.metodologia1_certificacao"> Não </label>
            </div>
            <div class="colunm-full">
                <span class="destaque">Tipo de Metodologia</span>
            </div>
            <taxonomy-checkboxes taxonomy="metodologias_areas" entity="agent" terms="termos.metodologias_areas"></taxonomy-checkboxes>
        </div>
    </div>
</form>

