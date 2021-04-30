/* global google */

angular
    .module('Configuracao')
    .controller('AvaliacaoRedistribuicaoCtrl', AvaliacaoRedistribuicaoCtrl);


AvaliacaoRedistribuicaoCtrl.$inject = ['$scope', '$state', '$http', 'estadosBrasil'];


/**
 * Formulário de cadastro e edição de agentes de certificação
 *
 * @param {type} $scope
 * @param {type} $state
 * @param {type} $http
 * @returns {undefined}
 */
function AvaliacaoRedistribuicaoCtrl($scope, $state, $http, estadosBrasil) {

    var codigo = $state.params.id;
    var novoRegistro = (!codigo || codigo === '');

    // Configuração da página
    $scope.pagina.titulo = 'Redistribuição';
    $scope.pagina.subTitulo = 'Configuração de rotina de redistribuição de avaliações para certificação de inscrições';
    $scope.pagina.classTitulo = '';
    $scope.pagina.ajudaTemplateUrl = '';
    $scope.marcaTodos = false;
    $scope.pagina.breadcrumb = [
        {
            title: 'Início',
            sref: 'pagina.relatorios'
        },
        {
            title: 'Configurações'
        },
        {
            title: 'Redistribuição',
        }
    ];
    $http.get('/avaliacao/configurar').then(function (response) {
        $scope.ufs = response.data;
    }, function (response) {
        var msg = 'Erro inesperado recuperar dados';
        if (response.data && response.data.message) {
            msg = response.data.message;
        }
        $scope.$emit('msg', msg, null, 'error', 'formulario');
    });
    $scope.redistribuir = function () {
        if(confirm("Tem certeza que deseja executar a rotina de distribuição e certificação? Esta ação não pode ser desfeita.")){
            $http.get('/avaliacao/distribuir').then(function (response) {
                $scope.filtrar();
            });
        }
    };
    $scope.marcarTodos = function () {
        if ($scope.marcaTodos === false) {
            $scope.marcaTodos = true;
            for (var uf in $scope.ufs) {
                $scope.ufs[uf].redistribuicao = true;
            }
        } else {
            $scope.marcaTodos = false;
            for (var uf in $scope.ufs) {
                $scope.ufs[uf].redistribuicao = false;
            }
        }
    };
    $scope.salvar = function () {
        var dto = AvaliacaoRedistribuicaoCtrl.converterParaEscopo($scope.ufs);
        console.log(dto, 'aqui');
        $http.post('/avaliacao/configurar', dto).then(function (response) {
            $scope.$emit('msgNextState', 'Certificadores Federais entrarão na redistribuição dos estados selecionados.', null, 'success');
            $state.go('pagina.configuracao.redistribuir', {}, {
                reload: true,
                inherit: true,
                notify: true
            });
        }, function (response) {
            var msg = 'Erro inesperado salvar dados';
            if (response.data && response.data.message) {
                msg = response.data.message;
            }
            $scope.$emit('msg', msg, null, 'error', 'formulario');
        });
    };


    $scope.filtrarAgente = function (texto) {
        if (!texto || texto === '') {
            $scope.agentes = null;
            return;
        }

        $scope.agentes = [];

        $scope.$emit('msgClear', 'filtro-promocoes');

        $http.get('/certificador/buscarAgente', {
            params: {
                nome: texto
            }
        }).then(function (response) {
            $scope.agentes = response.data;
            if (!response.data || response.data.length < 1) {
                $scope.$emit('msg', 'Nenhum Agente encontrado com o nome informado', null, 'info', 'bag-filtro-agentes');
            }
        }, function (error) {
            $scope.$emit('msg', 'Erro inesperado ao carregar a lista de Agentes', null, 'error', 'bag-filtro-agentes');
        });
    };

    $scope.selecionarAgente = function (agente) {
        $scope.dto.agenteId = agente.id;
        $scope.dto.agenteNome = agente.name;
        $scope.ref.buscarAgente = false;
    };
}

AvaliacaoRedistribuicaoCtrl.converterParaEscopo = function (dto) {
    var out = [];
    console.log(dto);
    for (var uf in dto) {
        console
        if (dto[uf] === true) {
            out.push(uf);
        }
        if (dto[uf].redistribuicao === true) {
            out.push(dto[uf].sigla);
        }
    }
    return out;
};
