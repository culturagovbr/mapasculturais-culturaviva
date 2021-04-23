<?php
require __DIR__.'/../../assets/php/functions.php';
    $this->bodyProperties['ng-app'] = "culturaviva";
    $this->layout = 'cadastro';
    $this->cadastroTitle = '2. Dados da Entidade ou Coletivo Cultural';
    $this->cadastroText = 'Inclua os dados da Entidade ou Coletivo Cultural responsável pelo Ponto de Cultura';
    $this->cadastroIcon = 'icon-home';
    $this->cadastroPageClass = 'dados-entidade page-base-form';
    //$this->cadastroLinkContinuar = 'pontoMapa';
    $this->cadastroLinkContinuar = 'portifolio';
    $this->cadastroLinkBack = 'responsavel';
?>

<!--Dados da Entidade ou Coletivo Cultural-->
<form name="form_entity" ng-controller="EntityCtrl">
    <?php $this->part('messages'); ?>
    <div class="form">
        <h4>Informações Obrigatórias</h4>

        <div class="row">
            <!--Pertence a alguma rede-->
            <label class="colunm1">
                <span class="destaque">Pertence ou pertenceu a alguma rede?*</span>
                <taxonomy-checkboxes taxonomy="rede_pertencente" entity="agent" terms="termos.rede_pertencente"></taxonomy-checkboxes>
            </label>

            <!--Deseja ser-->
            <label class="colunm1">
                <span class="destaque">Deseja ser: * <i class='hltip' ng-click="infoDesejaSer()" title=''>?</i></span>
                <select name="tipoPonto"
                        ng-change="save_field('tipoPonto')"
                        ng-model="agent.tipoPonto" required>
                    <option value="ponto_entidade">Entidade (ponto com constituição jurídica)</option>
                    <option value="ponto_coletivo">Coletivo (ponto sem constituição jurídica)</option>
                    <option value="pontao">Pontão</option>
                </select>
            </label>
        </div>

        <div class="row">
            <!--CNPJ-->
            <div ng-show="agent.tipoPonto==='pontao' || agent.tipoPonto === 'ponto_entidade'">
                <label class="colunm1">
                    <span class="destaque">CNPJ da Entidade*</span>
                    <input name="cnpj"
                           type="text"
                           ng-change="validaCNPJ()"
                           ng-model="agent.cnpj"
                           ui-mask="99.999.999/9999-99" required>
                    <span ng-class="error" style="color: red" ng-show="form_entity.cnpj.$error.required">Favor Digitar o CNPJ</span>
                </label>
            </div>
        </div>

        <!-- Nome fantasia e Razão Social -->
        <div class="row" ng-show="agent.tipoPonto === 'ponto_entidade' || agent.tipoPonto === 'pontao'">
            <label class="colunm1">
                <span class="destaque">Nome da Razão Social</span>
                <input required name="nomeCompleto"
                       type="text"
                       ng-blur="save_field('nomeCompleto')"
                       ng-model="agent.nomeCompleto">
                <span ng-class="error" style="color: red" ng-show="form_entity.nomeCompleto.$error.required">Favor digitar o nome da razão social</span>
            </label>

            <label class="colunm1">
                <span class="destaque">Nome Fantasia* <i class='hltip' title='Nome da entidade, tal como se reconhece e é reconhecida junto à comunidade'>?</i></span>
                </span>
                <div ng-messages="agent.name.$error" style="color:maroon" role="alert">
                    <div ng-message="required">You did not enter a field</div>
                    <div ng-message="minlength">Your field is too short</div>
                    <div ng-message="maxlength">Your field is too long</div>
                </div>
                <input required type="text" name="nomeFantasia" ng-blur="save_field('name')" ng-model="agent.name" >
                <span ng-class="error" style="color: red" ng-show="form_entity.nomeFantasia.$error.required">Favor digitar o nome fantasia</span>
            </label>
        </div>

        <div class="row">
            <div ng-show="agent.tipoPonto==='ponto_coletivo'">
                <div class="row">
                    <label>
                    <span class="destaque">Nome do Coletivo Cultural* <i class='hltip' title='Nome dado ao grupo que compõe o coletivo cultural'>?</i>
                    </span>
                        <input required name="name" type="text" ng-blur="save_field('name')" ng-model="agent.name">
                    </label>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <!-- Fim Nome fantasia e Razão Social -->

        <!--Nome ponto-->
        <div class="row">
            <label>
                <span class="destaque">
                    Nome do ponto*
                </span>
                <input  required type="text"
                        name="nomePonto"
                        ng-blur="save_field('nomePonto')"
                        ng-model="agent.nomePonto">
            </label>
        </div>
        <!--Fim Nome ponto-->

        <!-- Email institucional -->
        <div class="row">
            <label class="colunm-full">
                <span class="destaque">
                    E-mail institucional
                    <label ng-if="agent.tipoPonto == 'ponto_entidade'"> da Entidade </label>
                    <label ng-if="agent.tipoPonto == 'ponto_coletivo'"> do Coletivo </label>
                    <label ng-if="agent.tipoPonto == 'pontao'"> do Pontão </label> *

                    <i class='hltip' title='Este e-mail será utilizado pela Secretaria para comunicação, chamada de atualização, realização de pesquisa e quaisquer outros contatos que se fizerem necessários.'>?</i>
                </span>
                <input name="emailPrivado" type="email" ng-blur="save_field('emailPrivado')" ng-model="agent.emailPrivado" />
            </label>
        </div>
        <div class="clear"></div>
        <!-- Fim Email institucional -->

        <!--Telefone entidade-->
        <div class="row">
            <label class="colunm1" style="width:300px;">
                <span class="destaque">
                    Telefone institucional
                    <label ng-if="agent.tipoPonto == 'ponto_entidade'"> da Entidade </label>
                    <label ng-if="agent.tipoPonto == 'ponto_coletivo'"> do Coletivo </label>
                    <label ng-if="agent.tipoPonto == 'pontao'"> do Pontão </label> *
                </span>
                <input name="telefone1" type="text" ng-blur="save_field('telefone1')" ng-model="agent.telefone1" ui-mask="(99) ?99999-9999">
            </label>
        </div>
        <!-- Fim Telefone entidade-->

        <!--Endereço-->
        <div>
            <div class="row">
                <label class="colunm1">
                    <span class="destaque">Endereço {{agent.tipoPonto == 'ponto_coletivo' ? 'do Coletivo' : 'da Entidade'}}* <i class='hltip' title='Endereço atrelado ao CNPJ (não precisa ser o mesmo endereço do Ponto de Cultura)'>?</i></span>
                </label>
            </div>
            <div class="clear"></div>
            <div class="row">
                <label class="colunm2">
                    <span class="destaque">País*</span>
                    <select required name="pais" ng-blur="save_field('pais')" ng-model="agent.pais">
                        <?php echo get_countries_html();?>
                    </select>
                </label>

                <label class="colunm2" ng-if="agent.pais === 'Brasil'">
                    <span class="destaque">Estado*</span>
                    <select required name="En_Estado" ng-blur="save_field('En_Estado')" ng-model="agent.En_Estado">
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
                    <input required name="En_Estado" type="text" ng-blur="save_field('En_Estado')" ng-model="agent.En_Estado"/>
                </label>

                <label class="colunm2" ng-class="{busy: cidadecoder.busy}">
                    <span class="destaque">{{agent.pais == 'Brasil' ? 'Cidade*' : 'Cidade'}}</span>
                    <input required name="En_Municipio" type="text" ng-blur="save_field('En_Municipio'); cidadecoder.code(agent.En_Municipio, agent.pais)"
                           ng-model="agent.En_Municipio"/>
                    <span class="error" ng-repeat="error in errors.cidade">{{ error }}</span>
                </label>

                <label class="colunm2" ng-class="{'busy': cepcoder.busy}">
                    <span class="destaque">{{agent.pais == 'Brasil' ? 'CEP*' : 'CEP'}}</span>
                    <input required type="text"
                           name="cep"
                           ng-blur="save_field('cep'); cepcoder.code(agent.cep, 'cep')"
                           ng-model="agent.cep"
                           ui-mask="99999-999"
                           ng-if="agent.pais === 'Brasil'">
                    <input required type="text"
                           name="cep"
                           ng-blur="save_field('cep')"
                           ng-model="agent.cep"
                           ng-if="agent.pais !== 'Brasil'">
                </label>
            </div>
            <div class="clear"></div>
            <div class="row">
                <label class="colunm2">
                    <span class="destaque">{{agent.pais == 'Brasil' ? 'Rua*' : 'Rua'}}</span>
                    <input required name="En_Nome_Logradouro" type="text" ng-blur="save_field('En_Nome_Logradouro'); endcoder.code();" ng-model="agent.En_Nome_Logradouro"/>
                </label>
                <label class="colunm2">
                    <span class="destaque">{{agent.pais == 'Brasil' ? 'Número*' : 'Número'}}</span>
                    <input required name="En_Num" type="text" ng-blur="save_field('En_Num')" ng-model="agent.En_Num"/>
                </label>

                <label class="colunm2">
                    <span class="destaque">{{agent.pais == 'Brasil' ? 'Bairro*' : 'Bairro'}}</span>
                    <input required name="En_Bairro" type="text" ng-blur="save_field('En_Bairro'); endcoder.code();" ng-model="agent.En_Bairro"/>
                </label>

                <label class="colunm2">
                    <span class="destaque">Complemento</span>
                    <input type="text" ng-blur="save_field('En_Complemento')" ng-model="agent.En_Complemento"/>
                </label>
            </div>
        </div>
        <!--Fim Endereço-->

        <!-- Endereço ponto pontão -->
        <div class="row">
            <label>
                <span class="destaque">O endereço da unidade é o mesmo do ponto ou pontão?*</span>
                <select name="mesmoEndereco"
                        ng-change="save_field('mesmoEndereco')"
                        ng-model="agent.mesmoEndereco" required>
                    <option value="sim">Sim</option>
                    <option value="nao">Não</option>
                </select>
            </label>
        </div>

        <div ng-if="agent.mesmoEndereco === 'nao'">
            <div>
                <div class="row">
                    <label class="colunm1">
                        <span class="destaque"> Endereço do ponto ou pontão: </span>
                    </label>
                    <hr>
                </div>
                <div class="row">
                    <label class="colunm2">
                        <span class="destaque">País*</span>
                        <select required name="paisPontaPontao" ng-blur="save_field('paisPontaPontao')" ng-model="agent.paisPontaPontao">
                            <?php echo get_countries_html();?>
                        </select>
                    </label>

                    <label class="colunm2" ng-if="agent.paisPontaPontao === 'Brasil'">
                        <span class="destaque">Estado*</span>
                        <select required name="En_EstadoPontaPontao" ng-blur="save_field('En_EstadoPontaPontao')" ng-model="agent.En_EstadoPontaPontao">
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

                    <label class="colunm2" ng-if="agent.paisPontaPontao !== 'Brasil'">
                        <span class="destaque">Estado</span>
                        <input required name="En_EstadoPontaPontao" type="text" ng-blur="save_field('En_EstadoPontaPontao')" ng-model="agent.En_EstadoPontaPontao"/>
                    </label>

                    <label class="colunm2">
                        <span class="destaque">{{agent.paisPontaPontao == 'Brasil' ? 'Cidade*' : 'Cidade'}}</span>
                        <input required name="En_MunicipioPontaPontao" type="text" ng-blur="save_field('En_MunicipioPontaPontao')" ng-model="agent.En_MunicipioPontaPontao"/>
                    </label>

                    <label class="colunm2">
                        <span class="destaque">{{agent.paisPontaPontao == 'Brasil' ? 'CEP*' : 'CEP'}}</span>
                        <input required type="text"
                               name="cepPontaPontao"
                               ng-blur="save_field('cepPontaPontao'); cepcoder.code(agent.cepPontaPontao,'pontoPontao')"
                               ng-model="agent.cepPontaPontao"
                               ui-mask="99999-999"
                               ng-if="agent.paisPontaPontao === 'Brasil'">
                        <input required type="text"
                               name="cepPontaPontao"
                               ng-blur="save_field('cepPontaPontao')"
                               ng-model="agent.cepPontaPontao"
                               ng-if="agent.paisPontaPontao !== 'Brasil'">
                    </label>

                </div>
                <div class="clear"></div>
                <div class="row">
                    <label class="colunm2">
                        <span class="destaque">{{agent.paisPontaPontao == 'Brasil' ? 'Rua*' : 'Rua'}}</span>
                        <input required name="En_Nome_LogradouroPontaPontao" type="text" ng-blur="save_field('En_Nome_LogradouroPontaPontao')" ng-model="agent.En_Nome_LogradouroPontaPontao"/>
                    </label>
                    <label class="colunm2">
                        <span class="destaque">{{agent.paisPontaPontao == 'Brasil' ? 'Número*' : 'Número'}}</span>
                        <input required name="En_NumPontaPontao" type="text" ng-blur="save_field('En_NumPontaPontao')" ng-model="agent.En_NumPontaPontao"/>
                    </label>

                    <label class="colunm2">
                        <span class="destaque">{{agent.paisPontaPontao == 'Brasil' ? 'Bairro*' : 'Bairro'}}</span>
                        <input required name="En_BairroPontaPontao" type="text" ng-blur="save_field('En_BairroPontaPontao')" ng-model="agent.En_BairroPontaPontao"/>
                    </label>

                    <label class="colunm2">
                        <span class="destaque">Complemento</span>
                        <input type="text" ng-blur="save_field('En_ComplementoPontaPontao')" ng-model="agent.En_ComplementoPontaPontao"/>
                    </label>
                </div>
            </div>
        </div>
        <!-- Endereço ponto pontão -->

        <!-- Indicação no mapa -->
        <label>
            <span class="destaque">Indique a posição no mapa</span>
        </label>
        <div class="form form-mapa">
            <style type="text/css">.leaflet-canvas { min-height: 400px; }</style>
            <leaflet markers="markers"></leaflet>
        </div>
        <!-- Fim Indicação no mapa -->

        <!-- Informações do responsavel -->
        <div class="row">
            <label class="colunm1">
                <span class="destaque">Nome do Responsável {{agent.tipoPonto == 'ponto_coletivo' ? 'pelo Coletivo' : 'pela Entidade'}}* <i class='hltip' title='Pessoa que representa o Ponto de Cultura'>?</i></span>
                <input name="responsavel_nome" type="text" ng-blur="save_field('responsavel_nome')" ng-model="agent.responsavel_nome"/>
            </label>

            <label class="colunm05">
                <span class="destaque">CPF do Responsável*</span>
                <input type="text" ui-mask="999.999.999-99" name="responsavel_cpf" type="text" ng-blur="save_field('responsavel_cpf')" ng-model="agent.responsavel_cpf"/>
            </label>
        </div>
        <div class="clear"></div>
        <div class="row">
            <label class="colunm1">
                <span class="destaque">E-mail do Responsável* </span>
                <input name="responsavel_email" type="email" ng-blur="save_field('responsavel_email')" ng-model="agent.responsavel_email" />
            </label>

            <label class="colunm05">
                <span class="destaque">Telefone do Responsável*</span>
                <input name="responsavel_telefone" type="text" ng-blur="save_field('responsavel_telefone')" ng-model="agent.responsavel_telefone" ui-mask="(99) ?99999-9999"/>
            </label>
        </div>
        <!-- Fim Informações do responsavel -->
        <div class="clear"></div>
    </div>
    <div class="form form-opcional">
        <h4>Informações Opcionais</h4>
        <div class="row">
            <!--Redes sociais-->
            <div class="row">
                <span class="destaque redessociais">Seu perfil nas redes sociais: <i class='hltip' title='Queremos saber seu perfil nas redes sociais para podermos conectá-l@ com nossas atualizações e novidades.'>?</i></span>
                <label class="colunm-redes facebook">
                    <span class="destaque"><img src="<?php $this->asset('img/facebook.png') ?>"> Seu perfil no Facebook</span>
                    <input type="text" ng-blur="save_field('facebook')" ng-model="agent.facebook" placeholder="http://"/>
                </label>

                <label class="colunm-redes twitter">
                    <span class="destaque"><img src="<?php $this->asset('img/twitter.png') ?>"> Seu perfil no Twitter</span>
                    <input type="text" ng-blur="save_field('twitter')" ng-model="agent.twitter" placeholder="http://"/>
                </label>

                <label class="colunm-redes googleplus">
                    <span class="destaque"><img src="<?php $this->asset('img/googlePlus.ico') ?>"> Seu perfil no Google+</span>
                    <input type="text" ng-blur="save_field('googleplus')" ng-model="agent.googleplus" placeholder="http://"/>
                </label>

                <label class="colunm-redes telegram">
                    <span class="destaque"><img src="<?php $this->asset('img/telegram.ico') ?>"> Seu usuário no Telegram</span>
                    <input type="text" ng-blur="save_field('telegram')" ng-model="agent.telegram" placeholder="@SeuNome"/>
                </label>

                <label class="colunm-redes whatsapp">
                    <span class="destaque"><img src="<?php $this->asset('img/whatsapp.png') ?>"> Seu número do WhatsApp</span>
                    <input type="text" ng-blur="save_field('whatsapp')" ng-model="agent.whatsapp" placeholder="(11) _____-_____ "/>
                </label>

                <label class="colunm-redes culturadigital">
                    <span class="destaque"><img src="<?php $this->asset('img/CulturaDigital_favi.png') ?>"> Seu perfil no CulturaDigital.br</span>
                    <input type="text" ng-blur="save_field('culturadigital')" ng-model="agent.culturadigital" placeholder="http://"/>
                </label>

                <label class="colunm-redes diaspora">
                    <span class="destaque"><img src="<?php $this->asset('img/icon_diaspora.png') ?>">Perfil na Diáspora:</span>
                    <input type="text" ng-blur="save_field('diaspora')" ng-model="agent.diaspora" placeholder="http://"/>
                </label>

                <label class="colunm-redes instagram">
                    <span class="destaque"><img src="<?php $this->asset('img/instagram.png') ?>"> Seu perfil no Instagram.com</span>
                    <input type="text" ng-blur="save_field('instagram')" ng-model="agent.instagram" placeholder="http://"/>
                </label>

                <label class="colunm-redes flick">
                    <span class="destaque"><img src="<?php $this->asset('img/icon_flicker.png') ?>"> Página no Flickr</span>
                    <input type="text" ng-blur="save_field('flickr')" ng-model="agent.flickr" placeholder="http://"/>
                </label>

                <label class="colunm-redes youtube">
                    <span class="destaque"><img src="<?php $this->asset('img/icon_youtube.png') ?>"> Perfil no Youtube:</span>
                    <input type="text" ng-blur="save_field('youtube')" ng-model="agent.youtube" placeholder="http://"/>
                </label>
            </div>
            <div class="clear"></div>
            <!--Fim Redes sociais-->
        </div>
    </div>
</form>
