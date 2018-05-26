'use strict';
define([
    'angular',
    'angularRoute',
    'angular-storage',
    'angular-jwt',
    'angular-loading-bar',
    'js/bd/modules/home/home',
    'js/bd/modules/quote/request',
    'js/bd/modules/quote/accept',
    'js/bd/modules/account/complete',
    'js/bd/modules/account/profile',
    'js/bd/modules/invoice/invoice',
    'js/bd/modules/quote/delivery'
], function (angular) {
    var app = angular.module("gatewayPortalApp", [
        "ngRoute", 
        'angular-storage', 
        'angular-jwt',
        'angular-loading-bar',
        'gatewayPortalApp.home',
        'gatewayPortalApp.quote.request',
        'gatewayPortalApp.quote.accept',
        'gatewayPortalApp.account.complete',
        'gatewayPortalApp.account.profile',
        'gatewayPortalApp.invoice.invoices',
        'gatewayPortalApp.quote.delivery'
    ]).config(function ($routeProvider, jwtInterceptorProvider, $httpProvider, $provide) {
        /*lockProvider.init({
            domain: 'builddirect-dev.auth0.com',
            clientID: 'aygXpvp1Nm760vpCSMH8LH7PhkU5ltrF'
        });*/
        jwtInterceptorProvider.tokenGetter = function (store) {
            return store.get('token');
        }
        function redirect($q, $injector, authManager, store, $location) {
            return {
                responseError: function (rejection) {
                    if (rejection.status === 401) {
                        authManager.unauthenticate();
                        store.remove('token');
                        window.location.href = window.logoutUrl + "?returnTo=" + window.location.origin + "/logout&client_id=" + window.clientId;
                    }
                    return $q.reject(rejection);
                }
            }
        }
        $provide.factory('redirect', redirect);
        $httpProvider.interceptors.push('redirect');
        $httpProvider.interceptors.push('jwtInterceptor');
        
        $routeProvider.otherwise({
            redirectTo: '/home'
        });
    });
    app.run(function (jwtHelper, $q, store, authManager, $rootScope, $http, $location) {
        callOnReady();
        $rootScope.$on('$routeChangeStart', function (current, next) {
            var deferred = $q.defer();
            var token = store.get('token');
            /*lock.on('authenticated', function (authResult) {
                store.set('token', authResult.idToken);
                
            });*/
            if (token) {
                if (!jwtHelper.isTokenExpired(token)) {
                    $rootScope.user = window.userInfo;
                    //if(typeof($rootScope.user) == "undefined" || $rootScope.user=="") {
                        
                        /*$http({
                            method: 'GET',
                            url: '/api/v1/userinfo'
                        }).success(function(data) {
                            if(data.success) {
                                $rootScope.user = data.data;
                            } else {
                                $timeout(function() {
                                    $rootScope.user = {first_name: "Arun1", email: "aruncp123@gmail.com", contractSigned: 0};
                                    console.log("local set of user");
                                });
                                $rootScope.user = {first_name: "Arun1", email: "aruncp123@gmail.com", contractSigned: 0};
                            }
                            //$rootScope.user = {first_name: "Arun", email: "aruncp123@gmail.com", contractSigned: 1};
                            if(!$rootScope.user.contractSigned){
                                $location.path("/account/profile");
                            }
                            console.log("user info called")
                        });*/
                    //} else if(!$rootScope.user.contractSigned){
                    if (!$rootScope.user.contractSigned) {
                        $location.path("/account/complete");
                    }
                    authManager.authenticate(store.get('profile'), token);
                    activateMenu($location.path());
                    
                    deferred.resolve();
                } else {
                    store.remove('token');
                    window.location.href = "/";
                }
            } else {
                store.remove('token');
                window.location.href = "/";
            }
        });

    });
    
    app.directive('toolbar', toolbar);
    function toolbar() {
        return {
            templateUrl: 'js/bd/directives/toolbar.html',
            controller: toolbarController,
            controllerAs: 'toolbar'
        }
    }
    
    app.directive('resize', function ($window, $rootScope) {
        return function (scope, element) {
            var w = angular.element($window);
            var changeHeight = function () {
                var offset = $('.footer-outer').offset();
                if (offset) {
                    var minusVal = 300;
                    if (!$rootScope.user.contractSigned) {
                        minusVal = 160;
                    }
                    if (offset.top < w.height() - 40) {
                        element.css('min-height', (w.height() - minusVal) + 'px');
                    }
                } 
                
            };
            
            w.bind('resize', function () {        
                changeHeight();   // when window size gets changed             
            });  
            changeHeight(); // when page loads          
        }
    });
    app.factory('QuoteData', function ($http) {

        var data = {};

        return {
            getQuoteData: function (estimateId) {
                var quoteData = data[estimateId];
                delete data[estimateId];
                return quoteData;
            },
            setQuoteData: function (estimateId, quoteData) {
                data[estimateId] = quoteData;
            },
            fetchQuoteData: function (estimateId, callback) {
                $http({
                    method: 'GET',
                    url: '/api/v1/quote',
                    headers: {
                        'estimateid': estimateId
                    }
                }).success(function (data) {
                    callback(data);
                }).error(function (data) {
                    callback(data);
                });
            }
        };
    });
    
    function toolbarController($rootScope, $location, $timeout) {
       //this.user = $rootScope.user;
       //var vm = this;
        $timeout(function () {
           activateMenu($location.path());            
       })
       var callOnReady = function () {
           //Menu related codes
            var $mobilmenu = $('.mobmenu');
            var $menuac = $('#menuac');
            var $mobilenavleft = $('.mobilenavleft');
            $menuac.click(function () {
                $(this).toggleClass('acik');
                $('#ust').toggleClass('sagagel');
                $mobilmenu.toggleClass('mobmenu-acik');
                $(".overlay--menu").toggle();
            });
            $mobilenavleft.click(function () {
              $('#menuac').toggleClass('acik');
              $('#ust').toggleClass('sagagel');
              $mobilmenu.toggleClass('mobmenu-acik');
              $(".overlay--menu").toggle();
            });
            var $mobilmenuright = $('.mobmenuright');
            var $menuacright = $('#menuacright');
            var $closemenu = $('.close--menu');
            var $mobilenav = $('.mobilenav');
            $menuacright.click(function () {
                $mobilmenuright.toggleClass('mobmenu-acik-right');
                $(".overlay--menu__right").toggle();
                $(".close--menu").toggle();
            });
            $closemenu.click(function () {
                $mobilmenuright.toggleClass('mobmenu-acik-right');
                $(".overlay--menu__right").toggle();
                $(".close--menu").toggle();
            });
            $mobilenav.click(function () {
              $mobilmenuright.toggleClass('mobmenu-acik-right');
              $(".overlay--menu__right").toggle();
              $(".close--menu").toggle();
            });
            $('.panel a').click(function () {
                $(this).find('i').toggleClass('glyphicon-chevron-down glyphicon-chevron-up')
            });
       }
       callOnReady();
    }
    
    app.directive('footertmpl', footertmpl);
    function footertmpl() {
        return {
            templateUrl: 'js/bd/directives/footertmpl.html',
            controller: footertmplController,
            controllerAs: 'footertmpl'
        }
    }
    
    function footertmplController($timeout, $rootScope) {
        var changeHeight = function () {
            var offset = $('.footer-outer').offset();
            if (offset) {
                var minusVal = 300;
                if (!$rootScope.user.contractSigned) {
                    minusVal = 160;
                }
                var wHeight = $(window).height();
                if (offset.top < wHeight - 40) {
                    $('#mainViewId').css('min-height', (wHeight - minusVal) + 'px');
                }
            }
        };
        $timeout(function () {
            changeHeight();
        });
        
    }
    
    return app;
});

