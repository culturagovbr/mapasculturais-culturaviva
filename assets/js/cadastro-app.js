(function (angular) {
    'use strict';

    var app = angular.module('culturaviva', [
        'culturaviva.controllers',
        'culturaviva.services',
        'culturaviva.directives',
       //'Notifications',
        'ngFileUpload',
        'ngMessages',
        'ui.date',
        'ui.mask',
        'ngDialog',
        'ja.qr'
    ]);

    app.config(['$httpProvider', '$resourceProvider', '$locationProvider',
    function ($httpProvider, $resourceProvider, $locationProvider) {
            $httpProvider.defaults.headers.delete = {};
            $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
            $httpProvider.defaults.headers.patch['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
            $httpProvider.defaults.headers.delete['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
            $httpProvider.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
            $httpProvider.defaults.headers.patch['X-Requested-With'] = 'XMLHttpRequest';
            $httpProvider.defaults.headers.delete['X-Requested-With'] = 'XMLHttpRequest';
            $httpProvider.defaults.transformRequest = function (data) {
                var result = angular.isObject(data) && String(data) !== '[object File]' ? $.param(data) : data;

                return result;
            };
            $resourceProvider.defaults.stripTrailingSlashes = false;
        }]);

        app.config(["ngDialogProvider", function (ngDialogProvider) {
            ngDialogProvider.setDefaults({
                className: "ngdialog-theme-default",
                plain: false,
                showClose: true,
                closeByDocument: true,
                closeByEscape: true,
            });
        }]);
})(angular);