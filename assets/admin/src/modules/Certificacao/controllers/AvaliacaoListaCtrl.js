/* global google */

angular
        .module('Certificacao')
        .controller('AvaliacaoListaCtrl', AvaliacaoListaCtrl)
        .directive('avaliacaoListaCertificador', AvaliacaoListaCertificadorDirective);

AvaliacaoListaCtrl.$inject = ['$scope', '$state', '$http', 'UsuarioSrv'];

/**
 * Listagem de Inscrições disponíveis para Avaliação
 *
 * @param {type} $scope
 * @param {type} $state
 * @param {type} $http
 * @returns {undefined}
 */
function AvaliacaoListaCtrl($scope, $state, $http, UsuarioSrv) {
    console.log('$scope', $scope);

    // Configuração da página
    $scope.page.title = 'Avaliações';
    $scope.page.subTitle = 'Listagem de Avaliações para Certificação de Inscrições';
    $scope.page.titleClass = '';
    $scope.page.breadcrumb = [
        {
            title: 'Início',
            sref: 'pagina.relatorios'
        }
    ];

    $scope.certificadores = null;
    $scope.form = {};
    $scope.certificadores = {};

    // Obtém totais de avaliações
    $http.get('/avaliacao/total').then(function (response) {
        $scope.total = response.data;
    });
}

function AvaliacaoListaCertificadorDirective() {
    /**
     * @directive AppAdmin.directives.certificadorListaTabela
     *
     * @description Componente reutilizável para exibir a tabela com os certificadores cadastrados
     */
    return {
        restrict: 'E',
        templateUrl: 'modules/certificacao/templates/AvaliacaoListaCertificador.html',
        scope: {
            /**
             * @description O identificador do certificador
             */
            certificadorId: '@',
            /**
             * @description O nome do certificador
             */
            certificadorNome: '@',
            /**
             * @description O tipo do certificador
             */
            certificadorTipo: '@',
            /**
             * @description Estado da avaliação
             */
            estadoAvaliacao: '@'
        },
        controller: Controller
    };
    
    Controller.$inject = ['$scope', 'UsuarioSrv'];

    function Controller($scope, UsuarioSrv) {
        UsuarioSrv.obterUsuario().then(function (usuario) {
            $scope.usuario = usuario;
        });
    }
}

