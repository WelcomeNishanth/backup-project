define([
    'angular',
    'angularRoute',
    'angularPageslide'
], function (angular) {
    var app = angular.module('gatewayPortalApp.quote.delivery', ['ngRoute', 'angular-storage', 'pageslide-directive']);
    app.config(['$routeProvider', function ($routeProvider) {
            $routeProvider.when('/delivery/orders', {
                templateUrl: 'js/bd/modules/quote/delivery.html',
                controller: 'deliveryController'
            });
        }]);
    var deliveryController = function ($scope, $http, $timeout) {
        $scope.deliverySlide = false;
        $scope.filter = {};
        $scope.dataLoadInProgress = false;
        $scope.setPageSlideFlag = function (flag) {
            $scope.deliverySlide = flag;
        }
        $scope.slideModal = {};
        
        $scope.deliveryStatus = {PENDING_PICKUP: 1, IN_TRANSIT: 2, DELIVERED: 3};
        
        $scope.getStatusCode = function (status) {
            var mapKey='PENDING_PICKUP';
            if(status != null && status != '' && typeof status != 'undefined') {
                mapKey=status.replace(/ /g, '_');
            }
            return $scope.deliveryStatus[mapKey];
        }

        $scope.getDeliveryOrders = function () {
            $scope.dataLoadInProgress = true;
            $http({
                url: '/api/v1/deliveries',
                method: 'POST',
                data: {request: $scope.filter}
            }).success(function (response) {
                if (response.success) {
                    $scope.deliveryOrders = response.data.delivery;
                    if($scope.filter.searchId == null || $scope.filter.searchId == '') {
                        $scope.totalPage = response.data.totalPages;
                    }
                    $scope.filter.searchId = response.data.searchId;
                }
                $scope.dataLoadInProgress = false;
            }).error(function () {
                $scope.dataLoadInProgress = false;
            });
        }

        $scope.getDeliveryDetails = function (deliveryId) {
            $scope.dataLoadInProgress = true;
            $http({
                url: '/api/v1/delivery',
                method: 'GET',
                headers: {
                    'deliveryid': deliveryId
                }
            }).success(function (response) {
                if (response.success) {
                    $scope.slideModal = response.data.delivery;
                    $scope.setPageSlideFlag(true);
                }
                $scope.dataLoadInProgress = false;
            }).error(function () {
                $scope.dataLoadInProgress = false;
            });
        }

        $scope.initFilter = function () {
            $scope.filter.pageNo = 1;
            $scope.filter.limit = 10;
            $scope.filter.offset = ($scope.filter.pageNo - 1) * $scope.filter.limit;
            $scope.filter.sortKey = "created_time";
            $scope.filter.sortBy = "desc";
            $scope.getDeliveryOrders();
        }
        $scope.initFilter();

        $scope.goToPage = function (page) {
            if (page > 0 && page <= $scope.totalPage && page != $scope.filter.pageNo && !$scope.dataLoadInProgress) {
                if (page == 1) {
                    $scope.filter.searchId = null;
                }
                $scope.filter.pageNo = page;
                $scope.filter.offset = ($scope.filter.pageNo - 1) * $scope.filter.limit;
                $scope.getDeliveryOrders();
            }
        }

        $scope.limitChanged = function () {
            $scope.filter.searchId = null;
            $scope.filter.pageNo = 1;
            $scope.filter.offset = ($scope.filter.pageNo - 1) * $scope.filter.limit;
            $scope.getDeliveryOrders();
        }

        $scope.openPageSlide = function (deliveryId) {
            $scope.getDeliveryDetails(deliveryId);
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
            $scope.getDeliveryOrders();
        }
        
        $timeout(function () {
            $('.dropdown-toggle').dropdown();
            $('[data-toggle="tooltip"]').tooltip();
            $("div.panel").click(function () {
                if (($(this).hasClass("active--accordian"))) {
                    $(this).removeClass("active--accordian");
                } else {
                    $(".active--accordian").removeClass("active--accordian");
                    $(this).addClass("active--accordian");
                }
            });
            $('.delivery--wrapper .gateway--button__small').click(function (e) {
                e.stopPropagation();
            });
        });

        
    }
    app.controller('deliveryController', deliveryController);
    app.directive('bsTooltip', function(){
        return {
            restrict: 'A',
            link: function(scope, element, attrs){
                $(element).hover(function(){
                    $(element).tooltip('show');
                }, function(){
                    $(element).tooltip('hide');
                });
            }
        };
    });
    app.directive('bsPopover', function(){
        return {
            restrict: 'A',
            link: function(scope, element, attrs){
                $(element).popover({ placement: 'bottom', html: 'true'});
            }
        };
    });
});