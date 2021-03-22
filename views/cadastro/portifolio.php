<?php
    $this->bodyProperties['ng-app'] = "culturaviva";
    $this->layout = 'cadastro';
    $this->cadastroTitle = '3. Portfólio e Anexos';
    $this->cadastroText = 'Inclua suas fotos, links e redes sociais! Isto nos ajuda a entender que tipo de atividades culturais você realiza como Ponto de Cultura!';
    $this->cadastroIcon = 'icon-picture';
    $this->cadastroPageClass = 'portfolio page-base-form';
    //$this->cadastroLinkContinuar = 'entidadeFinanciamento';
    $this->cadastroLinkContinuar = 'articulacao';
    $this->cadastroLinkBack = 'entidadeDados';
?>
<style>

</style>
<form name="form_portifolio" ng-controller="PortifolioCtrl">
    <?php $this->part('messages'); ?>
    <div class="form">
        <h4>Informações Obrigatórias</h4>
        <div class="row" ng-controller="ImageUploadCtrl" ng-init="init('ponto')">
<!--            <span class="destaque espacoleft">Portifólio* <i class='hltip' title='Nos ajude a entender o histórico de seu Ponto de Cultura e as atividades desenvolvidas nele.'>?</i></span>-->

            <!-- Portifolio online -->
            <label class="colunm-100">
                <div class="row" ng-controller="PortifolioCtrl">
                    <label class="colunm1">
                                <span class="destaque">
                                          Portfólio Online
                        <i class='hltip' title="Caso possua um portfolio online, coloque o link aqui.">?</i></span>
                        <input type="text" name="atividadesEmRealizacaoLink" placeholder="http://" ng-blur="save_field('atividadesEmRealizacaoLink')" ng-model="agent.atividadesEmRealizacaoLink" />
                    </label>
                </div>
                <p>Caso ainda não possua um portfolio online, uma possibilidade é gravar um vídeo de até 10 minutos contando sobre seu Ponto de Cultura.
                    Publique-o no Youtube e compartilhe o link aqui.</p>
                <p>Se preferir, envie no campo abaixo arquivos no formato PDF de até 20MB. </p>
            </label>

            <p><strong>Você deve adicionar somente um: ou portifólio online ou arquivo PDF.</strong></p>

            <!-- Portifolio -->
            <div class="colunm-20">
                <div class="file-item">
                    <a ng-if="agent.files.portifolio" href="#" class="exclui" ng-click="deleteFile(agent.files.portifolio)" title="Excluir Portfólio">x</a>
                    <div type="file" ngf-select="uploadFile($file, 'portifolio')" accept="config.pdf.validation" ngf-max-size="config.pdf.maxUploadSize" title="{{agent.files.portifolio ? 'Clique para alterar o Portfólio' : 'Clique para incluir um Portfólio'}}">
                        <img ng-if="!agent.files.portifolio" src="<?php $this->asset('img/incluir_img.png') ?>" width="160" height="138">
                        <img ng-if="agent.files.portifolio" src="<?php $this->asset('img/pdflogo.png') ?>" width="160" height="138">
                    </div>
                    <div id="errorBox">
                        <span id="msg_errorBox" ng-hide=errozao>Arquivos devem possuir no máximo 20MB</span>
                    </div>
                </div>
                <a ng-if="agent.files.portifolio" href="{{agent.files.portifolio.url}}" target="_blank">{{agent.files.portifolio.name}}</a>
                <div class="progress row" ng-show="f.progress >= 0">
                    <span style="width:{{f.progress}}%;" ng-bind="f.progress + '%'"></span>
                </div>
            </div>

            <p>
                Precisa de ajuda para montar seu portfólio?
                <span class="destaque" style="display: inline-block">
                    <i class="hltip" title='Um portfólio é um relatório das atividades desenvolvidas pelo Ponto de Cultura com imagens, vídeos e outros itens que comprovem a sua existência'>?</i>
                </span> <br>
                <a href="<?php $this->asset('pdf/modelos_de_portfolio.docx')?>" target="_blank">Clique aqui</a> para baixar um modelo com orientações.
            </p>
            <!--Hitória do ponto de cultura-->
            <div class="row">
                <label class="colunm-full">
                    <span class="destaque">Conte um pouco sobre a história do Ponto de Cultura (max. 800 caracteres)* <i class='hltip' title='Nos diga um pouco mais sobre o ponto de cultura, como por exemplo como ele começou e como surgiu a idéia'>?</i>  </span>
                    <textarea rows="8" required ng-change="save_field('longDescription')" maxlength="800" ng-model="agent.longDescription"></textarea>
                    <span>{{800 - agent.longDescription.length}} caracteres restantes</span>
                    <span ng-class="error" style="color: red" ng-show="form_portifolio.longDescription.$error.required">Descrição Obrigatória</span>
                </label>
            </div>
            <div class="clear"></div>
        </div>

        <div class="row" ng-if="agent_entidade.tipoPonto == 'ponto_coletivo'">
            <div ng-controller="ImageUploadCtrl" ng-init="init('ponto')">
                <h4>Carta de Autorização de Coletivo sem Constituição Jurídica</h4>
                <div class="colunm-20">
                    <div class="file-item">
                        <a ng-if="agent.files.ata" href="#" class="exclui" ng-click="deleteFile(agent.files.ata)"
                           title="Excluir Portfólio">x</a>
                        <div type="file" ngf-select="uploadFile($file, 'ata')" accept="config.pdf.validation"
                             ngf-max-size="config.pdf.maxUploadSize"
                             title="{{agent.files.ata ? 'Clique para alterar a Carta' : 'Clique para incluir a Carta'}}">
                            <img ng-if="!agent.files.ata" src="<?php $this->asset('img/incluir_img.png') ?>" width="160"
                                 height="138">
                            <img ng-if="agent.files.ata" src="<?php $this->asset('img/pdflogo.png') ?>" width="160"
                                 height="138">
                        </div>
                    </div>
                    <a ng-if="agent.files.ata" href="{{agent.files.ata.url}}"
                       target="_blank">{{agent.files.ata.name}}</a>
                    <div class="progress row" ng-show="f.progress >= 0">
                        <span style="width:{{f.progress}}%;" ng-bind="f.progress + '%'"></span>
                    </div>
                </div>
                <label class="colunm-50">
                    Precisa de ajuda para montar sua carta?
                    <br>
                    <a href="<?php $this->asset('pdf/carta_de_apoio_da_comunidade_a_coletivo_v_3.pdf') ?>"
                       target="_blank">Clique aqui</a> para baixar um modelo com orientações.
                </label>
            </div>
        </div>

        <div ng-show="agent_entidade.tipoPonto==='ponto_entidade' || agent_entidade.tipoPonto==='ponto_coletivo'">

        <div class="row">
            <h4 style="background-color: #078979">Cartas de Reconhecimento</h4>
            <p style="text-align:justify;">Anexar 02 cartas de apoio à entidade ou coletivo cultural requerente, emitidas por Pontos de Cultura, instituições públicas, privadas, ou coletivos culturais relacionadas com arte, cultura, educação ou desenvolvimento comunitário. As cartas devem ser assinadas e digitalizadas. Serão aceitas somente assinaturas manuscritas em papel ou impressões digitais em caso de pessoas não alfabetizadas.</p>
            <p>O ato de assinar uma Carta de Reconhecimento implica na responsabilidade da instituições públicas, privadas, ou coletivos culturais para com a credibilidade do Ponto/Pontão de Cultura, firmando a legitimidade do mesmo.</p>
        </div>
        <div class="row">
            <span class="destaque espacoleft">Carta de Reconhecimento * <i class='hltip' title='As cartas de apoio nos ajudam a entender como o Ponto de Cultura se conecta com a comunidade ao seu redor e certifica a participação da comunidade no processo.'>?</i></span>
            <div class="colunm-20" ng-controller="ImageUploadCtrl" ng-init="init('ponto')">
              <div class="file-item">
                  <a ng-if="agent.files.carta1" href="#" class="exclui" ng-click="deleteFile(agent.files.carta1)" title="Excluir Carta de Recomendação">x</a>
                  <div type="file" ngf-select="uploadFile($file, 'carta1')" accept="config.pdf.validation" ngf-max-size="config.pdf.maxUploadSize" title="{{agent.files.carta1 ? 'Clique para alterar a carta de recomendação' : 'Clique para incluir uma carta de recomendação'}}">
                      <img ng-if="!agent.files.carta1" src="<?php $this->asset('img/incluir_img.png') ?>" width="160" height="138">
                      <img ng-if="agent.files.carta1" src="<?php $this->asset('img/pdflogo.png') ?>" width="160" height="138">
                  </div>
              </div>
              <a ng-if="agent.files.carta1" href="{{agent.files.carta1.url}}" target="_blank">{{agent.files.carta1.name}}</a>
              <div class="progress row" ng-show="f.progress >= 0">
                  <span style="width:{{f.progress}}%;" ng-bind="f.progress + '%'"></span>
              </div>
            </div>
            <div class="colunm-20" ng-controller="ImageUploadCtrl" ng-init="init('ponto')">
              <div class="file-item">
                  <a ng-if="agent.files.carta2" href="#" class="exclui" ng-click="deleteFile(agent.files.carta2)" title="Excluir Portfólio">x</a>
                  <div type="file" ngf-select="uploadFile($file, 'carta2')" accept="config.pdf.validation" ngf-max-size="config.pdf.maxUploadSize" title="{{agent.files.carta2 ? 'Clique para alterar a carta de recomendação' : 'Clique para incluir uma carta de recomendação'}}">
                      <img ng-if="!agent.files.carta2" src="<?php $this->asset('img/incluir_img.png') ?>" width="160" height="138">
                      <img ng-if="agent.files.carta2" src="<?php $this->asset('img/pdflogo.png') ?>" width="160" height="138">
                  </div>
              </div>
              <a ng-if="agent.files.carta2" href="{{agent.files.carta2.url}}" target="_blank">{{agent.files.carta2.name}}</a>
              <div class="progress row" ng-show="f.progress >= 0">
                  <span style="width:{{f.progress}}%;" ng-bind="f.progress + '%'"></span>
              </div>
            </div>

            <label class="colunm-50">

                <p>
                    Precisa de um modelo de carta?
                    <br>
                    <a href="<?php $this->asset('pdf/modelo_de_carta_de_referencia_ponto.docx')?>" target="_blank">Clique aqui</a> para baixar.
                </p>
            </label>
        </div>
        <div class="clear"></div>

        </div>

        <!-- Carta de Referência -->
        <div ng-show="agent_entidade.tipoPonto==='pontao'">
            <div class="row">
                <h4>Cartas de Referência</h4>
                <p style="text-align:justify;">Anexar uma carta de referência de ao menos 2 Pontos de Cultura reconhecendo o trabalho de Pontão realizado pela entidade, conforme os termos da Lei 13.018/2014 e da IN 08/2016</p>
            </div>
            <div class="row">
                <span class="destaque espacoleft">Carta de Referência * <i class='hltip' title='As cartas de apoio nos ajudam a entender como o Ponto de Cultura se conecta com a comunidade ao seu redor e certifica a participação da comunidade no processo.'>?</i></span>
                <div class="colunm-20" ng-controller="ImageUploadCtrl" ng-init="init('ponto')">
                    <div class="file-item">
                        <a ng-if="agent.files.cartaReferencia1" href="#" class="exclui" ng-click="deleteFile(agent.files.cartaReferencia1)" title="Excluir Carta de Referência">x</a>
                        <div type="file" ngf-select="uploadFile($file, 'cartaReferencia1')" accept="config.pdf.validation" ngf-max-size="config.pdf.maxUploadSize" title="{{agent.files.cartaReferencia1 ? 'Clique para alterar a carta de referência' : 'Clique para incluir uma carta de referência'}}">
                            <img ng-if="!agent.files.cartaReferencia1" src="<?php $this->asset('img/incluir_img.png') ?>" width="160" height="138">
                            <img ng-if="agent.files.cartaReferencia1" src="<?php $this->asset('img/pdflogo.png') ?>" width="160" height="138">
                        </div>
                    </div>

                    <a ng-if="agent.files.cartaReferencia1" href="{{agent.files.cartaReferencia1.url}}" target="_blank">{{agent.files.cartaReferencia1.name}}</a>
                    <div class="progress row" ng-show="f.progress >= 0">
                        <span style="width:{{f.progress}}%;" ng-bind="f.progress + '%'"></span>
                    </div>
                </div>
                <div class="colunm-20" ng-controller="ImageUploadCtrl" ng-init="init('ponto')">
                    <div class="file-item">
                        <a ng-if="agent.files.cartaReferencia2" href="#" class="exclui" ng-click="deleteFile(agent.files.cartaReferencia2)" title="Excluir Carta de Referência">x</a>
                        <div type="file" ngf-select="uploadFile($file, 'cartaReferencia2')" accept="config.pdf.validation" ngf-max-size="config.pdf.maxUploadSize" title="{{agent.files.cartaReferencia2 ? 'Clique para alterar a carta de referência' : 'Clique para incluir uma carta de referência'}}">
                            <img ng-if="!agent.files.cartaReferencia2" src="<?php $this->asset('img/incluir_img.png') ?>" width="160" height="138">
                            <img ng-if="agent.files.cartaReferencia2" src="<?php $this->asset('img/pdflogo.png') ?>" width="160" height="138">
                        </div>
                    </div>
                    <a ng-if="agent.files.cartaReferencia2" href="{{agent.files.cartaReferencia2.url}}" target="_blank">{{agent.files.cartaReferencia2.name}}</a>
                    <div class="progress row" ng-show="f.progress >= 0">
                        <span style="width:{{f.progress}}%;" ng-bind="f.progress + '%'"></span>
                    </div>
                </div>

                <label class="colunm-50">

                    <p>
                        Precisa de um modelo de carta de referência?
                        <br>
                        <a href="<?php $this->asset('pdf/modelo_de_carta_de_referencia_pontao.docx')?>" target="_blank">Clique aqui</a> para baixar.
                    </p>
                </label>
            </div>
            <div class="clear"></div>
        </div>
    </div>

    <div class="form form-opcional">
        <h4>Informações Opcionais</h4>
        <div class="row" ng-controller="ImageUploadCtrl" ng-init="init('ponto')">
            <span class="destaque espacoleft">Fotos de Divulgação do Ponto de Cultura <i class='hltip' title='Essas imagens devem mostrar as atividades que seu Ponto de Cultura desenvolve'>?</i></span>
            <p class="espacoleft">Inclua no máximo 10 arquivos, no formato JPG ou PNG com até 1MB</p>
            <div class="img_updade file-item" ng-repeat="f in agent.files.gallery">
                <a class="exclui" ng-click="deleteFile(f)" title="Excluir arquivo">x</a>
                <img src="{{f.url}}" width="160" height="138">
            </div>
            <div class="img_updade file-item">
                <div type="file" ngf-select="uploadFile($file, 'gallery')" accept="config.image.validation" ngf-max-size="config.image.maxUploadSize" title="Clique para incluir uma foto">
                    <img src="<?php $this->asset('img/incluir_img.png') ?>" width="160" height="138">
                </div>
                <div class="progress row" ng-show="f.progress >= 0">
                    <span style="width:{{f.progress}}%;" ng-bind="f.progress + '%'"></span>
                </div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
</form>
