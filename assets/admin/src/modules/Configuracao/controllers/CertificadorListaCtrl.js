/* global google */

angular
    .module('Configuracao')
    .controller('CertificadorListaCtrl', CertificadorListaCtrl);

CertificadorListaCtrl.$inject = ['$scope', '$state', '$http'];

/**
 * Listagem de certificadores
 *
 * @param {type} $scope
 * @param {type} $state
 * @param {type} $http
 * @returns {undefined}
 */
function CertificadorListaCtrl($scope, $state, $http) {


    // Configuração da página
    $scope.pagina.titulo = 'Certificadores';
    $scope.pagina.subTitulo = 'Listagem de Agentes de certificação';
    $scope.pagina.classTitulo = '';
    $scope.pagina.ajudaTemplateUrl = '';
    $scope.pagina.breadcrumb = [
        {
            title: 'Início',
            sref: 'pagina.relatorios'
        },
        {
            title: 'Configurações'
        }
    ];

    $scope.certificadores = null;
    $scope.form = {};
    $scope.certificadores = {};

    filter();

    function filter() {
        $http.get('/certificador/listar', {}).then(function (response) {
            $scope.certificadores.civil = {
                titular: response.data.filter(function (cert) {
                    return cert.tipo === 'C' && cert.titular;
                }),
                suplente: response.data.filter(function (cert) {
                    return cert.tipo === 'C' && !cert.titular;
                })
            };
            $scope.certificadores.publico = {
                titular: response.data.filter(function (cert) {
                    return cert.tipo === 'P' && cert.titular;
                }),
                suplente: response.data.filter(function (cert) {
                    return cert.tipo === 'P' && !cert.titular;
                })
            };
            $scope.certificadores.minerva = {
                titular: response.data.filter(function (cert) {
                    return cert.tipo === 'M' && cert.titular;
                }),
                suplente: response.data.filter(function (cert) {
                    return cert.tipo === 'M' && !cert.titular;
                })
            };
        })
    }
}
