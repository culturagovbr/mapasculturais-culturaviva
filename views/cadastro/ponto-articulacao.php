<?php
    $this->bodyProperties['ng-app'] = "culturaviva";
    $this->layout = 'cadastro';
    $this->cadastroTitle = '4. Atuação e Articulação';
    $this->cadastroText = 'Queremos entender melhor quais são as atividades realizadas pelo seu Ponto e quem é o público que as frequenta';
    $this->pageSubtitle = 'É necessário selecionar ao menos uma opção em cada questão';
    $this->cadastroIcon = 'icon-chat';
    $this->cadastroPageClass = 'ponto-mais page-base-form';
    $this->cadastroLinkContinuar = 'economiaViva';
    $this->cadastroLinkBack = 'portifolio';

?>
<form name="form_pontoArticulacao" ng-controller="PontoArticulacaoCtrl">
    <?php $this->part('messages'); ?>
    <div class="form">
        <h4>Informações Obrigatórias</h4>
        <!--<div class="row">
            <div class="colunm-full">
                <span class="destaque" ng-if="agent_entidade.tipoPontoCulturaDesejado != 'pontao'">Em qual edital do Ministério do Turismo  {{data.tipoOrganizacao == 'coletivo' ? 'a Entidade' : 'o Coletivo'}} de Cultura já foi contemplado? <i class="hltip" title='Caso nunca tenha sido contemplado, selecione "Ainda não fui Contemplado"'>?</i><br>(Pode escolher mais de uma opção) </span>
                <span class="destaque" ng-if="agent_entidade.tipoPontoCulturaDesejado == 'pontao'">Em qual edital do Ministério do Turismo  {{data.tipoOrganizacao == 'coletivo' ? 'a Entidade' : 'o Coletivo'}} de Cultura já foi contemplado?* <i class="hltip" title='Caso nunca tenha sido contemplado, selecione "Ainda não fui Contemplado"'>?</i><br>(Pode escolher mais de uma opção) </span>
            </div>
            <taxonomy-checkboxes taxonomy="contemplado_edital" entity="agent" terms="termos.contemplado_edital"></taxonomy-checkboxes>
        </div>-->
        <div class="row">
            <div class="colunm-full">
                <span class="destaque" ng-if="agent_entidade.tipoPontoCulturaDesejado != 'pontao'">Quais são as ações estruturantes do Ponto/Pontão de Cultura? <i class="hltip" title="Refere-se às áreas da cultura nas quais o Ponto/Pontão atua.">?</i><br>(Pode escolher mais de uma opção) </span>
                <span class="destaque" ng-if="agent_entidade.tipoPontoCulturaDesejado == 'pontao'">Quais são as ações estruturantes do Ponto/Pontão de Cultura?* <i class="hltip" title="Refere-se às áreas da cultura nas quais o Ponto/Pontão atua.">?</i><br>(Pode escolher mais de uma opção) </span>
            </div>
            <taxonomy-checkboxes taxonomy="acao_estruturante" entity="agent" terms="termos.acao_estruturante"></taxonomy-checkboxes>
        </div>
        <div class="row">
            <div class="colunm-full">
                <span class="destaque" ng-if="agent_entidade.tipoPontoCulturaDesejado != 'pontao'">Quais são as áreas do Ponto/Pontão de Cultura? <i class="hltip" title="Refere-se às principais atividades realizadas pelo Ponto/Pontão de cultura.">?</i><br>(Pode escolher mais de uma opção) </span>
                <span class="destaque" ng-if="agent_entidade.tipoPontoCulturaDesejado == 'pontao'">Quais são as áreas do Ponto/Pontão de Cultura?* <i class="hltip" title="Refere-se às principais atividades realizadas pelo Ponto/Pontão de cultura.">?</i><br>(Pode escolher mais de uma opção) </span>
            </div>
            <taxonomy-checkboxes taxonomy="area" entity="agent" terms="termos.area" ></taxonomy-checkboxes>
        </div>
        <div class="row">
            <div class="colunm-full">
                <span class="destaque" ng-if="agent_entidade.tipoPontoCulturaDesejado != 'pontao'">Quais os públicos que participam das ações do Ponto/Pontão de Cultura? <i class="hltip" title="Para quem as atividades do seu Ponto/Pontão de Cultura são direcionadas?">?</i><br>(Pode escolher mais de uma opção) </span>
                <span class="destaque" ng-if="agent_entidade.tipoPontoCulturaDesejado == 'pontao'">Quais os públicos que participam das ações do Ponto/Pontão de Cultura?* <i class="hltip" title="Para quem as atividades do seu Ponto/Pontão de Cultura são direcionadas?">?</i><br>(Pode escolher mais de uma opção) </span>
            </div>
            <taxonomy-checkboxes taxonomy="publico_participante" entity="agent" terms="termos.publico_participante"></taxonomy-checkboxes>
        </div>
        <div class="row">

            <div class="row">
                <span class="destaque" ng-if="agent_entidade.tipoPontoCulturaDesejado != 'pontao'">Especifique a área de experiência e temas que você pode compartilhar conhecimento:</span>
                <span class="destaque" ng-if="agent_entidade.tipoPontoCulturaDesejado == 'pontao'">Especifique a área de experiência e temas que você pode compartilhar conhecimento:*</span>
            </div>
             <taxonomy-checkboxes taxonomy="area_atuacao" entity="agent" terms="termos.area_atuacao"></taxonomy-checkboxes>
        </div>
        <div class="row">
          <h4 style="background-color: #078979"> Articulação </h4>
            <div class="colunm-full">
                <span class="destaque">Participa de algum movimento cultural?* </span>
            </div>
            <label class="colunm1">
                <input type="radio"
                       name="politicocultural"
                       ng-value="0"
                       ng-change="save_field('participacaoMovPolitico')"
                       ng-model="agent.participacaoMovPolitico">   Não
            </label>
            <label class="colunm2">
                <input type="radio"
                       name="politicocultural"
                       ng-value="1"
                       ng-change="save_field('participacaoMovPolitico')"
                       ng-model="agent.participacaoMovPolitico">  Sim
                <!-- textarea></textarea -->
            </label>
            <div class="colunm-full" ng-show="agent.participacaoMovPolitico == 1">
                <span class="destaque">Quais?*
                <input name="simMovimentoPoliticoCultural" class="colunm1"type="text" ng-blur="save_field('simMovimentoPoliticoCultural')" ng-model="agent.simMovimentoPoliticoCultural" /></span>
            </div>

            <div class="colunm-full">
                <span class="destaque">Participa de algum Fórum e/ou Conselho de Cultura?* </span>
            </div>
            <label class="colunm1">
                <input type="radio"
                       name="forumcultural"
                       ng-value="0"
                       ng-change="save_field('participacaoForumCultura')"
                       ng-model="agent.participacaoForumCultura">  Não
            </label>
            <label class="colunm2">
                <input type="radio"
                       name="forumcultural"
                       ng-value="1"
                       ng-change="save_field('participacaoForumCultura')"
                       ng-model="agent.participacaoForumCultura"> Sim

                <!-- textarea></textarea -->
            </label>
            <div class="colunm-full" ng-show="agent.participacaoForumCultura == 1">
                <span class="destaque">Quais?*
                <input name="simForumCultural" class="colunm1"type="text" ng-blur="save_field('simForumCultural')" ng-model="agent.simForumCultural" /></span>
            </div>

            <div class="colunm-full">
                <span class="destaque">Participa de instância de representação junto ao Ministério do Turismo? * </span>
                <span class="destaque" ng-if="agent_entidade.tipoPontoCulturaDesejado != 'pontao'">Participa de instância de representação junto ao Ministério do Turismo? </span>
                <span class="destaque" ng-if="agent_entidade.tipoPontoCulturaDesejado == 'pontao'">Participa de instância de representação junto ao Ministério do Turismo?* </span>
            </div>

            <label class="colunm1">
                <input type="radio"
                       name="representacaominc"
                       ng-value="0"
                       ng-change="save_field('representacaoMinc')"
                       ng-model="agent.representacaoMinc"> Não
            </label>

            <label class="colunm2">
                <input type="radio" name="representacaominc"
                       ng-value="1"
                       ng-change="save_field('representacaoMinc')"
                       ng-model="agent.representacaoMinc"> Sim
            </label>

            <div ng-show="agent.representacaoMinc == 1">
                <taxonomy-checkboxes taxonomy="instancia_representacao_minc" entity="agent" terms="termos.instancia_representacao_minc"></taxonomy-checkboxes>
            </div>

            <div class="colunm-full">
                <span class="destaque">Possui parceria com o Poder Público?* </span>
            </div>
            <label class="colunm1">
                <input type="radio"
                       name="poderpublico"
                       ng-value="0"
                       ng-change="save_field('parceriaPoderPublico')"
                       ng-model="agent.parceriaPoderPublico"> Não
            </label>
            <label class="colunm2">
                <input type="radio"
                       name="poderpublico"
                       ng-value="1"
                       ng-change="save_field('parceriaPoderPublico')"
                       ng-model="agent.parceriaPoderPublico">  Sim
                <!-- textarea></textarea -->
            </label>
            <div class="colunm-full" ng-show="agent.parceriaPoderPublico == 1">
                <span class="destaque">Recebe ou recebeu fomento?*</span>
                <label class="colunm1">
                    <input type="radio"
                           name="fomentoPublico"
                           ng-value="0"
                           ng-change="save_field('fomentoPublico')"
                           ng-model="agent.fomentoPublico"> Não
                </label>
                <label class="colunm2">
                    <input type="radio"
                           name="fomentoPublico"
                           ng-value="1"
                           ng-change="save_field('fomentoPublico')"
                           ng-model="agent.fomentoPublico">  Sim
                </label>
            </div>

            <div class="colunm-full" ng-show="agent.fomentoPublico == 1">
                <span class="destaque">
                    Qual esfera do fomento?*
                </span>
                <select name="esferaFomento"
                        ng-change="save_field('esferaFomento')"
                        ng-model="agent.esferaFomento" required>
                    <option value="municipal">Municipal</option>
                    <option value="estadual">Estadual</option>
                    <option value="federal">Federal</option>
                </select>
            </div>

            <div class="colunm-full">
                <span class="destaque">Possui parceria com iniciativa privada?*</span>
                <label class="colunm1">
                    <input type="radio"
                           name="parceriaPrivada"
                           ng-value="0"
                           ng-change="save_field('parceriaPrivada')"
                           ng-model="agent.parceriaPrivada"> Não
                </label>
                <label class="colunm2">
                    <input type="radio"
                           name="parceriaPrivada"
                           ng-value="1"
                           ng-change="save_field('parceriaPrivada')"
                           ng-model="agent.parceriaPrivada">  Sim
                </label>
            </div>
            <div class="colunm-full" ng-show="agent.parceriaPrivada == 1">
                <span class="destaque">
                    Qual?*
                </span>
                <input type="text"
                       ng-change="save_field('parceriaPrivadaQual')"
                       ng-model="agent.parceriaPrivadaQual"
                       name="parceriaPrivadaQual">
            </div>

            <!--<div class="colunm-full" ng-show="agent.parceriaPoderPublico">
                <span class="destaque">Quais?*
                <input name="simPoderPublico" class="colunm1" type="text" ng-blur="save_field('simPoderPublico')" ng-model="agent.simPoderPublico" /></span>
            </div>-->
        </div>
    </div>
</form>
