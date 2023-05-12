<?php

function createCampaign($auth, $email_text) {

    // Today's date
    $dateString = date('M j, Y');

    // Set the API endpoint
    $url = 'https://api.cc.email/v3/emails';

    // Set the request body as an array
    $requestBody = array(
        'name' => random_int(1,100000).'Daily News: '.$dateString, // name has to be unique
        'email_campaign_activities' => array(
            array(
                'format_type' => 5,
                'from_email' => 'EXAMPLE@COMPANY.COM',
                'from_name' => 'COMPANY_NAME',
                'reply_to_email' => 'EXAMPLE@COMPANY.COM',
                'subject' => 'EMAIL_SUBJECT',
                'html_content' => $email_text,
                'physical_address_in_footer' => array(
                    'address_line1' => 'ADDRESS 1',
                    'city' => 'CITY',
                    'state_code' => 'XX',
                    'country_code' => 'XX',
                    'organization_name' => 'ORG_NAME',
                    'postal_code' => 'ZIPCODE',
                ),
            )
        )
    );

    // Set the authorization header
    $authorization = 'Authorization: Bearer ' . $auth;

    // Set the content type header
    $contentType = 'Content-Type: application/json';

    // Initialize curl
    $ch = curl_init();

    // Set the curl options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array($authorization, $contentType));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the curl request
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        $errorMessage = 'cURL Error: ' . curl_error($ch);
        throw new Exception($errorMessage);
    }

    // Get the HTTP status code
    $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Close curl
    curl_close($ch);

    // Check for a successful response
    if ($httpStatusCode >= 200 && $httpStatusCode < 300) {
        return $response;
    } else {
        $errorMessage = 'HTTP Error: ' . $httpStatusCode . ', Response: ' . $response;
        throw new Exception($errorMessage);
    }

};