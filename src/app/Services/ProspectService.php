<?php

namespace App\Services;

use \App\Common\AwsTools;

class ProspectService {

    public static function sendSns($id, $firstName, $lastName, $email, $companyName, $phoneNumber, $action, $lastModified) {
        $messageData = array();

        $messageData['data'] = array("id" => $id,
            "firstname" => $firstName,
            "lastname" => $lastName,
            "email" => $email,
            "companyName" => $companyName,
            "phoneNumber" => $phoneNumber,
        );
        $messageData['JsonVersion'] = '1.0.0';
        $messageData['messageTime'] = \gmdate('c');

        $messageJson = json_encode($messageData);

        $properties = AwsTools::getMessageProperties('Customer', $id, $email, $action, $lastModified);

        $snsClient = AwsTools::getSnsClient(getenv('SNS_REGION'));
        $queueOrTopicName = getenv("PROSPECTS_SNS_ARN");

        AwsTools::sendMessage($snsClient, $queueOrTopicName, $id, $messageJson, null, $properties);
    }

}
