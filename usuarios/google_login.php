<?php
require '../vendor/autoload.php';
require 'config.php';

$client = new Google_Client();
$client->setClientId('YOUR_GOOGLE_CLIENT_ID');
$client->setClientSecret('YOUR_GOOGLE_CLIENT_SECRET');
$client->setRedirectUri('http://yourdomain.com/usuarios/google_callback.php');
$client->addScope('email');
$client->addScope('profile');

$login_url = $client->createAuthUrl();

header('Location: ' . filter_var($login_url, FILTER_SANITIZE_URL));
?>
