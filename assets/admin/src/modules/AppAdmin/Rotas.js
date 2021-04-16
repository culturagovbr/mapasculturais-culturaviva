'use strict';

angular
    .module("AppAdmin")
    .config(AppConfigRoutas);


AppConfigRoutas.$inject = ['$stateProvider', '$urlRouterProvider', '$locationProvider'];

function AppConfigRoutas($stateProvider, $urlRouterProvider, $locationProvider) {

    $locationProvider.html5Mode(false).hashPrefix('');

    /*========================================================================================
     * Rotas
     * ACCESS_LEVEL: Ver assets\admin\modules\base\RBAC.js
     *----------------------------------------------------------------------------------------*/

    // Tela de relatórios é a inicial
    $urlRouterProvider.otherwise('/');

    $stateProvider
        /*----------------------------------------------------------------------------------------*/
        // Template base das páginas
        /*----------------------------------------------------------------------------------------*/
        .state('pagina', {
            abstract: true,
            templateUrl: templateURL('Pagina', 'AppAdmin'),
            data: {
                ACCESS_LEVEL: window.RBAC.ACCESS_LEVEL.AGENTES
            },
            resolve: {
                mdl: resolveModule('TcComponents')
            }
        })
        /*----------------------------------------------------------------------------------------*/
        // Relatórios, tela inicial
        /*----------------------------------------------------------------------------------------*/
        .state('pagina.relatorios', {
            url: '/',
            templateUrl: templateURL('Inicio', 'Relatorios'),
            resolve: {
                mdl: resolveModule('Relatorios')
            }
        })
        /*----------------------------------------------------------------------------------------*/
        // Certificação
        /*----------------------------------------------------------------------------------------*/
        .state('pagina.certificacao', {
            url: '/certificacao',
            template: '<ui-view/>',
            data: {
                ACCESS_LEVEL: window.RBAC.ACCESS_LEVEL.AGENTES
            },
            resolve: {
                mdl: resolveModule('Certificacao')
            }
        })
        .state('pagina.certificacao.lista', {
            url: '/',
            templateUrl: templateURL('AvaliacaoLista', 'Certificacao')
        })
        .state('pagina.certificacao.formulario', {
            url: '/formulario/:id',
            templateUrl: templateURL('AvaliacaoFormulario', 'Certificacao')
        })
        /*----------------------------------------------------------------------------------------*/
        // Configurações
        /*----------------------------------------------------------------------------------------*/
        .state('pagina.configuracao', {
            url: '/configuracao',
            template: '<ui-view/>',
            data: {
                ACCESS_LEVEL: window.RBAC.ACCESS_LEVEL.AGENTE_AREA
            },
            resolve: {
                mdl: resolveModule('Configuracao')
            }
        })
        .state('pagina.configuracao.redistribuir', {
            url: '/redistribuir',
            templateUrl: templateURL('AvaliacaoRedistribuicao', 'Configuracao')
        })
        // Configurações::Criterios
        .state('pagina.configuracao.criterios', {
            url: '/criterios',
            templateUrl: templateURL('Criterios', 'Configuracao')
        })
        // Configurações::Certificadores
        .state('pagina.configuracao.certificador', {
            url: '/certificadores',
            template: '<ui-view/>'
        })
        .state('pagina.configuracao.certificador.lista', {
            url: '/',
            templateUrl: templateURL('CertificadorLista', 'Configuracao')
        })
        .state('pagina.configuracao.certificador.formulario', {
            url: '/formulario/:id',
            templateUrl: templateURL('CertificadorFormulario', 'Configuracao')
        })

        /*----------------------------------------------------------------------------------------*/
        // Logout -Redireciona o usuário para a inicial da aplicação
        /*----------------------------------------------------------------------------------------*/
        .state('logout', {
            url: '/logout',
            template: '<div>logout</div>',
            controller: function () {
                window.location = '/auth/logout';
            },
            data: {
                ACCESS_LEVEL: window.RBAC.ACCESS_LEVEL.PUBLIC
            }
        })
    ;
}


/**
 * Facilitador de criação de endereços para templates
 *
 * @param {type} pagina
 * @param {type} modulo
 * @returns {String}
 */
function templateURL(pagina, modulo) {
    return 'modules/' + modulo + '/templates/' + pagina + '.html';
}


/**
 * Faz a resolução para o módulo solicitado
 *
 * @param {type} modulo
 * @returns {Array}
 */
function resolveModule(modulo) {
    return [
        '$q', '$ocLazyLoad',
        function ($q, $ocLazyLoad) {
            if (window.__BUNDLED__MODULES__) {
                return $ocLazyLoad.load({
                    name: modulo,
                    files: ['/assets/admin/modules/' + modulo + '/' + modulo + '.bundle.min.js']
                });
            } else {
                var deferred = $q.defer();
                setTimeout(function () {
                    $ocLazyLoad.load({
                        name: modulo
                    });
                    deferred.resolve(true);
                });
                return deferred.promise;
            }
        }
    ];
}


