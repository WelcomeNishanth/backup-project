<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <title>Gateway Supply Chain</title>
        <!-- Favicon -->
        <link rel="icon" href="<?= secure_url('img/favicon.gif') ?>">
        <!-- Bootstrap -->
        <link rel="stylesheet" href="<?= secure_url('css/bootstrap.min.css') ?>">
        <!-- Main Style Sheet -->
        <link rel="stylesheet" type='text/css' href="<?= secure_url('css/gateway.min.css') ?>">
        <!-- Vendor Styles Sheet -->
        <link rel="stylesheet" type='text/css' href="<?= secure_url('vendors/intlTelInput.css') ?>">
        <link rel="stylesheet" type='text/css' href="<?= secure_url('vendors/styling.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?= secure_url('css/main.css') ?>">
        <!-- FontAwesome -->
        <link rel="stylesheet" type='text/css' href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Fonts -->
        <link rel="stylesheet" type='text/css' href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i">
    </head>
    <body class="bg-registration">
        <div class="loading--gateway" style="display: block;">
            <div class="icon-sent"></div>
        </div>
        <!-- Main Starts Herer -->
        <main class="bd-gateway" ng-app="signInApp">
            <div class="gateway--wrapper__full gateway--registration" ng-controller="signInController">
                <div class="container-fluid">

                    <div class="row">
                        <div class="mobile--logo__register"></div>

                        <!--About you Section Starts-->
                        <div class="create--account">

                            <?php if($hasError) {?>
                            <div class="create--account__padding">
                                <div class="col-md-12 error--two">
                                    <!--<div class="glyphicon glyphicon-remove-circle error--message__icon"></div>-->

                                    <h3 class="error--message"><?= $errorMessage; ?></h3>
                                </div>
                            </div>
                            <?php } else { ?>
                            <div class="create--account__padding">
                                <div class="col-md-12 error--one resend-url">
                                    <!--<div class="glyphicon glyphicon-remove-circle error--message__icon"></div>-->
                                    <h2 class="error--message">You password is successfully reset. Please sign-in with your new password.</h2>
                                    <button class="gateway--button__large bdblue" ng-click="login()">Sign In</button>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <!--About you Section Ends-->

                    </div>

                </div>
            </div>
        </main>
        <!-- Main Ends Here -->
        <!-- Footer Starts Here -->
        @include('footer')
        <!-- Footer Ends Here -->
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
        <?php 
            $allowedConnection = getenv("AUTH0_GATEWAY_CONNECTION_NAME");
        ?>
        <script>
            (function () {
                $('.loading--gateway').hide();
            }());

            var app = angular.module('signInApp', ['auth0.lock', 'angular-storage', 'angular-jwt', 'ui.router', 'angular-loading-bar']);
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
                        forgotPasswordLink: location.origin + '/account/forgot',
                        auth: {
                            redirectUrl: location.origin + '/login',
                            responseType: 'token',
                        },
                        allowedConnections: ['<?= $allowedConnection; ?>']
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
            app.controller('signInController', function ($scope, $http, $timeout, lock) {

                $scope.login = function() {
                    lock.show();
                }
            });
        </script>

    </body>
</html>
