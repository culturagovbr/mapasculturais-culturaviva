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
        .directive('tcForm', TcFormDirective)
        .directive('tcFormButton', TcFormButton);

TcFormDirective.$inject = ['$document'];
function TcFormDirective($document) {
    return {
        transclude: true,
        scope: {
            name: '@',
            buttons: '=',
            hideButtons: '=',
            onSubmit: '&',
            onSave: '&',
            onSaveLabel: '@',
            onClear: '&'
        },
        templateUrl: 'modules/TcComponents/templates/TcFormDirective.html',
        link: function ($scope, $el, $attrs) {
            var formName = $scope.name || 'form';
            $scope.formName = formName;

            // Injeta o form no scopo pai
            injectParentForm();

            if (!$attrs.hasOwnProperty('onClear')) {
                $scope.onClear = null;
            }

            if (!$attrs.hasOwnProperty('onSave')) {
                $scope.onSave = null;
            }

            if (!$attrs.hasOwnProperty('onSubmit')) {
                $scope.onSubmit = null;
            }

            if (!$attrs.hasOwnProperty('buttons')) {
                $scope.buttons = null;
            }

            /**
             *
             * @returns {undefined}
             */
            $scope.clear = function () {
                $scope[formName].$submitted = false;
                $scope[formName].$setPristine();
                $scope[formName].$setUntouched();

                if (angular.isFunction($scope.onClear)) {
                    $scope.onClear();
                }
            };

            /**
             * Faz as validações necessárias para salvar
             *
             * @returns {undefined}
             */
            $scope.submit = function (ignore) {
                if (ignore) {
                    return;
                }
                if ($scope[formName].isValid()) {
                    if (angular.isFunction($scope.onSubmit)) {
                        $scope.onSubmit();
                    } else if (angular.isFunction($scope.onSave)) {
                        $scope.onSave();
                    }
                } else {
                    $scope.$emit('msg', 'Existem erros no preenchimento do formulário', null, 'error', formName);
                    // Ao mudar de state, rola para o topo da página
                    setTimeout(function () {
                        $('#page-content-container')[0].scrollTop = $document[0].body.scrollTop = $document[0].documentElement.scrollTop = 0;
                    });
                }
            };


            /**
             * Injeta o formulário atual no scope pai
             *
             * @returns {unresolved}
             */
            function injectParentForm() {
                if ($scope.$$destroyed) {
                    return;
                }
                if (!$scope[formName]) {
                    return setTimeout(injectParentForm, 5);
                }
                $scope.$parent[formName] = $scope[formName];

                // Injeta os métodos de controle de formulário
                var form = $scope[formName];

                form.isValid = function () {
                    form.$submitted = true;
                    return form.$valid;
                };
                form.clear = $scope.clear;
                form.submit = $scope.submit;
            }
        }
    };
}

/**
 * Botão para submeter o formulário usando as validações do TcForm
 *
 * @returns {TcFormButton.TcFormDirectiveAnonym$1}
 */
function TcFormButton() {
    return {
        transclude: true,
        replace: true,
        require: '?form',
        template: '<button type="submit" ng-click="submit()"><ng-transclude></ng-transclude></button>',
        link: function ($scope, $el, $attrs, ctrl) {
            // Get the parent of the form
            var parent = $el.parent().controller('form');
            $scope.submit = function () {
                parent.submit();
            };
        }
    };
}

/**
 * Permite isolar subformulários
 *
 * https://jsfiddle.net/zrbjvxew/1/
 *
 * @returns {TcFormIsolateDirective.TcFormDirectiveAnonym$1}
 */
function TcFormIsolateDirective() {
    return {
        restrict: 'A',
        require: '?form',
        link: function ($scope, elm, attrs, ctrl) {
            if (!ctrl) {
                return;
            }

            isolate();

            function isolate() {
                if (!$scope.formName || !$scope[$scope.formName]) {
                    return setTimeout(isolate, 5);
                }

                // Do a copy of the controller
                var ctrlCopy = {};
                angular.copy(ctrl, ctrlCopy);

                // Get the parent of the form
                var parent = elm.parent().controller('form');
                // Remove parent link to the controller
                parent.$removeControl(ctrl);

                // Replace form controller with a "isolated form"
                var isolatedFormCtrl = {
                    $setValidity: function (validationToken, isValid, control) {
                        ctrlCopy.$setValidity(validationToken, isValid, control);
                        parent.$setValidity(validationToken, true, ctrl);
                    },
                    $setDirty: function () {
                        elm.removeClass('ng-pristine').addClass('ng-dirty');
                        ctrl.$dirty = true;
                        ctrl.$pristine = false;
                    },
                };
                angular.extend(ctrl, isolatedFormCtrl);
            }

        }
    };
}
