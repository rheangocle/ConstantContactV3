<?php
function scheduleEmailCampaign($auth, $time, $campaignActivityId) {

    // API Endpoint
    $url = "https://api.cc.email/v3/emails/activities/$campaignActivityId/schedules";

    // Request headers
    $headers = array(
        'Authorization: Bearer ' . $auth,
        'Content-Type: application/json',
        'cache-control: no-cache'
    );

    // Request data
    $data = array(
        'scheduled_date' => $time
    );

    // Initialize curl
    $ch = curl_init();

    // Set curl options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Execute curl request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
    } else {
        echo 'Successfully scheduled campaign'.$response;
    }

    // Close curl
    curl_close($ch);
};