function activateMenu(path) {
    //var path = route.originalPath;
    var urlMenuMap = {_home: {main_menu: 'main-menu-shipping', sub_menu: 'sub-menu-myquotes'},
        _quote_request : {main_menu: 'main-menu-shipping', sub_menu: 'sub-menu-myquotes'}, /*'sub-menu-requestquote'*/
        _quote_accept : {main_menu: 'main-menu-shipping', sub_menu: 'sub-menu-myquotes'},
        _delivery_orders : {main_menu: 'main-menu-shipping', sub_menu: 'sub-menu-delivery-orders'},
        _invoice_invoices: {main_menu: 'main-menu-invoice', sub_menu: 'sub-menu-invoices'}};
    
    var specialUrls = ['_quote_request', '_quote_accept'];
    
    if (path) {
        var mapKey=path.replace(/\//g, '_');
        $.each(specialUrls, function(key, value) {
            if(mapKey.indexOf(value) !== -1) {
                mapKey = value;
            }
        });
        $('.sub-menu-container').hide();
        $('.main-menu').removeClass('active');
        $('.sub-menu').removeClass('active');
        if(urlMenuMap[mapKey]) {
            $('.' + urlMenuMap[mapKey].main_menu + '-sub').show();
            $('.' + urlMenuMap[mapKey].main_menu).addClass('active');

            $('.' + urlMenuMap[mapKey].sub_menu).addClass('active');
        }
        
        /*var actionItems = path.split('/');
        if (actionItems.length > 1) {
            $('.main-menu').removeClass('active');
            $('.' + actionItems[1] + '-menu').addClass('active');
        }*/
    }
}

function animateMsgBox(className) {
    var body = $("html, body");
    body.animate({scrollTop: $(className).offset().top - 50}, '500', function () {
    });
}

function closeAlertMsgBox(className) {
    $('.' + className).hide();
}

function callOnReady() {
    $(document).ready(function () {
        console.log('Jquery Ready');
        /*Shipping-Rates---
        */
        $('.shipping--rate__bg').click(function () {
          $('.shipping--rate__bg___active').toggleClass('shipping--rate__bg___active');
            $(this).toggleClass('shipping--rate__bg___active');
        });
        $('a.step--one__btn').click(function () {
            $('#step--one').addClass("display--none");
            $('#step--two').addClass("display--block");
        });
        
        
    });
    /*$(window).resize(function() {
  autoHeight();
     });*/
}

/*function autoHeight() {
    console.log("auto height called");
  $('.bd-gateway').css('min-height', 0);
  $('.bd-gateway').css('min-height', (
    $(document).height()
    - $('.bd-gateway').height()
    - $('.gateway--footer').height()
  ));
 }*/
/*Menu right overlay */
// $(document).ready(function() {
//   var $mobilmenuright = $('.mobmenuright');
//   var $menuacright = $('#menuacright');
//   var $closemenu = $('.close--menu');
//   $menuacright.click(function() {
//     $mobilmenuright.toggleClass('mobmenu-acik-right');
//     $(".overlay--menu__right").toggle();
//     $(".close--menu").toggle();
//   });
//   $closemenu.click(function() {
//     $mobilmenuright.toggleClass('mobmenu-acik-right');
//     $(".overlay--menu__right").toggle();
//     $(".close--menu").toggle();
//   });
//   $('.panel a').click(function(){
//       $(this).find('i').toggleClass('glyphicon-chevron-down glyphicon-chevron-up')
//   });
// });

function isAlphaNumeric(event) {
    var keyCode = event.keyCode ? event.keyCode : event.charCode;
    var controlKeys = [8, 9, 13, 16, 17, 18, 19, 20, 27, 32];
    return ((keyCode >= 48 && keyCode <= 57) || (keyCode >= 65 && keyCode <= 90) || (keyCode >= 97 && keyCode <= 122) || (controlKeys.indexOf(keyCode) >= 0));//(keyCode === 32 || keyCode === 9 || keyCode === 27 || keyCode === 127 || keyCode === 8)
}