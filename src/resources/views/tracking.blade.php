<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie10 lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie10  lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie10  lt-ie9" lang="en"> <![endif]-->
<!--[if IE 9]>         <html class="no-js lt-ie10" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Gateway Supply Chain</title>

        <meta name="description" content="Gateway">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Adding Favicon-->
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <link rel="icon" href="favicon.ico" type="image/x-icon">

        <!-- Adding Stylesheets for Bootstrap,  Google Font, Font Awesome and our Custom Theme -->
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" >
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Lato:400,300,300italic,400italic,700,700italic">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/angular-loading-bar/0.9.0/loading-bar.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" type="text/css" href="<?= secure_url('css/main.css') ?>">
        <style>
            [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
                display: none !important;
            }
        </style>
    </head>
    @include('angular-tracking-page')
    
    <!-- Including jQuery  -->
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
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-loading-bar/0.9.0/loading-bar.js"></script>
        <script>
            var app = angular.module('trackingApp', ['auth0.lock', 'angular-storage', 'angular-jwt', 'ui.router', 'angular-loading-bar']);
            app.config(function ($provide, lockProvider) {
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
            app.run(function (authManager, store, lock, jwtHelper, $rootScope, $q, $timeout) {
                lock.interceptHash();
                lock.on('authenticated', function (authResult) {
                    $('.loading--gateway').show();

                    store.set('token', authResult.idToken);
                    authManager.authenticate();
                    window.location.href = "/home";

                });
                lock.on('authorization_error', function (error) {
                    lock.show({
                        flashMessage: {
                            type: 'error',
                            text: error.error_description
                        }
                    });
                });
                lock.on('unrecoverable_error', function (error) {
                    lock.show({
                        flashMessage: {
                            type: 'error',
                            text: error.error_description
                        }
                    });
                });
            });
            app.controller('trackingController', function ($scope, $http, lock) {
                $scope.trackId = '<?= $trackId ?>';
                $scope.dataLoadInProgress = false;
                $scope.deliveryStatus = {PENDING_PICKUP: 1, IN_TRANSIT: 2, DELIVERED: 3};
                $scope.login = function () {
                    lock.show();
                }
                
                $scope.getStatusCode = function (status) {
                    var mapKey='PENDING_PICKUP';
                    if(status != null && status != '' && typeof status != 'undefined') {
                        mapKey=status.replace(/ /g, '_');
                    }
                    return $scope.deliveryStatus[mapKey];
                }

                $scope.getDeliveryDetails = function () {
                    $('.alert-danger').hide();
                    $scope.dataLoadInProgress = true;
                    $http({
                        url: '/api/v1/trackOrder',
                        method: 'GET',
                        headers: {
                            'deliveryid': $scope.trackId
                        }
                    }).success(function (response) {
                        if (response.success) {
                            $scope.delivery = response.data.delivery;
                        } else {
                            $scope.delivery = null;
                            $('.alert-danger').show();
                        }
                        $scope.dataLoadInProgress = false;
                    }).error(function () {
                        $scope.delivery = null;
                        $('.alert-danger').show();
                        $scope.dataLoadInProgress = false;
                    });
                };
                
                $scope.changeMinHeight = function () {
                    $('#trackingMainId').css('min-height', ($(window).height() - 250) + 'px');
                };
                
                $scope.changeMinHeight();

                if (typeof $scope.trackId != 'undefined' && $scope.trackId != null && $scope.trackId != '') {
                    $scope.getDeliveryDetails();
                }

            });
        </script>
        @include('landingfooter')
</html>
