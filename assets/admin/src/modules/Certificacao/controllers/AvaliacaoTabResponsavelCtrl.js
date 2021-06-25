/* global google */

angular
        .module('Certificacao')
        .controller('AvaliacaoTabResponsavelCtrl', AvaliacaoTabResponsavelCtrl);

AvaliacaoTabResponsavelCtrl.$inject = ['$scope', 'Entity', 'estadosBrasil'];

function AvaliacaoTabResponsavelCtrl($scope, Entity, estadosBrasil) {

    var params = {
        // Ver AvaliacaoFormularioCtrl.js
        'id': $scope.avaliacao.responsavelId,
        '@select': [
            'id',
            'rcv_tipo',
            'singleUrl',
            'name',
            'rg',
            'rg_orgao',
            'relacaoPonto',
            'pais',
            'cpf',
            'En_Estado',
            'terms',
            'emailPrivado',
            'telefone1',
            'telefone1_operadora',
            'telefone2',
            'telefone2_operadora',
            'nomeCompleto',
            'En_Municipio',
            'facebook',
            'twitter',
            'googleplus',
            'telegram',
            'whatsapp',
            'culturadigital',
            'diaspora',
            'instagram',
            'flickr',
            'youtube',
            'mesmoEndereco',
            'shortDescription'
        ].join(','),
        '@files': '(avatar.avatarBig,portifolio,gallery.avatarBig):url',
        '@permissions': 'view'
    };

    Entity.get(params).then(function (response) {
        $scope.agent = response.data;

        $scope.agent._relacaoPontoTexto = {
            'responsavel': 'Responsável pelo Ponto/Pontão de Cultura',
            'funcionario': 'Trabalha no Ponto/Pontão de Cultura',
            'parceiro': 'Parceiro do Ponto/Pontão e está ajudando a cadastrar'
        }[$scope.agent.relacaoPonto];

        if ($scope.agent.pais === 'Brasil' && $scope.agent.En_Estado) {
            $scope.agent._En_EstadoTexto = estadosBrasil[$scope.agent.En_Estado];
        }
    });
}

