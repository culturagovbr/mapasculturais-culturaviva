'use strict';

angular
    .module('Configuracao')
    .directive('inputAgente', InputAgenteDirective);

InputAgenteDirective.$inject = ['$http', '$q'];
function InputAgenteDirective($http, $q) {
    /**
     * @description Autocomplete para CBO - Codigo Brasileiro de Ocupações
     */
    return {
        restrict: 'E',
        require: ['^form', 'ngModel'],
        templateUrl: 'modules/Configuracao/templates/InputAgenteDirective.html',
        scope: {
            /**
             * @description Data bind para variável de escopo
             */
            ngModel: '=',
            /**
             * @description Informa o título do campo
             *
             * Quando não informado, será exibido apenas as opções
             *
             * @type String
             * @optional
             */
            label: '@',
            /**
             * @description Permite adiconar texto descritivo do campo
             *
             * @type String
             * @optional
             */
            help: '@',
            /**
             * @description Determina o tamanho do grid
             *
             * @type Number
             * @default 12
             * @optional
             */
            col: '@',
            /**
             * @description Determina o tamanho do grid do input
             *
             * @type Number
             * @default 12
             * @optional
             */
            colInput: '@',
            /**
             * @description Informa se o preenchimento do campo é obrigatório
             *
             * @type Boolean
             * @optional
             */
            required: '=',
            /**
             * @description Informa se o campo está habilitado para edição ou não
             *
             * @type Boolean
             * @optional
             */
            disabled: '=',
            /**
             * @description Callback executado ao modificar o valor
             *
             * @type Boolean
             * @optional
             */
            readonly: '='
        },
        link: function ($scope, $element, $attrs, controllers) {
            // @see TcBaseFieldDirective
            $scope.baseForm = controllers[0];
            $scope.ngModelCtrl = controllers[1];

            // Gera o nome do input a partir do ngModel informado
            // @see TcBaseFieldDirective
            $scope.tcInputName = '_input_cbo_' + $attrs['ngModel'].replace(/[^a-z0-9]/ig, '_') + '_' + (+new Date);

            $scope.buscar = function (txt) {
                var defered = $q.defer();
                $http.get('/certificador/buscarAgente', {
                    params: {
                        nome: txt
                    }
                }).then(function (response) {
                    defered.resolve(response.data);
                    if (!response.data || response.data.length < 1) {
                        $scope.$emit('msg', 'Nenhum Agente encontrado com o nome informado', null, 'info', 'bag-filtro-agentes');
                    }
                }, function (cause, status) {
                    $scope.$emit('msg', 'Erro inesperado ao carregar a lista de Agentes', null, 'error', 'bag-filtro-agentes');
                    defered.reject(cause);
                });

                return defered.promise;
            };
        }
    };
}
