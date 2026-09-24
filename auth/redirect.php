<?php
require "../models/User.php";
require "../config/session.php";
require "../config/router.php";
require "../config/uuid.php";
require "../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->safeLoad();

$client = new Google\Client();

$client->setClientId($_ENV["GOOGLE_OAUTH_CLIENT_ID"]);
$client->setClientSecret($_ENV["GOOGLE_OAUTH_CLIENT_SECRET"]);
$client->setRedirectUri(BASE_URL . "/auth/redirect.php");

$client->addScope("email");
$client->addScope("profile");


if (!isset($_GET["code"])) {
  header("Location: ./auth.php");
  exit;
}

try {
  $token = $client->fetchAccessTokenWithAuthCode($_GET["code"]);

  if (isset($token["error"])) {
    die("Google auth failed: " . $token["error"]);
  }

  $client->setAccessToken($token);

  $oauth = new Google\Service\Oauth2($client);
  $userinfo = $oauth->userinfo->get();

  $email = $userinfo->email;
  $name = $userinfo->givenName;
  $pfp = $userinfo->picture;

  $username = $name . "-" . str_pad((string) rand(0, 99999), 5, "0", STR_PAD_LEFT);

  // Same lookup your email login uses — matches on email or username
  $user = User::getUser($email);

  if (!$user) {
    $id = generate_uuidv4();

    User::createUserFromGoogle($id, $email, $username);

    $pfp_data = file_get_contents($pfp);
    if ($pfp_data !== false) {
      User::setProfilePicture($id, base64_encode($pfp_data), "image/jpeg");
    }

    $user = User::getUser($email);
  }

  // Exactly what auth.php sets on a successful email/password login
  $_SESSION["current_user_id"] = htmlspecialchars($user["user_id"]);
  $_SESSION["current_user_email"] = htmlspecialchars($user["user_email"]);
  $_SESSION["current_user_username"] = htmlspecialchars($user["username"]);
  $_SESSION["current_user_activated"] = true;


  $_SESSION["last_regeneration"] = time();
  regenerate_session_id();

  $_SESSION["toast"] = "Login-success";
  header("Location: " . BASE_URL);
  exit;
} catch (Exception $e) {
  die("Google auth error: " . $e->getMessage());
}
