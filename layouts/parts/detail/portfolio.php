<div>
    <div class="form">
        <!--        <div class="row">-->
        <!--            <span><b> * Campos Obrigatórios </b></span>-->
        <!--        </div>-->
        <!-- <h4>Informações Obrigatórias</h4> -->
        <div class="row">
            <span class="destaque">Portfólio*</span>
            <div class="colunm1">
                <img ng-if="agent.files.portifolio" src="../../assets/img/pdflogo.png" width="160"
                     height="138">
                <a ng-if="ponto['@files:portifolio'].url" href="{{ponto['@files:portifolio'].url}}" target="_blank">
                    Baixar Arquivo
                </a>
                <a style="font-size: 12px" ng-if="ponto.atividadesEmRealizacaoLink"
                   href="{{ponto.atividadesEmRealizacaoLink}}" target="_blank">{{ponto.atividadesEmRealizacaoLink}}</a>
                <span ng-if="!ponto['@files:portifolio'].url && !ponto.atividadesEmRealizacaoLink"><b>Não informado</b></span>
            </div>
        </div>
        <div ng-if="entidade.tipoOrganizacao == 'coletivo'">
            <h4>Ata de composição e constituição do coletivo</h4>
            <div class="row">
                <div class="colunm1">
                    <img ng-if="agent.files.ata" src="<?php $this->asset('img/pdflogo.png') ?>" width="160"
                         height="138">
                    <a ng-if="ponto['@files:ata'].url" href="{{ponto['@files:ata'].url}}" target="_blank">
                        Baixar Arquivo
                    </a>
                    <span ng-if="!ponto['@files:ata'].url"><b>Não informado</b></span>
                </div>
            </div>
        </div>

        <!-- <div class="row"> -->
        <!-- <h4>Cartas de Reconhecimento</h4> -->
        <!-- </div> -->
        <div class="row">
            <span class="destaque">Cartas de Reconhecimento*</span>
            <div class="colunm1">
                <img ng-if="agent.files.carta1" src="<?php $this->asset('img/pdflogo.png') ?>" width="160" height="138">
                <a style="font-size: 12px; color: inherit" ng-if="ponto['@files:carta1'].url"
                   href="{{ponto['@files:carta1'].url}}" target="_blank">
                    {{agent.files.carta1.name}}
                    Baixar primeira carta</a>
                <span ng-if="!ponto['@files:carta1'].url"><b>Não informado</b></span>
            </div>
            <div class="colunm2">
                <img ng-if="agent.files.carta2" src="<?php $this->asset('img/pdflogo.png') ?>" width="160" height="138">
                <a style="font-size: 12px; color: inherit" ng-if="ponto['@files:carta2'].url"
                   href="{{ponto['@files:carta2'].url}}" target="_blank">
                    {{agent.files.carta2.name}}
                    Baixar segunda carta
                </a>
                <span ng-if="!ponto['@files:carta2'].url"><b>Não informado</b></span>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="form form-opcional">
        <div class="row">
            <span class="destaque">Conte um pouco sobre a história do ponto de Cultura (800 caracteres) <i class='hltip'
                                                                                                           title='Nos diga um pouco mais sobre o ponto de cultura, como por exemplo como ele começou e como surgiu a idéia'>?</i>  </span>
            <label class="colunm1">
                <span><b>{{ponto.longDescription}}</b></span>
                <span ng-if="!ponto.longDescription"><b>Não informado</b></span>
            </label>

        </div>
        <div class="clear"></div>
        <div class="row">
            <span class="destaque">Fotos de Divulgação do Ponto de Cultura</span>
            <div class="colunm1">
                <div class="img_updade file-item" ng-repeat="f in ponto['@files:gallery']">
                    <img src="{{f.url}}" width="160" height="138">
                </div>
                <span ng-if="!ponto['@files:gallery']"><b>Não informado</b></span>
            </div>
        </div>
    </div>
</div>
