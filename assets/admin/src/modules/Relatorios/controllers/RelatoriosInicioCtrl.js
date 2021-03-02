/* global google */

angular
        .module('Relatorios')
        .controller('RelatoriosInicioCtrl', RelatoriosInicioCtrl);

RelatoriosInicioCtrl.$inject = ['$scope', '$state', '$http'];

/**
 * Tela inicial da aplicação, exibe os relatórios disponíveis para o usuário logado
 *
 * @param {type} $scope
 * @param {type} $state
 * @param {type} $http
 * @returns {undefined}
 */
function RelatoriosInicioCtrl($scope, $state, $http) {

    // Configuração da página
    $scope.pagina.titulo = 'Política Nacional de Cultura Viva';
    $scope.pagina.subTitulo = '';
    $scope.pagina.classTitulo = 'header-pre-cards-pf';
    $scope.pagina.ajudaTemplateUrl = '';
    $scope.pagina.breadcrumb = null;


}

