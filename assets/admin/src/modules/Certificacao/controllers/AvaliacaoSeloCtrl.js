angular
    .module('Certificacao')
    .controller('AvaliacaoSeloCtrl', AvaliacaoSeloCtrl);


/* global google */

AvaliacaoSeloCtrl.$inject = ['$scope', '$state', '$http', '$window', 'Entity'];


/**
 * Listagem de Inscrições disponíveis para Avaliação
 *
 * @param {type} $scope
 * @param {type} $state
 * @param {type} $http
 * @returns {undefined}
 */
function AvaliacaoSeloCtrl($scope, $state, $http, $window, Entity) {

    var app = angular.module('culturaviva.services', ['ngResource']);
    var MapasCulturais = app.factory('MapasCulturais', MapasCulturais);

    var id = $scope.avaliacao.pontoId;
    var app = angular.module('culturaviva.services', ['ngResource']);
    var MapasCulturais = app.factory('MapasCulturais', MapasCulturais);


    $scope.urlQRCODE = null;

    var ponto = {
        '@select': 'id,name,user.id,homologado_rcv,status,longDescription',
        '@permissions': 'view',
        'id': id
    };

    $scope.ponto = Entity.get(ponto);

    $scope.createPdf = function () {
        var qr = document.getElementById('qrcode');

        function convertImgToBase64(callback) {
            var img = new Image();
            img.onload = function () {
                var canvas = document.createElement('CANVAS');
                var ctx = canvas.getContext('2d');
                // canvas.height = 1241;
                // canvas.width = 1754;
                canvas.height = img.height;
                canvas.width = img.width;
                ctx.drawImage(this, 0, 0);
                var dataURL = canvas.toDataURL('image/jpeg');
                if (typeof callback === 'function') {
                    callback(dataURL);
                }
                // canvas = null;
            };
            img.src = '/assets/img/certificado.png';
        }

        var button = document.getElementById("download");

        convertImgToBase64(function (dataUrl) {

        });
    }
}

