<?php
function addContactList($auth, $contactListId, $campaignActivityId)
{
    // Endpoint URL
    $url = "https://api.cc.email/v3/emails/activities/$campaignActivityId";

    // Headers
    $headers = array(
        "Authorization: Bearer " . $auth,
        "Content-Type: application/json",
        'cache-control: no-cache'
    );

    // Data
    $data = array(
        'from_email' => 'EXAMPLE@COMPANY.COM',
        'from_name' => 'COMPANY_NAME',
        'reply_to_email' => 'EXAMPLE@COMPANY.COM',
        'subject' => 'EMAIL_SUBJECT',
        'contact_list_ids' => array($contactListId)
    );

    // Initialize cURL
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // Execute cURL request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_error($ch)) {
        echo 'Error: ' . curl_error($ch);
    } else {
        // Check response for success
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpStatusCode == 200) {
            echo 'Contact list was successfully added.'; // Success message
        } else {
            echo 'Failed to add contact list. HTTP Code: ' . $httpStatusCode; // Error message
        }
        echo 'Response: ' . $response;
    }

    // Close cURL
    curl_close($ch);

    return $response;
}