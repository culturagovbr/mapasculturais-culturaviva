/* global google */

angular
    .module('Configuracao')
    .controller('CertificadorFormularioCtrl', CertificadorFormularioCtrl);


CertificadorFormularioCtrl.$inject = ['$scope', '$state', '$http'];

/**
 * O tipo de certificador sendo cadastrado
 *
 * @type Array
 */
CertificadorFormularioCtrl.TIPO_CERTIFICADOR = [
    {codigo: 'P', label: 'Poder Público Federal'},
    {codigo: 'C', label: 'Sociedade Civil Federal'},
    {codigo: 'M', label: 'Voto de Minerva'}
];

CertificadorFormularioCtrl.OPCOES_ATIVO = [
    {valor: true, label: 'Ativo'},
    {valor: false, label: 'Inativo'}
];

CertificadorFormularioCtrl.OPCOES_GRUPO = [
    {valor: true, label: 'Titular'},
    {valor: false, label: 'Suplente'}
];


/**
 * Faz o mapeamento do serviço de busca para o scope
 *
 * @param {type} dto
 * @returns {Object}
 */
CertificadorFormularioCtrl.converterParaEscopo = function (dto) {
    return {
        id: dto.id,
        agenteId: dto.agenteId,
        agenteNome: dto.agenteNome,
        tipo: CertificadorFormularioCtrl.TIPO_CERTIFICADOR.find(function (item) {
            return item.codigo === dto.tipo;
        }),
        titular: CertificadorFormularioCtrl.OPCOES_GRUPO.find(function (item) {
            return item.valor === dto.titular;
        }),
        ativo: CertificadorFormularioCtrl.OPCOES_ATIVO.find(function (item) {
            return item.valor === dto.ativo;
        })
    };
};


/**
 * Faz o mapeamento do DTO do scope para o serviço de persistencia
 *
 * @param {type} dto
 * @returns {Object}
 */
CertificadorFormularioCtrl.converterParaSalvar = function (dto) {
    return {
        id: dto.id,
        agenteId: dto.agenteId ? dto.agenteId : dto._agente.id,
        tipo: dto.tipo.codigo,
        titular: dto.titular ? dto.titular.valor : true,
        ativo: dto.ativo.valor
    };
};


/**
 * Formulário de cadastro e edição de agentes de certificação
 *
 * @param {type} $scope
 * @param {type} $state
 * @param {type} $http
 * @returns {undefined}
 */
function CertificadorFormularioCtrl($scope, $state, $http) {

    var codigo = $state.params.id;
    var novoRegistro = (!codigo || codigo === '');

    // Configuração da página
    $scope.pagina.titulo = novoRegistro ? 'Cadastrar Agente de Certificação' : 'Editar Agente de Certificação';
    $scope.pagina.subTitulo = '';
    $scope.pagina.classTitulo = '';
    $scope.pagina.ajudaTemplateUrl = '';
    $scope.pagina.breadcrumb = [
        {
            title: 'Início',
            sref: 'pagina.relatorios'
        },
        {
            title: 'Configurações'
        },
        {
            title: 'Certificadores',
            sref: 'pagina.configuracao.certificador.lista'
        }
    ];
    $scope.tipos = CertificadorFormularioCtrl.TIPO_CERTIFICADOR;
    $scope.opcoesAtivo = CertificadorFormularioCtrl.OPCOES_ATIVO;
    $scope.opcoesGrupo = CertificadorFormularioCtrl.OPCOES_GRUPO;
    // Variaveis utilitárias
    $scope.ref = {
        buscarAgente: false,
        novoRegistro: novoRegistro,
        filtrarAgenteTexto: ''
    };

    if (novoRegistro) {
        var paramTipoCertificador = $state.params.tipo;
        $scope.dto = {
            tipo: CertificadorFormularioCtrl.TIPO_CERTIFICADOR.find(function (item) {
                return item.codigo === paramTipoCertificador;
            }),
            ativo: CertificadorFormularioCtrl.OPCOES_ATIVO.find(function (item) {
                return item.valor;
            })
        };
    } else {
        $http.get('/certificador/obter/' + codigo).then(function (response) {
            $scope.dto = CertificadorFormularioCtrl.converterParaEscopo(response.data);
        }, function (response) {
            var msg = 'Erro ao recuperar dados do Agente de Certificação';
            if (response.data && response.data.message) {
                msg = response.data.message;
            }
            $scope.$emit('msg', msg, null, 'error');
        });
    }

    $scope.salvar = function () {
        var dto = CertificadorFormularioCtrl.converterParaSalvar($scope.dto);
        $http.post('/certificador/salvar', dto).then(function (response) {
            $scope.$emit('msgNextState', 'Agente de Certificação salvo com sucesso', null, 'success');
            if (novoRegistro) {
                $state.go('pagina.configuracao.certificador.formulario', {
                    id: response.data.id
                }, {
                    reload: true,
                    inherit: true,
                    notify: true
                });
            } else {
                $state.reload();
            }
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
