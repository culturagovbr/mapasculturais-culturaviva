angular
        .module('AppAdmin')
        .controller('PaginaCtrl', PaginaCtrl);


PaginaCtrl.$inject = ['$scope', '$rootScope'];

/**
 * Controller base para páginas
 *
 * @param {type} $scope
 * @param {type} $rootScope
 * @returns {undefined}
 */
function PaginaCtrl($scope, $rootScope) {
    $rootScope.pagina = {
        titulo: 'Título da pagina',
        subTitulo: '',
        classTitulo: '',
        ajudaTemplateUrl: '',
        breadcrumb: []
    };
}


