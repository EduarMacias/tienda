<?php
require '../vendor/autoload.php';
require 'config.php';

$client = new Google_Client();
$client->setClientId('YOUR_GOOGLE_CLIENT_ID');
$client->setClientSecret('YOUR_GOOGLE_CLIENT_SECRET');
$client->setRedirectUri('http://yourdomain.com/usuarios/google_callback.php');
$client->addScope('email');
$client->addScope('profile');

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);

    $oauth = new Google_Service_Oauth2($client);
    $userInfo = $oauth->userinfo->get();

    $stmt = $pdo->prepare("SELECT * FROM google_users WHERE google_id = ?");
    $stmt->execute([$userInfo->id]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
    } else {
        $stmt = $pdo->prepare("INSERT INTO google_users (google_id, name, email) VALUES (?, ?, ?)");
        $stmt->execute([$userInfo->id, $userInfo->name, $userInfo->email]);
        $_SESSION['user_id'] = $pdo->lastInsertId();
    }
    header("Location: ../index.php");
    exit();
}
?>
