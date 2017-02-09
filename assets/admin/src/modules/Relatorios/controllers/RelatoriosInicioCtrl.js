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
    $scope.page.title = 'Início';
    $scope.page.subTitle = 'Resumo dos processos de certificação';
    $scope.page.titleClass = 'header-pre-cards-pf';
    $scope.page.breadcrumb = null;


}
