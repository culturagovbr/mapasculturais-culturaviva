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


    var botaoDeferirIndeferir = {
        title: '...',
        disabled: true,
        click: function () {
            submit(botaoDeferirIndeferir.estadoAcao);
        }
    };

//    setTimeout(function () {
//        botao.title = "lba";
//        $scope.$digest();
//    }, 2000);
    $scope.botoes = [
        // Botões adicionais para o formulário
        botaoDeferirIndeferir
    ];


    $scope.permiteDeferir = false;
    $scope.permiteIndeferir = false;

    $scope.$watch('avaliacao.criterios', function (old, nue) {
        if (old === nue) {
            return;
        }

        $scope.permiteDeferir = true;
        $scope.permiteIndeferir = false;
        var botaoBloqueado = false;

        for (var a = 0, l = $scope.avaliacao.criterios.length; a < l; a++) {
            var criterio = $scope.avaliacao.criterios[a];
            if (criterio.aprovado === undefined || criterio.aprovado === null) {
                botaoBloqueado = true;
                break;
            }

            if (criterio.aprovado.valor === true && $scope.permiteIndeferir) {
                $scope.permiteDeferir = false;
            } else if (criterio.aprovado.valor === false) {
                $scope.permiteIndeferir = true;
                $scope.permiteDeferir = false;
            }
        }

        botaoDeferirIndeferir.disabled = botaoBloqueado;
        if (botaoBloqueado) {
            botaoDeferirIndeferir.title = "...";
            botaoDeferirIndeferir.class = "btn-default";
        } else if ($scope.permiteDeferir) {
            botaoDeferirIndeferir.title = "Deferir";
            botaoDeferirIndeferir.disabled = botaoBloqueado;
            botaoDeferirIndeferir.estadoAcao = ST_DEFERIDO;
            botaoDeferirIndeferir.class = "btn-success";
        } else if ($scope.permiteIndeferir) {
            botaoDeferirIndeferir.title = "Indeferir";
            botaoDeferirIndeferir.class = "btn-danger";
            botaoDeferirIndeferir.estadoAcao = ST_INDEFERIDO;
        }

        // criterio.aprovado
        // criterio.aprovado
    }, true);

    $scope.salvar = function (estado) {
        salvar();
    };

    function submit(estado) {
        var formName = 'formCriterios'
        if ($scope[formName].isValid()) {
            salvar(estado);
        } else {
            $scope.$emit('msg', 'Existem erros no preenchimento do formulário', null, 'error', formName);
            $window.scrollTo(0, 0);
        }
    }

    /**
     *
     * @param {type} estado
     * @returns {undefined}
     */
    function salvar(estado) {
        var criterios = [];
        for (var a = 0, l = $scope.avaliacao.criterios.length; a < l; a++) {
            var criterio = $scope.avaliacao.criterios[a];
            if (criterio.aprovado === undefined || criterio.aprovado === null) {
                continue;
            }

            criterios.push({
                id: criterio.id,
                aprovado: criterio.aprovado.valor
            });
        }

        $http.post('/avaliacao/salvar', {
            id: $scope.avaliacao.id,
            observacoes: $scope.avaliacao.observacoes,
            criterios: criterios,
            estado: estado
        }).then(function (response) {
            $scope.$emit('msgNextState', 'Dados da avaliação salvo com sucesso', null, 'success');
            //$scope.$emit('scrollToTop');
            $state.reload();
        }, function (response) {
            var msg = 'Erro inesperado salvar dados';
            if (response.data && response.data.message) {
                msg = response.data.message;
            }
            $scope.$emit('msg', msg, null, 'error', 'formulario');
        });
    }

    $scope.indeferido = function () {
        if ($scope.avaliacao != undefined) {
            return $scope.avaliacao.criterios.some(function (c) {
                return c.aprovado.valor === false;
            }) ? true : false;
        }
    };

    $scope.createPDF = function () {
        createPDF();
    };


    function createPDF() {
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

}

