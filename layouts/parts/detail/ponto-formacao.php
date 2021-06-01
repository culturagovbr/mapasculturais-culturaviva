<div>
    <div class="form">
<!--        <h4>Conhecimento em Rede</h4>-->
        <div class="row">
            <h4> Formadores</h4>
            <label class="colunm1">
                <span class="destaque">Nome Completo:</span>
                <input disabled="true" type="text" ng-model="ponto.formador1_nome">
            </label>
            <label class="colunm1">
                <span class="destaque">Email: </span>
                <input disabled="true" type="email" ng-model="ponto.formador1_email">
            </label>
            <?php /*
            <label class="colunm2">
                <span class="destaque">Telefone: </span>
                <input disabled="true" type="text" ng-model="ponto.formador1_telefone" ui-mask="(99) ?99999 9999">
            </label>
            */ ?>
            <label class="colunm2">
                <span class="destaque">Áreas de atuação (oficinas/atividades ministradas):</span>
                <input disabled="true" type="text" ng-model="ponto.formador1_areaAtuacao">
            </label>
            <label class="colunm-full">
                <span class="destaque">Descrição/Mini-bio:  </span>
                <textarea disabled="true" ng-model="ponto.formador1_bio"></textarea>
            </label>

            <?php /*

            <div class="colunm-full">
                <span class="destaque redessociais">Seu perfil nas redes sociais: <i class='hltip' title='Caso possua um perfil nas redes sociais abaixo coloque o link do perfil'>?</i></span>
            </div>
            <label class="colunm-redes facebook">
                <span><i class="icon icon-facebook-squared"></i> Seu perfil no Facebook</span>
                <input disabled="true" type="text" ng-model="ponto.formador1_facebook" placeholder="http://"/>
            </label>

            <label class="colunm-redes twitter">
                <span><i class="icon icon-twitter"></i> Seu perfil no Twitter</span>
                <input disabled="true" type="text" ng-model="ponto.formador1_twitter" placeholder="http://"/>
            </label>

            <label class="colunm-redes googleplus">
                <span><i class="icon icon-gplus"></i> Seu perfil no Google+</span>
                <input disabled="true" type="text" ng-model="ponto.formador1_google" placeholder="http://"/>
            </label>

            <h4>Espaços de Aprendizagem</h4>

            <label class="colunm1">
                <span class="destaque">Áreas de atuação (oficinas/atividades ministradas):</span>
                <input disabled="true" type="text" ng-model="ponto.espacoAprendizagem1_atuacao">
            </label>
            <div class="colunm-full">
                <span class="destaque">Que tipo de espaço/plataforma quer registrar?</span>
            </div>
            <label class="colunm-full">
                <input disabled="true" type="radio"
                       name="tipo-espaco"
                       value="temporario"
                       ng-model="ponto.espacoAprendizagem1_tipo"> Temporário (Por exemplo: Festival, seminário, encontro, evento digital ou presencial, entre outras)
            </label>
            <label class="colunm-full">
                <input disabled="true" type="radio"
                       name="tipo-espaco"
                       value="permanente"
                       ng-model="ponto.espacoAprendizagem1_tipo"> Permanente (Por exemplo:  Sede do Ponto de Cultura, espaços culturais onde podem ser realizadas atividades e processos formativos)
            </label>
            <label class="colunm1">
                <span class="destaque">Descreva o espaço (Cite atividades realizadas, público alcançado, periodicidade de ações):</span>
                <textarea disabled="true" ng-model="ponto.espacoAprendizagem1_desc"></textarea>
            </label>

            */ ?>

            <h4> Metodologias</h4>

            <label class="colunm-full">
                <span class="destaque">Nome da metodologia: </span>
                <input disabled="true" type="text" ng-model="ponto.metodologia1_nome">
            </label>
            <label class="colunm-full">
                <span class="destaque">Descrição:  </span>
                <textarea disabled="true" type="text" ng-model="ponto.metodologia1_desc"></textarea>
            </label>
            <label class="colunm-full">
                <span class="destaque">Necessidades técnicas:  </span>
                <textarea disabled="true" type="text" ng-model="ponto.metodologia1_necessidades"></textarea>
            </label>
            <label class="colunm1">
                <span class="destaque">Capacidade de público:  </span>
                 <input disabled="true" type="text" ng-model="ponto.metodologia1_capacidade">
            </label>
            <label class="colunm1">
                <span class="destaque">Carga horária:  </span>
                 <input disabled="true" type="text" ng-model="ponto.metodologia1_cargaHoraria">
            </label>
            <label class="colunm-full">
                <span class="destaque">Possui certificação?</span>

            </label>
            <div class="colunm-50">
                <label class="label-radio">
                    <input disabled="true" type="radio"
                           name="certificacao"
                           ng-value="1" ng-model="ponto.metodologia1_certificacao"> Sim</label>
                <label class="label-radio">
                    <input disabled="true" type="radio"
                           name="certificacao"
                           ng-value="0" ng-model="ponto.metodologia1_certificacao"> Não </label>
            </div>
            <div class="colunm-full">
                <span class="destaque">Tipo de Metodologia</span>
                <span style="height:auto" ng-repeat="termo in ponto.terms.metodologias_areas"><b>{{termo}}</b></span>
                <span ng-if="!ponto.terms.metodologias_areas.length"><b>Não informado</b></span>
            </div>
        </div>
    </div>
</div>
