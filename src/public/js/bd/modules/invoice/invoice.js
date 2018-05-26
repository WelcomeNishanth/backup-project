'use strict';
define([
    'angular',
    'angularRoute',
    'angularPageslide'
], function (angular) {
    var app = angular.module('gatewayPortalApp.invoice.invoices', ['ngRoute', 'angular-storage', 'pageslide-directive']);
    app.config(['$routeProvider', function ($routeProvider) {
        $routeProvider.when('/invoice/invoices', {
            templateUrl: 'js/bd/modules/invoice/invoice.html',
            controller: 'invoiceController'
        });
    }]);
    var invoiceController = function ($scope, $http) {
        $scope.invoiceSlide = false;
        $scope.filter = {};
        $scope.dataLoadInProgress = false;
        $scope.setPageSlideFlag = function (flag) {
            $scope.invoiceSlide = flag;
        }
        
        $scope.getInvoices = function () {
            $scope.dataLoadInProgress = true;
            $http({
                url : '/api/v1/invoices',
                method : 'POST',
                data : $scope.filter
            }).success(function (response) {
                if(response.success) {
                    $scope.invoices = response.data.records;
                    $scope.totalPage = response.data.totalPages;
                    $scope.filter.searchId = response.data.searchId;
                }
                $scope.dataLoadInProgress = false;
            }).error(function () {
                $scope.dataLoadInProgress = false;
            });
        }
                
        $scope.initFilter = function() {
            $scope.filter.pageNo = 1;
            $scope.filter.limit = 10;
            $scope.filter.offset = ($scope.filter.pageNo - 1) * $scope.filter.limit;
            //$scope.filter.orderKey = "o_creationDate";
            //$scope.filter.order = "desc";
            $scope.getInvoices();
        }
        $scope.initFilter();
        
        //As Netsuite API is not supporting sorting, commenting this function.
        /*$scope.getSortedList = function(orderBy) {
            if($scope.filter.orderKey == orderBy) {
                $scope.filter.order = ($scope.filter.order == 'asc') ? 'desc' : 'asc';
            } else {
                $scope.filter.orderKey = orderBy;
                $scope.filter.order = "asc";
            }
            $scope.filter.pageNo = 1;
            $scope.filter.offset = ($scope.filter.pageNo - 1) * $scope.filter.limit;
            $scope.getInvoices();
        }*/
        
        $scope.goToPage = function(page) {
            if (page > 0 && page <= $scope.totalPage && page != $scope.filter.pageNo && !$scope.dataLoadInProgress) {
                if(page == 1) {
                    $scope.filter.searchId = null;
                }
                $scope.filter.pageNo = page;
                $scope.filter.offset = ($scope.filter.pageNo - 1) * $scope.filter.limit;
                $scope.getInvoices();
            }
        }
        
        $scope.limitChanged = function () {
            $scope.filter.searchId = null;
            $scope.filter.pageNo = 1;
            $scope.filter.offset = ($scope.filter.pageNo - 1) * $scope.filter.limit;
            $scope.getInvoices();
        }
        
        $scope.openPageSlide = function (invoice) {
            $scope.slideModal = invoice;
            $scope.setPageSlideFlag(true);
        }
 
    }
    app.controller('invoiceController', invoiceController);
});