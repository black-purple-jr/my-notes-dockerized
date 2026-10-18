<?php
require "../models/User.php";

$reset_password_errors = ["password" => null, "password_confirmation" => null];
$success = false;

// Token can arrive via GET (clicking the email link) or POST (resubmitting the form)
$rawToken = $_POST["token"] ?? $_GET["token"] ?? null;
$user = null;

if ($rawToken) {
  $tokenHash = hash("sha256", $rawToken);
  $user = User::getUserByResetTokenHash($tokenHash);
}

$tokenValid = (bool) $user;

if ($tokenValid && $_SERVER["REQUEST_METHOD"] === "POST") {
  if (isset($_POST["password"]) && isset($_POST["password_confirmation"])) {
    $password = $_POST["password"];
    $password_confirmation = $_POST["password_confirmation"];

    try {
      if (empty($password)) {
        $reset_password_errors["password"] = "Password is required";
      }

      if (empty($password_confirmation)) {
        $reset_password_errors["password_confirmation"] = "Please confirm your password";
      } elseif ($password !== $password_confirmation) {
        $reset_password_errors["password_confirmation"] = "Passwords aren't matching";
      }

      if (empty(array_filter($reset_password_errors))) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        User::updatePassword($user["user_id"], $hash);
        $success = true;
      }
    } catch (PDOException $e) {
      die("Query failed: " . $e->getMessage());
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password - My Notes</title>
  <link rel="stylesheet" href="./css/form.css">
  <link rel="icon" type="image/svg+xml" href="../assets/favicon.svg">
  <script src="./js/reset.js" defer></script>
</head>

<body>
  <form action="" method="post">
    <?php if (!$tokenValid): ?>
      <h3>Invalid or expired link</h3>
      <br>
      <p style="color: var(--main-neutral-color, #838383);">
        This password reset link is invalid or has expired. Request a new one below.
      </p>
      <br>
      <p><a href="./forgot_password.php">Request a new reset link</a></p>
    <?php elseif ($success): ?>
      <h3>Password updated</h3>
      <br>
      <p style="color: var(--main-neutral-color, #838383);">
        Your password has been changed. You can now log in with your new password.
      </p>
      <br>
      <p><a href="./auth.php">Go to login</a></p>
    <?php else: ?>
      <h3>Choose a new password</h3>
      <input type="hidden" name="token" value="<?php echo htmlspecialchars($rawToken) ?>">
      <div class="wrapper">
        <label for="passwordInput" id="passwordLabel">Enter a new password</label>
        <div class="mini-wrapper">
          <input type="password" name="password" id="passwordInput" autocomplete="new-password" required>
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--brand-main-color)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-icon lucide-eye showPassword" id="showPassword">
            <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
            <circle cx="12" cy="12" r="3" />
          </svg>
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--brand-main-color)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off-icon lucide-eye-off hidePassword" id="hidePassword">
            <path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49" />
            <path d="M14.084 14.158a3 3 0 0 1-4.242-4.242" />
            <path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143" />
            <path d="m2 2 20 20" />
          </svg>
        </div>
      </div>
      <?php if (!empty($reset_password_errors["password"])): ?>
        <p class="errors"><?php echo htmlspecialchars($reset_password_errors["password"]) ?></p>
      <?php endif; ?>
      <div class="wrapper">
        <label for="passwordConfirmationInput" id="passwordConfirmationLabel">Confirm your new password</label>
        <div class="mini-wrapper">
          <input type="password" name="password_confirmation" id="passwordConfirmationInput" autocomplete="new-password" required>
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--brand-main-color)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-icon lucide-eye showPassword" id="showPasswordConfirmation">
            <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
            <circle cx="12" cy="12" r="3" />
          </svg>
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--brand-main-color)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off-icon lucide-eye-off hidePassword" id="hidePasswordConfirmation">
            <path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49" />
            <path d="M14.084 14.158a3 3 0 0 1-4.242-4.242" />
            <path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143" />
            <path d="m2 2 20 20" />
          </svg>
        </div>
      </div>
      <?php if (!empty($reset_password_errors["password_confirmation"])): ?>
        <p class="errors"><?php echo htmlspecialchars($reset_password_errors["password_confirmation"]) ?></p>
      <?php endif; ?>
      <div class="btn-container">
        <button type="submit">Set new password</button>
      </div>
    <?php endif; ?>
  </form>
</body>

</html>