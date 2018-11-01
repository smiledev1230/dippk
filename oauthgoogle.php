<?php 
include 'inc/app_top.php';

include_once 'gdrive/Google/autoload.php';

$client = new Google_Client();

$client->setApplicationName('ELearning');

//Scopes for gmail
$client->setAccessType('offline');        // offline access
$client->setIncludeGrantedScopes(true);   // incremental auth

$client->addScope(Google_Service_Drive::DRIVE);

$client->setAuthConfig('gdrive/client_secret.json');

$client->setRedirectUri('http://dippk.com/elearning/oauthgoogle.php'); 
$client->setApprovalPrompt ("force");

if (! isset($_GET['code'])) {
    $auth_url = $client->createAuthUrl();
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} else {
    $client->authenticate($_GET['code']);
    $_SESSION['google_access_token'] = $google_access_token = $client->getAccessToken();
    
    $client->setAccessToken($client->getAccessToken());
    
    //store the data in the database
    $sql = "UPDATE oauth SET google_access_token='$google_access_token' ";
    mysql_query($sql);
    $_SESSION['connect_success'] = 'Successfully connected to the Drive';
    $redirect_uri = 'http://dippk.com/elearning/index.php?page=connect';
    
    header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}