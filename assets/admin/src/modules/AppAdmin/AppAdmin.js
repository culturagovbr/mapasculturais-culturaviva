'use strict';

angular
    .module('AppAdmin', [
        'ng',
        'ngMessages',
        'ngSanitize',
        'ui.router',
        'ui.bootstrap',
        'blockUI',
        'oc.lazyLoad'
    ])
    .config(AppAdminConfig)
    .run(AppAdminRun);

AppAdminConfig.$inject = ['blockUIConfig'];

/**
 * Configuração do App
 *
 * @param {type} blockUIConfig
 * @returns {undefined}
 */
function AppAdminConfig(blockUIConfig) {

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



AppAdminRun.$inject = ['$q', '$rootScope', '$state', '$document', 'UsuarioSrv'];

/**
 * Inicialização do App
 *
 * @param {type} $q
 * @param {type} $rootScope
 * @param {type} $state
 * @param {type} $document
 * @param {AuthJwtStorage} UsuarioSrv
 * @returns {undefined}
 */
function AppAdminRun($q, $rootScope, $state, $document, UsuarioSrv) {

    /*========================================================================================
     * Eventos de rotas
     *----------------------------------------------------------------------------------------*/

    $rootScope.$on('$stateChangeSuccess', function () {
        // Ao mudar de state, rola para o topo da página
        $document[0].body.scrollTop = $document[0].documentElement.scrollTop = 0;
    });

    $rootScope.$on('scrollToTop', function () {
        setTimeout(function () {
            $document[0].body.scrollTop = $document[0].documentElement.scrollTop = 0;
        });
    });

    $rootScope.$on("$stateChangeStart", function (event, toState, toParams, fromState, fromParams) {

        // Limpa a lista de mensagens
        $rootScope.$emit('msgClear');

        // Verifica permissão de acesso à rota
        if (!('data' in toState) || !('ACCESS_LEVEL' in toState.data)) {
            // Importante que todas as rotas tenham a permissão definida
            $rootScope.error = "Nivel de acesso não definido para essa rota";
            event.preventDefault();

        } else if (UsuarioSrv.estaAutenticado()) {
            // Usuário autenticado, obtém os dados do usuário e finamente faz a validação do acesso

            var defered = $q.defer();
            UsuarioSrv.obterUsuario().then(function (usuario) {
                if (window.RBAC.authorize(toState.data.ACCESS_LEVEL, usuario.roles)) {
                    // Permite acesso
                    defered.resolve();
                } else {
                    defered.reject('nao_autorizado');
                    event.preventDefault();

                    $rootScope.error = "Parece que você tentou acessar uma rota que não têm acesso ...";
                    if (fromState.url === '^') {
                        $state.go('page.private.estabelecimento.lista');
                    }
                }
            }, function (cause) {
                defered.reject(cause);
            });

            return defered.promise;

        } else if (!window.RBAC.authorize(toState.data.ACCESS_LEVEL, window.RBAC.ROLE.GUEST)) {
            // Finalmente, verifica se a rota não for publica, não permite acesso
            event.preventDefault();
            $rootScope.error = null;
            $state.go('logout', {}, {reload: true});
        }
    });
    /*========================================================================================*/
}
