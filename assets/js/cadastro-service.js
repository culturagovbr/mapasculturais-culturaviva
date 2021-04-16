(function(angular){
    'use strict';

    var app = angular.module('culturaviva.services', ['ngResource']);

    app.factory('MapasCulturais', function (){
        if(!window.MapasCulturais) {
            throw new Error('É necessário ter o obj "MapasCulturais" em window');
        }
        return window.MapasCulturais;
    });

    app.factory('googleGeoCoder', function(){
        if(!(google && google.maps && google.maps.Geocoder)) {
            var script = document.createElement('script');
            script.src = '"https://maps.googleapis.com/maps/api/js?libraries=places&signed_in=true&callback=initMap';
            document.body.appendChild(script);
        }
        return new google.maps.Geocoder();
    });

    app.service('cepcoder', ['$q', '$http', function($q, $http){
        this.code = function(cep) {
            var deferred = $q.defer();
            cep = (cep || '').replace(/[^\d]/g, '');

            if(cep.match(/^\d{8,8}$/)){
                return $http.get('http://api.postmon.com.br/v1/cep/'+cep);
            }
            deferred.reject('Formato inválido para cep');
            return deferred.promise;
        };
    }]);

    app.service('cidadecoder', ['$q', '$http', function($q, $http){
        this.code = function(cidade, pais) {
            var deferred = $q.defer();
            cidade = cidade.replace(/ /g, '+');
            pais = pais.replace(/ /g, '+');

            if(cidade.indexOf(' ') == -1){
                return $http.get('http://nominatim.openstreetmap.org/search?city=' + cidade + '&country=' + pais + '&format=json');
            }
            deferred.reject('Formato inválido para cidade');
            return deferred.promise;
        };
    }]);

    //https://developers.google.com/maps/documentation/javascript/examples/geocoding-simple
    app.service('geocoder', ['$q', 'googleGeoCoder', function($q, googleGeoCoder){
        this._coder = googleGeoCoder;

        this.code = function(address) {
            var deferred = $q.defer();

            if(!address){
                deferred.reject('Não foi fornecido endereço.');
                return deferred.promise;
            }

            var cepMatch = address.match(/^\s*(\d\d\d\d\d)-?(\d\d\d)\s*$/);
            if(cepMatch) {
                address = cepMatch[1]+'-'+cepMatch[2]+', Brasil';
            }

            this._coder.geocode({'address': address}, function(results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    var obj = results[0];

                    var point = {
                        'lng': obj.geometry.location.lng(),
                        'lat': obj.geometry.location.lat(),
                        'message': obj.formatted_address || ''
                    };

                    deferred.resolve(point);
                } else {
                    deferred.reject('Endereço não encontrado');
                }
            });

            return deferred.promise;
        };
    }]);

    app.factory('Entity', ['$resource',
        function($resource){
            return $resource('/api/agent/findOne?id=EQ(:id)', {'id': '@id'}, {
                patch: {
                    url: '/agente/:id/',
                    method: 'PATCH'
                }
            });
        }
    ]);

})(angular);
