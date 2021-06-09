'use strict';

angular
    .module('Certificacao', []);

var app = angular.module('culturaviva.services', ['ngResource']);

app.factory('MapasCulturais', function () {
    if (!window.MapasCulturais) {
        throw new Error('É necessário ter o obj "MapasCulturais" em window');
    }
    return window.MapasCulturais;
});