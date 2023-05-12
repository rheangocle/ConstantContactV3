<?php
// Display any errors on page
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Add neccessary files

$accessTokenFromDb = getAccessTokenFromDb(REFRESH_TOKEN);

// Can be found doing a get request on developer.constantcontact.com
$dailyNewsContactListId = 'd7377860-d7c4-11ed-a578-fa163eb65cb7';

saveEmailToDb($email_text);



// Creating campaign & converting json string to object
$responseObject = json_decode(createCampaign($accessTokenFromDb, $email_text));

// Getting campaign activity ID from first index
$campaignActivityId = $responseObject->campaign_activities[0]->campaign_activity_id;

// Checking for campaign activity ID and scheduling campaign
if (!empty($campaignActivityId)) {

    $response = addContactList($accessTokenFromDb, $dailyNewsContactListId, $campaignActivityId);

    if ($response)
    {
        $scheduledDate = new DateTime();
        $scheduledDate->setTime(6, 0,0); // 6 AM schedule time
        // Might have to modify time, CC was 4 hours ahead of EST. 
        $scheduledDate->modify('+4 hours');// Add 4 hours to date you want to set, CC API time is 4 hrs ahead.
        $todaySixAm = $scheduledDate->format('Y-m-d\TH:i:s\Z');
        scheduleEmailCampaign($accessTokenFromDb, $todaySixAm, $campaignActivityId);
    } else {
        echo "Unable to schedule campaign";
    }
} else {
    echo "Campaign Activity Id not found, unable to add contact list" . PHP_EOL;
};