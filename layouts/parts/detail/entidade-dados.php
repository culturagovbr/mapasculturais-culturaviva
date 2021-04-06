<div>
    <div class="form">
        <div class="row">
            <label class="colunm1" ng-show="entidade.tipoPonto">
                <span class="destaque">Pertence ou pertenceu a alguma rede?*</span>
                <span><b>{{entidade.rede_pertencente}}</b></span>
                <span ng-if="!entidade.rede_pertencente"><b>Não informado</b></span>
            </label>
            <label class="colunm1">
                <span class="destaque">Deseja ser*</span>
                <span><b>{{entidade.tipoPonto}}</b></span>
                <span ng-if="!entidade.tipoPonto"><b>Não informado</b></span>
            </label>
        </div>

        <div class="row">
            <label class="colunm1">
                <span class="destaque">Nome do Coletivo Cultural</span>
                <span><b>{{entidade.name}}</b></span>
                <span ng-if="!entidade.name"><b>Não informado</b></span>
            </label>
            <label class="colunm1">
                <span class="destaque">Nome do Ponto/Pontão de Cultura*</span>
                <span><b>{{entidade.nomePonto}}</b></span>
                <span ng-if="!entidade.nomePonto"><b>Não informado</b></span>
            </label>
        </div>

        <div class="row" ng-if="ponto.shortDescription">
            <label class="colunm-full">
                <span class="destaque">Breve descrição (400 caracteres) do ponto de cultura*</span>
                <p><b>{{ponto.shortDescription}}</b></p>
            </label>
        </div>

        <div class="row">
            <label class="colunm1">
                <span class="destaque">E-mail institucional da Entidade/Coletivo*</span>
                <span><b>{{entidade.emailPrivado}}</b></span>
                <span ng-if="!entidade.emailPrivado"><b>Não informado</b></span>
            </label>
            <label class="colunm1">
                <span class="destaque">Telefone institucional da Entidade/Coletivo*</span>
                <span><b>{{entidade.telefone1}}</b></span>
                <span ng-if="!entidade.telefone1"><b>Não informado</b></span>
            </label>
        </div>

        <div class="clear"></div>

        <div class="row">
            <label class="colunm-full">
                <span class="destaque">Endereço da Entidade/Coletivo*</span>
            </label>

            <label class="colunm2">
                <span>País*</span>
                <span><b>{{entidade.pais}}</b></span>
                <span ng-if="!entidade.pais"><b>Não informado</b></span>
            </label>
            <label class="colunm2" ng-show="entidade.pais==='Brasil'">
                <span>Estado</span>
                <span><b>{{entidade.En_Estado}}</b></span>
                <span ng-if="!entidade.En_Estado"><b>Não informado</b></span>
            </label>
            <label class="colunm2">
                <span>Cidade</span>
                <span><b>{{entidade.En_Municipio}}</b></span>
                <span ng-if="!entidade.En_Municipio"><b>Não informado</b></span>
            </label>
            <label class="colunm2">
                <span>CEP</span>
                <span><b>{{entidade.cep}}</b></span>
                <span ng-if="!entidade.cep"><b>Não informado</b></span>
            </label>

            <div class="row">
                <label class="colunm2">
                    <span>Rua</span>
                    <span><b>{{entidade.En_Nome_Logradouro}}</b></span>
                    <span ng-if="!entidade.En_Nome_Logradouro"><b>Não informado</b></span>
                </label>
                <label class="colunm2">
                    <span>Número</span>
                    <span><b>{{entidade.En_Num}}</b></span>
                    <span ng-if="!entidade.En_Num"><b>Não informado</b></span>
                </label>
                <label class="colunm2">
                    <span>Bairro</span>
                    <span><b>{{entidade.En_Bairro}}</b></span>
                    <span ng-if="!entidade.En_Bairro"><b>Não informado</b></span>
                </label>
                <label class="colunm2">
                    <span>Complemento</span>
                    <span><b>{{entidade.En_Complemento}}</b></span>
                    <span ng-if="!entidade.En_Complemento"><b>Não informado</b></span>
                </label>
            </div>
        </div>

        <div ng-show="entidade.tipoOrganizacao==='coletivo'">
            <div class="row">
                <label class="colunm-50">
                    <span class="destaque">Nome do Coletivo Cultural*</span>
                    <span><b>{{entidade.name}}</b></span>
                    <span ng-if="!entidade.name"><b>Não informado</b></span>
                </label>
            </div>
            <div class="clear"></div>
        </div>

        <div class="row">
            <label for="" class="column-full">
                <span class="destaque">O endereço da unidade é o mesmo do ponto ou pontão?</span>
                <span><b>{{entidade.mesmoEndereco}}</b></span>
                <span ng-if="!entidade.mesmoEndereco"><b>Não informado</b></span>
            </label>
        </div>

        <div ng-show="entidade.tipoOrganizacao">
            <div ng-show="entidade.tipoOrganizacao==='entidade'">
                <div>
                    <label class="colunm-50">
                        <span class="destaque"><b>CNPJ da Entidade*</b></span>
                        <span><b>{{entidade.cnpj}}</b></span>
                    </label>

                    <br>

                    <label class="colunm-50">
                        <span class="destaque">Nome da Razão Social da Entidade*</span>
                        <span><b>{{entidade.nomeCompleto}}</b></span>
                        <span ng-if="!entidade.nomeCompleto"><b>Não informado</b></span>
                    </label>
                    
                </div>

                <div class="clear"></div>
                <div class="row">
                    <label class="colunm-50">
                        <span class="destaque">Nome do Representante Legal*</span>
                        <span><b>{{entidade.representanteLegal}}</b></span>
                        <span ng-if="!entidade.representanteLegal"><b>Não informado</b></span>
                    </label>

                    <label class="colunm-50">
                        <span class="destaque">Nome Fantasia*</span>
                        <span><b>{{entidade.name}}</b></span>
                        <span ng-if="!entidade.name"><b>Não informado</b></span>
                    </label>
                </div>
                <div class="clear"></div>
                <?php /*
                <div class="row">
                    <label class="colunm-50">
                        <span class="destaque">Tipo de Certificação* <i>?</i></span>
                        <select name="tipoCertificacao"
                                ng-change="save_field('tipoCertificacao')"
                                ng-model="entidade.tipoCertificacao">
                            <option value="ponto_coletivo">Ponto de Cultura - Grupo ou Coletivo</option>
                            <option value="ponto_entidade">Ponto de Cultura - Entidade</option>
                            <option value="pontao_entidade">Pontão de Cultura - Entidade</option>
                        </select>
                    </label>
                </div>
                <div class="clear"></div>
                */ ?>
            </div>
        </div>

        <hr>

        <div class="row">
            <label class="colunm1">
                <span class="destaque">Nome do Responsável pela Entidade/Coletivo*</span>
                <span><b>{{entidade.responsavel_nome}}</b></span>
                <span ng-if="!entidade.responsavel_nome"><b>Não informado</b></span>
            </label>
            <label class="colunm1">
                <span class="destaque">CPF do Responsável*</span>
                <span><b>{{entidade.responsavel_cpf}}</b></span>
                <span ng-if="!entidade.responsavel_nome"><b>Não informado</b></span>
            </label>
        </div>
        <div class="clear"></div>
        <div class="row">
            <label class="colunm1">
                <span class="destaque">E-mail do Responsável*</span>
                <span><b>{{entidade.responsavel_email}}</b></span>
                <span ng-if="!entidade.responsavel_email"><b>Não informado</b></span>
            </label>

            <label class="colunm2">
                <span class="destaque">Telefone do Responsável*</span>
                <span><b>{{entidade.responsavel_telefone}}</b></span>
                <span ng-if="!entidade.responsavel_telefone"><b>Não informado</b></span>
            </label>
        </div>

        <div class="clear"></div>

        <div class="row">
            <div class="row">
                <label class="colunm-full">
                    <span class="destaque redessociais">Perfil nas redes sociais:</span>
                </label>
                <label class="colunm-redes facebook">
                    <span><i class="icon icon-facebook-squared"></i> Perfil no Facebook</span>
                    <span><b>{{entidade.facebook}}</b></span>
                    <span ng-if="!entidade.facebook"><b>Não informado</b></span>
                </label>

                <label class="colunm-redes twitter">
                    <span><i class="icon icon-twitter"></i> Perfil no Twitter</span>
                    <span><b>{{entidade.twitter}}</b></span>
                    <span ng-if="!entidade.twitter"><b>Não informado</b></span>
                </label>

                <label class="colunm-redes googleplus">
                    <span><i class="icon icon-gplus"></i> Perfil no Google+</span>
                    <span><b>{{entidade.googleplus}}</b></span>
                    <span ng-if="!entidade.googleplus"><b>Não informado</b></span>
                </label>
            </div>

            <div class="row">
                <label class="colunm-redes telegram">
                    <span><i class="icon icon-telegram"></i> Usuário no Telegram</span>
                    <span><b>{{entidade.telegram}}</b></span>
                    <span ng-if="!entidade.telegram"><b>Não informado</b></span>
                </label>
                <label class="colunm-redes whatsapp">
                    <span><i class="icon icon-whatsapp"></i> Número do WhatsApp</span>
                    <span><b>{{entidade.whatsapp}}</b></span>
                    <span ng-if="!entidade.whatsapp"><b>Não informado</b></span>
                </label>
                <label class="colunm-redes culturadigital">
                    <span><i class="icon icon-culturadigital"></i> Perfil no CulturaDigital.br</span>
                    <span><b>{{entidade.culturadigital}}</b></span>
                    <span ng-if="!entidade.culturadigital"><b>Não informado</b></span>
                </label>
            </div>

            <div class="row">
                <label class="colunm-redes diaspora">
                    <span><i class="icon icon-diaspora"></i> Perfil no Diasporabr.com.br</span>
                    <span><b>{{entidade.diaspora}}</b></span>
                    <span ng-if="!entidade.diaspora"><b>Não informado</b></span>
                </label>
                <label class="colunm-redes instagram">
                    <span><i class="icon icon-instagram"></i> Perfil no Instagram.com</span>
                    <span><b>{{entidade.instagram}}</b></span>
                    <span ng-if="!entidade.instagram"><b>Não informado</b></span>
                </label>
            </div>
        </div>
    </div>
</div>