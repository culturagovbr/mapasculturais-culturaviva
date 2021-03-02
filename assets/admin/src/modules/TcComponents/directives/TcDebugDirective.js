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
        .directive('tcDebug', TcDebugDirective)
        .filter('prettyjson', PrettyPrintJsonFilter);

/**
 * Permite imprimir um json formatado.
 *
 * Util durante o desenvolvimento de funcionalidades como ferramenta de depuração
 *
 * @author Alex Rodin <contato@alexrodin.info>
 */
function TcDebugDirective() {
    return {
        template: [
            '<div class="col-md-{{col|| 12}}"">',
            '    <div class="tc-prettyprint"><span class="title">{{title || \'Resultado:\'}}</span>',
            '        <div ng-bind-html="model | prettyjson:all"></div>',
            '    </div>',
            '</div>'
        ].join(''),
        scope: {
            model: '=',
            title: '@',
            all: '=?',
            col: '@'
        },
        link: function ($scope, $el, $attrs) {
            if (!$scope.title) {
                $scope.title = $attrs['model'];
            }
        }
    };
}

PrettyPrintJsonFilter.$inject = ['$sce'];
function PrettyPrintJsonFilter($sce) {
    return function (obj, showAngularFields) {
        return $sce.trustAsHtml(prettyPrintJson(obj, showAngularFields));
    };
}

/**
 * Imprime um json formatado
 *
 * Inprirado em http://jsfiddle.net/unlsj/
 *
 * @author Alex Rodin <contato@alexrodin.info>
 * @param {Object} obj
 * @param {Boolean} showAngularFields
 * @returns {String}
 */
function prettyPrintJson(obj, showAngularFields) {
    var jsonLine = /^( *)("[\w]+": )?("[^"]*"|[\w.+-]*)?([,[{])?$/mg;
    var out = '<pre><code class="json">';
    if (obj === null || obj === undefined) {
        out += '<span class="value">' + obj + '</span>';
    } else {
        var cache = [];
        var jsonStr = JSON.stringify(obj, function (key, value) {
            if (typeof value === 'object' && value !== null) {
                if (cache.indexOf(value) !== -1) {
                    // Referencia circular encontrada, descarta a chave
                    return '~#ref';
                }
                // Salva o valor no cache para validaçao circular
                cache.push(value);
            }
            if (!showAngularFields && key[0] === '$') {
                // remove os atributos do angular.js
                return undefined;
            }
            return value;
        }, 2);

        out += jsonStr.replace(/&/g, '&amp;').replace(/\\"/g, '&quot;')
                .replace(/</g, '&lt;').replace(/>/g, '&gt;')
                .replace(jsonLine, function (match, pIndent, pKey, pVal, pEnd) {
                    var key = '<span class="key">';
                    var val = '<span class="value">';
                    var str = '<span class="string">';
                    var r = pIndent || '';
                    if (pKey) {
                        r = r + key + pKey.replace(/[": ]/g, '') + '</span>: ';
                    }
                    if (pVal) {
                        r = r + (pVal[0] === '"' ? str : val) + pVal + '</span>';
                    }
                    return r + (pEnd || '');
                });
    }
    out += '</code></pre>';
    return out;
}

