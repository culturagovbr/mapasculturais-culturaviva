'use strict';
angular.module('AppAdmin')
        .service('UsuarioSrv', UsuarioSrv)
        ;


UsuarioSrv.$inject = ['$q', '$http', '$rootScope'];

/**
 * Processamento e validação dos dados do usuário
 *
 * @param {type} $q
 * @param {type} $http
 * @param {type} $rootScope
 * @returns {undefined}
 */
function UsuarioSrv($q, $http, $rootScope) {
    var that = this;
    var user = null;
    var uid = null;

    /**
     * Limpa as informações sobre o usuário logado
     *
     * @returns {undefined}
     */
    that.limpar = function () {
        user = null;
        uid = null;
        $rootScope.$broadcast('auth.logout');
    };

    that.obterUID = function () {
        uid = parseInt((document.cookie.match(/(^|;\s+)mapasculturais.uid=([^;]*)/) || 0)[2]);
        if (!uid || isNaN(uid)) {
            uid = null;
        }
        return uid;
    };

    // Inicializa a informação sobre o usuário conectado
    uid = that.obterUID();

    /**
     * Verifica se o usuário está autenticado
     *
     * @returns {Boolean}
     */
    that.estaAutenticado = function () {
        if (user) {
            return true;
        }

        var uid = that.obterUID();
        if (uid) {
            return true;
        } else {
            return false;
        }
    };

    // Controle para evitar requisições desnecessárias
    var promiseRequisicao = false;

    /**
     * Resolve as informações do usuário logado
     *
     * @returns {json.user|JSON@call;parse.user|$q@call;defer.promise}
     */
    that.obterUsuario = function () {
        var defered = $q.defer();
        if (this.estaAutenticado()) {
            if (user) {
                // Já possui as informações do usuario
                setTimeout(function () {
                    defered.resolve(user);
                }, 1);
            } else {
                // busca os dados do usuário logado
                if (promiseRequisicao) {
                    // Já está requisitando os dados do usuário
                    promiseRequisicao.then(defered.resolve, defered.reject);
                } else {
                    promiseRequisicao = defered.promise;
                    $http.get('/admin/user').then(function (response) {
                        user = response.data;
                        defered.resolve(user);
                        promiseRequisicao = null;
                    }, function (cause) {
                        defered.reject(cause);
                        promiseRequisicao = null;
                    });
                }
            }
        }
        return defered.promise;
    };
}