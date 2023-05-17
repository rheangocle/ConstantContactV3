# Constant Contact V3 PHP Example

An example of implementing Constant Contact version 3 with functionality for OAuth and email creation/scheduling. This is what worked for us when I was trying to update our code to work with version 3. This example works with OAuth 2 Authorization Code Flow. 

## OAuth 2 and Authorization

- Constant Contact OAuth2 overview: <https://developer.constantcontact.com/api_guide/auth_overview.html>
  - Ensure that you have selected OAuth2 Authorization Code Flow if you are following these examples. In addition,we are using a long-lived refresh token, so if you are using a short lived refresh token, the methods will be slightly different but most things should still apply.
- Update or create new application
- Get client Id and client secret (and store in .env)
- Choose type of auth
- Use client Id and client secret to get access token and refresh token
- Access tokens and short-lived refresh tokens expire after 24 hours.
- Follow CC Examples or go to Postman to obtain auth code.

## Creating an email campaign

Name of the campaign must be unique. Here I concatenated a random number with random_int() to my email campaign name.

```
  // Set API endpoint
  $url = 'https://api.cc.email/v3/emails';

  // Set the request body as an array
  $requestBody = array(
      'name' => random_int(1,100000).'EMAIL_NAME'
      'email_campaign_activities' => array(
          array(
              'format_type' => 5,
              'from_email' => 'EXAMPLE@COMPANY.COM',
              'from_name' => 'COMPANY_NAME',
              'reply_to_email' => 'EXAMPLE@COMPANY.COM',
              'subject' => 'EMAIL_SUBJECT',
              'html_content' => 'EMAIL_BODY',
              'physical_address_in_footer' => array(
                  'address_line1' => 'ADDRESS_1',
                  'city' => 'CITY',
                  'state_code' => 'XX',
                  'country_code' => 'XX',
                  'organization_name' => 'ORG_NAME',
                  'postal_code' => 'ZIPCODE',
              ),
          )
      )
  );

  // Set the authorization header, $auth is access token
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

  // Close curl
  curl_close($ch);

```

## Scheduling a campaign

After doing the previous step, the email will be saved to CC as a draft. To schedule a time for the email to be sent out, you will first need to add a contact list to the email campaign.
You will need the campaign activity ID as a parameter in the API endpoint to schedule a campaign. This value could be obtained from the response of createCampaign().

```
// createCampaign returns a json, use json_decode to convert to object
$responseObject = json_decode(createCampaign($accessTokenFromDb, $email_text));

// Getting campaign activity ID from first index
$campaignActivityId = $responseObject->campaign_activities[0]->campaign_activity_id;
```

## Contact

Feel free to contact me with any questions at rheangocle@gmail.com or on here. Thank you. 