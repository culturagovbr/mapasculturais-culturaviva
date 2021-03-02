'use strict';

angular.module('AppAdmin')
        .factory('Entity', EntityFactory);

EntityFactory.$inject = ['$http'];

function EntityFactory($http) {
    return {
        get: function (params) {
            var id = params.id;
            delete params.id;
            return $http.get('/api/agent/findOne?id=EQ(' + id + ')', {
                params: params
            });
        }
    };
}