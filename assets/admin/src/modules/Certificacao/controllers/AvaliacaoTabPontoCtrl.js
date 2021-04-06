/* global google */

angular
        .module('Certificacao')
        .controller('AvaliacaoTabPontoCtrl', AvaliacaoTabPontoCtrl);

AvaliacaoTabPontoCtrl.$inject = ['$scope', 'Entity', 'estadosBrasil'];

function AvaliacaoTabPontoCtrl($scope, Entity, estadosBrasil) {

    var params = {
        // Ver AvaliacaoFormularioCtrl.js
        'id': $scope.avaliacao.pontoId,
        '@select': [
            'id',
            'rcv_tipo',
            'terms',
            'name',
            'shortDescription',
            'cep',
            'tem_sede',
            'sede_realizaAtividades',
            'mesmoEndereco',
            'pais',
            'En_Estado',
            'En_Municipio',
            'En_Bairro',
            'En_Num',
            'En_Nome_Logradouro',
            'En_Complemento',
            'localRealizacao_estado',
            'localRealizacao_cidade',
            'localRealizacao_cidade',
            'localRealizacao_espaco',
            'location',
        ].join(','),
        '@files': '(avatar.avatarBig,portifolio,gallery.avatarBig):url',
        '@permissions': 'view'
    };

    Entity.get(params).then(function (response) {
        $scope.agent = response.data;

        $scope.agent._tem_sede = {
            '1': 'Sim',
            '0': 'Não',
        }[$scope.agent.tem_sede];

        $scope.agent._sede_realizaAtividades = {
            '1': 'Sim',
            '0': 'Não',
        }[$scope.agent.sede_realizaAtividades];

        if ($scope.agent.pais === 'Brasil' && $scope.agent.En_Estado) {
            $scope.agent._En_EstadoTexto = estadosBrasil[$scope.agent.En_Estado];
        }
    });
}

