<?php
    $this->bodyProperties['ng-app'] = "culturaviva";
    $this->layout = 'cadastro';
    $this->cadastroTitle = '3. Seu Ponto no Mapa';
    $this->cadastroText = 'Vamos colocar seu Ponto no mapa! Com estes dados podemos cartografar a rede de Pontos de Cultura por todo Brasil';
    $this->cadastroIcon = 'icon-location';
    $this->cadastroPageClass = 'ponto-mapa page-base-form';
    $this->cadastroLinkContinuar = 'portifolio';
?>
<style>
#obsBox{
margin-left:260px;
color:gray;
border: medium dashed;
border-color: gray;
}
</style>

<form name="form_ponto" ng-controller="PointCtrl">
    <?php $this->part('messages'); ?>
    <div class="form">
        <h4>Informações Obrigatórias</h4>
        <div class="row">
          <label class="colunm-full">


            <div class="img_updade" ng-controller="ImageUploadCtrl" ng-init="init('ponto')">
                <div class="file-item">
                    <a ng-if="agent.files.avatar" class="exclui" ng-click="deleteFile(agent.files.avatar)" title="Excluir arquivo">x</a>
                    <div type="file" ngf-select="uploadFile($file, 'avatar')" accept="config.image.validation" ngf-max-size="config.image.maxUploadSize" title="{{agent.files.avatar ? 'Clique para alterar a logo' : 'Clique para incluir uma logo'}}">
                        <img ng-if="!agent.files.avatar" src="<?php $this->asset('img/incluir_img.png') ?>" width="160" height="138">
                        <img ng-if="agent.files.avatar" src="{{agent.files.avatar.url}}" width="160" height="138">
                    </div>
                </div>
                <div class="progress row" ng-show="f.progress >= 0">
                  <span style="width:{{f.progress}}%;" ng-bind="f.progress + '%'"></span>
                </div>
            </div>
            <span class="destaque-img" style="width:65%; margin-top:45px;" ng-controller="ImageUploadCtrl">Incluir o logo vai fazer com que seu Ponto seja facilmente reconhecido no mapa da Rede Cultura Viva. Utilize arquivos JPG ou PNG de até {{config.images.maxUploadSize}}</span>
          </label>
            <label class="colunm-full">
                <span class="destaque">Nome do Ponto/Pontão de Cultura*</span>
                <input name="name" type="text" ng-blur="save_field('name')" ng-model="agent.name" />
                <span class="error" ng-repeat="error in errors.name">{{ error }}</span>
            </label>
        </div>

        <div class="clear"></div>

        <div class="row">
            <label class="colunm-full">
                <span class="destaque">Breve descrição do Ponto/Pontão de Cultura (max. 400 caracteres)* <i class='hltip' title='Esta descrição será publicada no mapa da Rede Cultura Viva, aproveite para contar um pouco mais do seu Ponto e atrair o interesse do público.'>?</i></span>
                <textarea name="shortDescription" maxlength="400" ng-blur="save_field('shortDescription')" ng-model="agent.shortDescription"></textarea>
                <span class="error" ng-repeat="error in errors.shortDescription">{{ error }}</span>
                <span>{{400 - agent.shortDescription.length}} Characters</span>
            </label>

            <label class="colunm1">
                <span class="destaque">País*</span>
                <select name="pais" ng-blur="save_field('pais')" ng-model="agent.pais">
                  <option value="Brasil" selected>Brasil</option>
                  <option value="África do Sul">África do Sul</option>      <option value="Albânia">Albânia</option>
                  <option value="Alemanha">Alemanha</option>                <option value="Andorra">Andorra</option>
                  <option value="Angola">Angola</option>                    <option value="Anguilla">Anguilla</option>
                  <option value="Antigua">Antigua</option>                  <option value="Arábia Saudita">Arábia Saudita</option>
                  <option value="Argentina">Argentina</option>              <option value="Armênia">Armênia</option>
                  <option value="Aruba">Aruba</option>                      <option value="Austrália">Austrália</option>
                  <option value="Áustria">Áustria</option>                  <option value="Azerbaijão">Azerbaijão</option>
                  <option value="Bahamas">Bahamas</option>                  <option value="Bahrein">Bahrein</option>
                  <option value="Bangladesh">Bangladesh</option>            <option value="Barbados">Barbados</option>
                  <option value="Bélgica">Bélgica</option>                  <option value="Benin">Benin</option>
                  <option value="Bermudas">Bermudas</option>                <option value="Botsuana">Botsuana</option>
                            <option value="Brunei">Brunei</option>
                  <option value="Bulgária">Bulgária</option>                <option value="Burkina Fasso">Burkina Fasso</option>
                                 <option value="Cabo Verde">Cabo Verde</option>
                  <option value="Camarões">Camarões</option>                <option value="Camboja">Camboja</option>
                  <option value="Canadá">Canadá</option>                    <option value="Cazaquistão">Cazaquistão</option>
                  <option value="Chade">Chade</option>                      <option value="Chile">Chile</option>
                  <option value="China">China</option>                      <option value="Cidade do Vaticano">Cidade do Vaticano</option>
                  <option value="Colômbia">Colômbia</option>                <option value="Congo">Congo</option>
                  <option value="Coréia do Sul">Coréia do Sul</option>      <option value="Costa do Marfim">Costa do Marfim</option>
                  <option value="Costa Rica">Costa Rica</option>            <option value="Croácia">Croácia</option>
                  <option value="Dinamarca">Dinamarca</option>              <option value="Djibuti">Djibuti</option>
                  <option value="Dominica">Dominica</option>                <option value="EUA">EUA</option>
                  <option value="Egito">Egito</option>                      <option value="El Salvador">El Salvador</option>
                  <option value="Emirados Árabes">Emirados Árabes</option>      <option value="Equador">Equador</option>
                  <option value="Eritréia">Eritréia</option>                <option value="Escócia">Escócia</option>
                  <option value="Eslováquia">Eslováquia</option>            <option value="Eslovênia">Eslovênia</option>
                  <option value="Espanha">Espanha</option>                  <option value="Estônia">Estônia</option>
                  <option value="Etiópia">Etiópia</option>                  <option value="Fiji">Fiji</option>
                  <option value="Filipinas">Filipinas</option>              <option value="Finlândia">Finlândia</option>
                  <option value="França">França</option>                    <option value="Gabão">Gabão</option>
                  <option value="Gâmbia">Gâmbia</option>                    <option value="Gana">Gana</option>
                  <option value="Geórgia">Geórgia</option>                  <option value="Gibraltar">Gibraltar</option>
                  <option value="Granada">Granada</option>                  <option value="Grécia">Grécia</option>
                  <option value="Guadalupe">Guadalupe</option>              <option value="Guam">Guam</option>
                  <option value="Guatemala">Guatemala</option>              <option value="Guiana">Guiana</option>
                  <option value="Guiana Francesa">Guiana Francesa</option>      <option value="Guiné-bissau">Guiné-bissau</option>
                  <option value="Haiti">Haiti</option>                      <option value="Holanda">Holanda</option>
                  <option value="Honduras">Honduras</option>                <option value="Hong Kong">Hong Kong</option>
                  <option value="Hungria">Hungria</option>                  <option value="Iêmen">Iêmen</option>
                  <option value="Ilhas Cayman">Ilhas Cayman</option>        <option value="Ilhas Cook">Ilhas Cook</option>
                  <option value="Ilhas Curaçao">Ilhas Curaçao</option>      <option value="Ilhas Marshall">Ilhas Marshall</option>
                  <option value="Ilhas Turks & Caicos">Ilhas Turks & Caicos</option>      <option value="Ilhas Virgens (brit.)">Ilhas Virgens (brit.)</option>
                  <option value="Ilhas Virgens(amer.)">Ilhas Virgens(amer.)</option>      <option value="Ilhas Wallis e Futuna">Ilhas Wallis e Futuna</option>
                  <option value="Índia">Índia</option>                     <option value="Indonésia">Indonésia</option>
                  <option value="Inglaterra">Inglaterra</option>           <option value="Irlanda">Irlanda</option>
                  <option value="Islândia">Islândia</option>               <option value="Israel">Israel</option>
                  <option value="Itália">Itália</option>                   <option value="Jamaica">Jamaica</option>
                  <option value="Japão">Japão</option>                     <option value="Jordânia">Jordânia</option>
                  <option value="Kuwait">Kuwait</option>                   <option value="Latvia">Latvia</option>
                  <option value="Líbano">Líbano</option>                   <option value="Liechtenstein">Liechtenstein</option>
                  <option value="Lituânia">Lituânia</option>               <option value="Luxemburgo">Luxemburgo</option>
                  <option value="Macau">Macau</option>                     <option value="Macedônia">Macedônia</option>
                  <option value="Madagascar">Madagascar</option>           <option value="Malásia">Malásia</option>
                  <option value="Malaui">Malaui</option>                   <option value="Mali">Mali</option>
                  <option value="Malta">Malta</option>                     <option value="Marrocos">Marrocos</option>
                  <option value="Martinica">Martinica</option>             <option value="Mauritânia">Mauritânia</option>
                  <option value="Mauritius">Mauritius</option>             <option value="México">México</option>
                  <option value="Moldova">Moldova</option>                 <option value="Mônaco">Mônaco</option>
                  <option value="Montserrat">Montserrat</option>           <option value="Nepal">Nepal</option>
                  <option value="Nicarágua">Nicarágua</option>             <option value="Niger">Niger</option>
                  <option value="Nigéria">Nigéria</option>                 <option value="Noruega">Noruega</option>
                  <option value="Nova Caledônia">Nova Caledônia</option>      <option value="Nova Zelândia">Nova Zelândia</option>
                  <option value="Omã">Omã</option>                         <option value="Palau">Palau</option>
                  <option value="Panamá">Panamá</option>                   <option value="Papua-nova Guiné">Papua-nova Guiné</option>
                  <option value="Paquistão">Paquistão</option>             <option value="Peru">Peru</option>
                  <option value="Polinésia Francesa">Polinésia Francesa</option>      <option value="Polônia">Polônia</option>
                  <option value="Porto Rico">Porto Rico</option>           <option value="Portugal">Portugal</option>
                  <option value="Qatar">Qatar</option>                     <option value="Quênia">Quênia</option>
                  <option value="Rep. Dominicana">Rep. Dominicana</option>      <option value="Rep. Tcheca">Rep. Tcheca</option>
                  <option value="Reunion">Reunion</option>                <option value="Romênia">Romênia</option>
                  <option value="Ruanda">Ruanda</option>                  <option value="Rússia">Rússia</option>
                  <option value="Saipan">Saipan</option>                  <option value="Samoa Americana">Samoa Americana</option>
                  <option value="Senegal">Senegal</option>                <option value="Serra Leone">Serra Leone</option>
                  <option value="Seychelles">Seychelles</option>          <option value="Singapura">Singapura</option>
                  <option value="Síria">Síria</option>                    <option value="Sri Lanka">Sri Lanka</option>
                  <option value="St. Kitts & Nevis">St. Kitts & Nevis</option>      <option value="St. Lúcia">St. Lúcia</option>
                  <option value="St. Vincent">St. Vincent</option>        <option value="Sudão">Sudão</option>
                  <option value="Suécia">Suécia</option>                  <option value="Suiça">Suiça</option>
                  <option value="Suriname">Suriname</option>              <option value="Tailândia">Tailândia</option>
                  <option value="Taiwan">Taiwan</option>                  <option value="Tanzânia">Tanzânia</option>
                  <option value="Togo">Togo</option>                      <option value="Trinidad & Tobago">Trinidad & Tobago</option>
                  <option value="Tunísia">Tunísia</option>                <option value="Turquia">Turquia</option>
                  <option value="Ucrânia">Ucrânia</option>                <option value="Uganda">Uganda</option>
                  <option value="Uruguai">Uruguai</option>                <option value="Venezuela">Venezuela</option>
                  <option value="Vietnã">Vietnã</option>                  <option value="Zaire">Zaire</option>
                  <option value="Zâmbia">Zâmbia</option>                  <option value="Zimbábue">Zimbábue</option>
                </select>
            </label>

            <label class="colunm1">
                <span class="destaque">O Ponto/Pontão de Cultura tem sede própria?*</span>
                <select name="tem_sede" ng-blur="save_field('tem_sede')" ng-model="agent.tem_sede">
                    <option></option>
                    <option value="1" ng-value="1">Sim</option>
                    <option value="0" ng-value="0">Não</option>
                </select>
                <label class="check">
                    <input type="checkbox"
                           name="sede_realizaAtividades"
                           ng-change="save_field('sede_realizaAtividades', true)"
                           ng-model="agent.sede_realizaAtividades"
                           ng-true-value="1"
                           ng-false-value="0"/>
                    realiza atividades culturais na sede
                </label>
                <?php /*
                <input type="checkbox" ng-change="save_field('mesmoEndereco', true)" ng-model="agent.mesmoEndereco" ng-checked="agent.mesmoEndereco == 'true'"/>
                <span class="check">mesmo endereço cadastrado no CNPJ da entidade</span>
                */ ?>

                <span class="error" ng-repeat="error in errors.tem_sede">{{ error }}</span>
            </label>


        </div>

        <div class="clear"></div>

        <div class="row">
            <span class="colunm1">
                <span class="destaque">Informe o endereço do Ponto/Pontão de Cultura <i class='hltip' title='Caso não tenha uma sede, coloque o endereço de referência do Ponto de Cultura'>?</i></span>
            </span>
        </div>

        <div class="clear"></div>

        <div class="row">

            <!-- inicio campos CEP e Código Postal -->
            <label class="colunm2"
                   ng-class="{'busy': cepcoder.busy}"
                   ng-if="agent.pais === 'Brasil'">
                <span class="destaque">CEP* <i class='hltip' title='Caso não saiba o CEP acesse o site dos correios'>?</i></span>
                <input type="text"
                       name="cep"
                       ng-blur="save_field('cep'); cepcoder.code(agent.cep)"
                       ng-model="agent.cep"
                       ui-mask="99999-999">
                       <!-- <input type="text"
                                   name="cep"
                                   ng-blur="save_field('cep'); cepcoder.code(agent.cep)"
                                   ng-model="agent.cep"
                                   ui-mask="99999-999"> -->
                <span class="error" ng-repeat="error in errors.cep">{{ error }}</span>
            </label>

            <label class="colunm2"
                   ng-class="{busy: cepcoder.busy}"
                   ng-if="agent.pais !== 'Brasil'">
                <span class="destaque">Código Postal <i class='hltip' title='Caso não saiba o código postal consulte com seu governo'>?</i></span>
                <input type="text"
                       name="cep"
                       ng-blur="save_field('cep'); cepcoder.code(agent.cep)"
                       ng-model="agent.cep">
                <span class="error" ng-repeat="error in errors.cep">{{ error }}</span>
            </label>
            <!-- Fim campos CEP e Código Postal -->

            <label ng-if="agent.pais === 'Brasil'" class="colunm2">
                <span class="destaque">Estado*</span>
                <select name="En_Estado" ng-blur="save_field('En_Estado')" ng-model="agent.En_Estado">
                    <option value="AC">Acre</option>              <option value="AL">Alagoas</option>
                    <option value="AP">Amapá</option>             <option value="AM">Amazonas</option>
                    <option value="BA">Bahia</option>             <option value="CE">Ceará</option>
                    <option value="DF">Distrito Federal</option>  <option value="ES">Espírito Santo</option>
                    <option value="GO">Goiás</option>             <option value="MA">Maranhão</option>
                    <option value="MT">Mato Grosso</option>       <option value="MS">Mato Grosso do Sul</option>
                    <option value="MG">Minas Gerais</option>      <option value="PA">Pará</option>
                    <option value="PB">Paraíba</option>           <option value="PR">Paraná</option>
                    <option value="PE">Pernambuco</option>        <option value="PI">Piauí</option>
                    <option value="RJ">Rio de Janeiro</option>    <option value="RN">Rio Grande do Norte</option>
                    <option value="RS">Rio Grande do Sul</option> <option value="RO">Rondônia</option>
                    <option value="RR">Roraima</option>           <option value="SC">Santa Catarina</option>
                    <option value="SP">São Paulo</option>         <option value="SE">Sergipe</option>
                    <option value="TO">Tocantins</option>
                </select>
                <span class="error" ng-repeat="error in errors.estado">{{ error }}</span>
            </label>




            <label class="colunm2" ng-if="agent.pais !== 'Brasil'">
                <span class="destaque">Estado</span>
                <input name="En_Estado" type="text" ng-blur="save_field('En_Estado')" ng-model="agent.En_Estado"/>
            </label>

            <label class="colunm2" ng-class="{busy: cidadecoder.busy}"  ng-if="agent.pais !== 'Brasil'">
                <span class="destaque">Cidade*</span>
                <input type="text" name="En_Municipio" ng-blur="save_field('En_Municipio'); cidadecoder.code(agent.En_Municipio, agent.pais)"
                       ng-model="agent.En_Municipio"/>
                <span class="error" ng-repeat="error in errors.cidade">{{ error }}</span>
            </label>

            <label class="colunm2"  ng-if="agent.pais === 'Brasil'">
                <span class="destaque">Cidade*</span>
                <input type="text" name="En_Municipio" ng-blur="save_field('En_Municipio')" ng-model="agent.En_Municipio"/>
                <span class="error" ng-repeat="error in errors.cidade">{{ error }}</span>
            </label>

            <label class="colunm3">
                <span class="destaque">{{agent.pais == 'Brasil' ? 'Bairro*' : 'Bairro'}}</span>
                <input type="text" name="En_Bairro" ng-blur="save_field('En_Bairro'); endcoder.code();" ng-model="agent.En_Bairro"/>
                <span class="error" ng-repeat="error in errors.bairro">{{ error }}</span>
            </label>
        </div>

        <div class="clear"></div>

        <div class="row">

            <label class="colunm1" style="width:413px;">
                <span class="destaque">{{agent.pais == 'Brasil' ? 'Rua*' : 'Rua'}}</span>
                <input type="text" name="En_Nome_Logradouro" ng-blur="save_field('En_Nome_Logradouro'); endcoder.code();" ng-model="agent.En_Nome_Logradouro"/>
                <span class="error" ng-repeat="error in errors.rua">{{ error }}</span>
            </label>

            <label class="colunm2">
                <span class="destaque">{{agent.pais == 'Brasil' ? 'Número*' : 'Número'}}</span>
                <input type="text" name="En_Num" ng-blur="save_field('En_Num')" ng-model="agent.En_Num"/>
                <span class="error" ng-repeat="error in errors.numero">{{ error }}</span>
            </label>

            <label class="colunm3">
                <span class="destaque">Complemento</span>
                <input type="text" ng-blur="save_field('En_Complemento')" ng-model="agent.En_Complemento"/>
                <span class="error" ng-repeat="error in errors.complemento">{{ error }}</span>
            </label>
        </div>
    </div>
    <div class="clear"></div>
    <div>
      <b id="obsBox">*Arraste o ícone para alterar a localização do seu ponto*</b>
    </div>
    <div class="form form-mapa">
        <?php /*
        <div class="mapa js-map-container">
            <div class="clearfix js-leaflet-control" data-leaflet-target=".leaflet-top.leaflet-left">
                <a id ="button-locate-me" class="control-infobox-open hltip botoes-do-mapa" title="Encontrar minha localização"></a>
            </div>
            <div id="single-map-container" class="js-map" data-lat="-46.633328" data-lng="-23.548991"></div>
            <input type="hidden" id="map-target" data-name="location" class="js-editable" data-edit="location" data-value="">
        </div>
        */ ?>
        <style type="text/css">.leaflet-canvas { min-height: 400px; }</style>
        <leaflet markers="markers"></leaflet>
    </div>

