<?php

function getAccessTokenFromDb($refreshToken) {
    $db = 'constant_contact_v3';
    $dbConnector = new DatabaseConnector($db);

    $query = "SELECT access_token FROM access_tokens WHERE refresh_token = '$refreshToken' ORDER BY time_added DESC LIMIT 1";
    $result = $dbConnector->runQuery($query);
    $row = $result->fetch(PDO::FETCH_ASSOC);

    if ($row == 0) {
        return null;
    } else {
        return $row['access_token'];
    }
};