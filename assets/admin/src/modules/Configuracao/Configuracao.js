'use strict';

angular
    .module('Configuracao', [
        'blockUI',
        'oc.lazyLoad'
        ])
    .config(ConfiguracaoConfig, AppConfiguracaoConfig);


ConfiguracaoConfig.$inject = ['blockUIConfig'];
AppConfiguracaoConfig.$inject = ['blockUIConfig'];

function ConfiguracaoConfig(blockUIConfig) {
    debugger;
    // Faz o blockUI ignorar algumas requisições
    blockUIConfig.requestFilter = function (config) {
        // Conflito com o typeahead http://stackoverflow.com/a/29606685
        if (config.url.indexOf('/certificador/buscarAgente' === 0)) {
            return false;
        }
    };
}

function AppConfiguracaoConfig(blockUIConfig) {

    /*========================================================================================
     * BlockUI
     *----------------------------------------------------------------------------------------*/
    blockUIConfig.message = 'Carregando...';
    blockUIConfig.delay = 100;
    blockUIConfig.autoInjectBodyBlock = false;
    blockUIConfig.template = [
        '<div class="block-ui-overlay"></div>',
        '<div class="block-ui-message-container" aria-live="assertive" aria-atomic="true">',
        '    <div class="block-ui-message" ng-class="$_blockUiMessageClass">',
        '        <img src="/assets/img/cultura-viva-share.png" class="heartbeat-loading heartbeat" />',
        '        {{ state.message }}',
        '    </div>',
        '</div>'
    ].join('');
    /*========================================================================================*/
}
