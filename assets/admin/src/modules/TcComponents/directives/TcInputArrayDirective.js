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
        .directive('tcInputArray', TcInputArrayDirective);

/**
 * Inspirado em http://bootsnipp.com/snippets/featured/dynamic-form-fields-add-amp-remove
 *
 * @returns {TcInputArrayDirective.TcInputArrayDirectiveAnonym$0}
 */
function TcInputArrayDirective() {
    return {
        require: ['^form', 'ngModel'],
        templateUrl: 'modules/TcComponents/templates/TcInputArrayDirective.html',
        scope: {
            /**
             * Permite a
             */
            iMask: '@',
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
        link: function ($scope, $el, $attrs, controllers) {
            // @see TcBaseFieldDirective
            $scope.baseForm = controllers[0];
            $scope.ngModelCtrl = controllers[1];

            // Gera o nome do input a partir do ngModel informado
            // @see TcBaseFieldDirective
            $scope.tcInputName = '_tc_input_' + $attrs['ngModel'].replace(/[^a-z0-9]/ig, '_') + '_' + (+new Date);

            $scope.values = [''];

            $scope.add = function (e) {
                $scope.values.push('');
            };

            $scope.allowAdd = function ($index) {
                return $scope.isTcInputValid() && $scope.values[$index] && $scope.values[$index] !== '';
            };

            $scope.remove = function (idx) {
                if (idx >= 0) {
                    $scope.values.splice(idx, 1);
                }
            };


            var ignoreWatch = false;

            $scope.$watch('ngModel', function (nue, old) {
                if (ignoreWatch) {
                    ignoreWatch = false;
                    return;
                }

                if (!Array.isArray($scope.ngModel)) {
                    return;
                }

                $scope.values = [].concat($scope.ngModel);
            });

            $scope.$watch('values', function (nue, old) {
                if (nue === old) {
                    return;
                }
                var values = $scope.values.filter(function (item) {
                    return item && item !== '';
                });
                if ($scope.required && values.length < 1) {
                    $scope.setTcInputValidity('required', false);
                } else {
                    $scope.setTcInputValidity('required', true);
                }
                if ($scope.isTcInputValid()) {
                    ignoreWatch = true;
                    $scope.ngModelCtrl.$setViewValue(values);
                    $scope.ngModelCtrl.$render(); // will update the input value as well
                }
            }, true);

        }
    };
}
