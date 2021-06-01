<div>
    <div class="form">
<!--        <div class="row">-->
<!--            <span><b> * Campos Obrigatórios </b></span>-->
<!--        </div>-->
        <!-- <h4>Informações Obrigatórias</h4> -->
        <?php /*
        <div class="row">
            <div class="colunm-full">
                <span ng-if="entidade.tipoPontoCulturaDesejado == 'ponto'" class="destaque">Em qual edital do Ministério da Cidadania a Entidade/Coletivo Cultural já foi contemplado?</span>
                <span ng-if="entidade.tipoPontoCulturaDesejado == 'pontao'" class="destaque">Em qual edital do Ministério da Cidadania a Entidade/Coletivo Cultural já foi contemplado?*</span>
                <span style="height:auto" ng-repeat="termo in ponto.terms.contemplado_edital"><b>{{termo}}</b></span>
                <span ng-if="!ponto.terms.contemplado_edital.length"><b>Não informado</b></span>
            </div>
        </div>
         */ ?>
        <div class="row">
            <div class="colunm-full">
                <label class="destaque">Quais são as ações estruturantes do Ponto/Pontão de Cultura?</label>
                <span style="height:auto" ng-repeat="termo in ponto.terms.acao_estruturante"><b>{{termo}}</b></span>
                <span ng-if="!ponto.terms.acao_estruturante.length"><b>Não informado</b></span>
            </div>
        </div>
        <div class="row">
            <div class="colunm-full">
                <label class="destaque">Quais são as áreas do Ponto/Pontão de Cultura? </label>
                <span style="height:auto" ng-repeat="termo in ponto.terms.area"><b>{{termo}}</b></span>
                <span ng-if="!ponto.terms.area.length"><b>Não informado</b></span>
            </div>
        </div>
        <div class="row">
            <div class="colunm-full">
                <label class="destaque">Quais os públicos que participam das ações do Ponto/Pontão de Cultura? </label>
                <span style="height:auto" ng-repeat="termo in ponto.terms.publico_participante"><b>{{termo}}</b></span>
                <span ng-if="!ponto.terms.publico_participante.length"><b>Não informado</b></span>
            </div>
        </div>
        <div class="row">
            <div class="colunm-full">
                <label class="destaque">Especifique a área de experiência e temas que você pode compartilhar conhecimento: </label>
                <span style="height:auto" ng-repeat="termo in ponto.terms.area_atuacao"><b>{{termo}}</b></span>
                <span ng-if="!ponto.terms.area_atuacao.length"><b>Não informado</b></span>
            </div>
        </div>
        <div class="row">
          <h4> Articulação </h4>
            <div class="colunm-full">
                <label class="destaque"> Participa de algum movimento cultural? </label>
            </div>
            <label class="colunm1">
                <input type="radio"
                       name="politicocultural"
                       disabled="true"
                       ng-value="0"
                       ng-model="ponto.participacaoMovPolitico">   Não
            </label>
            <label class="colunm2">
                <input type="radio"
                       name="politicocultural"
                       disabled="true"
                       ng-value="1"
                       ng-model="ponto.participacaoMovPolitico">  Sim
                <!-- textarea></textarea -->
            </label>
            <div class="colunm-full" ng-show="ponto.participacaoMovPolitico">
                <span>Quais?
                <span style="height:auto"><b>{{ponto.simMovimentoPoliticoCultural}}</b></span>
            </div>

            <div class="colunm-full">
                <label class="destaque"> Participa de algum Fórum e/ou Conselho de Cultura? </label>
            </div>
            <label class="colunm1">
                <input type="radio"
                       name="forumcultural"
                       disabled="true"
                       ng-value="0"
                       ng-model="ponto.participacaoForumCultura">  Não
            </label>
            <label class="colunm2">
                <input type="radio"
                       name="forumcultural"
                       disabled="true"
                       ng-value="1"
                       ng-model="ponto.participacaoForumCultura"> Sim

                <!-- textarea></textarea -->
            </label>
            <div class="colunm-full" ng-show="ponto.participacaoForumCultura">
                <span>Quais?
                <span style="height:auto"><b>{{ponto.simForumCultural}}</b></span>
            </div>
            <div class="colunm-full">
                <label class="destaque"> Participa de instância de representação junto ao Ministério da Cidadania?</label>
                <span style="height:auto" ng-repeat="termo in ponto.terms.instancia_representacao_minc"><b>{{termo}}</b></span>
                <span ng-if="!ponto.terms.instancia_representacao_minc.length"><b>Não informado</b></span>
            </div>
            <div class="colunm-full">
                <span class="destaque">Possui parceria com o Poder Público? </span>
            </div>
            <label class="colunm1">
                <input type="radio"
                       name="poderpublico"
                       disabled="true"
                       ng-value="0"
                       ng-model="ponto.parceriaPoderPublico"> Não
            </label>
            <label class="colunm2">
                <input type="radio"
                       name="poderpublico"
                       disabled="true"
                       ng-value="1"
                       ng-model="ponto.parceriaPoderPublico">  Sim
                <!-- textarea></textarea -->
            </label>
            <div class="colunm-full" ng-show="ponto.parceriaPoderPublico">
                <span>Quais?
                <span style="height:auto"><b>{{ponto.simPoderPublico}}</b></span>
            </div>
        </div>
    </div>
</div>
