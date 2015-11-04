<?php
    $this->bodyProperties['ng-app'] = "culturaviva";
    $this->layout = 'cadastro';
    $this->cadastroTitle = '6. Atuação e Articulação';
    $this->cadastroText = 'Queremos entender melhor quais são as atividades realizadas pelo seu Ponto e quem é o público que as frequenta';
    $this->cadastroIcon = 'icon-chat';
    $this->cadastroPageClass = 'ponto-mais page-base-form';
    $this->cadastroLinkContinuar = 'economiaViva';

?>

<form ng-controller="PontoArticulacaoCtrl">
    <?php $this->part('messages'); ?>
    <div class="form">
        <h4>Informações Obrigatórias</h4>
        <div class="row">
            <div class="colunm-full">
                <span class="destaque">Em qual edital do Ministério da Cultura a entidade/coletivo já foi contemplado? * <i class="hltip" title='Caso nunca tenha sido contemplado, selecione "Ainda não fui Contemplado"'>?</i><br>(Pode escolher mais de uma opção) </span>
            </div>
            <taxonomy-checkboxes taxonomy="contemplado_edital" entity="agent" terms="termos.contemplado_edital"></taxonomy-checkboxes>
        </div>
        <div class="row">
            <div class="colunm-full">
                <span class="destaque">Quais são as ações estruturantes do Ponto/Pontão de Cultura? * <i class="hltip" title="Refere-se às áreas da cultura nas quais o Ponto/Pontão atua.">?</i><br>(Pode escolher mais de uma opção) </span>
            </div>
            <taxonomy-checkboxes taxonomy="acao_estruturante" entity="agent" terms="termos.acao_estruturante"></taxonomy-checkboxes>
        </div>
        <div class="row">
            <div class="colunm-full">
                <span class="destaque">Quais são as áreas do Ponto/Pontão de Cultura? * <i class="hltip" title="Refere-se às principais atividades realizadas pelo Ponto/Pontão de cultura.">?</i><br>(Pode escolher mais de uma opção) </span>
            </div>
            <taxonomy-checkboxes taxonomy="area" entity="agent" terms="termos.area" restricted-terms="true"></taxonomy-checkboxes>
        </div>
        <div class="row">
            <div class="colunm-full">
                <span class="destaque">Quais os públicos que participam das ações do Ponto/Pontão de Cultura? * <i class="hltip" title="Para quem as atividades do seu Ponto/Pontão de Cultura são direcionadas?">?</i><br>(Pode escolher mais de uma opção) </span>
            </div>
            <taxonomy-checkboxes taxonomy="publico_participante" entity="agent" terms="termos.publico_participante"></taxonomy-checkboxes>
        </div>
        <div class="row">

            <div class="row">
                <span class="destaque">Especifique a área de experiência e temas que você pode compartilhar conhecimento: *</span>
            </div>
             <taxonomy-checkboxes taxonomy="area_atuacao" entity="agent" terms="termos.area_atuacao"></taxonomy-checkboxes>
        </div>
        <div class="row">
          <h4> Articulação </h4>
            <div class="colunm-full">
                <span class="destaque">Participa de algum movimento político-cultural? * </span>
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
            <div class="colunm-full" ng-show="agent.participacaoMovPolitico">
                <span>Quais?*
                <input class="colunm1"type="text" ng-blur="save_field('simMovimentoPoliticoCultural')" ng-model="agent.simMovimentoPoliticoCultural" /></span>
            </div>

            <div class="colunm-full">
                <span class="destaque">Participa de algum Fórum de Cultura? * </span>
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
            <div class="colunm-full" ng-show="agent.participacaoForumCultura">
                <span>Quais?*
                <input class="colunm1"type="text" ng-blur="save_field('simForumCultural')" ng-model="agent.simForumCultural" /></span>
            </div>
            <div class="colunm-full">
                <span class="destaque">Participa de instância de representação junto ao Ministério da Cultura? * </span>
            </div>
            <taxonomy-checkboxes taxonomy="instancia_representacao_minc" entity="agent" terms="termos.instancia_representacao_minc"></taxonomy-checkboxes>
            <div class="colunm-full">
                <span class="destaque">Possui parceria com o Poder Público? * </span>
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
            <div class="colunm-full" ng-show="agent.parceriaPoderPublico">
                <span>Quais?*
                <input class="colunm1"type="text" ng-blur="save_field('simPoderPublico')" ng-model="agent.simPoderPublico" /></span>
            </div>
        </div>
    </div>
</form>
