'use strict';

angular
    .module('Certificacao', [
        'ng',
        'ngMessages',
        'ngSanitize',
        'ui.router',
        'ui.bootstrap',
        'blockUI',
        'oc.lazyLoad'
    ])
    .config(AppACertificacaoConfig);


AppACertificacaoConfig.$inject = ['blockUIConfig'];

function AppACertificacaoConfig(blockUIConfig) {

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
