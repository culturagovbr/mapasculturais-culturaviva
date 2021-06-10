(function (angular) {
    'use strict';

    var app = angular.module('Certificacao', ['ngResource']);

    app.factory('MapasCulturais', function () {
        if (!window.MapasCulturais) {
            throw new Error('É necessário ter o obj "MapasCulturais" em window');
        }
        return window.MapasCulturais;
    });

})(angular);