/* global google */

angular
    .module('Configuracao')
    .controller('CertificadorListaCtrl', CertificadorListaCtrl);

CertificadorListaCtrl.$inject = ['$scope', '$state', '$http', 'estadosBrasil'];

/**
 * Listagem de certificadores
 *
 * @param {type} $scope
 * @param {type} $state
 * @param {type} $http
 * @returns {undefined}
 */
function CertificadorListaCtrl($scope, $state, $http, estadosBrasil) {


    // Configuração da página
    $scope.pagina.titulo = 'Certificadores';
    $scope.pagina.subTitulo = 'Listagem de Agentes de certificação';
    $scope.pagina.classTitulo = '';
    $scope.pagina.ajudaTemplateUrl = '';
    $scope.uf = (function () {
        var out = [];
        for (var uf in estadosBrasil) {
            if (estadosBrasil.hasOwnProperty(uf)) {
                out.push({valor: uf, label: uf + ' - ' + estadosBrasil[uf], active: false});
            }
        }
        return out;
    })();
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


    $scope.filtrar = function(uf) {
        filter(uf.valor);
    }
    filter();
    function filter(uf) {
        $http.get('/certificador/listar', {
            params: {
                uf: uf
            }
        }).then(function (response) {
            $scope.certificadores.civil = {
                titular: response.data.filter(function (cert) {
                    return cert.tipo === 'C' && cert.titular;
                }),
                suplente: response.data.filter(function (cert) {
                    return cert.tipo === 'C' && !cert.titular;
                })
            };
            $scope.certificadores.civilEstadual = {
                titular: response.data.filter(function (cert) {
                    return cert.tipo === 'S' && cert.titular;
                }),
                suplente: response.data.filter(function (cert) {
                    return cert.tipo === 'S' && !cert.titular;
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
            $scope.certificadores.publicoEstadual = {
                titular: response.data.filter(function (cert) {
                    return cert.tipo === 'E' && cert.titular;
                }),
                suplente: response.data.filter(function (cert) {
                    return cert.tipo === 'E' && !cert.titular;
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
