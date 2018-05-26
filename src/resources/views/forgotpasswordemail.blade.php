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
        <main class="bd-gateway" ng-app="forgotPassApp">
            <div class="gateway--wrapper__full gateway--registration" ng-controller="forgotPassController">
                <div class="container-fluid">

                    <div class="row">
                        <div class="mobile--logo__register"></div>

                        <!--About you Section Starts-->
                        <div class="create--account  reset--password__container">
                            <form name="resetPasswordForm" >
                                <div class="create--account__padding">
                                    <div class="col-md-12">
                                        <h1><span>Password Reset</span></h1>
                                        <div class="alert alert-success resetPasswordForm-msg resetPasswordForm-success">
                                            <a href="" class="close" aria-label="close" ng-click="close('resetPasswordForm-success')"> ×</a>
                                            The reset password link has been sent to your email. Please click on the link to proceed.
                                        </div>
                                        <div class="alert alert-dismissible alert-danger resetPasswordForm-msg resetPasswordForm-error" role="alert">
                                            <button type="button" class="close" aria-label="Close" ng-click="close('resetPasswordForm-error')">
                                              <span aria-hidden="true">×</span>
                                            </button>
                                            <div class="resetPasswordForm-error-content"></div>
                                        </div>
                                        <h2>Please enter your registered email address to receive the reset password link.</h2>
                                        <div class="form-group">
                                            <i class="form--icon fa fa-envelope fa-6" aria-hidden="true"></i>
                                            <input type="email" class="form-control email" id="exampleInputEmail1" placeholder="Enter Email Address" required ng-model="user.email">
                                            <span class="errorcontainer"><i class="fa fa-exclamation-triangle fa-1" aria-hidden="true"></i>Please provide a valid email</span>
                                        </div>
                                        <button class="gateway--button__large bdblue" data-toggle="modal" data-target="#" ng-click="resendUrl()" ng-disabled="resetPasswordForm.$invalid || resetPasswordForm.$pristine">Reset Password</button>
                                    </div>
                                </div>
                            </form>
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
                    var app = angular.module('forgotPassApp', []);
                    
                    app.controller('forgotPassController', function ($scope, $timeout, $http, $location) {
                        $scope.resendUrl = function () {
                            $('.resetPasswordForm-msg').hide();
                            $http({
                                url: '/api/v1/forgotPwd',
                                method: 'POST',
                                headers: {'email' : $scope.user.email}
                            }).success(function (data) {
                                if (data.success) {
                                    $scope.resetPasswordForm.$setPristine();
                                    $('.resetPasswordForm-success').show();
                                    animateMsgBox('.resetPasswordForm-success');
                                } else {
                                    var message = JSON.stringify(data.message);
                                    message = message.replace(/\\n/g, "");
                                    $('.resetPasswordForm-error-content').html(message.replace(/"/g, ""));
                                    $('.resetPasswordForm-error').show();
                                    animateMsgBox('.resetPasswordForm-error');
                                }

                            }).error(function (data) {
                                var message = JSON.stringify(data.message);
                                message = message.replace(/\\n/g, "");
                                $('.resetPasswordForm-error-content').html(message.replace(/"/g, ""));
                                $('.resetPasswordForm-error').show();
                                animateMsgBox('.resetPasswordForm-error');
                            });
                        }
                        $scope.close = function(className) {
                            $('.'+className).hide();
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
