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

angular.module('TcComponents')
    .directive('tcBaseField', TcBaseField);


/**
 * A lista com as mensagens padrão.
 *
 * Para substituir, inserir no $scope da diretiva que está sendo criada a variável errorMessages
 *
 * @type type
 */
TcBaseField.DEFAULT_MESSAGES = {
    required: 'Este campo é obrigatório',
    minlength: 'Valor informado é muito curto',
    maxlength: 'Valor informado é muito longo',
    pattern: 'Valor informado é inválido'
};

function TcBaseField() {
    return {
        transclude: true,
        scope: false,
        templateUrl: 'modules/TcComponents/templates/TcBaseFieldDirective.html',
        link: function ($scope) {
            $scope._formName = '_tc_base_field_form_' + $scope.tcInputName;
            $scope._errorMessages = (function (defMess, inhMess) {
                var out = {};
                for (var a in defMess) {
                    out[a] = defMess[a];
                }
                if (inhMess) {
                    for (var a in inhMess) {
                        out[a] = inhMess[a];
                    }
                }
                return out;
            })(TcBaseField.DEFAULT_MESSAGES, $scope.errorMessages);


            $scope.setTcInputDirty = function () {
                getForm(function (form) {
                    var input = form[$scope.tcInputName];
                    input.$setDirty();
                });
            };

            $scope.setTcInputTouched = function () {
                getForm(function (form) {
                    var input = form[$scope.tcInputName];
                    input.$setTouched();
                });
            };

            /**
             * Permite alterar uma validação de um componente que extenda base field
             *
             * @param {type} validationErrorKey
             * @param {type} isValid
             * @returns {undefined}
             */
            $scope.setTcInputValidity = function (validationErrorKey, isValid) {
                getForm(function (form) {
                    form.$setValidity(validationErrorKey, isValid);
                });
            };

            /**
             * Verifica se o componente possui erros
             *
             * @returns {unresolved}
             */
            $scope.isTcInputValid = function () {
                try {
                    return $scope.baseForm[$scope._formName].$valid;
                } catch (e) {
                    if (window.console && window.console.log) {
                        window.console.log(e);
                    }
                    return false;
                }
            };

            function getForm(cb, attempts) {
                if (attempts && attempts > 15) {
                    return;
                }
                var form = $scope.baseForm[$scope._formName];
                if (form) {
                    cb(form);
                } else {
                    setTimeout(getForm.bind(null, cb, (attempts) ? attempts++ : 1), 10);
                }
            }
        }
    };
}
