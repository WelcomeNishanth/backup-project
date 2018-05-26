(function () {
    var app = angular.module('registration', ['auth0.lock', 'angular-storage', 'angular-jwt', 'ui.router', 'angular-loading-bar', 'validation.match']);
    app.config(function($provide, lockProvider) {
        lockProvider.init({
            domain: window.authDomain,
            clientID: window.authId,
            options: {
                theme: {
                    logo: window.cdnLocation+'img/logo-normal-gw.png',
                    primaryColor: '#167066'
                },
                allowSignUp: false,
                languageDictionary: {
                    title: " "
                },
                forgotPasswordLink: location.origin+'/account/forgot',
                auth: {
                    redirectUrl: location.origin + '/login',
                    responseType: 'token',
                },
                allowedConnections: [window.allowedConnection]
            }
            
        });
    });
    app.run(function(authManager, store, lock, jwtHelper, $rootScope, $q, $timeout) {
        lock.interceptHash();
        var token = store.get('token');
        if(token) {
            if (!jwtHelper.isTokenExpired(token)) {
                goToHomePage();
            } else {
                $('.landing-content').show();
                $('.loading--gateway').hide();
            }
        } else {
            $('.landing-content').show();
            $('.loading--gateway').hide();
        }
        lock.on('authenticated', function (authResult) {
            $('.loading--gateway').show();

            store.set('token', authResult.idToken);
            authManager.authenticate();
            /*lock.getUserInfo(authResult.accessToken, function(error, profile) {
                if (error) {
                  console.log(error);
                  return;
                }
                store.set('profile', JSON.stringify(profile));
                
            });*/
            goToHomePage();
            
        });
        lock.on('authorization_error', function(error) {
            lock.show({
                flashMessage: {
                    type: 'error',
                    text: error.error_description
                }
            });
        });
        lock.on('unrecoverable_error', function(error) {
            lock.show({
                flashMessage: {
                    type: 'error',
                    text: error.error_description
                }
            });
        });
    });
    app.controller('registrationController', function ($scope, $http, $timeout, lock) {
        $scope.submitRegistration = function() {
            $('.successmsg').hide();
            $('.form-mainerror').hide();
            $http({
                url: 'api/v1/signup',
                method: 'POST',
                data: $scope.person
            }).success(function(data) {
                if(data.success) {
                    $('.successmsg').show();
                    $('.sign-up-button').hide();
                    $('#survey').modal('toggle');
                } else {
		    if(data.message)
	                    var message = JSON.stringify(data.message);
		    else
	                    var message = JSON.stringify(data);

                    $('.form-mainerror').html(message.replace(/"/g, ""));
                    $('.form-mainerror').show();
                    var body = $("html, body");
                    body.animate({scrollTop: $('.form-mainerror').offset().top - 50}, '500', function () {
                    });
                }
            })
        }
        $scope.openSurvey = function () {
            $('#survey').modal('toggle');
            window.open("https://www.surveygizmo.com/s3/3551101/Gateway-Survey");
        }
		$timeout(function () {
		   $('.form-error').css("display", "block");
	   });
           
        $scope.login = function() {
            lock.show();
        }
    });

}());

var goToHomePage = function() {
    window.location.href = "/home";
}
