<?php

// Add the required files

// Can be found doing a get request on developer.constantcontact.com
$contactListId = "xxxxxxxxxxxxxxxxxxxx";

// Creating campaign & converting json string to object
$responseObject = json_decode(createCampaign($accessTokenFromDb, $email_text));

// Getting campaign activity ID from first index
$campaignActivityId = $responseObject->campaign_activities[0]->campaign_activity_id;

// Checking for campaign activity ID and scheduling campaign
if (!empty($campaignActivityId)) {

    // Add contact list to campaign 
    $response = addContactList($accessTokenFromDb, $contactListId, $campaignActivityId);

    if ($response)
    {
        $scheduledDate = new DateTime();

        $scheduledDate->setTime(7, 0,0); // 7 AM schedule time
        // Might have to modify time, CC was 4 hours ahead of EST. 
        $scheduledTime = $scheduledDate->format('Y-m-d\TH:i:s\Z');

        // Scheduling campaign
        scheduleEmailCampaign($accessTokenFromDb, $scheduledTime, $campaignActivityId);
    } else {
        echo "Unable to schedule campaign";
    }
} else {
    echo "Campaign Activity Id not found, unable to add contact list" . PHP_EOL;
};