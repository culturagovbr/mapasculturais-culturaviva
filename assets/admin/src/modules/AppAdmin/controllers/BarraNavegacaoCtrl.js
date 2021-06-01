angular
        .module('AppAdmin')
        .controller('BarraNavegacaoCtrl', BarraNavegacaoCtrl);


BarraNavegacaoCtrl.$inject = ['$scope', '$state', 'UsuarioSrv'];

/**
 * Controla a exibição da barra superior
 *
 * @param {type} $scope
 * @param {type} $state
 * @param {type} UsuarioSrv
 * @returns {undefined}
 */
function BarraNavegacaoCtrl($scope, $state, UsuarioSrv) {

    $scope.$state = $state;

    $scope.user = null;

    UsuarioSrv.obterUsuario().then(function (usuario) {
        $scope.user = usuario;
    }, function () {
        $scope.$emit('msg', 'Erro ao obter os dados do usuário autenticado', null, 'error');
    });

    $scope.$on('auth.logout', function () {
        $scope.user = null;
    });

    $scope.logout = function () {
        UsuarioSrv.limpar();
        $state.go('logout');
    };
}


