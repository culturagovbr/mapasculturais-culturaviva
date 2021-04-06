'use strict';

angular
        .module('Configuracao', [])
        .config(ConfiguracaoConfig);


ConfiguracaoConfig.$inject = ['blockUIConfig'];

function ConfiguracaoConfig(blockUIConfig) {
    debugger;
    // Faz o blockUI ignorar algumas requisições
    blockUIConfig.requestFilter = function (config) {
        // Conflito com o typeahead http://stackoverflow.com/a/29606685
        if (config.url.indexOf('/certificador/buscarAgente' === 0)) {
            return false;
        }
    };
}
