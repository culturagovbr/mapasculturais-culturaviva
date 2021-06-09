/* global google */

angular
    .module('Certificacao')
    .controller('AvaliacaoCertificadoCtrl', AvaliacaoCertificadoCtrl);

AvaliacaoCertificadoCtrl.$inject = ['$scope', '$state', '$http', '$window'];

/**
 * Listagem de Inscrições disponíveis para Avaliação
 *
 * @param {type} $scope
 * @param {type} $state
 * @param {type} $http
 * @returns {undefined}
 */
function AvaliacaoCertificadoCtrl($scope, $state, $http, $window) {

    /**
     * Indica que o Certificador finalizou a análise da Inscrição como DEFERIDO
     */
    var ST_DEFERIDO = 'D';

    /**
     * Indica que o Certificador finalizou a análise da Inscrição como INDEFERIDO
     */
    var ST_INDEFERIDO = 'I';

    // Configuração da página
    $scope.pagina.titulo = 'Avaliação do Ponto/Pontão de Cultura';
    $scope.pagina.subTitulo = '';
    $scope.pagina.classTitulo = '';
    $scope.pagina.ajudaTemplateUrl = '';
    $scope.pagina.breadcrumb = [
        {
            title: 'Início',
            sref: 'pagina.relatorios'
        },
        {
            title: 'Avaliações',
            sref: 'pagina.certificacao.lista'
        }
    ];

    $scope.certificadores = null;
    $scope.form = {};
    $scope.certificadores = {};

    var codigo = $state.params.id;

    $scope.simNao = [
        {valor: true, label: 'Sim'},
        {valor: false, label: 'Não'}
    ];


    $http.get('/avaliacao/obter/' + codigo).then(function (response) {
        var data = response.data;
        $scope.avaliacao = data;

        // Usado pelos controllers filhos
        $scope.agentId = data.agenteId;

        $scope.situacaoAvaliacao = {
            'P': 'Pendente',
            'A': 'Em Análise',
            'D': 'Deferido',
            'I': 'Indeferido'
        }[data.estado];

        $scope.situacaoInscricao = {
            'P': 'Pendente',
            'C': 'Certificado',
            'N': 'Não Certificado',
            'R': 'Re-Submissão',
        }[data.inscricaoEstado];

        angular.forEach($scope.avaliacao.criterios, function (criterio) {
            if (criterio.aprovado === true) {
                criterio.aprovado = $scope.simNao[0];
            } else if (criterio.aprovado === false) {
                criterio.aprovado = $scope.simNao[1];
            }
        })
    }, function (cause) {
        var data = cause.data;
        var msg = 'Erro ao recuperar dados da Avaliação';
        if (data && data.message) {
            msg = data.message;
        }
        $scope.$emit('msg', msg, null, 'error');
    });

    // var app = angular.module('culturaviva.services', ['ngResource']);
    // app.factory('MapasCulturais', function () {
    //     if (!window.MapasCulturais) {
    //         throw new Error('É necessário ter o obj "MapasCulturais" em window');
    //     }
    //     return window.MapasCulturais;
    // });
    //
    // var id = MapasCulturais.redeCulturaViva.agentePonto;
    //
    // $scope.data = MapasCulturais.redeCulturaViva;
    // $scope.urlQRCODE = null;
    //
    // var ponto = {
    //     '@select': 'id,name,user.id,homologado_rcv,status,longDescription',
    //     '@permissions': 'view',
    //     'id': id
    // };
    //
    // $scope.ponto = Entity.get(ponto);
    //
    $scope.createPDF = function () {
        createPDF();
    }

    function createPDF() {
        // var doc = new jsPDF()
        //
        // doc.text('Hello world!', 10, 10)
        // doc.save('a4.pdf')
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

            doc.save('Certificado.pdf');
            return doc;
        });
    }
}

