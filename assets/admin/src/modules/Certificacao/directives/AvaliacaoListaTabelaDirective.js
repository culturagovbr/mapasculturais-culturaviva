'use strict';

angular
        .module('Certificacao')
        .directive('avaliacaoListaTabela', AvaliacaoListaTabelaDirective);


AvaliacaoListaTabelaDirective.$inject = ['estadosBrasil'];

function AvaliacaoListaTabelaDirective(estadosBrasil) {

    Controller.$inject = ['$scope', '$http', 'UsuarioSrv'];
    function Controller($scope, $http, UsuarioSrv) {
        $scope.ref = {
            filtrarTexto: null,
            filtrarUf: null,
            filtrarMunicipio: null,
            texto: null,
            pagina: 1,
            data: null
        };
        $scope.registros = null;

        UsuarioSrv.obterUsuario().then(function (usuario){$scope.usuario = usuario;});

        $scope.estadosBrasil = (function () {
            var out = [];
            for (var uf in estadosBrasil) {
                if (estadosBrasil.hasOwnProperty(uf)) {
                    out.push({valor: uf, label: uf + ' - ' + estadosBrasil[uf]});
                }
            }
            return out;
        })();

        $scope.filtrar = function () {
            $scope.pagina = 1;
            $scope.filtrarAvaliacoes();
        };

        $scope.filtrarAvaliacoes = function () {
            $http.get('/avaliacao/listar', {
                params: {
                    pagina: $scope.ref.pagina,
                    nome: $scope.ref.filtrarTexto,
                    uf: $scope.ref.filtrarUf ? $scope.ref.filtrarUf.valor : undefined,
                    municipio: $scope.ref.filtrarMunicipio,
                    estado: $scope.estado
                }
            }).then(function (response) {
                $scope.ref.data = response.data;
                $scope.$emit("changetotal", {
                    pagina: $scope.ref.pagina,
                    nome: $scope.ref.filtrarTexto,
                    uf: $scope.ref.filtrarUf ? $scope.ref.filtrarUf.valor : undefined,
                    municipio: $scope.ref.filtrarMunicipio,
                    estado: $scope.estado
                });
                if ($scope.ref.data.rows) {
                    $scope.ref.data.chunk = (function chunk(arr, size) {
                        var newArr = [];
                        for (var i = 0; i < arr.length; i += size) {
                            newArr.push(arr.slice(i, i + size));
                        }
                        return newArr;
                    })($scope.ref.data.rows, 3);
                }
            });
        };

        $scope.redistribuir = function () {
            if(confirm("Tem certeza que deseja executar a rotina de distribuição e certificação? Esta ação não pode ser desfeita.")){
                $http.get('/avaliacao/distribuir').then(function (response) {
                    $scope.filtrar();
                });
            }
        };

        $scope.$watch('ref.pagina', $scope.filtrarAvaliacoes);
    }

    /**
     * @directive AppAdmin.directives.certificadorListaTabela
     *
     * @description Componente reutilizável para exibir a tabela com os certificadores cadastrados
     */
    return {
        restrict: 'E',
        templateUrl: 'modules/Certificacao/templates/AvaliacaoListaTabela.html',
        scope: {
            /**
             * @description Estado da avaliação, usado no filtro de pesquisa
             */
            estado: '@',
            /**
             * @description Descrição para o tipo de avaliação
             */
            descricao: '@'
        },
        controller: Controller
    };
}