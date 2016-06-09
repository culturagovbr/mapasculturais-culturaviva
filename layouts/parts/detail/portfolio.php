<div  >
    <div class="form">
        <h4>Informações Obrigatórias</h4>
        <div class="row">
            <span class="destaque">Portfólio</span>
                <div class="colunm1">
                    <a ng-if="ponto.files.portifolio.url" href="{{ponto.files.portifolio.url}}" target="_blank">Baixar Arquivo</a>
                    <a ng-if="ponto.atividadesEmRealizacaoLink" href="{{ponto.atividadesEmRealizacaoLink}}" target="_blank">{{ponto.atividadesEmRealizacaoLink}}</a>
                    <span ng-if="!ponto.files.portifolio.url && !ponto.atividadesEmRealizacaoLink"><b>Não informado</b></span>
                </div>
        </div>
        <div ng-if="entidade.tipoOrganizacao == 'coletivo'">
          <h4>Ata de composição e constituição do coletivo</h4>
          <div class="row">
              <div class="colunm1">
                    <a ng-if="ponto.files.ata" href="{{ponto.files.ata.url}}" target="_blank">Baixar Arquivo</a>
                    <span ng-if="!ponto.files.ata"><b>Não informado</b></span>
              </div>
          </div>
        </div>

        <div class="row">
            <h4>Cartas de Reconhecimento</h4>
        </div>
        <div class="row">
            <span class="destaque">Cartas de Reconhecimento</span>
            <div class="colunm1">
              <a ng-if="ponto.files.carta1" href="{{ponto.files.carta1.url}}" target="_blank">Baixar primeira carta</a>
              <span ng-if="!ponto.files.carta1"><b>Não informado</b></span>
            </div>
            <div class="colunm2">
              <a ng-if="ponto.files.carta2" href="{{ponto.files.carta2.url}}" target="_blank">Baixar segunda carta</a>
              <span ng-if="!ponto.files.carta2"><b>Não informado</b></span>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="form form-opcional">
        <h4>Informações Opcionais</h4>
        <div class="row">
            <span class="destaque destaque-conecte">Redes sociais:</span>
        </div>
        <div class="row">
            <label class="colunm-redes site-oficial">
                <span><i class="icon icon-location"></i> Site oficial</span>
                <span><b>{{ponto.site}}</b></span>
                <span ng-if="!ponto.site"><b>Não informado</b></span>
            </label>
        </div>
        <div class="row">
            <label class="colunm-redes facebook">
                <span><i class="icon icon-facebook-squared"></i> Página Facebook</span>
                <span><b>{{ponto.facebook}}</b></span>
                <span ng-if="!ponto.facebook"><b>Não informado</b></span>
            </label>

            <label class="colunm-redes twitter">
                <span><i class="icon icon-twitter"></i> Perfil no Twitter</span>
                <span><b>{{ponto.twitter}}</b></span>
                <span ng-if="!ponto.twitter"><b>Não informado</b></span>
            </label>

            <label class="colunm-redes googleplus">
                <span><i class="icon icon-gplus"></i> Perfil no Google+</span>
                <span><b>{{ponto.googleplus}}</b></span>
                <span ng-if="!ponto.googleplus"><b>Não informado</b></span>
            </label>
        </div>
        <div class="row">
            <label class="colunm-redes flick">
                <span><img src="<?php $this->asset('img/icon_flicker.png') ?>"> Página no Flickr</span>
                <span><b>{{ponto.flickr}}</b></span>
                <span ng-if="!ponto.flickr"><b>Não informado</b></span>
            </label>

            <label class="colunm-redes diaspora">
                <span><img src="<?php $this->asset('img/icon_diaspora.png') ?>"> Perfil na Diáspora:</span>
                <span><b>{{ponto.diaspora}}</b></span>
                <span ng-if="!ponto.diaspora"><b>Não informado</b></span>
            </label>

            <label class="colunm-redes youtube">
                <span><img src="<?php $this->asset('img/icon_youtube.png') ?>"> Perfil no Youtube:</span>
                <span><b>{{ponto.youtube}}</b></span>
                <span ng-if="!ponto.youtube"><b>Não informado</b></span>
            </label>
        </div>
        <div class="clear"></div>
        <div class="row">
            <span class="destaque">Conte um pouco sobre a história do ponto de Cultura (800 caracteres) <i class='hltip' title='Nos diga um pouco mais sobre o ponto de cultura, como por exemplo como ele começou e como surgiu a idéia'>?</i>  </span>
            <label class="colunm1">
                <span><b>{{ponto.longDescription}}</b></span>
                <span ng-if="!ponto.longDescription"><b>Não informado</b></span>
            </label>

        </div>
        <div class="clear"></div>
        <div class="row">
            <span class="destaque">Fotos de Divulgação do Ponto de Cultura</span>
            <div class="colunm1">
                <div class="img_updade file-item" ng-repeat="f in ponto.files.gallery">
                    <img src="{{f.files.avatarBig.url}}" width="160" height="138">
                </div>
                <span ng-if="!ponto.files.gallery"><b>Não informado</b></span>
            </div>
        </div>
    </div>
</div>
