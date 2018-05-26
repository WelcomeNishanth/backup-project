<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <title>Gateway Supply Chain Services</title>
        <!-- Favicon -->
        <link rel="icon" href="<?= secure_url('img/favicon.gif') ?>">
        <!-- Bootstrap -->
        <link rel="stylesheet" href="<?= secure_url('css/bootstrap.min.css') ?>">
        <!-- Main Style Sheet -->
        <link rel="stylesheet" type='text/css' href="<?= secure_url('css/gateway.min.css') ?>">
        <!-- Vendor Styles Sheet -->
        <link rel="stylesheet" type='text/css' href="<?= secure_url('vendors/intlTelInput.css') ?>">
        <link rel="stylesheet" type='text/css' href="<?= secure_url('vendors/styling.css') ?>">
        <!-- FontAwesome -->
        <link rel="stylesheet" type='text/css' href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Fonts -->
        <link rel="stylesheet" type='text/css' href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i">
    </head>
    <body class="bg-registration" ng-app="activationApp" ng-controller="validationController">

        <div class="loading--gateway" style="display: block;">
            <div class="icon-sent"></div>
        </div>
        
        <!-- Modal Starts Here -->
        <div class="modal fade gateway--modal" id="success" data-backdrop="static"  data-keyboard="false">
          <div class="modal-dialog gateway--content__modal" role="document">
            <div class="modal-content">
              <div class="modal-header header">
                <h1 class="text-center">Connection Timed Out</h1>
                
              </div>
              <div class="modal-body" id="login-failed-msg">
                
              </div>
              <div class="modal-footer">
                  <a class="gateway--button__medium bdblue bdblue" href="/">OK</a>
              </div>
            </div>
          </div>
        </div>
        <!-- Modal Ends Here -->
        
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.12/angular.min.js"></script>
        <script src="https://cdn.auth0.com/w2/auth0-7.6.1.min.js"></script>
        <script src="<?= secure_url('node_modules/angular-lock/dist/angular-lock.js') ?>"></script>
        <script type="text/javascript" src="https://cdn.auth0.com/js/lock/10.8/lock.min.js"></script>
        <!-- <script src="https://cdn.auth0.com/js/lock/10.12.1/lock.min.js"></script> -->
        <script src="<?= secure_url('node_modules/angular-storage/dist/angular-storage.js') ?>"></script>
        <script src="<?= secure_url('node_modules/angular-jwt/dist/angular-jwt.js') ?>"></script>
        <script src="<?= secure_url('node_modules/angular-ui-router/release/angular-ui-router.js') ?>"></script>
        <!-- Including jQuery  -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

        <!-- Including jQuery Cycle  -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.cycle2/2.1.6/jquery.cycle2.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-loading-bar/0.9.0/loading-bar.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/js/bootstrap.min.js"></script>
        <script>

            (function () {
                var app = angular.module('activationApp', ['auth0.lock', 'angular-storage', 'angular-jwt', 'ui.router', 'angular-loading-bar']);
                app.config(function($provide, lockProvider) {
                    lockProvider.init({
                        domain: '<?= $authDomain ?>',
                        clientID: '<?= $authId; ?>',
                        options: {
                            theme: {
                                logo: 'https://<?=$cdnlocation?>/img/logo-normal-gw.png'
                            },
                            allowSignUp: false,
                            languageDictionary: {
                                title: " "
                            },
                            forgotPasswordLink: '<?= secure_url("/") ?>/account/forgot',
                            auth: {
                                redirect: false,
                                redirectUrl: location.origin + '/login',
                                responseType: 'token',
                            }
                        }

                    });
                });
                app.run(function(authManager, store, lock, jwtHelper, $rootScope, $q, $timeout) {
                    lock.interceptHash();
                    lock.on('authenticated', function (authResult) {
                        $('.loading--gateway').show();

                        store.set('token', authResult.idToken);
                        authManager.authenticate();
                        window.location.href = "/home";
                        
                       
                    });
                    
                    lock.on('authorization_error', function(error) {
                        $('#login-failed-msg').html('<p> Please try again after sometime. </p>');
                        $('.loading--gateway').hide();
                        $('#success').modal('show');
                    });
                    
                    lock.on('unrecoverable_error', function(error) {
                        $('#login-failed-msg').html('<p> Please try again after sometime. </p>');
                        $('.loading--gateway').hide();
                        $('#success').modal('show');
                    });
                });



                app.controller('validationController', function ($scope, $timeout, $http, $location, lock) {

                });

            }());
        </script>
    </body>
</html>
