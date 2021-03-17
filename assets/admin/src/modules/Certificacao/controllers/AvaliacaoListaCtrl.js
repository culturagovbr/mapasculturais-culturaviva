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

    // Configuração da página
    $scope.pagina.titulo = 'Avaliações';
    $scope.pagina.subTitulo = 'Listagem de Avaliações para Certificação de Inscrições';
    $scope.pagina.classTitulo = '';
    $scope.pagina.ajudaTemplateUrl = 'modules/Certificacao/templates/ajuda/AvaliacaoListaPagina.html';
    $scope.pagina.breadcrumb = [
        {
            title: 'Início',
            sref: 'pagina.relatorios'
        }
    ];

    $scope.certificadores = null;
    $scope.form = {};
    $scope.certificadores = {};

    // Obtém totais de avaliações
    function atualizaTotal(params) {
        $http.get('/avaliacao/total', {params: params}).then(function (response) {
            $scope.total = response.data;
        });
    }

    atualizaTotal();

    $scope.$on("changetotal", function (event, params) {
        atualizaTotal(params);
    });
}

function AvaliacaoListaCertificadorDirective() {

    Controller.$inject = ['$scope', 'UsuarioSrv'];

    function Controller($scope, UsuarioSrv) {
        UsuarioSrv.obterUsuario().then(function (usuario) {
            $scope.usuario = usuario;
        });
    }

    /**
     * @directive AppAdmin.directives.certificadorListaTabela
     *
     * @description Componente reutilizável para exibir a tabela com os certificadores cadastrados
     */
    return {
        restrict: 'E',
        templateUrl: 'modules/Certificacao/templates/AvaliacaoListaCertificador.html',
        scope: {
            /**
             * @description O identificador do certificador
             */
            avaliacaoId: '@',
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


}

