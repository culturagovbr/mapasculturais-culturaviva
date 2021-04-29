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
    .directive('tcSelect', TcSelectDirective);

TcSelectDirective.$inject = ['$http'];
function TcSelectDirective($http) {
    /**
     * @directive TcComponents.tcLocalidade
     *
     * @description Diretiva para exibição de campo combo box
     */
    return {
        restrict: 'E',
        require: ['^form', 'ngModel'],
        templateUrl: 'modules/TcComponents/templates/TcSelectDirective.html',
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
            change: '&?',
            /**
             * @description Permite ocultar a opçao default
             *
             */
            hideNoneOption: '=',
            /**
             * @description Permite alterar o texto para o campo quando não há valor selecionado
             *
             * @type Boolean
             * @optional
             */
            noneSelectedText: '@',
            field: '@',
            order: '@',
            /**
             * @description As opçoes para a criação do combo
             *
             * @type String
             * @optional
             */
            options: '=',
        },
        link: function ($scope, $element, $attrs, controllers) {
            // @see TcBaseFieldDirective
            $scope.baseForm = controllers[0];
            $scope.ngModelCtrl = controllers[1];

            // Gera o nome do input a partir do ngModel informado
            // @see TcBaseFieldDirective
            $scope.tcInputName = '_tc_select_' + $attrs['ngModel'].replace(/[^a-z0-9]/ig, '_') + '_' + (+new Date);

            $scope.$watch('ngModel', checkNgModelRef);

            /**
             * Quando valor for setado na controller, geralmente somente o código da localidade,
             * fazer o link para o dado correto
             *
             * @returns {undefined}
             */
            function checkNgModelRef() {
                if ($scope.options && $scope.options.length > 0) {
                    var founded = $scope.options.find(function (item) {
                        return item === $scope.ngModel;
                    });
                    if (founded) {
                        $scope.ngModelCtrl.$setViewValue(founded);
                    }
                }
            }
        }
    };
}
