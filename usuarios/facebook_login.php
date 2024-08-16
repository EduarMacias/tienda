<?php
require_once '../vendor/autoload.php';
require_once 'config.php';

$fb = new \Facebook\Facebook([
  'app_id' => 'YOUR_FACEBOOK_APP_ID',
  'app_secret' => 'YOUR_FACEBOOK_APP_SECRET',
  'default_graph_version' => 'v2.10',
]);

$helper = $fb->getRedirectLoginHelper();
$permissions = ['email'];
$loginUrl = $helper->getLoginUrl('http://yourdomain.com/usuarios/facebook_callback.php', $permissions);

header('Location: ' . $loginUrl);
?>
