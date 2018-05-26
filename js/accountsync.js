/* 
 * Salesforce account sync
 */
'use strict';

const https = require('https');
const async = require('async');

exports.handler = (event, context, callback) => {
    console.log('Loading Account Sync function');
    var records = event.Records;
    if (typeof records == 'undefined' || records == null) {
        return;
    }

    async.waterfall([
        function getAccessToken(next) {
            var accessTokenReq = {
                "audience": "http://gatewaysupplychain.com/api/admini/v1/",
                "grant_type": "client_credentials",
                "client_id": "",
                "client_secret": ""
            };
            var options = {
                host: 'builddirect-dev.auth0.com',
                path: '/oauth/token',
                headers: {'content-type': 'application/json'},
                method: 'POST'
            };
            var accessTokenJson = '';
            callback = function (response) {
                var str = '';
                response.on('data', function (chunk) {
                    str += chunk;
                });
                response.on('end', function () {
                    accessTokenJson = str;
                    next(null, accessTokenJson);
                });
            };
            var request = https.request(options, callback);
            request.write(JSON.stringify(accessTokenReq));
            request.end();
        },
        function createGwCompany(accessTokenJson, next) {
            var accessToken = JSON.parse(accessTokenJson);
            for (var i = 0, len = records.length; i < len; i++) {
                var accountJson = event.Records[i].Sns.Message;
                accountJson = accountJson.replace('\n/g', '');
                var accountArray = JSON.parse(accountJson);
                if (accountArray['SellerChannel'] != "Gateway") {
                    next(null);
                }

                var accountStatus = accountArray['Status'];
                var companyStatus = null;
                switch (accountStatus) {
                    case "Onboarding":
                        companyStatus = 'pending';
                        break;
                    case "Active":
                        companyStatus = 'approved';
                        break;
                    case "Active(Self sign-on)":
                        companyStatus = 'approved';
                        break;
                    case "Inactive":
                        companyStatus = 'inactive';
                        break;
                    case "Cancelled":
                        companyStatus = 'inactive';
                        break;
                    default:
                        next(null);
                        break;
                }

                var mailing_address = {
                    "address_id": 0,
                    "address1": accountArray['ShippingStreet'],
                    "city": accountArray['ShippingCity'],
                    "state": accountArray['ShippingState'],
                    "country": accountArray['ShippingCountry'],
                    "postal_code": accountArray['ShippingPostalCode'],
                    "country_code": null,
                    "extension": null,
                    "phone_number": accountArray['ShippingPhone']
                };

                var billing_address = {
                    "address_id": 0,
                    "address1": accountArray['BillingStreet'],
                    "city": accountArray['BillingCity'],
                    "state": accountArray['BillingState'],
                    "country": accountArray['BillingCountry'],
                    "postal_code": accountArray['BillingPostalCode'],
                    "country_code": null,
                    "extension": null,
                    "phone_number": accountArray['BillingPhone']
                };

                var same_mailing_billing = false;
                if (JSON.stringify(mailing_address) === JSON.stringify(billing_address)) {
                    same_mailing_billing = true;
                }

                var account = {
                    "company_id": accountArray['GatewayCompanyID'],
                    "legal_name": accountArray['AccountName'],
                    "status": companyStatus,
                    "sf_account_id": accountArray['ID'],
                    "sf_account_status": accountArray['Status'],
                    "gw_prospect_id": accountArray['GatewayProspectID'],
                    "same_mailing_billing": same_mailing_billing,
                    "billing_address": billing_address,
                    "mailing_address": mailing_address
                };

                var companyAlias = accountArray['CompanyAlias'];
                if (!companyAlias || companyAlias.length === 0) {
                    account['name'] = accountArray['AccountName'];
                } else {
                    account['name'] = companyAlias;
                }

                console.log('AccessToken: ' + accessToken['access_token']);
                console.log('Account Info: ' + JSON.stringify(account));
                var options = {
                    host: 'dev.gatewaysupplychain.com',
                    path: '/api/admin/v1/companies',
                    headers: {'content-type': 'application/json', 'Authorization': accessToken['access_token'], 'client': 'salesforce'},
                    method: 'POST'
                };
                var accountInfo = '';
                callback = function (response) {
                    var str = ''

                    response.on('data', function (chunk) {
                        str += chunk;
                    });
                    response.on('end', function () {
                        accountInfo = str;
                        console.log('Create/Update Status: ' + accountInfo);
                        next(null);
                    });
                }

                var request = https.request(options, callback);
                request.write(JSON.stringify(account));
                request.end();
            }
        }],
            function (err, result) {
                if (err) {
                    console.error("Error:" + err);
                }

                console.log("End of create company");
            }
    );
}