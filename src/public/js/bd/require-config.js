/* global require */

'use strict';

var angularCdn = 'https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.12/';
var angularLoadingBarCdn = 'https://cdnjs.cloudflare.com/ajax/libs/angular-loading-bar/0.9.0/';
var auth0Cdn = 'https://cdn.auth0.com/w2/';
var lockCdn = 'https://cdn.auth0.com/js/lock/10.8/';
var bootstrapCdn = 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/';
var jqueryCdn = 'https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.2/';
//var auth0Js = 'https://cdn.auth0.com/w2/auth0-7.6.1.min';
require.config({
    waitSeconds: 0,
    paths: {
        'jQuery': 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min',
        'angular': angularCdn + 'angular.min',
        'angularRoute': angularCdn + 'angular-route.min',
        'angularLock': '/node_modules/angular-lock/dist/angular-lock',
        'auth0': auth0Cdn + "/auth0-7.6.1.min",
        'lockJs': lockCdn + '/lock.min',
        'angular-storage' : '/node_modules/angular-storage/dist/angular-storage',
        'angular-jwt' : '/node_modules/angular-jwt/dist/angular-jwt',
        'angular-loading-bar': angularLoadingBarCdn + 'loading-bar',
        'bootstrap': bootstrapCdn + 'js/bootstrap.min',
        'angularPageslide': '/js/lib/angular-pageslide/angular-pageslide-directive'
    },
    shim: {
        'angular': {'exports': 'angular', 'deps': ['jQuery']},
        'angularRoute': {'deps': ['angular']},
        'auth0': {'exports': 'auth0'},
        'lockJs': {'deps': ['jQuery', 'auth0'], 'exports': 'lockJs'},
        'angularLock': {'deps': ['angular', 'auth0', 'lockJs'], 'exports': 'angularLock'},
        'angular-storage' : {'deps': ['angular'], 'exports': 'angular-storage'},
        'angular-jwt' : {'deps': ['angular'], 'exports': 'angular-jwt'},
        'angular-loading-bar': {
            'deps': ['angular']
        },
        //'jquery': {'exports': 'jquery'},
        'bootstrap': {'deps': ['jQuery']},
        'angularPageslide': {
            'deps': ['angular']
        }
    },
    priority: [
        "angular"
    ],
    deps: [],
    callback: null,
    baseUrl: ''
});

require([
    'angular',
    'js/bd/app'
], function (angular, app) {
    angular.element().ready(
            function () {
                if(localStorage.getItem('token')) {
                    $.ajax({
                        url: '/api/v1/userinfo',
                        type: 'GET',
                        headers: {"Authorization": "Bearer "+localStorage.getItem('token').replace(/"/g, "")},
                        success: function(data) {
                            if(data.success && data.data && data.data.is_activated && data.data.status !== 'inactive') {
                                window.userInfo = data.data;
                                angular.bootstrap(document, ['gatewayPortalApp']);
                            } else if(!data.data.is_activated || data.data.status === 'inactive') {
								localStorage.removeItem('token');
								window.location.href = "/inactive";
							} else {
								localStorage.removeItem('token');
								window.location.href = "/";
							}
                            
                            },
                        error: function(data) {
							localStorage.removeItem('token');
                            window.location.href = "/";
                        }
                    });
                } else {
					localStorage.removeItem('token');
                    window.location.href = "/";
                }
                
            }
    );
});