<?php /*
    <div class="form form-opcional">
        <h4>Informações Opcionais</h4>

        <label>
            <span class="destaque-opcional">Selecione o local em que são realizadas as ações culturais do Ponto/Pontão de Cultura (marque quantas opções quiser)</span>
        </label>

        <div class="row">
            <label class="colunm1">
                <span>Estado*</span>
                <select ng-blur="save_field('localRealizacao_estado')" ng-model="agent.localRealizacao_estado">
                    <option value="AC">Acre</option>              <option value="AL">Alagoas</option>
                    <option value="AP">Amapá</option>             <option value="AM">Amazonas</option>
                    <option value="BA">Bahia</option>             <option value="CE">Ceará</option>
                    <option value="DF">Distrito Federal</option>  <option value="ES">Espírito Santo</option>
                    <option value="GO">Goiás</option>             <option value="MA">Maranhão</option>
                    <option value="MT">Mato Grosso</option>       <option value="MS">Mato Grosso do Sul</option>
                    <option value="MG">Minas Gerais</option>      <option value="PA">Pará</option>
                    <option value="PB">Paraíba</option>           <option value="PR">Paraná</option>
                    <option value="PE">Pernambuco</option>        <option value="PI">Piauí</option>
                    <option value="RJ">Rio de Janeiro</option>    <option value="RN">Rio Grande do Norte</option>
                    <option value="RS">Rio Grande do Sul</option> <option value="RO">Rondônia</option>
                    <option value="RR">Roraima</option>           <option value="SC">Santa Catarina</option>
                    <option value="SP">São Paulo</option>         <option value="SE">Sergipe</option>
                    <option value="TO">Tocantins</option>
                </select>
                <span class="error" ng-repeat="error in errors.localRealizacao_estado">{{ error }}</span>
            </label>

            <label class="colunm2">
                <span>Cidade*</span>
                <input type="text" ng-blur="save_field('localRealizacao_cidade')" ng-model="agent.localRealizacao_cidade"/>
                <span class="error" ng-repeat="error in errors.localRealizacao_cidade">{{ error }}</span>
            </label>
        </div>


        <div class="row">
            <div class="row">
                <taxonomy-checkboxes taxonomy="local_realizacao" entity="agent" terms="termos.local_realizacao"></taxonomy-checkboxes>
            </div>

            <div class="clear"></div>
        </div>
*/?>
  <!--</div>
  <div class="clear"></div>-->
</form>
