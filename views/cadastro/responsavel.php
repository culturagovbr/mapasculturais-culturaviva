<?php
    $this->bodyProperties['ng-app'] = "culturaviva";
    $this->layout = 'cadastro';
    $this->cadastroTitle = '1. Identificação do Responsável pelo Cadastro';
    $this->cadastroText = 'Precisamos saber quem é você e pegar seus contatos! Afinal, comunicação é um requisito vital para que nossa rede se mantenha viva!';
    $this->cadastroIcon = 'icon-user';
    $this->cadastroPageClass = 'responsavel page-base-form';
    $this->cadastroLinkContinuar = 'entidadeDados';
    $this->cadastroLinkBack = 'index';
?>

<!-- Informações do Responsável -->
<form name="form_responsavel" ng-controller="ResponsibleCtrl">
    <?php $this->part('messages'); ?>
    <div class="form">
        <h4>Informações Obrigatórias</h4>
        <div class="row">
            <label class="colunm1">
                <span class="destaque">Nome completo*</span>
                <input type="text" name="nomeCompleto" ng-blur="save_field('nomeCompleto')" ng-model="agent.nomeCompleto" required >
                <span ng-class="error" style="color: red" ng-show="form_responsavel.nomeCompleto.$error.required">Nome Obrigatório</span>
            </label>

            <label class="colunm2">
                <span class="destaque">CPF*</span>
                <input type="text"
                       name="cpf"
                       ng-blur="save_field('cpf')"
                       ng-model="agent.cpf"
                       ui-mask="999.999.999-99" required>
                <span ng-class="error" style="color: red" ng-show="form_responsavel.cpf.$error.required">CPF Obrigatório</span>
            </label>
        </div>
        <div class="clear"></div>
        <div class="row">
            <label class="colunm1">
                <span class="destaque">E-mail Pessoal*</span>
                <input type="email" name="emailPrivado" ng-blur="save_field('emailPrivado')" ng-model="agent.emailPrivado" required>
                <span ng-class="error" style="color: red" ng-show="form_responsavel.emailPrivado.$error.required">E-mail Obrigatório</span>
            </label>

            <label class="colunm2">
                <span class="destaque">Telefone Pessoal (com DDD)*</span>
                <input type="text" name="telefone1" ng-blur="save_field('telefone1')" ng-model="agent.telefone1" ui-mask="(99) ?99999 9999" required>
                <span ng-class="error" style="color: red" ng-show="form_responsavel.telefone1.$error.required">Telefone Obrigatório</span>
            </label>
        </div>
        <div class="clear"></div>
        <div class="row">
            <label class="colunm1">
                <span class="destaque">Qual sua relação com o Ponto/Pontão de Cultura?* <i class='hltip' title='Você não precisa necessariamente ser o responsável legal para entrar na Rede Cultura Viva, descreva o que você faz no Ponto de Cultura. Ex. colaborador; parceiro; funcionário; coordenador de comunicação; etc'>?</i></span>
                <select name="relacaoPonto" ng-blur="save_field('relacaoPonto')" ng-model="agent.relacaoPonto" required>
                    <option value="responsavel">Sou o responsável pelo Ponto/Pontão de Cultura</option>
                    <option value="funcionario">Trabalho no Ponto/Pontão de Cultura</option>
                    <option value="parceiro">Sou parceiro do Ponto/Pontão e estou ajudando a cadastrar</option>
                </select>
            </label>
        </div>
        <div class="clear"></div>
    </div>
    <div class="form form-opcional">
        <h4>Informações Opcionais</h4>
        <div class="row">
            <label class="nome_chamado">
                <span class="destaque">Qual nome você gostaria de ser chamado <i class='hltip' title='Utilize este espaço para nos informar se você possui um nome social, nome artístico ou nome pelo qual é conhecido em sua comunidade'>?</i></span>
                <input type="text" ng-blur="save_field('name')" ng-model="agent.name"/>
            </label>
            <label class="colunm2">
                <span class="destaque">Outro Telefone (com DDD)</span>
                <input type="text" name="telefone2" ng-blur="save_field('telefone2')" ng-model="agent.telefone2" ui-mask="(99) ?99999 9999">
            </label>
            <div class="clear"></div>
        </div>
    </div>
</form>
