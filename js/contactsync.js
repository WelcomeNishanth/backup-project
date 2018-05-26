/* 
 * Salesforce contact sync
 */

const https = require('https');
const async = require('async');

exports.handler = (event, context, callback) => {
    console.log('Loading Contact Sync function');

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
        function createGwUser(accessTokenJson, next) {
            var accessToken = JSON.parse(accessTokenJson);

            for (var i = 0, len = records.length; i < len; i++) {
                var contactJson = event.Records[i].Sns.Message;
                contactJson = contactJson.replace('\n/g', '');

                var contactArray = JSON.parse(contactJson);

                var contactStatus = contactArray['Status'];
                var userStatus = null;

                switch (contactStatus) {
                    case "Inactive":
                        userStatus = 'inactive';
                        break;
                    default:
                        next(null);
                        break;
                }

                var contact = {
                    "user_id": contactArray['GatewayContactID'],
                    "status": userStatus,
                    "company_id": contactArray['GatewayCompanyID'],
                    "email": contactArray['Email'],
                    "first_name": contactArray['FirstName'],
                    "last_name": contactArray['LastName'],
                    "phone_number": contactArray['Phone'],
                    "country_code": contactArray['PhoneCountryCode'],
                    "extension": contactArray['PhoneExtension'],
                    "sf_account_id": contactArray['AccountID'],
                    "sf_contact_status": contactArray['Status'],
                    "sf_contact_id": contactArray['ID'],
                    "gw_prospect_id": contactArray['GatewayProspectID'],
                    "primary_user": contactArray['PrimaryUser'],
                };

                console.log('AccessToken: ' + accessToken['access_token']);
                console.log('Contact Info: ' + JSON.stringify(contact));

                var options = {
                    host: 'dev.gatewaysupplychain.com',
                    path: '/api/admin/v1/users',
                    headers: {'content-type': 'application/json', 'Authorization': accessToken['access_token'], 'client': 'salesforce'},
                    method: 'POST'
                };

                var contactInfo = '';
                callback = function (response) {
                    var str = ''

                    response.on('data', function (chunk) {
                        str += chunk;
                    });

                    response.on('end', function () {
                        contactInfo = str;
                        console.log('Create/Update Status: ' + contactInfo);

                        next(null);
                    });

                }

                var request = https.request(options, callback);
                request.write(JSON.stringify(contact));
                request.end();
            }
        }],
            function (err, result) {
                if (err) {
                    console.error("Error:" + err);
                }

                console.log("End of create user");
            }
    );

}