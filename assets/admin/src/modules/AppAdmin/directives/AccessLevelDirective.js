'use strict';

angular.module('AppAdmin')
        .directive('accessLevel', AccessLevelDirective);


AccessLevelDirective.$inject = ['UsuarioSrv'];

/**
 * Exibe ou oculta um bloco de conteúdo de acordo com as permissões de acesso e funções do usuário
 *
 * Utilize o sinal "!" para bloquear um nivel de acesso
 *
 * Uso: <div access-level="CERTIFICADORES;!AGENTE_AREA">
 *
 * @param {type} UsuarioSrv
 * @returns {AccessLevelDirective.AccessLevelShowAnonym$0}
 */
function AccessLevelDirective(UsuarioSrv) {
    return {
        link: function ($scope, $element, $attrs) {
            if (!angular.isString($attrs.accessLevel)) {
                return;
            }

            $element.hide();
            UsuarioSrv.obterUsuario().then(function (usuario) {
                var access = $attrs.accessLevel.split(';');
                var allow = false;
                for (var a = 0, l = access.length; a < l; a++) {
                    var level = access[a];
                    var dontAllowMark = level[0] === '!';
                    if (dontAllowMark) {
                        level = level.slice(1);
                        if (!window.RBAC.authorize(window.RBAC.ACCESS_LEVEL[level], usuario.roles)) {
                            allow = false;
                            break;
                        }
                    } else {
                        if (window.RBAC.authorize(window.RBAC.ACCESS_LEVEL[level], usuario.roles)) {
                            allow = true;
                        }
                    }
                }

                if (allow) {
                    $element.show();
                } else {
                    $element.hide();
                }
            }, function (cause) {
                $scope.$emit('msg', 'Erro ao obter os dados do usuário autenticado', null, 'error');
            });
        }
    };
}