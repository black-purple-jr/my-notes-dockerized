<?php
require "../models/User.php";
require "../config/mailer.php";
require "../config/router.php";
require "html.php";

$sent = false;
$forgot_password_errors = ["email" => null];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (isset($_POST["email"])) {
    $email = $_POST["email"];

    if (empty($email)) {
      $forgot_password_errors["email"] = "Email is required";
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
      $forgot_password_errors["email"] = "Invalid email";
    }

    if (empty(array_filter($forgot_password_errors))) {
      try {
        $user = User::getUser($email);

        // Only generate/send a token if the account exists AND is activated.
        // The response shown to the user is identical either way (see $sent below),
        // so this branch can't be used to check whether an email is registered.
        if ($user && User::isActivated($user["user_id"])) {
          $rawToken = bin2hex(random_bytes(32));
          $tokenHash = hash("sha256", $rawToken);
          $expiry = date("Y-m-d H:i:s", time() + 60 * 30); // 30 minutes

          User::resetTokenHash($tokenHash, $expiry, $email);

          $resetLink = BASE_URL . "/auth/reset_password.php?token=" . $rawToken;
          send_email($email, "Reset your My Notes password", resetPasswordHtmlBody($resetLink));
        }

        // Always show the same success state, whether or not the email was found/activated,
        // so this form can't be used to enumerate registered accounts.
        $sent = true;
      } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
      }
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Password Recovery - My Notes</title>
  <link rel="stylesheet" href="./css/form.css">
  <link rel="icon" type="image/svg+xml" href="../assets/favicon.svg">
  <script src="./js/recover.js" defer></script>
</head>

<body>
  <form action="" method="post">
    <h3>Password recovery</h3>
    <?php if ($sent): ?>
      <br>
      <p style="color: var(--main-neutral-color, #838383);">
        If an account exists for that email, we've sent a link to reset your password. Check your inbox.
      </p>
      <br>
    <?php else: ?>
      <div class="wrapper">
        <label for="emailInput" id="emailLabel">Enter your email</label>
        <input type="email" name="email" id="emailInput" value="<?php echo isset($email) ? htmlspecialchars($email) : '' ?>" required>
      </div>
      <?php if (!empty($forgot_password_errors["email"])): ?>
        <p class="errors"><?php echo htmlspecialchars($forgot_password_errors["email"]) ?></p>
      <?php endif; ?>
      <div class="btn-container">
        <button type="submit">Send recovery link</button>
      </div>
    <?php endif; ?>
    <p><a href="./auth.php">Back to login</a></p>
  </form>
</body>

</html>