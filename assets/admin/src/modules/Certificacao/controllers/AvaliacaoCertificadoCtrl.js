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

    $scope.createPDF = function () {
        createPDF();
    };

    function createPDF() {
        var id = $scope.avaliacao.entidadeId;

        console.log(id);
        $scope.data = MapasCulturais.redeCulturaViva;
        $scope.urlQRCODE = null;

        var ponto = {
            '@select': 'id,name,user.id,homologado_rcv,status,longDescription',
            '@permissions': 'view',
            'id': id
        };
        $scope.ponto = Entity.get(ponto);

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

            var dataURLQR = qr.children[0].toDataURL('image/png');
            doc.setFontSize(20);
            doc.text(MapasCulturais.createUrl('agent', 'single', [ponto.id]), 630, 1225);
            doc.addImage(dataURLQR, 'png', 659, 996, 200, 199);

            doc.save('Selo.pdf');
            return doc;
        });
    }
}

