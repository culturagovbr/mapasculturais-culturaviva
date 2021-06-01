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
        .factory('TcNotitication', TcNotiticationService);

TcNotiticationService.$inject = ['$rootScope'];

/**
 * Serviço para tratamento e gerenciamento das listas de mensagens
 *
 * @param {Object} $rootScope
 * @author Alex Rodin <contato@alexrodin.info>
 */
function TcNotiticationService($rootScope) {




    /**
     * Lista de mensagens registradas
     *
     * {
     *   MSG01 : {
     *     text : "Texto da mensagem",
     *     type : "error"
     *   },
     *   MSG02 : {
     *     text : "Texto da mensagem",
     *     type : "success"
     *   }
     * }
     * @type messages
     */
    var messageList = {};

    /**
     * Lista reversa de mensagens registradas
     *
     * {
     *   "Texto da mensagem" : {
     *     id : "MSG02",
     *     type : "error"
     *   },
     *   "Texto da mensagem" : {
     *     id : "MSG02",
     *     type : "success"
     *   }
     * }
     * @type messages
     */
    var messageListReverse = {};

    var service = {
        /**
         * Permite registrar uma lista de mensagens no serviço, facilitando o uso do componente
         *
         * @param {type} messages Lista de mensagens no formato:
         *     {
         *       success : {
         *         MSG01 : "Texto da mensagem"
         *       },
         *       error : {
         *         MSG05 : "Texto da mensagem"
         *       }
         *     }
         * @returns {undefined}
         */
        registerList: function (messages) {

            for (var type in messages) {
                if (!messages.hasOwnProperty(type)) {
                    continue;
                }
                for (var id in messages[type]) {
                    if (!messages[type].hasOwnProperty(id)) {
                        continue;
                    }

                    if (typeof messages[type][id] !== 'string') {
                        continue;
                    }

                    messageList[id] = {
                        type: type,
                        text: messages[type][id]
                    };
                    messageListReverse[messageList[id].text] = {
                        type: type,
                        id: id
                    };
                }
            }
        },
        /**
         * Permite descobrir o tipo correto da mensagem
         *
         * @param {String} mensagem
         * @returns {String} O tipo da mensagem registrada
         */
        getType: function (mensagem) {
            var type = 'info';
            if (messageList.hasOwnProperty(mensagem)) {
                type = messageList[mensagem].type;
            } else if (messageListReverse.hasOwnProperty(mensagem)) {
                type = messageListReverse[mensagem].type;
            }

            return type;
        },
        /**
         * Faz o parsing de uma mensagem informada
         *
         * Os parametros informados serão substituidos na mensagem (caso exista)
         *
         * @param {String} message
         * @param {Array|String[]} params
         * @returns {String}
         */
        parse: function (message, params) {
            if (typeof message === 'string') {
                if (messageList.hasOwnProperty(message)) {
                    message = messageList[message].text;
                }

                if (arguments.length !== 2) {
                    params = Array.prototype.slice.call(arguments, 1);
                }

                if (Array.isArray(params)) {
                    // array de parametros
                    for (var a = 0, l = params.length; a < l; a++) {
                        message = message.replace(new RegExp('\\$\\{' + a + '\\}', 'g'), params[a]);
                    }
                } else {
                    // objeto de parametros
                    for (var a in params) {
                        if (!params.hasOwnProperty(a)) {
                            continue;
                        }
                        message = message.replace(new RegExp('\\$\\{' + a + '\\}', 'g'), params[a]);
                    }
                }
            }
            return message;
        },
        msgNextStateCallbacks: null
    };


    // Eventos para gerenciamento das mensagens
    $rootScope.$on('msg', onRootscopeMsg);
    $rootScope.$on('msgClear', onRootscopeClearMsg);
    $rootScope.$on('msgNextState', function (event, message, parametros, type, container, delay) {
        if (!service.msgNextStateCallbacks) {
            service.msgNextStateCallbacks = [];
        }
        service.msgNextStateCallbacks.push(onRootscopeMsg.bind(null, event, message, parametros, type, container, delay));
//        var discard = $rootScope.$on('pageContentUpdated', function () {
//            onRootscopeMsg(event, message, parametros, type, container, delay);
//            discard();
//        });
    });


    /**
     *
     * @param {type} event
     * @param {type} container
     * @returns {undefined}
     */
    function onRootscopeClearMsg(event, container) {
        // Envia o evento para directiva correta
        var eventName = 'tcNotitication';
        if (container) {
            eventName += ':' + container;
        }

        $rootScope.$broadcast(eventName, 'clear');
    }

    /**
     * Permite o uso do BUS para disparar mensagens.
     * Com isso,  nao é necessário injetar esse serviço onde for usado,
     * basta disparar o evento para o bus, usando o $scope
     *
     * @example $scope.$emit('msg', 'MSGA62', {param1:'valor'}, 'success', 'containerid', 1000);
     * @example $scope.$emit('msg', 'MSGA62');
     *
     * @param {type} event
     * @param {type} message
     * @param {type} parametros
     * @param {type} type
     * @param {type} container
     * @param {type} delay
     * @returns {undefined}
     */
    function onRootscopeMsg(event, message, parametros, type, container, delay) {
        type = (type && [
            'danger',
            'error',
            'warning',
            'success',
            'info'
        ].indexOf(type) >= 0) ? type : service.getType(message);

        if (type === 'error') {
            type = 'danger';
        }


        // Envia o evento para directiva correta
        var eventName = 'tcNotitication';
        if (container) {
            eventName += ':' + container;
        }

        $rootScope.$broadcast(eventName, {
            msg: service.parse(message, parametros),
            type: type,
            delay: delay
        });
    }

    return service;
}





