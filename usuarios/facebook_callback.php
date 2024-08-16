<?php
require_once '../vendor/autoload.php';
require_once 'config.php';

$fb = new \Facebook\Facebook([
  'app_id' => 'YOUR_FACEBOOK_APP_ID',
  'app_secret' => 'YOUR_FACEBOOK_APP_SECRET',
  'default_graph_version' => 'v2.10',
]);

$helper = $fb->getRedirectLoginHelper();

try {
  $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

if (!isset($accessToken)) {
  echo 'No OAuth data could be obtained from the request.';
  exit;
}

$oAuth2Client = $fb->getOAuth2Client();
$tokenMetadata = $oAuth2Client->debugToken($accessToken);
$tokenMetadata->validateAppId('YOUR_FACEBOOK_APP_ID');
$tokenMetadata->validateExpiration();

if (! $accessToken->isLongLived()) {
  try {
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
  } catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Error getting long-lived access token: ' . $e->getMessage();
    exit;
  }
}

$fb->setDefaultAccessToken($accessToken);

try {
  $response = $fb->get('/me?fields=id,name,email');
  $user = $response->getGraphUser();
  
  $stmt = $pdo->prepare("SELECT * FROM facebook_users WHERE facebook_id = ?");
  $stmt->execute([$user['id']]);
  $existingUser = $stmt->fetch();

  if ($existingUser) {
      // Iniciar sesión
      $_SESSION['user_id'] = $existingUser['id'];
  } else {
      // Registrar el usuario
      $stmt = $pdo->prepare("INSERT INTO facebook_users (facebook_id, name, email) VALUES (?, ?, ?)");
      $stmt->execute([$user['id'], $user['name'], $user['email']]);
      // Iniciar sesión
      $_SESSION['user_id'] = $pdo->lastInsertId();
  }
  header("Location: ../index.php");
  exit();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}
?>
