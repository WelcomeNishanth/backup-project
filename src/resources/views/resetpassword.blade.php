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
                                <form name="userActivationForm" action="/api/v1/resetpwd" method="post">
                                    <input type="hidden" name="activationId" value="<?= $activationId;?>">
                                    <div class="create--account__padding">
                                        <div class="col-md-12">
                                            <h1>Password Reset</h1>
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
                                                                this.form.submit();" ng-disabled="userActivationForm.$invalid">Save</button>
                                        </div>
                                    </div>
                                </form>
                            <?php
                            } else if($linkExpired) {?>
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
        <!-- Including jQuery  -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>
            
            (function () {
                var userJson = <?= $userJson ?>;
                var urlKey = '<?= $activationId ?>';
                var app = angular.module('activationApp', []);

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

                app.controller('validationController', function ($scope, $timeout, $http, $location) {
                    $scope.user = userJson;
                    $scope.resendUrl = function () {
                        console.log($scope.user);
                        $http({
                            url: '/api/v1/resendPwdEmail',
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
