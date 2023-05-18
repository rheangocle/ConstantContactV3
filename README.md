#  📧 Constant Contact V3 PHP Example

This repository provides an example of implementing Constant Contact version 3 with functionality for OAuth and email creation/scheduling. It serves as a reference for updating existing code to work with Constant Contact API version 3. The example focuses on OAuth 2 Authorization Code Flow.


---
## OAuth 2 and Authorization

For a comprehensive overview of Constant Contact OAuth2, please refer to the Constant Contact OAuth2 documentation. When following these examples, make sure to select OAuth2 Authorization Code Flow. Note that this example assumes the use of a long-lived refresh token. If you are using a short-lived refresh token, some methods may differ slightly, but the core concepts should still apply.

Here are the steps involved in OAuth 2 and authorization:
1. Update or create a new application in Constant Contact Dev.
2. Obtain the client ID and generate the client secret (store it securely in a .env file).
3. Choose the type of authentication required for your application.
4. Use the client ID and client secret to obtain an access token and refresh token.
5. Keep in mind that access tokens and short-lived refresh tokens expire after 24 hours.
6. Follow the Constant Contact examples or use Postman to obtain an authorization code.

<br>

---
## Refreshing tokens

There is a crucial difference in this step compared to the Constant Contact documentation. Please read the explanation below after reviewing the following code block:

```php
// Define the base URL
$url = 'https://authz.constantcontact.com/oauth2/default/v1/token';

// Create the cURL handle
$ch = curl_init();

// Set the cURL options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/x-www-form-urlencoded',
    'Authorization: Basic ' . base64_encode($clientId . ':' . $clientSecret),
));

// Set the POST fields
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
    'refresh_token' => $refreshToken,
    'grant_type' => 'refresh_token'
)));

// Execute the request
$responseObject = json_decode(curl_exec($ch));

$accessToken = $responseObject->access_token;

// Store access tokens and refresh tokens in a file or database.

```

**Important Note**: Constant Contact docs showed the step for obtaining access token and refreshing tokens with the request parameters `refresh_token` and `grant_type` in the request URL. However in practice, they need to be in the POST fields.

<br>

---
## Creating an email campaign

The name of the email campaign must be unique. In this example, a random number is concatenated with random_int() to generate the campaign name. Review the following code block:

```php
  // Set API endpoint
  $url = 'https://api.cc.email/v3/emails';

  // Set the request body as an array
  $request = array(
      'name' => random_int(1,100000).'EMAIL_NAME'
      'email_campaign_activities' => array(
          array(
              'format_type' => 5,
              'from_email' => {EXAMPLE@COMPANY.COM},
              'from_name' => {COMPANY_NAME},
              'reply_to_email' => {EXAMPLE@COMPANY.COM},
              'subject' => {EMAIL_SUBJECT},
              'html_content' => {EMAIL_BODY},
              'physical_address_in_footer' => array(
                  'address_line1' => {ADDRESS},
                  'city' => {CITY},
                  'state_code' => {XX},
                  'country_code' => {XX},
                  'organization_name' => {ORG_NAME},
                  'postal_code' => {ZIPCODE},
              ),
          )
      )
  );

  // Initialize, set options, and execute curl request

```

Few points:
* `format_type` of 5 indicates a custom code email. 
* `physical_address_in_footer` is optional.

---
## Scheduling a campaign

After completing the previous step, the email will be saved as a draft in Constant Contact. To schedule the email to be sent at a specific time, you need to add a contact list to the email campaign. To retrieve the lists collection, refer to the [Constant Contact API reference](https://developer.constantcontact.com/api_reference/index.html#!/Contact_Lists/getLists).

To schedule the campaign, you need the campaign activity ID, which can be obtained from the response of the `createCampaign()` function. Here's an example of obtaining the campaign activity ID:

```php
// createCampaign returns a json, use json_decode to convert to object
$responseObject = json_decode(createCampaign($accessTokenFromDb, $email_text));

// Obtain campaign activity ID from first index
$campaignActivityId = $responseObject->campaign_activities[0]->campaign_activity_id;
```

Once you have the campaign activity ID, you can proceed with scheduling the campaign:

```php
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
        'scheduled_date' => {TIME}
    );

    //Initialize curl, set options, and execute curl request
```
Feel free to customize the scheduled_date with the desired time to schedule the email campaign.

<br>

---
## Contact

If you have any questions or need further assistance, you can contact me via email at rheangocle@gmail.com or through this platform. Thank you.