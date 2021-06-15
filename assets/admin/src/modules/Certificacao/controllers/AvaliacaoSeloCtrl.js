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



    $scope.createPDF = function () {

        var ponto = {
            '@select': 'id,name,user.id,homologado_rcv,status,longDescription',
            '@permissions': 'view',
            'id': $scope.avaliacao.pontoId
        };

        Entity.get(ponto).then(function (ponto) {
            $scope.urlQRCODE = null;
            $scope.ponto = ponto.data;

            function convertImgToBase64(callback) {
                console.log('###');
                var img = new Image();
                img.onload = function () {
                    var canvas = document.createElement('CANVAS');
                    var ctx = canvas.getContext('2d');
                    // canvas.height = 1241;
                    // canvas.width = 1754;
                    canvas.height = img.height;
                    canvas.width = img.width;
                    ctx.drawImage(this, 0, 0);
                    var dataURL = canvas.toDataURL('image/png');
                    if (typeof callback === 'function') {
                        callback(dataURL);
                    }
                    // canvas = null;
                };
                img.src = '/assets/img/certificado.png';
            }

            var button = document.getElementById("download");

            convertImgToBase64(function (dataUrl) {

                var app = angular.module('culturaviva.services', ['ngResource']);
                var MapasCulturais = app.factory('MapasCulturais', MapasCulturais);
                var qr = document.getElementById('qrcode');
                var dataURLQR = qr[0].toDataURL("image/png");

                console.log('qr');
                console.log(qr);
                console.log('dataURLQR');
                console.log(dataURLQR);

                console.log('aqui');
                console.log(dataUrl);
                console.log($scope.ponto.name);
                var doc = new jsPDF('l', 'pt', [1755, 1238]);

                doc.addImage(dataUrl, 'png', 0, 0, 1755, 1238, '', 'NONE');

                doc.setFontType("bold");
                doc.setTextColor("#FFFFFF");
                doc.setFontSize(35);
                var text = "A Secretaria Especial da Cultura do Ministério da Cidadania, por meio da Secretaria da Diversidade Cultural, reconhece o coletivo/entidade\n\n" +
                    "\n\n" +
                    "como Ponto de Cultura a partir dos critérios estabelecidos na Lei Cultura Viva (13.018/2014).\n\n" +
                    "Este certificado comprova que a iniciativa desenvolve e articula atividades culturais em sua comunidade, " +
                    "e contribui para o acesso, a proteção e a promoção dos direitos, da cidadania e da diversidade cultural no Brasil."

                var text = doc.splitTextToSize(text, 1090)
                doc.text(text, 490, 290, '', '', 'center');

                var name = doc.splitTextToSize($scope.ponto.name, 1400)
                doc.setFontSize(25);
                doc.text(name, 490, 410);

                console.log('aqui2');
                doc.setFontSize(20);
                doc.text(MapasCulturais.createUrl('agent', 'single', [ponto.id]), 630, 1225);
                console.log('aqui3');
                doc.addImage(dataURLQR, 'png', 659, 996, 200, 199);
                console.log('aqui4');
                doc.save('Certificado.pdf');
                return doc;
            });


        });


    }
}

