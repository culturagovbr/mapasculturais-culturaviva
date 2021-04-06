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
        .directive('tcInputSearch', TcInputSearchDirective);

function TcInputSearchDirective() {
    return {
        require: 'ngModel',
        scope: {
            /**
             * @description Data bind para variável de escopo
             */
            ngModel: '=',
            /**
             * @description Texto para o campo de pesquisa
             */
            placeholder: '@',
            /**
             * @description Executado ao submeter o formulário de pesquisa
             */
            onSubmit: '&'
        },
        templateUrl: 'modules/TcComponents/templates/TcInputSearchDirective.html',
        link: function ($scope, $el, $attrs) {

            $scope.inputName = '_tc_input_search_' + $attrs['ngModel'].replace(/[^a-z0-9]/ig, '_') + '_' + (+new Date);

            getForm();

            function getForm(attempts) {
                if (attempts && attempts > 15) {
                    return;
                }
                var form = $scope['form' + $scope.inputName];
                if (form) {
                    $scope.inputSearchForm = form;
                } else {
                    setTimeout(getForm.bind(null, (attempts) ? attempts++ : 1), 10);
                }
            }

        }
    };
}
