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
        <!-- FontAwesome -->
        <link rel="stylesheet" type='text/css' href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Fonts -->
        <link rel="stylesheet" type='text/css' href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i">
    </head>
    <body class="bg-registration">

        <!-- Main Starts Herer -->
        <main class="bd-gateway" ng-app="activationApp">
            <div class="gateway--wrapper__full gateway--registration" ng-controller="validationController">
                <div class="container-fluid">

                    <div class="row">
                        <div class="mobile--logo__register"></div>

                        <!--About you Section Starts-->
                        <div class="create--account">
                            <?php if (!$hasError) { ?>
                                <form name="userActivationForm" action="/api/v1/activateUser/<?= $activationId ?>" method="post">
                                    <div class="create--account__padding">
                                        <div class="col-md-12">
                                            <h1>Create Account</h1>
                                            <div class="form-group mar--top__th min-height">
                                                <i class="form--icon fa fa-envelope fa-6" aria-hidden="true"></i>
                                                <input type="email" class="form-control email" id="exampleInputEmail1" placeholder="Email" ng-model="user.email" ng-disabled="true">
                                                <span class="errorcontainer"><i class="fa fa-exclamation-triangle fa-1" aria-hidden="true"></i>Error Message</span>
                                            </div>
                                            <div class="form-group min-height">
                                                <i class="form--icon fa fa-lock fa-1" aria-hidden="true"></i>
                                                <input id="password" type="password" class="form-control create_password angular-error" name="password" placeholder="Create Password" ng-model="user.password" ng-minlength="8" check-pattern ng-maxlength="15" autocomplete="off" required>
                                                <span class="errorcontainer" ng-show="userActivationForm.password.$touched && userActivationForm.password.$error.required"><i class="fa fa-exclamation-triangle fa-1" aria-hidden="true"></i>Password is required</span>
                                                <span class="errorcontainer" ng-show="userActivationForm.password.$touched && userActivationForm.password.$error.minlength"><i class="fa fa-exclamation-triangle fa-1" aria-hidden="true"></i>Minimum 8 characters is required</span>
                                                <span class="errorcontainer" ng-show="userActivationForm.password.$touched && userActivationForm.password.$error.maxlength"><i class="fa fa-exclamation-triangle fa-1" aria-hidden="true"></i>Maximum character limit is 15</span>
                                                <span class="errorcontainer" ng-show="userActivationForm.password.$touched && userActivationForm.password.$error.checkPattern"><i class="fa fa-exclamation-triangle fa-1" aria-hidden="true"></i>Password should contain letters or numbers with at least 1 special character ($,#,@,!,%,&,*,-,_)</span>
                                            </div>
                                            <div class="form-group min-height">
                                                <i class="form--icon fa fa-lock fa-1" aria-hidden="true"></i>
                                                <input id="password" type="password" class="form-control confirm_password angular-error" name="confirmPassword" placeholder="Confirm Password" ng-model="user.confirmPassword" compare-to="user.password" autocomplete="off">
                                                <span class="errorcontainer" ng-show="userActivationForm.confirmPassword.$touched && userActivationForm.confirmPassword.$error.compareTo"><i class="fa fa-exclamation-triangle fa-1" aria-hidden="true"></i>Passwords don't match</span>
                                            </div>
                                            <button class="gateway--button__large bdblue" data-toggle="modal" data-target="#" onclick="this.disabled = true;
                                                                this.form.submit();" ng-disabled="userActivationForm.$invalid">Create Account</button>
                                        </div>
                                    </div>
                                </form>
                            <div class="create--account__footer">
                                <p>Already have an account? <a href="<?= secure_url('/') ?>">Sign in here</a></p>
                            </div>
                            <?php
                            } else if($authUserExist) {
                            ?>
                            <div class="create--account__padding">
                                <div class="col-md-12 error--one">
                                    <!--<div class="glyphicon glyphicon-remove-circle error--message__icon"></div>-->
                                    <h3 class="error--message">You already have an account with Gateway Supply Chain.<br/> Email: <?= $email; ?> <br/><br/> Please sign in with the same credentials.</h3>
                                    <button class="gateway--button__large bdblue" ng-click="login()">Sign In</button>
                                </div>
                            </div>
                            <?php
                            } else if($userExist) {
                            ?>
                            <div class="create--account__padding">
                                <div class="col-md-12 error--one">
                                    <!--<div class="glyphicon glyphicon-remove-circle error--message__icon"></div>-->
                                    <h3 class="error--message">Your account is already active. Please click below to Sign-In.</h3>
                                    <button class="gateway--button__large bdblue" ng-click="login()">Sign In</button>
                                </div>
                            </div>
                            <?php } else if($linkExpired) {?>
                            <div class="create--account__padding">
                                <div class="col-md-12 error--one resend-url">
                                    <!--<div class="glyphicon glyphicon-remove-circle error--message__icon"></div>-->
                                    <h3 class="error--message">This link has expired. Please click below to receive an email with new link.</h3>
                                    <button class="gateway--button__large bdblue" data-toggle="modal" data-target="#" ng-click="resendUrl()">Generate New Link</button>
                                </div>
                            </div>
                            <?php } else if($hasError) {?>
                            <div class="create--account__padding">
                                <div class="col-md-12 error--two">
                                    <!--<div class="glyphicon glyphicon-remove-circle error--message__icon"></div>-->
                                    <h3 class="error--message"><?= $errorMessage; ?></h3>
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
                var userJson = <?= $userJson ?>;
                var urlKey = '<?= $activationId ?>';
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
                            forgotPasswordLink: location.origin + '/account/forgot',
                            auth: {
                                redirectUrl: location.origin + '/login',
                                responseType: 'token',
                            },
                            prefill: { email: '<?= $email; ?>'},
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

                var compareTo = function () {
                    return {
                        require: "ngModel",
                        scope: {
                            otherModelValue: "=compareTo"
                        },
                        link: function (scope, element, attributes, ngModel) {

                            ngModel.$validators.compareTo = function (modelValue) {
                                return modelValue == scope.otherModelValue;
                            };

                            scope.$watch("otherModelValue", function () {
                                ngModel.$validate();
                            });
                        }
                    };
                };

                var checkPattern = function () {
                    return {
                        require: "ngModel",
                        link: function (scope, element, attributes, ngModel) {
                            ngModel.$validators.checkPattern = function (modelValue) {
                                var pattern = new RegExp("^[a-zA-Z0-9!@#$%^&*\-_]*$");
                                var pattern1 = new RegExp("(?=.*[_!@#$%^&*\-])");
                                if (pattern.test(modelValue) && pattern1.test(modelValue))
                                    return true;
                                else
                                    return false;
                                //return (pattern.test(modelValue) && pattern1.test(modelValue));
                            };
                            scope.$watch(attributes.ngModel, function () {
                                ngModel.$validate();
                            });
                        }
                    };
                };

                app.directive("compareTo", compareTo);
                app.directive("checkPattern", checkPattern);

                app.controller('validationController', function ($scope, $timeout, $http, $location, lock) {
                    $scope.user = userJson;
                    $scope.resendUrl = function () {
                        console.log($scope.user);
                        $http({
                            url: '/api/v1/resendActivation',
                            method: 'POST',
                            headers: {'activationToken' : urlKey}
                        }).success(function (data) {
                            if (data.success) {
                                $('.resend-url').html('<h2 class="error--message">The new activation mail is sent to your registered mail address. Please check it and click on the activation link.</h2>')
                            } else {
                                var message = JSON.stringify(data.message);
                                message = message.replace(/\\n/g, "");
                                $('.resend-url').html(message.replace(/"/g, ""));
                            }

                        }).error(function(data){
                            var message = JSON.stringify(data.message);
                            message = message.replace(/\\n/g, "");
                            $('.resend-url').html(message.replace(/"/g, ""));
                        });
                    }
                    $scope.login = function() {
                        lock.show();
                    }
                    //                $scope.closeMessage = function () {
                    //                    $('.alert').hide();
                    //                }
                    //                $timeout(function(){
                    //                    $('.bds--error').css("display", "block");
                    //                });
                });

            }());
        </script>
    </body>
</html>
