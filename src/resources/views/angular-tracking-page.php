<body ng-app="trackingApp" ng-controller="trackingController">
        <div ng-show="dataLoadInProgress">
            <div class="loading--gateway" style="display: block">
                <div class="icon-sent"></div>
            </div>
        </div>
        <?php
            $CDN_LOCATION = 'https://' . getenv("CDN_LOCATION") . "/";
        ?>
        <!-- Nav Starts Here -->
        <nav class="navbar navbar-fixed-top topnav" role="navigation">
            <div class="wide-wrapper overflow-wrapper">
                <a href="/" class="logo--news" title="Gateway">
                    <img src="<?php echo $CDN_LOCATION;?>img/gateway-white.png" alt="Gateway">
                </a>
                <div class="pull-right landing--menu">
                    <ul class="nav nav-pills">
                        <li ng-show="false"><a href="#" class="main-menu home-menu">Who We Service</a></li>
                        <li><a href="/news" class="main-menu quote-menu">Newsroom</a></li>
                        <li ng-show="false"><a href="#" class="main-menu account-menu">FAQ</a></li>
                        <li><a href="/tracking" class="main-menu account-menu">Track Shipment</a></li>
                        <li><a href="javascript: void(0);" ng-click="login()" class="main-menu account-menu">Log In</a></li>
                    </ul>
                </div>
                <!-- <a href="#" ng-click="login()" class="login--btn">Sign In</a> -->
            </div>
            <div class="clearfix"></div>
        </nav>
        <!-- Nav Ends Here -->

        <!-- Header Starts -->
        <div class="business-header trackshipment--header">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="tagline">Track Shipment</h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- Header Ends-->
        <!-- Main Starts Herer -->
        <main style="min-height: 500px;" id="trackingMainId">
            <div class="track--shipment">
                <section class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <h2>Track your shipment</h2>
                            <span class="subhead">Here you can check the status of your order. To get started enter your Delivery Order ID below.</span>

                            <div class="input-group image-preview" data-original-title="" title="">
                                <label>Delivery Order ID</label>
                                <input type="text" class="form-control image-preview-filename" ng-model="trackId">
                                <span class="input-group-btn">
                                    <button class="gateway--button__large teal" ng-click="getDeliveryDetails()">
                                        Track Shipment
                                    </button>
                                </span>
                                <div class="clearfix"></div>
                                <br>
                                <div class="alert alert-danger" role="alert" style="display: none;">
                                    Delivery details not available.
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="track--shipment__main" ng-show="delivery != null && delivery != '' && dataLoadInProgress==false" ng-cloak>
                <section class="container">
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <h5>ORDER DETAILS FOR</h5>
                            <h1>Delivery Order ID: {{delivery.deliveryId}} <a href="#" class="btn--share" ng-show="false">Track another shipment</a></h1>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-3 col-lg-3">
                            <h4>Delivery To:</h4>
                            <span class="common--text">
                                {{delivery.destinationLocation.contact.name}}</br>
                                <span ng-show="delivery.destinationLocation.addressLine1 != null && delivery.destinationLocation.addressLine1 != ''">{{delivery.destinationLocation.addressLine1}},</br></span>
                                <span ng-show="delivery.destinationLocation.city != null && delivery.destinationLocation.city != ''">{{delivery.destinationLocation.city}},</span> <span ng-show="delivery.destinationLocation.state != null && delivery.destinationLocation.state != ''">{{delivery.destinationLocation.state}},</span> {{delivery.destinationLocation.country}} {{delivery.destinationLocation.postalCode}}</br>
                                <span ng-show="delivery.destinationLocation.contact.phone != null && delivery.destinationLocation.contact.phone != ''">{{delivery.destinationLocation.contact.phone}}<br></span>
                                <span ng-show="delivery.destinationLocation.locationType != null && delivery.destinationLocation.locationType != ''"><strong>Delivery Location Type: </strong>{{delivery.destinationLocation.locationType}}</span>
                            </span>
                            <h6 ng-show="delivery.shippingNotes.destinationLocation != null && delivery.shippingNotes.destinationLocation != ''">Delivery Shipping Notes</h6>
                            <span class="common--text"ng-show="delivery.shippingNotes.destinationLocation != null && delivery.shippingNotes.destinationLocation != ''">{{delivery.shippingNotes.destinationLocation}} </span>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <h4></h4>
                            <span class="common--text">
                                <span ng-show="delivery.quote_id != null && delivery.quote_id != ''"><strong>Quote Id:</strong>{{delivery.quote_id}}</br></span>
                                <strong>No. of Products:</strong> {{delivery.freightItems.length}}</br>
                                <strong>Order Total:</strong> ${{delivery.orderTotal| number: 2}}</br>
                                <span ng-show="delivery.shipping_option != null && delivery.shipping_option != ''"><strong>Shipping Option:</strong> {{delivery.shipping_option}}</br></span>
                                <span ng-show="delivery.drop_off_location != null && delivery.drop_off_location != ''"><strong>Drop-off Location:</strong> {{delivery.drop_off_location}}</br></br></span>
                                <strong ng-show="delivery.pickUpServices != null && delivery.pickUpServices != ''">Pickup Services:</strong> {{delivery.pickUpServices}}</br>
                                <strong ng-show="delivery.deliveryServices != null && delivery.deliveryServices != ''">Delivery Services:</strong> {{delivery.deliveryServices}}
                            </span>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="tracking" ng-repeat="item in delivery.freightItems">
                            <div class="product--details">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Product {{$index + 1}}</div>
                                    <div class="panel-body">
                                        <div class="col-sm-12 col-md-12 col-lg-12 bs-wizard">
                                            <div class="col-xs-6 bs-wizard-step" ng-class="getStatusCode(item.transitStatus) >= 2 ? 'intransit' : 'disabled'" id="track1">
                                                <div class="progress"><div class="progress-bar"></div></div>
                                                <a href="#" class="bs-wizard-dot step-completed">
                                                    <i class="fa fa-check" aria-hidden="true"></i>
                                                    <div class="bs-wizard-stepnum">Picked Up</div>
                                                </a>
                                                <a href="#" class="bs-wizard-dot-last intransit--status">
                                                    <i class="fa" aria-hidden="true" ng-class="{'fa-check' : getStatusCode(item.transitStatus) >= 3}"></i>
                                                    <div class="bs-wizard-stepnum">In Transit</div>
                                                </a>
                                            </div>
                                            <div class="col-xs-6 bs-wizard-step" ng-class="getStatusCode(item.transitStatus) >= 3 ? 'intransit' : 'disabled'" id="track2">
                                                <div class="progress"><div class="progress-bar"></div></div>
                                                <a href="#" class="bs-wizard-dot-last intransit--status">
                                                    <i class="fa" aria-hidden="true" ng-class="{'fa-check' : getStatusCode(item.transitStatus) >= 3}"></i>
                                                    <div class="bs-wizard-stepnum">Delivered</div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12">
                                            <h6 ng-show="item.description != null && item.description != ''">Description:</h6>
                                            <span class="common--text" ng-show="item.description != null && item.description != ''">
                                                {{item.description}}
                                            </span>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                            <h6>Pickup from:</h6>
                                            <span class="common--text">
                                                {{item.originLocation.addressLine1}}<br/>
                                                <span ng-show="item.originLocation.addressLine2 != null && item.originLocation.addressLine2 != ''">{{item.originLocation.addressLine2}},<br/></span>
                                                <span ng-show="item.originLocation.city != null && item.originLocation.city != ''">{{item.originLocation.city}},</span> <span ng-show="item.originLocation.state != null && item.originLocation.state != ''">{{item.originLocation.state}},</span> <span ng-show="item.originLocation.country != null && item.originLocation.country != ''">{{item.originLocation.country}}</span> {{item.originLocation.postalCode}}</br>
                                                {{item.originLocation.contact.phone}}
                                            </span>
                                            <h6 ng-show="item.originLocation.locationType != null && item.originLocation.locationType != ''">Pickup Location Type:</h6>
                                            <span ng-show="item.originLocation.locationType != null && item.originLocation.locationType != ''">{{item.originLocation.locationType}}</span>
                                            <h6 ng-show="item.originNotes != null && item.originNotes != ''">Pickup Shipping Notes</h6>
                                            <span class="common--text" ng-show="item.originNotes != null && item.originNotes != ''">{{item.originNotes}}</span>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                            <h6>Product Details</h6>
                                            <span class="common--text">
                                                <span class="product--dimensions">{{item.quantity}} {{item.unit}} of {{item.dimensions.length}}” x {{item.dimensions.width}}” x {{item.dimensions.height}}” at {{item.weight.weight}} {{item.weight.unit}}</span></br>
                                                <span class=""ng-show="item.classCode != null && item.classCode != ''">Class code: {{item.classCode}}</span></br>
                                                <span class="" ng-show="item.nmfc != null && item.nmfc != ''">NMFC: {{item.nmfc}}</span></br>
                                                <!-- Commenting the link as we are not supporting this in the API - 01-09-2017 -->
                                                <!--                                                <a href="#">Get shipping label</a>-->
                                            </span>
                                            <h6 ng-show="item.pickedUpDate != null && item.pickedUpDate != ''">Picked Up On</h6>
                                            <span class="common--text">
                                                <span class="">{{item.pickedUpDate}}</span>
                                            </span>
                                            <h6><span ng-show="item.carrier == null || item.carrier == ''">Pickup </span>Carrier</h6>
                                            <span class="common--text">
                                                <span class=""ng-show="item.carrier != null && item.carrier != ''">
                                                    {{item.carrier.name}}</br>
                                                    {{item.carrier.phone}}</br>
                                                    {{item.carrier.email}}</br>
                                                    <a href="javascript: void(0)" ng-show="false">View signature</a>
                                                </span>
                                                <span class="" ng-show="item.carrier == null && item.carrier == ''">
                                                    {{item.pickUpCarrier.name}}</br>
                                                    {{item.pickUpCarrier.phone}}</br>
                                                    {{item.pickUpCarrier.email}}</br>
                                                    <a href="javascript: void(0)" ng-show="false">View signature</a>
                                                </span>
                                            </span>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                            <h6 ng-show="item.pickedUpDate == null || item.pickedUpDate == ''">Est. Pickup Date</h6>
                                            <span class="common--text" ng-show="item.pickedUpDate == null || item.pickedUpDate == ''">
                                                <span class="">{{item.estimatedPickedUpDate}}</span>
                                            </span>
                                            <h6 ng-show="item.pickedUpDate != null && item.pickedUpDate != ''">Picked Up On</h6>
                                            <span class="common--text" ng-show="item.pickedUpDate != null && item.pickedUpDate != ''">
                                                <span class="">{{item.pickedUpDate}}</span>
                                            </span>
                                            <h6 ng-show="item.deliveredDate == null || item.estimtatedDeliveryDate == ''">Est. Delivery Date</h6>
                                            <span class="common--text" ng-show="item.deliveredDate == null && item.deliveredDate == ''">
                                                <span class="">{{item.estimtatedDeliveryDate}}</span>
                                            </span>
                                            <h6 ng-show="item.deliveredDate != null && item.deliveredDate != ''">Delivered Date</h6>
                                            <span class="common--text" ng-show="item.deliveredDate != null && item.deliveredDate != ''">
                                                <span class="">{{item.deliveredDate}}</span>
                                            </span>
                                            <h6 ng-show="item.deliveryCarrier != null && item.deliveryCarrier != ''">Delivery Carrier</h6>
                                            <span class="common--text" ng-show="item.deliveryCarrier != null && item.deliveryCarrier != ''">
                                                <span class="">
                                                    {{item.deliveryCarrier.name}}</br>
                                                    {{item.deliveryCarrier.phone}}</br>
                                                    {{item.deliveryCarrier.email}}</br>
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tracking--details">
                                <span class="tracking--details__heading">Tracking Details</span>
                                <label class="intransit">{{item.transitStatus}}</label>
                                <ul class="tracking--details__section" ng-repeat="tracking in item.trackingInfo" ng-show="item.trackingInfo != null && item.trackingInfo != ''">
                                    <li>
                                        <span>{{tracking.date}}</soan>
                                    </li>
                                    <li ng-repeat="event in tracking.events">
                                        <strong class="status">{{event.time}} {{event.status}}</strong></br>
                                        <span>{{event.location}}</soan>
                                    </li>
                                </ul>
                                <ul class="tracking--details__section" ng-show="item.trackingInfo == null || item.trackingInfo == ''">
                                    <li style="text-align: center; margin: 10px 10px; border: none;">Information not available.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </section>
            </div>
        </main>
    </body>
