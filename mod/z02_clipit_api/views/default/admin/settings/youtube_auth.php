<?php
session_start();

set_include_path(
    get_include_path() . PATH_SEPARATOR . elgg_get_plugins_path() . "z02_clipit_api/libraries/google_api/src/"
);
require_once 'Google/Client.php';
require_once 'Google/Service/YouTube.php';
$REDIRECT = elgg_get_site_url() . "admin/settings/youtube_auth";
$APP_NAME = elgg_get_site_entity()->name;
$SCOPE = "https://www.googleapis.com/auth/youtube";

$html_body = "";
if(get_config("google_refresh_token")) {
    $html_title = "SUCCESS!";
    $html_body .= "ClipIt is now authenticated with YouTube";
} elseif($_GET['code']) {
    $client = new Google_Client();
    $client->setAccessType('offline');
    $client->setApplicationName($APP_NAME);
    $client->setClientId(get_config("google_id"));
    $client->setClientSecret(get_config("google_secret"));
    $client->setRedirectUri($REDIRECT);
    $client->setScopes($SCOPE);
    $client->setApprovalPrompt("force");
    if(strval($_SESSION['state']) !== strval($_GET['state'])) {
        die('The session state did not match.');
    }
    $client->authenticate($_GET['code']);
    $token_obj = json_decode($client->getAccessToken());
    set_config("google_token", $token_obj->access_token);
    set_config("google_refresh_token", $token_obj->refresh_token);
    $html_title = "SUCCESS!";
    $html_body .= "ClipIt is now authenticated with YouTube";
} elseif($_GET['google_id'] && $_GET['google_secret']) {
    set_config("google_id", $_GET['google_id']);
    set_config("google_secret", $_GET['google_secret']);
    $client = new Google_Client();
    $client->setAccessType('offline');
    $client->setApplicationName($APP_NAME);
    $client->setClientId(get_config("google_id"));
    $client->setClientSecret(get_config("google_secret"));
    $client->setRedirectUri($REDIRECT);
    $client->setScopes($SCOPE);
    $client->setApprovalPrompt("force");
    $html_title = "Please click on 'Authorize'";
    $html_body .= "<a href='" . $client->createAuthUrl() . "'>
            <button type='button'>Authorize</button>
        </a>";
} else {
    $html_title = "Enter Google Account Information";
    $html_body .= "<form action='" . $REDIRECT . "'>
            <p/><font size=-1>(from <a href='https://console.developers.google.com'>Google Developers Console</a> -> APIs & auth -> Credentials)</font>
            <p/>Google API Client ID<br><input type='text' name='google_id' style='width:50%;'><br>
            <p/>Google API Client Secret<br><input type='text' name='google_secret' style='width:50%;'><br>
            <p/><input type='submit' value='Submit'>
        </form>";
}
?>

<html>
<body>
<H3><? echo $html_title ?></H3>
<? echo $html_body ?>
</body>
</html>

