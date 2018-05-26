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
		<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0], j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-T9GQ');</script>
		<!-- End Google Tag Manager -->
                <style>
                    .landing-content {
                        display: none;
                    }
                </style>
    </head>
    <body id="bd-gateway" ng-app="registration" ng-controller="registrationController">
        <div class="loading--gateway" style="display: block;">
            <div class="icon-sent"></div>
        </div>

		<!-- Google Tag Manager (noscript) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T9GQ"
		height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<!-- End Google Tag Manager (noscript) -->

        <header id="header" class="landing-content">
            <div class="wide-wrapper overflow-wrapper">
                <a href="#" class="logo" title="Gateway">
                    <?php
                    $CDN_LOCATION = 'https://' . getenv("CDN_LOCATION") . "/";

                    ?>
                    <img src="<?php echo $CDN_LOCATION;?>img/landing/gateway-white.png" alt="Gateway">
                </a>
                <nav class="pull-right landing--menu landing--menu__landing">
                  <ul class="nav nav-pills">
                    <!--<li><a href="#" class="main-menu home-menu active">Who We Service</a></li> -->
                    <li><a href="/news" class="main-menu quote-menu">Newsroom</a></li>
                    <!-- <li><a href="#" class="main-menu account-menu">FAQ</a></li> -->
                    <li><a href="/tracking" class="main-menu account-menu">Track Shipment</a></li>
                    <li><a  href="#" ng-click="login()" class="main-menu account-menu">Log In</a></li>
                  </ul>
                </nav>
                <!-- <a href="#" ng-click="login()" class="login--btn">Sign In</a> -->

                <div class="clearfix"></div>
                <h1>Introducing <b>GATEWAY</b></h1>
                <div class="tagline-main">
                    The one-stop-shop for anywhere to-the-home heavyweight shipping.
                </div>
                <a class="link-button teal" href="#joinus">Sign Up Today</a>
            </div>
            <div class="header-trust-metrics">
                <div class="wide-wrapper trusts">
                    <div class="trustmetric-item"><div><i class="fa fa-circle"></i> 8.2M pounds</div><span>of product ship per month</span></div>
                    <div class="trustmetric-item"><div><i class="fa fa-circle"></i> 5.9M miles</div><span>is the distance products travel<br> each month</span></div>
                    <div class="trustmetric-item"><div><i class="fa fa-circle"></i> 35+ countries</div><span>have imports and exports<br> managed</span></div>
                    <div class="trustmetric-item"><div><i class="fa fa-circle"></i> 17 years</div><span>spent building the supply chain from the ground up</span></div>
                </div>
                <div class="trustmetric-slideshow cycle-slideshow"
                    data-cycle-fx="fade"
                    data-cycle-timeout="2000"
                    data-cycle-slides="> div" >
                    <div class="trustmetric-item"><div>8.2M pounds</div><span>of product ship per month</span></div>
                    <div class="trustmetric-item"><div>5.9M miles</div><span>is the distance products travel<br> each month</span></div>
                    <div class="trustmetric-item"><div>35+ countries</div><span>have imports and exports<br> managed</span></div>
                    <div class="trustmetric-item"><div>17 years</div><span>spent building the supply chain from the ground up</span></div>
                </div>
            </div>
        </header>
        <nav class="landing-content">
            <ul>
                <li><a href="#howitworks">How It Works</a></li>
                <li><a href="#whyitsdifferent">Why It's Different</a></li>
                <li><a href="#joinus">Join Us</a></li>
            </ul>
        </nav>
        <main class="landing-content">
            <section class="howitworks" id="howitworks">
                <div class="medium-wrapper">
                    <div class="how-it-works-text">
                        <h2>How It Works</h2>
                        <div class="underline"></div>
                        <p>
                            Gateway’s advanced network of warehousing services, ground and ocean logistics makes it easy and affordable to ship heavyweight products from anywhere in the world, to homes across North America. With lower prices and less damage to products, your customers will get better value. <a href="#joinus" class="mainlinkz linkz">Sign up today</a>
                        </p>
                    </div>
                    <div class="price-table-wrapper">
                        <div class="price-table-col">
                            <span>CURRENT HEAVYWEIGHT SHIPPING</span>
                            <img class="img-responsive" src="<?php echo $CDN_LOCATION;?>img/landing/laptop-current.png">
                        </div>
                        <div class="price-table-divider">vs</div>
                        <div class="price-table-col">
                            <span class="teal">SHIPPING WITH GATEWAY</span>
                            <img class="img-responsive" src="<?php echo $CDN_LOCATION;?>img/landing/laptop-bd.png">
                        </div>
                    </div>
                </div>
            </section>
            <section>
                <div class="full-wrapper">
                    <div class="callout-wrapper">
                        <div class="callout-image">
                            <div class="image">
                                <img class="img-responsive" src="<?php echo $CDN_LOCATION;?>img/landing/callout-home.jpg">
                            </div>
                        </div>
                        <div class="callout-outertext">
                            <div class="callout-text">
                                <h2>Last-mile Delivery</h2>
                                <p>
                                    Gateway encompasses 25+ ground carriers delivering orders averaging 1,500 pounds. You can <strong>save up to 30%</strong> on shipping costs and can choose how you would like your products delivered. You can keep the savings or pass them on to the customer.
                                </p>
                                <a href="#joinus" class="blue-button linkz">Sign Up for Last-mile Delivery</a>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="full-wrapper">
                    <div class="callout-wrapper even">
                        <div class="callout-image first" >
                            <div class="image">
                                <img class="img-responsive" src="<?php echo $CDN_LOCATION;?>img/landing/callout-warehouse.jpg">
                            </div>
                        </div>
                        <div class="callout-outertext" >
                            <div class="callout-text">
                                <h2>Warehousing Solutions</h2>
                                <p>
                                    Includes 40+ warehouse locations in North America for partners to access the Gateway. Made up of Super Distribution Centers, Regional Distribution Centers and Micro Distribution Centers, any of which can serve as an access point into the Gateway network.
                                </p>
                                <a href="#joinus" class="blue-button linkz">Sign Up for Warehousing </a>
                            </div>
                        </div>
                        <div class="callout-image last" >
                            <div class="image">
                                <img class="img-responsive" src="<?php echo $CDN_LOCATION;?>img/landing/callout-warehouse.jpg">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="full-wrapper">
                    <div class="callout-wrapper">
                        <div class="callout-image">
                            <div class="image">
                                <img class="img-responsive" src="<?php echo $CDN_LOCATION;?>img/landing/callout-ocean.jpg">
                            </div>
                        </div>
                        <div class="callout-outertext">
                            <div class="callout-text">
                                <h2>Ocean Freight</h2>
                                <p>
                                    Gateway network is comprised of five ocean service providers servicing six continents globally. Take advantage of our volume pricing discounts to keep your shipping costs lower than your competition. Gateway is C-TPAT (Customs–Trade Partnership Against Terrorism) and PIP (Partners in Protection) certified which leads to expedited processing and fewer customs examinations, saving you more.
                                </p>
                                <a href="#joinus" class="blue-button linkz">Sign Up for Ocean Freight</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="quote" id="quote">
                <div class="wide-wrapper">
                    <div class="quote-map">

                    </div>
                    <div class="quote-text">
                        <div>
                            “With minimal investment, no long-term contracts, and a turn-key infrastructure, Gateway offers tremendous value, convenience and savings. This supply chain has the potential to revolutionize heavyweight ecommerce and advance an entire industry.”
                        </div>
                        <span><b> Alexandre Decarie </b> - CEO of Power Dekor North America</span>
                    </div>
                </div>
            </section>
            <section class="whyitsdifferent" id="whyitsdifferent">
                <div class="medium-wrapper">
                    <h2>Why It’s Different</h2>
                    <div class="underline"></div>
                    <div class="points">
                        <div class="tickmark"></div>
                        <div class="pointscontent">
                            <h3>Fewer touchpoints</h3>
                            <p>
                                Gateway offers end-to-end solutions for heavyweight shipping resulting in fewer touchpoints and less damage to the delivered product. In fact, the average consolidated Gateway shipment is handled 50% less than those in other heavyweight supply chain networks.
                            </p>
                        </div>
                    </div>
                    <div class="points">
                        <div class="tickmark"></div>
                        <div class="pointscontent">
                            <h3>Accountability via ratings and reviews</h3>
                            <p>
                                Once a delivery is complete, customers provide feedback on their experiences. These ratings and reviews are funnelled back through the system and carriers that perform well will get more business and those that receive consistently poor feedback, are removed.
                            </p>
                        </div>
                    </div>
                    <div class="points">
                        <div class="tickmark"></div>
                        <div class="pointscontent">
                            <h3>Powered by predictive analytics</h3>
                            <p>
                                You will get access to varying levels of data depending on how you engage with the platform. At the highest level, you get real-time access to a dashboard that leverages sophisticated predictive analytics to show where demand for your products are. Products in the network are easily moved around the country to ensure they are close to your customers, which closes the gap on shipping costs and offers more reliable delivery windows.
                            </p>
                        </div>
                    </div>
                    <div class="points">
                        <div class="tickmark"></div>
                        <div class="pointscontent">
                            <h3>Customized delivery options</h3>
                            <p>
                                Your customers can save up to 30% on shipping costs and can choose how they would like their products delivered: white glove (i.e. room of choice), first threshold (i.e. inside a garage), light or heavy assembly and removal of old product, garbage, or dunnage. Additionally, as manufacturers and distributors you can select how to manage the movement of your products (ocean and ground logistics, or warehousing services).
                            </p>
                        </div>
                    </div>
                    <div class="clearfix-tablet"></div>
                    <div class="points">
                        <div class="tickmark"></div>
                        <div class="pointscontent">
                            <h3>Flexibility</h3>
                            <p>
                                All supply chain services are available to any retailer and offer flexibility for distributors and manufacturers to enter the North American market.
                            </p>
                        </div>
                    </div>
                    <div class="tablet-spacer"></div>
                </div>
            </section>

            <section class="joinus" id="joinus" >
                <div class="small-wrapper" >
                    <h2 class="whitetxt">Join Us</h2>
                    <div class="underline"></div>
                    <div class="taglines whitetxt">
                        Getting started is simple. Start by telling us a little about yourself.
                    </div>
                    <form name="registrationForm">
                        <div class="form-wrapper">
                            <div class="form-row">
                                <div class="form-mainerror" style="display: none;"> </div>
                            </div>
                            <div class="form-row">
                                <div class="form-half">
                                    <label for="full-name" class="form-firstname">First Name</label>
                                    <div style="overflow: hidden;">
                                        <input type="text" id="full-name" name="firstName" ng-class="{error: ((registrationForm.$submitted || registrationForm.firstName.$touched) && (registrationForm.firstName.$error.required || registrationForm.firstName.$error.maxlength)), done: ((registrationForm.$submitted || registrationForm.firstName.$touched) && (!registrationForm.firstName.$error.required && !registrationForm.firstName.$error.maxlength))}" required ng-model="person.firstName" ng-maxlength="255">
                                        <div class="error-state-div">
                                            <img class="img img-responsive success" src="<?php echo $CDN_LOCATION;?>img/landing/form-success.png">
                                            <img class="img img-responsive fail" src="<?php echo $CDN_LOCATION;?>img/landing/form-failure.png">
                                        </div>
                                        <label for="full-name" class="form-firstname form-error" ng-show="registrationForm.firstName.$touched && registrationForm.firstName.$error.required">First Name is required.</label>
                                        <label for="full-name" class="form-firstname form-error" ng-show="registrationForm.firstName.$touched && registrationForm.firstName.$error.maxlength">Maximum length exceeded.</label>
                                    </div>
                                </div>
                                <div  class="form-half last">
                                    <label for="last-name" class="form-lastname">Last Name</label>
                                    <div style="overflow: hidden;">
                                        <input type="text" id="last-name" name="lastName" required ng-model="person.lastName" ng-class="{error: ((registrationForm.$submitted || registrationForm.lastName.$touched) && (registrationForm.lastName.$error.required || registrationForm.lastName.$error.maxlength)), done: ((registrationForm.$submitted || registrationForm.lastName.$touched) && (!registrationForm.lastName.$error.required && !registrationForm.lastName.$error.maxlength))}" ng-maxlength="255">
                                        <div class="error-state-div">
                                            <img class="img img-responsive success" src="<?php echo $CDN_LOCATION;?>img/landing/form-success.png">
                                            <img class="img img-responsive fail" src="<?php echo $CDN_LOCATION;?>img/landing/form-failure.png">
                                        </div>
                                        <label for="full-name" class="form-lastname form-error" ng-show="registrationForm.lastName.$touched && registrationForm.lastName.$error.required">Last Name is required.</label>
                                        <label for="full-name" class="form-lastname form-error" ng-show="registrationForm.lastName.$touched && registrationForm.lastName.$error.maxlength">Maximum length exceeded.</label>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="form-row">
                                <label for="email" class="form-email">Email</label>
                                <div style="overflow: hidden;">
                                    <input type="email" id="email" name="email" required ng-model="person.email" ng-class="{error: ((registrationForm.$submitted || registrationForm.email.$touched) && (registrationForm.email.$error.required || registrationForm.email.$error.email || registrationForm.email.$error.maxlength)), done: ((registrationForm.$submitted || registrationForm.email.$touched) && (!registrationForm.email.$error.required && !registrationForm.email.$error.email && !registrationForm.email.$error.maxlength))}" ng-maxlength="255">
                                    <div class="error-state-div">
                                        <img class="img img-responsive success" src="<?php echo $CDN_LOCATION;?>img/landing/form-success.png">
                                        <img class="img img-responsive fail" src="<?php echo $CDN_LOCATION;?>img/landing/form-failure.png">
                                    </div>
                                    <label for="email" class="form-email form-error" ng-show="registrationForm.email.$touched && registrationForm.email.$error.required">Email is required.</label>
                                    <label for="email" class="form-email form-error" ng-show="registrationForm.email.$touched && registrationForm.email.$error.email">Please provide a valid Email.</label>
                                    <label for="email" class="form-email form-error" ng-show="registrationForm.email.$touched && registrationForm.email.$error.maxlength">Maximum length exceeded.</label>
                                </div>
                            </div>
                            <div class="form-row">
                                <label for="conf-email" class="form-conf-email">Confirm Email</label>
                                <div style="overflow: hidden;">
                                    <input type="email" id="conf-email" name="confirmEmail" required ng-model="person.confirmEmail" ng-class="{error: ((registrationForm.$submitted || registrationForm.confirmEmail.$touched) && (registrationForm.confirmEmail.$error.required || registrationForm.confirmEmail.$error.email || registrationForm.confirmEmail.$error.maxlength || registrationForm.confirmEmail.$error.match)), done: ((registrationForm.$submitted || registrationForm.confirmEmail.$touched) && (!registrationForm.confirmEmail.$error.required && !registrationForm.confirmEmail.$error.email && !registrationForm.confirmEmail.$error.maxlength && !registrationForm.confirmEmail.$error.match))}" ng-maxlength="255" ng-paste="$event.preventDefault();" data-match="person.email" data-match-caseless="true">
                                    <div class="error-state-div">
                                        <img class="img img-responsive success" src="<?php echo $CDN_LOCATION;?>img/landing/form-success.png">
                                        <img class="img img-responsive fail" src="<?php echo $CDN_LOCATION;?>img/landing/form-failure.png">
                                    </div>
                                    <label for="conf-email" class="form-conf-email form-error" ng-show="registrationForm.confirmEmail.$touched && registrationForm.confirmEmail.$error.required">Email is required.</label>
                                    <label for="conf-email" class="form-conf-email form-error" ng-show="registrationForm.confirmEmail.$touched && registrationForm.confirmEmail.$error.email">Please provide a valid Email.</label>
                                    <label for="conf-email" class="form-conf-email form-error" ng-show="registrationForm.confirmEmail.$touched && registrationForm.confirmEmail.$error.maxlength">Maximum length exceeded.</label>
                                    <label for="conf-email" class="form-conf-email form-error" ng-show="registrationForm.confirmEmail.$touched && registrationForm.confirmEmail.$error.match">Email is not matching.</label>
                                </div>
                            </div>
                            <div class="form-row">
                                <label for="phone" class="form-phone">Phone Number</label>
                                <div style="overflow: hidden;">
                                    <input type="text" id="phone" name="phoneNumber" required ng-model="person.phoneNumber" ng-maxlength="25" ng-minlength="7" ng-class="{error: ((registrationForm.$submitted || registrationForm.phoneNumber.$touched) && (registrationForm.phoneNumber.$error.required || registrationForm.phoneNumber.$error.maxlength || registrationForm.phoneNumber.$error.minlength)), done: ((registrationForm.$submitted || registrationForm.phoneNumber.$touched) && (!registrationForm.phoneNumber.$error.required && !registrationForm.phoneNumber.$error.minlength && !registrationForm.phoneNumber.$error.maxlength))}">
                                    <div class="error-state-div">
                                        <img class="img img-responsive success" src="<?php echo $CDN_LOCATION;?>img/landing/form-success.png">
                                        <img class="img img-responsive fail" src="<?php echo $CDN_LOCATION;?>img/landing/form-failure.png">
                                    </div>
                                    <label for="phone" class="form-phone form-error" ng-show="registrationForm.phoneNumber.$touched && registrationForm.phoneNumber.$error.required">Phone is required.</label>
                                    <label for="phone" class="form-phone form-error" ng-show="registrationForm.phoneNumber.$touched && registrationForm.phoneNumber.$error.minlength">Minimum Phone Number length is 7.</label>
                                    <label for="phone" class="form-phone form-error" ng-show="registrationForm.phoneNumber.$touched && registrationForm.phoneNumber.$error.maxlength">Maximum length exceeded.</label>
                                </div>
                            </div>
                            <div class="form-row">
                                <label for="company-name" class="form-companyname">Company Name</label>
                                <div style="overflow: hidden;">
                                    <input type="text" id="company-name" name="companyName" required ng-model="person.companyName" ng-class="{error: ((registrationForm.$submitted || registrationForm.companyName.$touched) && (registrationForm.companyName.$error.required || registrationForm.companyName.$error.maxlength)), done: ((registrationForm.$submitted || registrationForm.companyName.$touched) && (!registrationForm.companyName.$error.required && !registrationForm.companyName.$error.maxlength))}" ng-maxlength="255">
                                    <div class="error-state-div">
                                        <img class="img img-responsive success" src="<?php echo $CDN_LOCATION;?>img/landing/form-success.png">
                                        <img class="img img-responsive fail" src="<?php echo $CDN_LOCATION;?>img/landing/form-failure.png">
                                    </div>
                                    <label for="company-name" class="form-companyname form-error" ng-show="registrationForm.companyName.$touched && registrationForm.companyName.$error.required">Company Name is required.</label>
                                    <label for="company-name" class="form-companyname form-error" ng-show="registrationForm.companyName.$touched && registrationForm.companyName.$error.maxlength">Maximum length exceeded.</label>
                                </div>
                            </div>
                            <button type="button" ng-click="submitRegistration()" ng-disabled="registrationForm.$invalid || registrationForm.$pristine" class="sign-up-button">Sign Up</button>
                            <div class="successmsg" style="display: none;">Thank you! A representative will be in contact soon!</div>
                        </div>
                    </form>
                    <a class="backtotop" href="#header"><i class="fa fa-arrow-circle-up"></i> Back to top</a>
                </div>
            </section>

            <!-- Survey Modal -->
            <div class="modal fade" id="survey" role="dialog">
              <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <a type="button" class="close" data-dismiss="modal">&times;</a>
                    <h1 class="modal-title">Thanks for Registering!</h1>
                  </div>
                  <div class="modal-body">
                    <p>A Gateway Supply Chain representative will be in touch with you regarding your next steps.</p>
                    <p>To further help us understand your needs, we invite you to fill out this short survey.</p>
                  </div>
                  <div class="modal-footer">
                    <a class="borer-button" href="#" data-dismiss="modal">No Thanks</a>
                    <button type="button" class="btn btn-default darkblue-button" ng-click="openSurvey()" >Complete Survey</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- Survey Modal Ends -->

        </main>
        <!-- Main Ends Here -->
        <!-- Footer Starts Here -->
        @include('landingfooter')
        <!-- Footer Ends Here -->
        <!-- Including jQuery  -->

        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.12/angular.min.js"></script>
        <script src="https://cdn.auth0.com/w2/auth0-7.6.1.min.js"></script>
        <script src="node_modules/angular-lock/dist/angular-lock.js"></script>
        <script type="text/javascript" src="https://cdn.auth0.com/js/lock/10.8/lock.min.js"></script>
        <!-- <script src="https://cdn.auth0.com/js/lock/10.12.1/lock.min.js"></script> -->
        <script src="node_modules/angular-storage/dist/angular-storage.js"></script>
        <script src="node_modules/angular-jwt/dist/angular-jwt.js"></script>
        <script src="node_modules/angular-ui-router/release/angular-ui-router.js"></script>
        <!-- Including jQuery  -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <!-- Including jQuery Cycle  -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.cycle2/2.1.6/jquery.cycle2.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-loading-bar/0.9.0/loading-bar.js"></script>

        <?php
            $allowedConnection = getenv("AUTH0_GATEWAY_CONNECTION_NAME");
        ?>

        <script>
            window.authDomain = '<?= $authDomain; ?>';
            window.authId = '<?= $authId; ?>';
            window.cdnLocation = '<?= $CDN_LOCATION; ?>';
            window.allowedConnection = '<?= $allowedConnection; ?>';
            $(document).ready(function () {
                console.log("Jquery Ready");

                $('nav a[href^="#"],.backtotop,.link-button,.linkz,.border-button').on('click', function(event) {
                    var target = $(this.getAttribute('href'));
                    if(!$('nav').hasClass('sticky'))
                        var height_sticky=$('nav').height();
                    else
                        var height_sticky=0;

                    if( target.length ) {
                        event.preventDefault();
                        $('html, body').stop().animate({
                            scrollTop: ((target.offset().top)-height_sticky)
                        }, 1000);
                    }
                });

                $(window).scroll(function() {
                    var distanceFromTop = $(document).scrollTop();
                    console.log("distanceFromTop"+distanceFromTop+" header height"+$('#header').height());
                    if (distanceFromTop >= $('#header').height())
                    {
                        console.log("yes");
                        $('nav').addClass('sticky');
                        $('.sticky-spacer').show();
                    }
                    else
                    {
                        $('nav').removeClass('sticky');
                        $('.sticky-spacer').hide();
                    }
                });

            });
        </script>
        <script src="js/lib/angular-validation-match/angular-validation-match.js"></script>
        <script src="js/registration.js"></script>
    </body>
</html>
