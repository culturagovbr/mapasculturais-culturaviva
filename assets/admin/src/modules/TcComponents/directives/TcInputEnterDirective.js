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
    .directive('tcInputEnter', TcInputEnterDirective);

function TcInputEnterDirective() {
    return function (scope, iElement, attrs) {
        iElement.bind("keypress", function (e) {
            if (e.which === 13) {
                scope.$apply(function () {
                    scope.$eval(attrs.tcInputEnter, {e: e});
                });
                e.preventDefault();
            }
        });
    };
}
