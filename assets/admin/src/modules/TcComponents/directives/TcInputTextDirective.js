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
        .directive('tcInputText', TcInputTextDirective);


function TcInputTextDirective() {
    /**
     * @directive TcComponents.tcInput
     *
     * @description Input de texto simples
     */
    return {
        restrict: 'E',
        require: ['^form', 'ngModel'],
        templateUrl: 'modules/TcComponents/templates/TcInputTextDirective.html',
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
             * @description Determina o tamanho do textarea
             *
             * @type Number
             * @default 3
             * @optional
             */
            rows: '@',
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
            minlength: '=',
            maxlength: '=',
            pattern: '=',
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
            $scope.tcInputName = '_tc_textarea_' + $attrs['ngModel'].replace(/[^a-z0-9]/ig, '_') + '_' + (+new Date);



        }
    };
}