'use strict';
define([
    'angular',
    'angularRoute'
], function (angular) {
    var app = angular.module('gatewayPortalApp.quote.accept', ['ngRoute', 'angular-storage']);
    app.config(['$routeProvider', function ($routeProvider, $routeParams) {
        $routeProvider.when('/quote/accept/:estId', {
            templateUrl: 'js/bd/modules/quote/accept.html',
            controller: 'acceptController'
        });
    }]);
    
    var acceptController = function ($scope, $http, store, $timeout, $routeParams, $location, QuoteData, $rootScope) {
        $scope.checkout = {};
		$scope.countryValues = {US: 'United States', CA: 'Canada'};
        $scope.acceptQuoteInProgress = false;
        $scope.dataLoadInProgress = true;
        animateMsgBox('.gateway--modal');
        $scope.getQuote = function () {
        $http({
            method: 'GET',
            url: '/api/v1/quote',
            headers: {
                    'estimateid': $routeParams.estId
            }
            }).success(function (data) {
                if (data.success) {
                    $scope.processQuoteData(data.data);
                } else {
                    $scope.backToHome();
                }
            }).error(function (data) {
                $scope.backToHome();
            });

            console.log($scope.checkout);
        };

        $scope.processQuoteData = function (data) {
            $scope.dataLoadInProgress = false;
            $scope.quote = data;
                    $scope.deliveryOptions = {};
                    angular.forEach($scope.quote.options, function (o, i) {
                        $scope.deliveryOptions[o.type] = o;
                    });
                    
                    if ($scope.quote.options.length <= 0) {
                        $scope.backToHome();
                    }
                    $scope.selectedDeliveryType = $scope.quote.options[0].type;
                    
//                    $scope.checkout.accepted_quote_id = $scope.deliveryOptions[$scope.selectedDeliveryType]['quote_id'];
                    $scope.checkout.quote_id = $scope.deliveryOptions[$scope.selectedDeliveryType]['quote_id'];
                    $scope.checkout.rateQuoted = $scope.deliveryOptions[$scope.selectedDeliveryType]['quote_rate'];
                    $scope.checkout.quote_type = $scope.deliveryOptions[$scope.selectedDeliveryType]['name'];
                    $scope.checkout.originLocation = $scope.quote.request.originLocation;
                    
                    if (typeof $scope.quote.request.originLocation.information.locationType !== 'undefined' && 
                            $scope.quote.request.originLocation.information.locationType !== null) {
                        $scope.checkout.originLocation.locationType = $scope.quote.request.originLocation.information.locationType.displayName;
            } else
                    {
                        $scope.checkout.originLocation.locationType = '';
                    }
                    
                    $scope.checkout.destinationLocation = $scope.quote.request.destinationLocation;
                    
                    if (typeof $scope.quote.request.destinationLocation.information.locationType !== 'undefined' && 
                            $scope.quote.request.destinationLocation.information.locationType !== null) {
                        $scope.checkout.destinationLocation.locationType = $scope.quote.request.destinationLocation.information.locationType.displayName;
            } else {
                        $scope.checkout.destinationLocation.locationType = '';
                    }
                    
                    $scope.checkout.originLocation.contact = {};
                    $scope.checkout.originLocation.contact.countryCode = '+1';
                    $scope.checkout.destinationLocation.contact = {};
                    $scope.checkout.destinationLocation.contact.countryCode = '+1';
        };
        
        $scope.selectDeliveryType = function (t) {
            $scope.selectedDeliveryType = t;
//            $scope.checkout.accepted_quote_id = $scope.deliveryOptions[t]['quote_id'];
            $scope.checkout.quote_id = $scope.deliveryOptions[t]['quote_id'];
            $scope.checkout.quote_type = $scope.deliveryOptions[t]['name'];
            $scope.checkout.rateQuoted = $scope.deliveryOptions[t]['quote_rate'];

        };
        
        var quoteData = QuoteData.getQuoteData($routeParams.estId);
        if (typeof quoteData !== 'undefined' && quoteData !== null) {
            $scope.processQuoteData(quoteData);
//            QuoteData.setQuoteData($routeParams.estId, null);
        } else {
    $scope.getQuote();
        }
    
        $scope.confirmQuote = function () {
        $('acceptQuoteForm-msg').hide();

            if (!$scope.acceptQuoteForm.$invalid) {
                $scope.acceptQuoteInProgress = true;
            $http({
                method: 'POST',
                url: '/api/v1/confirmorder',
                data: {estimate_id: $routeParams.estId, accepted_quote_id: $scope.checkout.quote_id, accepted_quote_type: $scope.checkout.quote_type, request: $scope.checkout}
                }).success(function (data) {
                    if (data.success) {
                        //@TODO: Go to Delivery orders page
                        $location.path('/delivery/orders');
                    //$scope.backToHome();
                } else {
                        $scope.acceptQuoteInProgress = false;
                    var message = JSON.stringify(data.message);
                    message = message.replace(/\\n/g, "");
                    $('.acceptQuoteForm-error-content').html(message.replace(/"/g, ""));
                    $('.acceptQuoteForm-error').show();
                    animateMsgBox('.acceptQuoteForm-error');
                }
                }).error(function (data) {
                    $scope.acceptQuoteInProgress = false;
                    var message = JSON.stringify(data.message);
                    message = message.replace(/\\n/g, "");
                    $('.acceptQuoteForm-error-content').html(message.replace(/"/g, ""));
                    $('.acceptQuoteForm-error').show();
                    animateMsgBox('.acceptQuoteForm-error');
            });
        } else {
            angular.forEach($scope.acceptQuoteForm.$error, function (field) {
                    angular.forEach(field, function (errorField) {
                    errorField.$setTouched();
                });
            });
        }
        };
        $scope.close = function (className) {
        closeAlertMsgBox(className);
        };
        
        $scope.backToHome = function () {
            if($rootScope.quotePageFilter != null && $rootScope.quotePageFilter != '') {
                $rootScope.backToQoute = true;
            }
            $location.path('/home');
        };
    //console.log($routeParams.estId);
        
    };
    app.controller('acceptController', acceptController);
});
