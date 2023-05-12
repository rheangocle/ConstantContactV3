<?php

/**
 * @param $refreshToken - The refresh token provided with the previous access token
 * @param $clientId - API Key
 * @param $clientSecret - API Secret
 * @return string - JSON String of results
 */
function refreshToken($refreshToken, $clientId, $clientSecret)
{
    // Define base URL
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

    // Make the cURL call and get the response
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        $error_message = 'cURL Error: ' . curl_error($ch);
        throw new Exception($error_message);
    }

    // Get the HTTP status code
    $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Close curl
    curl_close($ch);

    // Check for a successful response
    if ($http_status_code >= 200 && $http_status_code < 300) {
        return $response;
    } else {
        $error_message = 'HTTP Error: ' . $http_status_code . ', Response: ' . $response;
        throw new Exception($error_message);
    }
}

$responseObject = json_decode(refreshToken(REFRESH_TOKEN, CLIENT_ID, CLIENT_SECRET));
$accessToken = $responseObject->access_token;
echo $accessToken;

if ($accessToken == null) {
    echo "Could not retrieve access token, check refresh token method";
} else {
    echo "Refresh token was used to successfully update access token!";

    $db = 'constant_contact_v3';

    try {
        // Put access token and refresh token in db
        $dbConnector = new DatabaseConnector($db);

        // Delete any previous access tokens with the same refresh token
        $delete_query = "DELETE FROM access_tokens WHERE refresh_token = :refresh_token";
        $delete_prepared = $dbConnector->prepareQuery($delete_query);
        $dbConnector->executeQuery($delete_prepared, array(':refresh_token' => REFRESH_TOKEN));

        // Insert the new access token
        $insert_query = "INSERT INTO access_tokens (refresh_token, access_token) VALUES (:refresh_token, :access_token)";
        $insert_prepared = $dbConnector->prepareQuery($insert_query);
        $dbConnector->executeQuery($insert_prepared, array(':refresh_token' => REFRESH_TOKEN, ':access_token' => $accessToken));
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}