'use strict';
define([
    'angular',
    'angularRoute',
    'bootstrap'
], function (angular) {
    var app = angular.module('gatewayPortalApp.quote.request', ['ngRoute', 'angular-storage']);
    app.config(['$routeProvider', function ($routeProvider, $routeParams) {
        $routeProvider.when('/quote/request', {
            templateUrl: 'js/bd/modules/quote/request.html',
            controller: 'requestController'
        });
            $routeProvider.when('/quote/request/:estimateIdToUse', {
                templateUrl: 'js/bd/modules/quote/request.html',
                controller: 'requestController'
            });
    }]);
    
    var requestController = function ($scope, $http, store, $timeout, $location, QuoteData, $routeParams) {
        animateMsgBox('.loading--gateway');
        $scope.quote = {};
        $scope.quote.freightItems = [];
        $scope.freightItemId = 1;
        $scope.zipCodeNotInList = false;
        $scope.quote.originLocation = {isValidPostalCode: '123'};
        $scope.quote.destinationLocation = {isValidPostalCode: '123'};
        $scope.classCode = {dimensions: {unit: 'inches'}, weight: {unit: 'pounds'}};
        $scope.selectedCommodity = {};
        $scope.showUnitError = [];
        $scope.requestQuoteInProgress = false;
        $scope.pageLoadInProgress = true;
        $scope.estimateIdToUse = $routeParams.estimateIdToUse;
        $scope.minValuesToValidate = {
            'quantity': '0',
            'dimensions': {'length': '0', 'width': '0', 'height': '0'},
            'weight': {'weight': '0'}
        };
        $scope.minValueErrors = {};


        $scope.resetValidZipCode = function (location) {
            location.isValidPostalCode = '123';
        };

        $scope.enableUnitError = function (id, v) {
            $scope.showUnitError[id] = v;
        };
        
        $scope.addCommodity = function () {
            $scope.quote.freightItems.push({freightItemId: $scope.freightItemId, sellUnitPerShipUnit: 1, dimensions: {unitDisplay : 'Inches'}, weight: {unitDisplay: 'Pounds'}});
            $scope.freightItemId++;
        };
        
        $scope.removeFromFreightItems = function (id) {
            $.each($scope.quote.freightItems, function (key, value) {
                if (value.freightItemId == id) {
                    $scope.quote.freightItems.splice(key, 1);
                }
            });
            
        };

        $scope.goToQuotes = function () {
            $('#success-modal').modal('hide');
            $('body').removeClass("modal-open");
            $location.path('/home');
        };
        
        $scope.onInputChange = function (commodity, attr) {
            var elN = attr + "" + commodity.freightItemId;
            $("input[name='" + elN + "']").removeClass('ng-invalid ng-invalid-required');
            delete $scope.minValueErrors[elN];
        };

        $scope.checkForValueError = function (freightItemId) {
            return $scope.minValueErrors['quantity' + freightItemId] || $scope.minValueErrors['length' + freightItemId] || $scope.minValueErrors['width' + freightItemId] || $scope.minValueErrors['height' + freightItemId] || $scope.minValueErrors['weight' + freightItemId];
        };

        $scope.validateInputValues = function () {
            angular.forEach($scope.quote.freightItems, function (freightItem) {
                var freightItemId = freightItem.freightItemId;
                angular.forEach($scope.minValuesToValidate, function (v, k) {
                    if (typeof v === 'number' || typeof v === 'string') {
                        var elN = k + "" + freightItemId;
                        if (freightItem[k]) {
                            $("input[name='" + elN + "']").removeClass('ng-invalid ng-invalid-required')
                        }
                        if (freightItem[k] <= parseFloat(v)) {
                            $scope.minValueErrors[elN] = true;
//                            $scope.requestForQuoteForm[elN].$setValidity('zerovalue', true);
//                            $scope.requestForQuoteForm[elN].$setTouched();
                            $("input[name='" + elN + "']").addClass('ng-invalid ng-invalid-required ng-touched')
                        } else {
                            delete $scope.minValueErrors[elN];
                        }
                    } else {
                        angular.forEach(v, function (sv, sk) {
                            var elN = sk + "" + freightItemId;
                            if (freightItem[k] && freightItem[k][sk]) {
                                $("input[name='" + elN + "']").removeClass('ng-invalid ng-invalid-required')
                            }
                            if (freightItem[k] && freightItem[k][sk] <= parseFloat(sv)) {
                                $scope.minValueErrors[elN] = true;
                                $("input[name='" + elN + "']").addClass('ng-invalid ng-invalid-required ng-touched')
                            } else {
                                delete $scope.minValueErrors[elN];
                            }
                        });
                    }
                });
            });
        };

        $scope.requestQuote = function () {
            $('requestForQuoteForm-msg').hide();
            $scope.validateInputValues();
            if (!$scope.requestForQuoteForm.$invalid) {
                if (Object.keys($scope.minValueErrors).length <= 0) {
                $scope.requestQuoteInProgress = true;
               $http({
                    method: 'POST',
                    url: '/api/v1/requestquote',
                    data: {request: $scope.quote}
                }).success(function (data) {
                        $scope.minValueErrors = {};
                    if (data.success) {
                        if (data.data.quotes_available == true) {
                            $location.path('/quote/accept/' + data.data.estimate_id);
                            QuoteData.setQuoteData(data.data.estimate_id, data.data);
                        } else {
                            $('#success-modal').modal({'keyboard': false, 'show': true, 'backdrop': 'static'});
                        }
                    } else {
                        $scope.requestQuoteInProgress = false;
                        var message = JSON.stringify(data.message);
                        message = message.replace(/\\n/g, "");
                        $('.requestForQuoteForm-error-content').html(message.replace(/"/g, ""));
                        $('.requestForQuoteForm-error').show();
                        animateMsgBox('.requestForQuoteForm-error');
                    }

                }).error(function (data, status) {
                        $scope.minValueErrors = {};
                    if (status == '500') {
                        //message = "Unfortunately the quotation you have requested requires more details to accurately provide you with one. A representative will contact you soon to follow up.";
                        $('#success-modal').modal({'keyboard': false, 'show': true, 'backdrop': 'static'});
                    } else {
                        $scope.requestQuoteInProgress = false;
                        var message = JSON.stringify(data.message);
                        message = message.replace(/\\n/g, "");
                        $('.requestForQuoteForm-error-content').html(message.replace(/"/g, ""));
                        $('.requestForQuoteForm-error').show();
                        animateMsgBox('.requestForQuoteForm-error');
                    }
                });
                }
            } else {
                angular.forEach($scope.requestForQuoteForm.$error, function (field) {
                    angular.forEach(field, function (errorField) {
                        errorField.$setTouched();
                    });
                });
            }
        };
        
        $scope.getAddress = function (location) {
            if (location.postalCode && location.postalCode != null && typeof location.postalCode != 'undefined' && location.postalCode != '') {
                $scope.pageLoadInProgress = true;
                $http({
                    method: 'GET',
                    url: '/api/v1/getAddressInfo',
                    headers: {
                        'zipCode': '' + location.postalCode,
                        'countries': 'US,CA'
                    }
                }).success(function (data) {
                    $scope.pageLoadInProgress = false;
                    if (data.success) {
                        location.isValidPostalCode = location.postalCode;
                        location.country = data.data.country;
                        location.cities = null;
                        if (location.country) {
                        location.state = data.data.state;
                        location.city = data.data.city;
                        if (data.data.cities && data.data.cities.length > 0) {
                            location.cities = data.data.cities;
                            location.city = location.cities[0];
                        }
                    } else {
                            location.isValidPostalCode = null;
                            location.country = "";
                            location.state = "";
                            location.city = "";
                            location.cities = null;
                        }
                    } else {
                        //$scope.zipCodeNotInList = true;
                        location.isValidPostalCode = null;
                        location.country = "";
                        location.state = "";
                        location.city = "";
                        location.cities = null;
                    }
                }).error(function () {
                    $scope.pageLoadInProgress = false;
                    //$scope.zipCodeNotInList = true;
                    location.isValidPostalCode = null;
                    location.country = "";
                    location.state = "";
                    location.city = "";
                    location.cities = null;
                });
            }
        };

        $scope.populateValueOnClassCode = function (commodity, freightItemId) {
            $('.calculateClassCodeForm-msg').hide();
            $scope.selectedFreightItemId = freightItemId;
            $scope.classCode = {dimensions: {unit: 'inches'}, weight: {unit: 'pounds'}};
            if (commodity && commodity.dimensions) {
                $scope.classCode.dimensions.length = angular.copy(commodity.dimensions.length);
                $scope.classCode.dimensions.width = angular.copy(commodity.dimensions.width);
                $scope.classCode.dimensions.height = angular.copy(commodity.dimensions.height);
            }
            if (commodity && commodity.weight) {
                $scope.classCode.weight.weight = angular.copy(commodity.weight.weight);
            }
        };
        
        $scope.useEstimate = function () {
            $.each($scope.quote.freightItems, function (key, value) {
                if (value.freightItemId == $scope.selectedFreightItemId) {
                    if (!value.dimensions) {
                        value.dimensions = {};
                    }
                    if (!value.weight) {
                        value.weight = {};
                    }
                    value.dimensions.length = $scope.classCode.dimensions.length;
                    value.dimensions.width = $scope.classCode.dimensions.width;
                    value.dimensions.height = $scope.classCode.dimensions.height;
                    value.weight.weight = $scope.classCode.weight.weight;
                    value.classCode = $scope.classCode.estimate;
                }
            });
            $('#class-code').modal('toggle');
        };
        
        $scope.calculateClassCode = function () {
            $('calculateClassCodeForm-msg').hide();
            $('.calculateClassCodeForm-error').hide();
            $http({
                method: 'POST',
                url: '/api/v1/classcode',
                data: $scope.classCode
            }).success(function (data) {
                if (data.success) {
                    $scope.classCode.estimate = data.data.classCode;
                } else {
                    var message = JSON.stringify(data.message);
                    message = message.replace(/\\n/g, "");
                    $('.calculateClassCodeForm-error-content').html(message.replace(/"/g, ""));
                    $('.calculateClassCodeForm-error').show();
                }
            }).error(function (data) {
                var message = JSON.stringify(data.message);
                message = message.replace(/\\n/g, "");
                $('.calculateClassCodeForm-error-content').html(message.replace(/"/g, ""));
                $('.calculateClassCodeForm-error').show();
            });
        };
        
        $scope.autoFillClassCode = function (commodity) {
            if(commodity && commodity.dimensions && commodity.dimensions.length != null && commodity.dimensions.length != '' && commodity.dimensions.width != null && commodity.dimensions.width != '' && commodity.dimensions.height != null && commodity.dimensions.height != '' && commodity.weight && commodity.weight.weight != null && commodity.weight.weight != '') {
                $scope.classCode.dimensions.length = angular.copy(commodity.dimensions.length);
                $scope.classCode.dimensions.width = angular.copy(commodity.dimensions.width);
                $scope.classCode.dimensions.height = angular.copy(commodity.dimensions.height);
                $scope.classCode.weight.weight = angular.copy(commodity.weight.weight);
                //var estimate=null;
                $scope.pageLoadInProgress = true;
                $http({
                    method: 'POST',
                    url: '/api/v1/classcode',
                    data: $scope.classCode
                }).success(function (data) {
                    if (data.success && data.data.classCode != null && data.data.classCode != '') {
                        $.each($scope.quote.freightItems, function (key, value) {
                            if (value.freightItemId == commodity.freightItemId) {
                                value.classCode = data.data.classCode;
                            }
                        });
                    }
                    $scope.pageLoadInProgress = false;
                }).error(function (data) {
                    $scope.pageLoadInProgress = false;
                });
            }
        }

        $scope.close = function (className) {
            closeAlertMsgBox(className);
        };

        $timeout(function () {
            $('[data-toggle="popover"]').popover();
        });

        if (typeof $scope.estimateIdToUse != 'undefined' && $scope.estimateIdToUse != null) {
            $timeout(function () {
                QuoteData.fetchQuoteData($scope.estimateIdToUse, function (data) {
                    console.log(data);
                    if (data.success) {
                        var qData = data.data;
                        $scope.quote.originLocation = qData.request.originLocation;
                        $scope.quote.originLocation.location_type = qData.request.originLocation.information.locationType.value;
                        delete $scope.quote.originLocation.information;
                        $scope.quote.originLocation.state = $scope.quote.originLocation.stateFullName;
                        $scope.quote.originLocation.isValidPostalCode = $scope.quote.originLocation.postalCode;
                        delete $scope.quote.originLocation.stateFullName;

                        $scope.quote.destinationLocation = qData.request.destinationLocation;
                        $scope.quote.destinationLocation.location_type = qData.request.destinationLocation.information.locationType.value;
                        delete $scope.quote.destinationLocation.information;
                        $scope.quote.destinationLocation.state = $scope.quote.destinationLocation.stateFullName;
                        $scope.quote.destinationLocation.isValidPostalCode = $scope.quote.destinationLocation.postalCode;
                        delete $scope.quote.destinationLocation.stateFullName;

                        $scope.quote.freightItems = [];
                        angular.forEach(qData.request.freightItems, function (freightItem) {
                            freightItem.freightItemId = $scope.freightItemId;
                            $scope.quote.freightItems.push(freightItem);
                            $scope.freightItemId++;
                        });
                        $timeout(function () {
                            $("form select,input").trigger('change');
                        });
                        console.log($scope.quote.freightItems);
                    } else {
                        $scope.addCommodity();
                    }
                    $scope.pageLoadInProgress = false;
                });
            });
        } else {
            $scope.addCommodity();
            $scope.pageLoadInProgress = false;
        }
        
    };
    app.controller('requestController', requestController);
    app.directive('bsPopover', function(){
        return {
            restrict: 'A',
            link: function(scope, element, attrs){
                $(element).popover({ placement: 'bottom', html: 'true'});
            }
        };
    });
    /*app.directive('zipCodeNotAvailable', function () {
        return {
            require: "ngModel",
            link: function (scope, element, attributes, ngModel) {
                ngModel.$validators.zipCodeNotAvailable = function (modelValue) {
                    console.log(modelValue);
                    if((modelValue!="" && modelValue!=null) || typeof modelValue == 'undefined')
                        return modelValue;
                    else
                        return true;
                };
                scope.$watch("quote.originLocation.isValidPostalCode", function () {
                    ngModel.$validate();
                });
            }
        };
    });*/
});