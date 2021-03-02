/*!
 * Componentes de Interface Tres Camadas (tc)
 *
 *
 * Copyright 2016 3 Camadas Soluções, http://www.3camadas.com.br.
 *
 * Licenciado sob a licença MIT
 *
 * @author Alex Rodin <contato@alexrodin.info>
 */
'use strict';

angular
        .module('TcComponents')
        .directive('tcInputNumber', TcInputNumberDirective);

TcInputNumberDirective.$inject = ['$http'];
function TcInputNumberDirective($http) {
    /**
     * @directive TcComponents.tcInputNumber
     *
     * @description Input de numeros, utiliza o http://www.virtuosoft.eu/code/bootstrap-touchspin/
     */
    return {
        restrict: 'E',
        require: ['^form', 'ngModel'],
        templateUrl: 'modules/TcComponents/templates/TcInputNumberDirective.html',
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
             * @description Determina o incremento quando for input de numero
             *
             * @type Boolean
             * @default false
             * @optional
             */
            step: '=',
            min: '=',
            max: '=',
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
            readonly: '=',
            pattern: '@',
            change: '&',
            blur: '&',
            keyup: '&',
            keydown: '&',
            paste: '&',
            copy: '&',
            cut: '&'
        },
        link: function ($scope, $element, $attrs, controllers) {
            // @see TcBaseFieldDirective
            $scope.baseForm = controllers[0];
            $scope.ngModelCtrl = controllers[1];

            // Gera o nome do input a partir do ngModel informado
            // @see TcBaseFieldDirective
            $scope.tcInputName = '_tc_input_number_' + $attrs['ngModel'].replace(/[^a-z0-9]/ig, '_') + '_' + (+new Date);


            // Cria o touchspin para numeros http://www.virtuosoft.eu/code/bootstrap-touchspin/
            (function parseOnlyNumberInput() {
                if ($scope.$destroyed) {
                    return;
                }

                var element = angular.element($element);
                var input = $("input[name='" + $scope.tcInputName + "']", element);

                if (!input || input.size() < 1) {
                    setTimeout(parseOnlyNumberInput, 50);
                    return;
                }

                var initial = parseInt($scope.ngModel);
                var min = typeof $attrs.min !== 'undefined' ? $attrs.min : 0;
                var max = typeof $attrs.max !== 'undefined' ? $attrs.max : 999;
                var step = typeof $attrs.step !== 'undefined' ? $attrs.step : 1;

                input.TouchSpin({
                    min: min,
                    max: max,
                    step: parseInt(step),
                    initval: initial,
                    forcestepdivisibility: 'none'
                });

                var ignoreWatch = false;
                input.val($scope.ngModel);
                $scope.$watch('ngModel', function (nue, old) {
                    if (ignoreWatch) {
                        ignoreWatch = false;
                        return;
                    }

                    input.val($scope.ngModel);
                });

                input.on('change', function (e) {
                    ignoreWatch = true;
                    $scope.ngModelCtrl.$setViewValue(input.val());
                    $scope.ngModelCtrl.$render();
                });

                input.on('keyup', function (e) {
                    ignoreWatch = true;
                    $scope.ngModelCtrl.$setViewValue(input.val());
                    $scope.ngModelCtrl.$render();
                });
            })();
        }
    };
}