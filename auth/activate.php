<?php
require "../config/session.php";
require "../models/User.php";

$rawToken = $_GET["token"] ?? null;
$success = false;

if ($rawToken) {
  $tokenHash = hash("sha256", $rawToken);
  $user = User::getUserByActivationTokenHash($tokenHash);

  if ($user) {
    User::activateUser($user["user_id"]);
    $success = true;

    if (isset($_SESSION["current_user_id"]) && $_SESSION["current_user_id"] === $user["user_id"]) {
      $_SESSION["current_user_activated"] = true;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Activate account - My Notes</title>
  <link rel="icon" type="image/svg+xml" href="../assets/favicon.svg">
  <link rel="stylesheet" href="./css/form.css">
</head>

<body>
  <form action="" method="post">
    <h3><?php echo $success ? "Account activated" : "Invalid activation link" ?></h3>
    <br>
    <p style="color: var(--main-neutral-color, #838383);">
      <?php echo $success
        ? "Your account is now active."
        : "This activation link is invalid or has already been used."; ?>
    </p>
    <br>
    <p><a href="<?php echo isset($_SESSION["current_user_id"]) ? '../' : './auth.php' ?>">
        <?php echo isset($_SESSION["current_user_id"]) ? "Back to My Notes" : "Go to login" ?>
      </a></p>
  </form>
</body>

</html>