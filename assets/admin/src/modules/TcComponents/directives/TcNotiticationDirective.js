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
        .directive('tcNotitication', TcNotiticationDirective);


TcNotiticationDirective.$inject = ['TcNotitication'];

/**
 * Directiva que representa um agrupador de mensagens.
 *
 * Pode receber id para permitir entregar mensagens em um container específico
 *
 * @param {Object} TcNotitication
 * @author Alex Rodin <contato@alexrodin.info>
 */
function TcNotiticationDirective(TcNotitication) {
    /**
     * @directive TcComponents.tcNotitication
     *
     * @description Message Bag, permite agrupar mensagens de notificação
     */
    return {
        scope: {
            /**
             * @description Permite criar um message bag específico.
             * Pode ser usado para criar um message bag dentro de uma modal por exemplo, que recebe mensagens
             * específicas desta tela.
             *
             * @type String
             * @optional
             */
            id: '@'
        },
        template: [
            '<div class="row"><div class="col-md-12">',
            '    <div ',
            '      ng-repeat="notification in notifications"',
            '      class="alert alert-dismissable alert-{{notification.type}}"',
            '      >',
            '        <button ',
            '          type="button" ',
            '          class="close" ',
            '          data-dismiss="alert" ',
            '          aria-hidden="true" ',
            '          ng-click="remove(notification)"',
            '          >',
            '            <span class="pficon pficon-close"></span>',
            '        </button>',
            '        <span class="pficon {{ICONS[notification.type]}}"></span>',
            '        {{notification.msg}}',
            '    </div>',
            '</div></div>'
        ].join(''),
        link: function ($scope, $el, $attrs) {

            // Notificações dessa sacola
            $scope.notifications = [];

            $scope.ICONS = {
                'danger': ' pficon-error-circle-o',
                'warning': ' pficon-warning-triangle-o',
                'success': ' pficon-ok',
                'info': ' pficon-info'
            };

            /**
             * Permite remover uma notificação
             *
             * @param {type} notification
             * @returns {undefined}
             */
            $scope.remove = function (notification) {
                var idx = $scope.notifications.indexOf(notification);
                if (idx < 0) {
                    return;
                }

                $scope.notifications.splice(idx, 1);
            };

            $scope.$watch('id', function (newId, old) {
                handleEvent();
            });

            var eventHandler;
            // Espera receber evento para modificar a apresentação da mensagem
            function handleEvent() {
                if (eventHandler) {
                    eventHandler();
                    eventHandler = null;
                }

                var eventName = 'tcNotitication';
                if ($scope.id) {
                    eventName += ':' + $scope.id;
                } else {
                    // Notification global

                    if (TcNotitication.msgNextStateCallbacks) {
                        // Exibe as mensagens programadas para proxima tela
                        setTimeout(function () {
                            angular.forEach(TcNotitication.msgNextStateCallbacks, function (fn) {
                                fn();
                            });
                            TcNotitication.msgNextStateCallbacks = null;
                        });
                    }
                }

                $scope.$on(eventName, function (evnt, notification) {
                    if (notification === 'clear') {
                        $scope.notifications = [];
                        if (!$scope.hasOwnProperty('$$phase')) {
                            $scope.$digest();
                        }
                    } else {
                        $scope.notifications.push(notification);

                        // Tratamento para mensagens com delay
                        if (notification.delay || notification.delay === undefined || notification.delay === null) {
                            setTimeout(function () {
                                $scope.remove(notification);

                                $scope.$digest();
                            }, notification.delay || 10000);
                        }
                    }
                });
            }
        }
    };
}







