'use strict';
define([
    'angular',
    'angularRoute',
    'bootstrap'
], function (angular) {
    var app= angular.module('gatewayPortalApp.account.complete', ['ngRoute']);
    app.config(['$routeProvider', function ($routeProvider) {
        $routeProvider.when('/account/complete', {
            templateUrl: 'js/bd/modules/account/complete.html',
            controller: 'completeController'
        });
    }]);
    
    var completeController = function($scope, $http, $rootScope, $location) {
        $scope.step = 1;
        $scope.stepIndex=1;
        $scope.company = {};
        $scope.stepList={1:1,2:2,3:2.5,4:2.7,5:3};
        
         $scope.countryList = [{
                "key": "Afghanistan",
                "value": "AF"
            }, {
                "key": "Albania",
                "value": "AL"
            }, {
                "key": "Algeria",
                "value": "DZ"
            }, {
                "key": "American Samoa",
                "value": "AS"
            }, {
                "key": "Andorra",
                "value": "AD"
            }, {
                "key": "Angola",
                "value": "AO"
            }, {
                "key": "Anguilla",
                "value": "AI"
            }, {
                "key": "Antarctica",
                "value": "AQ"
            }, {
                "key": "Antigua and Barbuda",
                "value": "AG"
            }, {
                "key": "Argentina",
                "value": "AR"
            }, {
                "key": "Armenia",
                "value": "AM"
            }, {
                "key": "Aruba",
                "value": "AW"
            }, {
                "key": "Ascension Island",
                "value": "AC"
            }, {
                "key": "Australia",
                "value": "AU"
            }, {
                "key": "Austria",
                "value": "AT"
            }, {
                "key": "Azerbaijan",
                "value": "AZ"
            }, {
                "key": "Bahamas",
                "value": "BS"
            }, {
                "key": "Bahrain",
                "value": "BH"
            }, {
                "key": "Bangladesh",
                "value": "BD"
            }, {
                "key": "Barbados",
                "value": "BB"
            }, {
                "key": "Belarus",
                "value": "BY"
            }, {
                "key": "Belgium",
                "value": "BE"
            }, {
                "key": "Belize",
                "value": "BZ"
            }, {
                "key": "Benin",
                "value": "BJ"
            }, {
                "key": "Bermuda",
                "value": "BM"
            }, {
                "key": "Bhutan",
                "value": "BT"
            }, {
                "key": "Bolivia",
                "value": "BO"
            }, {
                "key": "Bosnia and Herzegovina",
                "value": "BA"
            }, {
                "key": "Botswana",
                "value": "BW"
            }, {
                "key": "Bouvet Island",
                "value": "BV"
            }, {
                "key": "Brazil",
                "value": "BR"
            }, {
                "key": "British Indian Ocean Territory",
                "value": "IO"
            }, {
                "key": "British Virgin Islands",
                "value": "VG"
            }, {
                "key": "Brunei",
                "value": "BN"
            }, {
                "key": "Bulgaria",
                "value": "BG"
            }, {
                "key": "Burkina Faso",
                "value": "BF"
            }, {
                "key": "Burundi",
                "value": "BI"
            }, {
                "key": "Cambodia",
                "value": "KH"
            }, {
                "key": "Cameroon",
                "value": "CM"
            }, {
                "key": "Canada",
                "value": "CA"
            }, {
                "key": "Canary Islands",
                "value": "IC"
            }, {
                "key": "Cape Verde",
                "value": "CV"
            }, {
                "key": "Caribbean Netherlands",
                "value": "BQ"
            }, {
                "key": "Cayman Islands",
                "value": "KY"
            }, {
                "key": "Central African Republic",
                "value": "CF"
            }, {
                "key": "Ceuta and Melilla",
                "value": "EA"
            }, {
                "key": "Chad",
                "value": "TD"
            }, {
                "key": "Chile",
                "value": "CL"
            }, {
                "key": "China",
                "value": "CN"
            }, {
                "key": "Christmas Island",
                "value": "CX"
            }, {
                "key": "Clipperton Island",
                "value": "CP"
            }, {
                "key": "Cocos (Keeling) Islands",
                "value": "CC"
            }, {
                "key": "Colombia",
                "value": "CO"
            }, {
                "key": "Comoros",
                "value": "KM"
            }, {
                "key": "Congo - Brazzaville",
                "value": "CG"
            }, {
                "key": "Congo - Kinshasa",
                "value": "CD"
            }, {
                "key": "Cook Islands",
                "value": "CK"
            }, {
                "key": "Costa Rica",
                "value": "CR"
            }, {
                "key": "Croatia",
                "value": "HR"
            }, {
                "key": "Cuba",
                "value": "CU"
            }, {
                "key": "Cura\u00e7ao",
                "value": "CW"
            }, {
                "key": "Cyprus",
                "value": "CY"
            }, {
                "key": "Czech Republic",
                "value": "CZ"
            }, {
                "key": "C\u00f4te d\u2019Ivoire",
                "value": "CI"
            }, {
                "key": "Denmark",
                "value": "DK"
            }, {
                "key": "Diego Garcia",
                "value": "DG"
            }, {
                "key": "Djibouti",
                "value": "DJ"
            }, {
                "key": "Dominica",
                "value": "DM"
            }, {
                "key": "Dominican Republic",
                "value": "DO"
            }, {
                "key": "Ecuador",
                "value": "EC"
            }, {
                "key": "Egypt",
                "value": "EG"
            }, {
                "key": "El Salvador",
                "value": "SV"
            }, {
                "key": "Equatorial Guinea",
                "value": "GQ"
            }, {
                "key": "Eritrea",
                "value": "ER"
            }, {
                "key": "Estonia",
                "value": "EE"
            }, {
                "key": "Ethiopia",
                "value": "ET"
            }, {
                "key": "European Union",
                "value": "EU"
            }, {
                "key": "Falkland Islands",
                "value": "FK"
            }, {
                "key": "Faroe Islands",
                "value": "FO"
            }, {
                "key": "Fiji",
                "value": "FJ"
            }, {
                "key": "Finland",
                "value": "FI"
            }, {
                "key": "France",
                "value": "FR"
            }, {
                "key": "French Guiana",
                "value": "GF"
            }, {
                "key": "French Polynesia",
                "value": "PF"
            }, {
                "key": "French Southern Territories",
                "value": "TF"
            }, {
                "key": "Gabon",
                "value": "GA"
            }, {
                "key": "Gambia",
                "value": "GM"
            }, {
                "key": "Georgia",
                "value": "GE"
            }, {
                "key": "Germany",
                "value": "DE"
            }, {
                "key": "Ghana",
                "value": "GH"
            }, {
                "key": "Gibraltar",
                "value": "GI"
            }, {
                "key": "Greece",
                "value": "GR"
            }, {
                "key": "Greenland",
                "value": "GL"
            }, {
                "key": "Grenada",
                "value": "GD"
            }, {
                "key": "Guadeloupe",
                "value": "GP"
            }, {
                "key": "Guam",
                "value": "GU"
            }, {
                "key": "Guatemala",
                "value": "GT"
            }, {
                "key": "Guernsey",
                "value": "GG"
            }, {
                "key": "Guinea",
                "value": "GN"
            }, {
                "key": "Guinea-Bissau",
                "value": "GW"
            }, {
                "key": "Guyana",
                "value": "GY"
            }, {
                "key": "Haiti",
                "value": "HT"
            }, {
                "key": "Heard & McDonald Islands",
                "value": "HM"
            }, {
                "key": "Honduras",
                "value": "HN"
            }, {
                "key": "Hong Kong SAR China",
                "value": "HK"
            }, {
                "key": "Hungary",
                "value": "HU"
            }, {
                "key": "Iceland",
                "value": "IS"
            }, {
                "key": "India",
                "value": "IN"
            }, {
                "key": "Indonesia",
                "value": "ID"
            }, {
                "key": "Iran",
                "value": "IR"
            }, {
                "key": "Iraq",
                "value": "IQ"
            }, {
                "key": "Ireland",
                "value": "IE"
            }, {
                "key": "Isle of Man",
                "value": "IM"
            }, {
                "key": "Israel",
                "value": "IL"
            }, {
                "key": "Italy",
                "value": "IT"
            }, {
                "key": "Jamaica",
                "value": "JM"
            }, {
                "key": "Japan",
                "value": "JP"
            }, {
                "key": "Jersey",
                "value": "JE"
            }, {
                "key": "Jordan",
                "value": "JO"
            }, {
                "key": "Kazakhstan",
                "value": "KZ"
            }, {
                "key": "Kenya",
                "value": "KE"
            }, {
                "key": "Kiribati",
                "value": "KI"
            }, {
                "key": "Kosovo",
                "value": "XK"
            }, {
                "key": "Kuwait",
                "value": "KW"
            }, {
                "key": "Kyrgyzstan",
                "value": "KG"
            }, {
                "key": "Laos",
                "value": "LA"
            }, {
                "key": "Latvia",
                "value": "LV"
            }, {
                "key": "Lebanon",
                "value": "LB"
            }, {
                "key": "Lesotho",
                "value": "LS"
            }, {
                "key": "Liberia",
                "value": "LR"
            }, {
                "key": "Libya",
                "value": "LY"
            }, {
                "key": "Liechtenstein",
                "value": "LI"
            }, {
                "key": "Lithuania",
                "value": "LT"
            }, {
                "key": "Luxembourg",
                "value": "LU"
            }, {
                "key": "Macau SAR China",
                "value": "MO"
            }, {
                "key": "Macedonia",
                "value": "MK"
            }, {
                "key": "Madagascar",
                "value": "MG"
            }, {
                "key": "Malawi",
                "value": "MW"
            }, {
                "key": "Malaysia",
                "value": "MY"
            }, {
                "key": "Maldives",
                "value": "MV"
            }, {
                "key": "Mali",
                "value": "ML"
            }, {
                "key": "Malta",
                "value": "MT"
            }, {
                "key": "Marshall Islands",
                "value": "MH"
            }, {
                "key": "Martinique",
                "value": "MQ"
            }, {
                "key": "Mauritania",
                "value": "MR"
            }, {
                "key": "Mauritius",
                "value": "MU"
            }, {
                "key": "Mayotte",
                "value": "YT"
            }, {
                "key": "Mexico",
                "value": "MX"
            }, {
                "key": "Micronesia",
                "value": "FM"
            }, {
                "key": "Moldova",
                "value": "MD"
            }, {
                "key": "Monaco",
                "value": "MC"
            }, {
                "key": "Mongolia",
                "value": "MN"
            }, {
                "key": "Montenegro",
                "value": "ME"
            }, {
                "key": "Montserrat",
                "value": "MS"
            }, {
                "key": "Morocco",
                "value": "MA"
            }, {
                "key": "Mozambique",
                "value": "MZ"
            }, {
                "key": "Myanmar (Burma)",
                "value": "MM"
            }, {
                "key": "Namibia",
                "value": "NA"
            }, {
                "key": "Nauru",
                "value": "NR"
            }, {
                "key": "Nepal",
                "value": "NP"
            }, {
                "key": "Netherlands",
                "value": "NL"
            }, {
                "key": "Netherlands Antilles",
                "value": "AN"
            }, {
                "key": "New Caledonia",
                "value": "NC"
            }, {
                "key": "New Zealand",
                "value": "NZ"
            }, {
                "key": "Nicaragua",
                "value": "NI"
            }, {
                "key": "Niger",
                "value": "NE"
            }, {
                "key": "Nigeria",
                "value": "NG"
            }, {
                "key": "Niue",
                "value": "NU"
            }, {
                "key": "Norfolk Island",
                "value": "NF"
            }, {
                "key": "North Korea",
                "value": "KP"
            }, {
                "key": "Northern Mariana Islands",
                "value": "MP"
            }, {
                "key": "Norway",
                "value": "NO"
            }, {
                "key": "Oman",
                "value": "OM"
            }, {
                "key": "Outlying Oceania",
                "value": "QO"
            }, {
                "key": "Pakistan",
                "value": "PK"
            }, {
                "key": "Palau",
                "value": "PW"
            }, {
                "key": "Palestinian Territories",
                "value": "PS"
            }, {
                "key": "Panama",
                "value": "PA"
            }, {
                "key": "Papua New Guinea",
                "value": "PG"
            }, {
                "key": "Paraguay",
                "value": "PY"
            }, {
                "key": "Peru",
                "value": "PE"
            }, {
                "key": "Philippines",
                "value": "PH"
            }, {
                "key": "Pitcairn Islands",
                "value": "PN"
            }, {
                "key": "Poland",
                "value": "PL"
            }, {
                "key": "Portugal",
                "value": "PT"
            }, {
                "key": "Puerto Rico",
                "value": "PR"
            }, {
                "key": "Qatar",
                "value": "QA"
            }, {
                "key": "Romania",
                "value": "RO"
            }, {
                "key": "Russia",
                "value": "RU"
            }, {
                "key": "Rwanda",
                "value": "RW"
            }, {
                "key": "R\u00e9union",
                "value": "RE"
            }, {
                "key": "Saint Barth\u00e9lemy",
                "value": "BL"
            }, {
                "key": "Saint Helena",
                "value": "SH"
            }, {
                "key": "Saint Kitts and Nevis",
                "value": "KN"
            }, {
                "key": "Saint Lucia",
                "value": "LC"
            }, {
                "key": "Saint Martin",
                "value": "MF"
            }, {
                "key": "Saint Pierre and Miquelon",
                "value": "PM"
            }, {
                "key": "Samoa",
                "value": "WS"
            }, {
                "key": "San Marino",
                "value": "SM"
            }, {
                "key": "Saudi Arabia",
                "value": "SA"
            }, {
                "key": "Senegal",
                "value": "SN"
            }, {
                "key": "Serbia",
                "value": "RS"
            }, {
                "key": "Seychelles",
                "value": "SC"
            }, {
                "key": "Sierra Leone",
                "value": "SL"
            }, {
                "key": "Singapore",
                "value": "SG"
            }, {
                "key": "Sint Maarten",
                "value": "SX"
            }, {
                "key": "Slovakia",
                "value": "SK"
            }, {
                "key": "Slovenia",
                "value": "SI"
            }, {
                "key": "Solomon Islands",
                "value": "SB"
            }, {
                "key": "Somalia",
                "value": "SO"
            }, {
                "key": "South Africa",
                "value": "ZA"
            }, {
                "key": "South Georgia & South Sandwich Islands",
                "value": "GS"
            }, {
                "key": "South Korea",
                "value": "KR"
            }, {
                "key": "South Sudan",
                "value": "SS"
            }, {
                "key": "Spain",
                "value": "ES"
            }, {
                "key": "Sri Lanka",
                "value": "LK"
            }, {
                "key": "St. Vincent & Grenadines",
                "value": "VC"
            }, {
                "key": "Sudan",
                "value": "SD"
            }, {
                "key": "Suriname",
                "value": "SR"
            }, {
                "key": "Svalbard and Jan Mayen",
                "value": "SJ"
            }, {
                "key": "Swaziland",
                "value": "SZ"
            }, {
                "key": "Sweden",
                "value": "SE"
            }, {
                "key": "Switzerland",
                "value": "CH"
            }, {
                "key": "Syria",
                "value": "SY"
            }, {
                "key": "S\u00e3o Tom\u00e9 and Pr\u00edncipe",
                "value": "ST"
            }, {
                "key": "Taiwan",
                "value": "TW"
            }, {
                "key": "Tajikistan",
                "value": "TJ"
            }, {
                "key": "Tanzania",
                "value": "TZ"
            }, {
                "key": "Thailand",
                "value": "TH"
            }, {
                "key": "Timor-Leste",
                "value": "TL"
            }, {
                "key": "Togo",
                "value": "TG"
            }, {
                "key": "Tokelau",
                "value": "TK"
            }, {
                "key": "Tonga",
                "value": "TO"
            }, {
                "key": "Trinidad and Tobago",
                "value": "TT"
            }, {
                "key": "Tristan da Cunha",
                "value": "TA"
            }, {
                "key": "Tunisia",
                "value": "TN"
            }, {
                "key": "Turkey",
                "value": "TR"
            }, {
                "key": "Turkmenistan",
                "value": "TM"
            }, {
                "key": "Turks and Caicos Islands",
                "value": "TC"
            }, {
                "key": "Tuvalu",
                "value": "TV"
            }, {
                "key": "U.S. Outlying Islands",
                "value": "UM"
            }, {
                "key": "U.S. Virgin Islands",
                "value": "VI"
            }, {
                "key": "Uganda",
                "value": "UG"
            }, {
                "key": "Ukraine",
                "value": "UA"
            }, {
                "key": "United Arab Emirates",
                "value": "AE"
            }, {
                "key": "United Kingdom",
                "value": "GB"
            }, {
                "key": "United States",
                "value": "US"
            }, {
                "key": "Unknown Region",
                "value": "ZZ"
            }, {
                "key": "Uruguay",
                "value": "UY"
            }, {
                "key": "Uzbekistan",
                "value": "UZ"
            }, {
                "key": "Vanuatu",
                "value": "VU"
            }, {
                "key": "Vatican City",
                "value": "VA"
            }, {
                "key": "Venezuela",
                "value": "VE"
            }, {
                "key": "Vietnam",
                "value": "VN"
            }, {
                "key": "Wallis and Futuna",
                "value": "WF"
            }, {
                "key": "Western Sahara",
                "value": "EH"
            }, {
                "key": "Yemen",
                "value": "YE"
            }, {
                "key": "Zambia",
                "value": "ZM"
            }, {
                "key": "Zimbabwe",
                "value": "ZW"
            }, {
                "key": "\u00c5land Islands",
                "value": "AX"
            }
        ];
        
         $scope.emptyState = function(d){
            d.state = '';
        };
        
        $scope.getUsers = function() {
            $http({
                method: 'GET',
                url: '/api/v1/users'
            }).success(function(data) {
                if(data.success) {
                    $scope.user = data.data;
                }
            });
        }
        $scope.getUsers();

        $scope.getCompany = function() {
            $http({
                method: 'GET',
                url: '/api/v1/companies'
            }).success(function(data) {
                if(data.success) {
                    $scope.company = data.data;
                    if($scope.company.billing_address) {
                        $scope.company.billing_address.country_code="+1";
                    } else {
                        $scope.company.billing_address = {country_code: "+1"};
                    }
                    if($scope.company.mailing_address) {
                        $scope.company.mailing_address.country_code="+1";
                    } else {
                        $scope.company.mailing_address = {country_code: "+1"};
                    }
                }
            });
        }
        $scope.getCompany();
        
        $scope.stateList= { US : [
                {value: 'Alabama'},
                {value: 'Alaska'},
                {value: 'American Samoa'},
                {value: 'Arizona'},
                {value: 'Arkansas'},
                {value: 'California'},
                {value: 'Colorado'},
                {value: 'Connecticut'},
                {value: 'Delaware'},
                {value: 'District of Columbia'},
                {value: 'Florida'},
                {value: 'Georgia'},
                {value: 'Guam'},
                {value: 'Hawaii'},
                {value: 'Idaho'},
                {value: 'Illinois'},
                {value: 'Indiana'},
                {value: 'Iowa'},
                {value: 'Kansas'},
                {value: 'Kentucky'},
                {value: 'Louisiana'},
                {value: 'Maine'},
                {value: 'Maryland'},
                {value: 'Massachusetts'},
                {value: 'Michigan'},
                {value: 'Minnesota'},
                {value: 'Mississippi'},
                {value: 'Missouri'},
                {value: 'Montana'},
                {value: 'Nebraska'},
                {value: 'Nevada'},
                {value: 'New Hampshire'},
                {value: 'New Jersey'},
                {value: 'New Mexico'},
                {value: 'New York'},
                {value: 'North Carolina'},
                {value: 'North Dakota'},
                {value: 'Northern Mariana Islands'},
                {value: 'Ohio'},
                {value: 'Oklahoma'},
                {value: 'Oregon'},
                {value: 'Pennsylvania'},
                {value: 'Puerto Rico'},
                {value: 'Rhode Island'},
                {value: 'South Carolina'},
                {value: 'South Dakota'},
                {value: 'Tennessee'},
                {value: 'Texas'},
                {value: 'United States Minor Outlying Islands'},
                {value: 'Utah'},
                {value: 'Vermont'},
                {value: 'Virgin Islands, U.S.'},
                {value: 'Virginia'},
                {value: 'Washington'},
                {value: 'West Virginia'},
                {value: 'Wisconsin'},
                {value: 'Wyoming'}
            ],
            CA : [
                {value: 'Alberta'},
                {value: 'British Columbia'},
                {value: 'Manitoba'},
                {value: 'New Brunswick'},
                {value: 'Newfoundland and Labrador'},
                {value: 'Northwest Territories'},
                {value: 'Nova Scotia'},
                {value: 'Nunavut'},
                {value: 'Ontario'},
                {value: 'Prince Edward Island'},
                {value: 'Quebec'},
                {value: 'Saskatchewan'},
                {value: 'Yukon Territory'}
            ]
        };
        $scope.getStateByCountry = function(country) {
            if(country && typeof country !='undefined') {
                /*var countryValue = angular.copy(country);
                countryValue=countryValue.replace(/ /g, "_");*/
                return $scope.stateList[country];
            }
        }
        
        $scope.saveAndContinue = function(className) {
            $('.'+className+'-msg').hide();
            var url = "/api/v1/companies";
            var data = {name: $scope.company.name, legal_name: $scope.company.legal_name};
            if($scope.step==1) {
                url = "/api/v1/users";
                data = $scope.user;
            } else if($scope.step==2.5) {
                data.same_mailing_billing = $scope.company.same_mailing_billing;
                data.mailing_address = $scope.company.mailing_address;
            } else if($scope.step==2.7) {
                data.same_mailing_billing = $scope.company.same_mailing_billing;
                data.billing_address = $scope.company.billing_address;
            } else if($scope.step == 3) {
                data.signed_agreement = $scope.company.signed_agreement;
            }
            console.log(url);
            console.log(data);
            $http({
                method: 'POST',
                url: url,
                data: data
            }).success(function(data) {
                if(data.success) {
                    if($scope.step==3) {
                        $rootScope.user.contractSigned=1;
                        $location.path("/home");
                    }
                    $scope.nextStep();
                } else {
                    var message = JSON.stringify(data.message);
                    message = message.replace(/\\n/g, "");
                    $('.'+className+'-error-content').html(message.replace(/"/g, ""));
                    $('.'+className+'-error').show();
                    animateMsgBox('.'+className+'-error');
                }
            }).error(function(data) {
                var message = JSON.stringify(data.message);
                message = message.replace(/\\n/g, "");
                $('.'+className+'-error-content').html(message.replace(/"/g, ""));
                $('.'+className+'-error').show();
                animateMsgBox('.'+className+'-error');
            });
        }
		$scope.close = function(className) {
            closeAlertMsgBox(className);
        }
        $scope.nextStep = function() {
            $scope.stepIndex++;
            if($scope.stepIndex==4 && $scope.company.same_mailing_billing) {
                $scope.company.billing_address = $scope.company.mailing_address;
                $scope.stepIndex++;
            }
            if($scope.stepIndex>5) {
                $scope.stepIndex=5;
            }
            $scope.step= $scope.stepList[$scope.stepIndex];
        }
        $scope.backToPreviousStep = function() {
            $scope.stepIndex--;
            if($scope.stepIndex==4 && $scope.company.same_mailing_billing) {
                $scope.stepIndex--;
            }
            if($scope.stepIndex<1) {
                $scope.stepIndex=1;
            }
            $scope.step= $scope.stepList[$scope.stepIndex];
        }
        $scope.downloadAgreement = function() {
            $http({
                url: '/api/v1/downloadAgreement',
                method: 'GET',
                responseType:'arraybuffer'
            }).success(function (data, status, headers) {
                headers = headers();
                
                var filename = headers['x-filename'];
                var contentType = headers['content-type'];

                var linkElement = document.createElement('a');
                try {
                    var blob = new Blob([data], { type: contentType });
                    var url = window.URL.createObjectURL(blob);

                    linkElement.setAttribute('href', url);
                    linkElement.setAttribute("download", filename);

                    var clickEvent = new MouseEvent("click", {
                        "view": window,
                        "bubbles": true,
                        "cancelable": false
                    });
                    linkElement.dispatchEvent(clickEvent);
                } catch (ex) {
                    //console.log(ex);
                }
            })
        }
        
    };
    app.controller('completeController', completeController);
});