<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie10 lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie10  lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie10  lt-ie9" lang="en"> <![endif]-->
<!--[if IE 9]>         <html class="no-js lt-ie10" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Gateway Supply Chain Services</title>

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
    <link rel="stylesheet" type="text/css" href="css/main.css">

  </head>
  <body ng-app="signInApp" ng-controller="signInController">
    <?php
      $CDN_LOCATION = 'https://' . getenv("CDN_LOCATION") . "/";
    ?>
  <!-- Nav Starts Here -->
  <nav class="navbar navbar-fixed-top topnav" role="navigation">
    <div class="wide-wrapper overflow-wrapper">
      <a href="<?= $url; ?>" class="logo--news" title="Gateway">
        <img src="<?php echo $CDN_LOCATION;?>img/landing/gateway-white.png" alt="Gateway">
      </a>
      <div class="pull-right landing--menu">
        <ul class="nav nav-pills">
        <!--<li><a href="#" class="main-menu home-menu active">Who We Service</a></li> -->
        <li><a href="/news" class="main-menu quote-menu">Newsroom</a></li>
        <!-- <li><a href="#" class="main-menu account-menu">FAQ</a></li>  -->
        <li><a href="/tracking" class="main-menu account-menu">Track Shipment</a></li>
        <li><a href="#" ng-click="login()" class="main-menu quote-menu">Log In</a></li>
        </ul>
      </div>
      <!-- <a href="#" ng-click="login()" class="login--btn">Sign In</a> -->
    </div>
    <div class="clearfix"></div>
  </nav>
  <!-- Nav Ends Here -->

  <!-- Header Starts -->
  <div class="business-header newsroom--header">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="tagline">Newsroom</h1>
        </div>
      </div>
    </div>
  </div>
  <!-- Header Ends-->
  <!-- Main Starts Herer -->
  <main class="bigmedium--wrapper">
    <section class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <p class="lead">“We partnered with BuildDirect to ensure that we can ship our products directly to you, our US customers,
            with ease. We do this to cut out the middleman and to offer you the best manufacturer-direct prices out there.
            No extra duties, no extra shipping costs, only rock bottom rates on everything!” – <span>TileDirect</span>
          </p>
        </div>
      </div>
    <!-- Featured Article -->
    <div class="row article article-main">
        <div class="col-sm-6 col-md-6 left-gutter">
          <div class="label">FEATURED ARTICLE</div>
          <a href="http://tiledirect.com/tiledirect-partners-builddirect-usa/" target="_blank">
            <img class="img-article" src="<?php echo $CDN_LOCATION;?>img/landing/article_0.png" alt="">
          </a>
        </div>
        <div class="col-sm-6 col-md-6 article-text">
          <h3>TileDirect Partners with BuildDirect! USA Here We Come!</h3>
          <p>TileDirect is excited to announce that we have partnered with BuildDirect to bring YOU, our customers across the United States,
            the best of the tile world directly to your door, with the best possible shipping rates available.
            <a href="http://tiledirect.com/tiledirect-partners-builddirect-usa/" target="_blank" class="text--link">Read more</a>
          </p>
          <span class="name-date">TileDirect</span>
          <span class="name-date">Jun 16, 2017</span>
        </div>
      </div>
    <!-- Featured Article Ends-->
    <!-- Article -->
    <div class="row article">
      <div class="col-sm-6 col-md-6 left-gutter">
        <div class="article-sub">
          <a href="https://techcrunch.com/2017/02/21/builddirect-opens-up-its-platform-for-heavyweight-shipping/" target="_blank">
            <img class="img-article" src="<?php echo $CDN_LOCATION;?>img/landing/article_1.png" alt="">
          </a>
          <div class="article-details">
            <h4>BuildDirect opens its freight-shipping platform</h4>
            <p>Over the last few years, BuildDirect developed a name for itself as an online store for home improvement products.
              <a class="text--link" href="https://techcrunch.com/2017/02/21/builddirect-opens-up-its-platform-for-heavyweight-shipping/"  target="_blank" class="text-link">Read more</a>
            </p>
            <span class="name-date">TechCrunch</span>
            <span class="name-date">Jun 16, 2017</span>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-6 right-gutter">
        <div class="article-sub">
          <a href="http://www.supplychaindive.com/news/last-mile-spotlight-infographic-process-supply-chain/442861/" target="_blank">
            <img class="img-article" src="<?php echo $CDN_LOCATION;?>img/landing/article_2.png" alt="">
          </a>
          <div class="article-details">
            <h4>Perfecting the last mile</h4>
            <p>Supply chain stakeholders must work together if they are to make the process more efficient.
              <a class="text--link" href="http://www.supplychaindive.com/news/last-mile-spotlight-infographic-process-supply-chain/442861/" target="_blank" class="text-link">Read more</a>
            </p>
            <span class="name-date">SupplyChainDIVE</span>
            <span class="name-date">Jun 16, 2017</span>
          </div>
        </div>
      </div>
      <!-- <div class="col-sm-6 col-md-6 left-gutter">
        <div class="article-sub">
          <a href="#">
            <img class="img-article" src="img/landing/article_1.png" alt="">
          </a>
          <div class="article-details">
            <h4>Article title</h4>
            <p>Subcopy for the article can go here. It can be as long as two lines then you see the.
              <a href="#" class="text-link">Read more</a>
            </p>
            <span class="name-date">News Site</span>
            <span class="name-date">May 22, 2017</span>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-6 right-gutter">
        <div class="article-sub">
          <a href="#">
            <img class="img-article" src="img/landing/article_2.png" alt="">
          </a>
          <div class="article-details">
            <h4>Article title</h4>
            <p>Subcopy for the article can go here. It can be as long as two lines then you see the.
              <a href="#" class="text-link">Read more</a>
            </p>
            <span class="name-date">News Site</span>
            <span class="name-date">May 22, 2017</span>
          </div>
        </div>
      </div> -->
    </div>
    <!-- Article Ends-->
    </section>
  </main>
  <!-- Main Ends Here -->
        <!-- Main Ends Here -->
        <!-- Footer Starts Here -->
        @include('landingfooter')
        <!-- Footer Ends Here -->
        <!-- Including jQuery  -->
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
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-loading-bar/0.9.0/loading-bar.js"></script>

    <?php
        $allowedConnection = getenv("AUTH0_GATEWAY_CONNECTION_NAME");
        $authDomain = getenv('OAUTH_BASE_DOMAIN');
        $authId = getenv('OAUTH_BASE_CLIENT_ID');
    ?>

  <script>
    var app = angular.module('signInApp', ['auth0.lock', 'angular-storage', 'angular-jwt', 'ui.router', 'angular-loading-bar']);
    app.config(function($provide, lockProvider) {
        lockProvider.init({
            domain: '<?= $authDomain ?>',
            clientID: '<?= $authId; ?>',
            options: {
                theme: {
                    logo: 'https://d3241vr6m32sxu.cloudfront.net/img/logo-normal-gw.png'
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
    app.controller('signInController', function ($scope, $http, $timeout, lock) {

        $scope.login = function() {
            lock.show();
        }
    });
    </script>

  </body>
  </html>
