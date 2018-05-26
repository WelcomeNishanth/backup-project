'use strict';
define([
    'angular',
    'angularRoute'
], function (angular) {
    var app = angular.module('gatewayPortalApp.home', ['ngRoute']);
    app.config(['$routeProvider', function ($routeProvider) {
        $routeProvider.when('/home', {
            templateUrl: 'js/bd/modules/home/home.html',
            controller: 'homeController'
        });
    }]);
    
    var homeController = function ($scope, $http, $location, store, QuoteData, $rootScope) {

		$scope.countryValues = {US: 'United States', CA: 'Canada'};
		$scope.dataLoadInProgress = true;
        $scope.pendingQuoteCount = 0;
		$scope.filter = {};
        animateMsgBox('.loading--gateway');
        $scope.getSummary = function () {
            $http({
                method: 'GET',
                url: '/api/v1/stats'
            }).success(function (data) {
                if (data.success) {
                    $scope.summaryData = data.data;
                    if ($scope.summaryData && $scope.summaryData.quotes && $scope.summaryData.quotes.pending) {
                        $scope.pendingQuoteCount = $scope.summaryData.quotes.pending;
                    }
                }
            });
        };
        $scope.getSummary();
        $scope.getQuotes = function () {
            $scope.dataLoadInProgress = true;
            $http({
                method: 'POST',
                url: '/api/v1/quotes',
                data : {request: $scope.filter}
            }).success(function (data) {
                if (data.success) {
                    $scope.response = data.data;
                    if($scope.filter.searchId == null || $scope.filter.searchId == '') {
                        $scope.filter.totalPages = data.data.totalPages;
                    }
                    $scope.filter.searchId = data.data.searchId;
                }
                $scope.dataLoadInProgress = false;
            }).error(function (data) {
                $scope.dataLoadInProgress = false;
            });
        };
        
        $scope.initFilter = function() {
            $scope.filter.pageNo = 1;
            $scope.filter.limit = 10;
            $scope.filter.offset = ($scope.filter.pageNo - 1) * $scope.filter.limit;
            $scope.filter.sortKey = "created_time";
            $scope.filter.sortBy = "desc";
            $rootScope.quotePageFilter = null;
            $scope.getQuotes();
        }
        if($rootScope.backToQoute) {
            $scope.filter = $rootScope.quotePageFilter;
            $rootScope.quotePageFilter = null;
            $rootScope.backToQoute = false;
            $scope.getQuotes();
        } else {
            $scope.initFilter();
        }
        
        $scope.getSortedList = function(orderBy) {
            if($scope.filter.sortKey == orderBy) {
                $scope.filter.sortBy = ($scope.filter.sortBy == 'asc') ? 'desc' : 'asc';
            } else {
                $scope.filter.sortKey = orderBy;
                $scope.filter.sortBy = "asc";
            }
            $scope.filter.searchId = null;
            $scope.filter.pageNo = 1;
            $scope.filter.offset = ($scope.filter.pageNo - 1) * $scope.filter.limit;
            $scope.getQuotes();
        }
        
        $scope.goToPage = function(page) {
            if (page > 0 && page <= $scope.filter.totalPages && page != $scope.filter.pageNo && !$scope.dataLoadInProgress) {
                if(page == 1) {
                    $scope.filter.searchId = null;
                }
                $scope.filter.pageNo = page;
                $scope.filter.offset = ($scope.filter.pageNo - 1) * $scope.filter.limit;
                $scope.getQuotes();
            }
        }
        
        $scope.limitChanged = function () {
            $scope.filter.searchId = null;
            $scope.filter.pageNo = 1;
            $scope.filter.offset = ($scope.filter.pageNo - 1) * $scope.filter.limit;
            $scope.getQuotes();
        }
        
        $scope.useForNewQuote = function (quote) {
            //$rootScope.quotePageFilter = $scope.filter;
            $location.path('/quote/request/' + quote.estimate_id);
        };
        $scope.loadMoreQuoteInfo = function (quote) {
            quote.detailedInfoLoaded = true;
            quote.detailedInfoLoadInProgress = false;
            /*if ((typeof quote.detailedInfoLoaded == 'undefined' || !quote.detailedInfoLoaded) && (typeof quote.detailedInfoLoadInProgress == 'undefined' || !quote.detailedInfoLoadInProgress)) {
             quote.detailedInfoLoadInProgress = true;
             QuoteData.fetchQuoteData(quote.estimate_id, function (data) {
             quote.detailedInfoLoadInProgress = false;
             if (data.success) {
             var qData = data.data;
             quote.detailedInfoLoaded = true;
             quote.request = qData.request;
             quote.options = qData.options;
             }
             });
             }*/
        };
        
        $scope.acceptQuote = function(estimate_id) {
            $rootScope.quotePageFilter = $scope.filter;
            $location.path('/quote/accept/' + estimate_id);
        }
        
        $('.noaccordion').on('click', function (e) {
            e.stopPropagation();
        });
    };
    app.controller('homeController', homeController);
});