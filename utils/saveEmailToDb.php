
<?php
function saveEmailToDb($email_text) {
    $dbConnector = new DatabaseConnector('rss');
    $date = date('M-j-Y');
    $mod_email_text = str_replace('<span style="font-size: 12px; color: rgb(0, 155, 186);"><A HREF="https://wfsu.org/constant-contact/news-email/daily-news?date='.date('F-jS-Y', strtotime($date)).'" style="font-size: 12px; color: rgb(0, 155, 186);">View as Webpage</A></span>', '', $email_text);
    $query = "INSERT INTO `emails`(`date`, `html_content`) 
             VALUES ('$date','".htmlentities(addslashes($mod_email_text))."')";
    $result =  $dbConnector->runQuery($query);
    $dbConnector->disconnectFromDB();
}
