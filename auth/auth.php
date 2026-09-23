<?php
require "../models/User.php";
require "../config/uuid.php";
require "../config/session.php";
require "../config/router.php";
require "../config/mailer.php";
require "../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->safeLoad();

$sign_up_errors = ["email" => null, "username" => null, "password" => null, "password_confirmation" => null];
$login_errors = ["login" => null, "password" => null];

$client = new Google\Client();

$client->setClientId($_ENV["GOOGLE_OAUTH_CLIENT_ID"]);
$client->setClientSecret($_ENV["GOOGLE_OAUTH_CLIENT_SECRET"]);
$client->setRedirectUri(BASE_URL . "/auth/redirect.php");

$client->addScope("email");
$client->addScope("profile");

$client->setPrompt('select_account');

$url = $client->createAuthUrl();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (isset($_POST["login"])) {
    if (isset($_POST["username_email"]) && isset($_POST["password"])) {
      $username_email = $_POST["username_email"];
      $password = $_POST["password"];

      try {
        if (empty($username_email)) {
          $login_errors["login"] = "Email or username is required";
        }

        if (empty($password)) {
          $login_errors["password"] = "Password is required";
        }

        if ($login_errors["login"] === null) {
          $user = User::getUser($username_email);

          if (!$user) {
            $login_errors["login"] = "There is no account with this email";
          } elseif ($login_errors["password"] === null && !password_verify($password, $user["user_password"])) {
            $login_errors["password"] = "Wrong password";
          }
        }

        if (empty(array_filter($login_errors))) {

          global $user;
          $_SESSION["current_user_id"] = htmlspecialchars($user["user_id"]);
          $_SESSION["current_user_email"] = htmlspecialchars($user["user_email"]);
          $_SESSION["current_user_username"] = htmlspecialchars($user["username"]);
          $_SESSION["current_user_activated"] = (bool) $user["is_activated"];

          $_SESSION["last_regeneration"] = time();
          regenerate_session_id();

          $_SESSION["toast"] = "Login-success";
          header("Location: " . BASE_URL);
        }
      } catch (Exception $e) {
        die("Query failed : " . $e->getMessage());
      }
    }
  } elseif (isset($_POST["sign_up"])) {
    if (isset($_POST["email"]) && isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["password_confirmation"])) {
      $email = $_POST["email"];
      $username = $_POST["username"];
      $password = $_POST["password"];
      $password_confirmation = $_POST["password_confirmation"];
      $id = generate_uuidv4();

      try {
        if (empty($email)) {
          $sign_up_errors["email"] = "Email is required";
        } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
          $sign_up_errors["email"] = "Invalid email";
        } elseif (User::getUser($email)) {
          $sign_up_errors["email"] = "Email already registered";
        }

        if (empty($password)) {
          $sign_up_errors["password"] = "Password is required";
        }

        if (empty($username)) {
          $sign_up_errors["username"] = "Username is required";
        } elseif (User::usernameExists($username)) {
          $sign_up_errors["username"] = "Username already taken";
        }

        if (empty($password_confirmation)) {
          $sign_up_errors["password_confirmation"] = "Please confirm your password";
        } elseif ($password !== $password_confirmation) {
          $sign_up_errors["password_confirmation"] = "Passwords aren't matching";
        }

        if (empty(array_filter($sign_up_errors))) {
          $hash = password_hash($password, PASSWORD_DEFAULT);
          User::createUser($id, $username, $email, $hash);

          $rawToken = bin2hex(random_bytes(32));
          User::setActivationToken($id, hash("sha256", $rawToken));

          $activationLink = BASE_URL . "/auth/activate.php?token=" . $rawToken;
          include "html.php";
          send_email($email, "Activate your My Notes account", htmlBody($activationLink));

          $_SESSION["current_user_id"] = htmlspecialchars($id);
          $_SESSION["current_user_email"] = htmlspecialchars($email);
          $_SESSION["current_user_username"] = htmlspecialchars($username);
          $_SESSION["current_user_activated"] = false;

          $_SESSION["toast"] = "sign-up-success";
          header("Location: " . BASE_URL);
          exit;
        }
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
  <title>Login - My Notes</title>
  <link rel="stylesheet" href="./css/auth.css" />
  <link rel="stylesheet" href="../styles/spinner.css">
  <script src="./js/auth.js" defer></script>
  <link rel="icon" type="image/svg+xml" href="../assets/favicon.svg">
</head>

<body>
  <?php $show_register = isset($_POST["sign_up"]) || !empty(array_filter($sign_up_errors)); ?>
  <div class="container<?php echo $show_register ? ' active' : '' ?>">
    <div class="form-box login">
      <form method="post" action="">
        <h1>Sign in to My Notes</h1>
        <div class="input-box" id="login-mail-input-box">
          <label for="login-email-input">Email or username</label>
          <input type="text" name="username_email" id="login-email-input" />
        </div>
        <?php if (!empty($login_errors["login"])): ?>
          <p class="errors"><?php echo htmlspecialchars($login_errors["login"]) ?></p>
        <?php endif; ?>
        <div class="input-box" id="login-password-input-box">
          <label for="passwd-input">Password</label>
          <div class="mini-box">
            <input type="password" name="password" id="passwd-input">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-icon lucide-eye"
              id="show-password-login">
              <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
              <circle cx="12" cy="12" r="3" />
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off-icon lucide-eye-off"
              id="hide-password-login">
              <path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49" />
              <path d="M14.084 14.158a3 3 0 0 1-4.242-4.242" />
              <path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143" />
              <path d="m2 2 20 20" />
            </svg>
          </div>
        </div>
        <?php if (!empty($login_errors["password"])): ?>
          <p class="errors"><?php echo htmlspecialchars($login_errors["password"]) ?></p>
        <?php endif; ?>
        <a href="./forgot_password.php" class="forgot-link">forgot password?</a>
        <button type="submit" name="login" class="btn login-button">
          <div class="spinner spinner1"></div>
          <p>Connect to your account</p>
        </button>
        <div class="seperator"></div>
        <p class="or">or</p>
        <a href="<?php echo htmlspecialchars($url); ?>" class="continue-google" name="google">
          <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="29" height="29" viewBox="0 0 48 48">
            <path fill="#FFC107"
              d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z">
            </path>
            <path fill="#FF3D00"
              d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z">
            </path>
            <path fill="#4CAF50"
              d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z">
            </path>
            <path fill="#1976D2"
              d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z">
            </path>
          </svg>
          Continue with google
        </a>
      </form>
    </div>

    <div class="form-box register">
      <form action="" method="post">
        <h1>Register to My Notes</h1>
        <div class="input-box" id="register-mail-input-box">
          <label for="email-input-register">Enter your email</label>
          <input type="email" name="email" id="email-input-register" />
        </div>
        <?php if (!empty($sign_up_errors["email"])): ?>
          <p class="errors"><?php echo htmlspecialchars($sign_up_errors["email"]) ?></p>
        <?php endif; ?>
        <div class="input-box" id="register-username-input-box">
          <label for="username-input-register">Enter your username</label>
          <input type="text" name="username" id="username-input-register" />
        </div>
        <?php if (!empty($sign_up_errors["username"])): ?>
          <p class="errors"><?php echo htmlspecialchars($sign_up_errors["username"]) ?></p>
        <?php endif; ?>
        <div class="input-box" id="register-password-input-box">
          <label for="passwd-input-register">Enter your password</label>
          <div class="mini-box">
            <input type="password" name="password" id="passwd-input-register" autocomplete="new-password">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-eye-icon lucide-eye" id="show-password-register">
              <path
                d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
              <circle cx="12" cy="12" r="3" />
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-eye-off-icon lucide-eye-off" id="hide-password-register">
              <path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49" />
              <path d="M14.084 14.158a3 3 0 0 1-4.242-4.242" />
              <path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143" />
              <path d="m2 2 20 20" />
            </svg>
          </div>
        </div>
        <?php if (!empty($sign_up_errors["password"])): ?>
          <p class="errors"><?php echo htmlspecialchars($sign_up_errors["password"]) ?></p>
        <?php endif; ?>
        <div class="input-box" id="register-password-confirmation-input-box">
          <label for="passwd-conf-input-register">Confirm your password</label>
          <div class="mini-box">
            <input type="password" name="password_confirmation" id="passwd-conf-input-register" autocomplete="new-password">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-icon lucide-eye"
              id="show-password-conf-register">
              <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
              <circle cx="12" cy="12" r="3" />
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off-icon lucide-eye-off"
              id="hide-password-conf-register">
              <path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49" />
              <path d="M14.084 14.158a3 3 0 0 1-4.242-4.242" />
              <path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143" />
              <path d="m2 2 20 20" />
            </svg>
          </div>
        </div>
        <?php if (!empty($sign_up_errors["password_confirmation"])): ?>
          <p class="errors"><?php echo htmlspecialchars($sign_up_errors["password_confirmation"]) ?></p>
        <?php endif; ?>
        <div class="btn-container">
          <button type="submit" name="sign_up" class="btn register-button">
            <div class="spinner spinner2"></div>
            <p>Create your account</p>
          </button>
          <div class="seperator"></div>
          <p class="or">or</p>
          <a href="<?php echo htmlspecialchars($url); ?>" class="continue-google" name="google">
            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="29" height="29" viewBox="0 0 48 48">
              <path fill="#FFC107"
                d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z">
              </path>
              <path fill="#FF3D00"
                d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z">
              </path>
              <path fill="#4CAF50"
                d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z">
              </path>
              <path fill="#1976D2"
                d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z">
              </path>
            </svg>
            Continue with google
          </a>
        </div>
      </form>
    </div>

    <div class="toggle-box">
      <div class="toggle-panel toggle-left">
        <h1 class="greet">Welcome back</h1>
        <p>Don't have an account?</p>
        <button type="button" class="btn register-btn">Register</button>
      </div>
      <div class="toggle-panel toggle-right">
        <h1 class="greet">Welcome</h1>
        <p>Already have an account?</p>
        <button type="button" class="btn login-btn">Login</button>
      </div>
    </div>
  </div>
</body>

</html>