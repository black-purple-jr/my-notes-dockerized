<?php
require "../../config/session.php";
require "../../models/User.php";

if (!isset($_SESSION["current_user_id"])) {
  header("Location: ../../auth/auth.php");
  exit;
}

$current_user_id = $_SESSION["current_user_id"];
$check = User::userExists($current_user_id);

if (!$check) {
  header("Location: ../../auth/auth.php");
  exit;
}

$user = User::getUserById($current_user_id);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profile - My Notes</title>
  <link rel="icon" type="image/svg+xml" href="../../assets/favicon.svg" />
  <link rel="stylesheet" href="../css/profile.css" />
</head>

<body>
  <header>
    <a href="../../">
      <h1>
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#5b5eeb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-notebook-text-icon lucide-notebook-text">
          <path d="M2 6h4" />
          <path d="M2 10h4" />
          <path d="M2 14h4" />
          <path d="M2 18h4" />
          <rect width="16" height="20" x="4" y="2" rx="2" />
          <path d="M9.5 8h5" />
          <path d="M9.5 12H16" />
          <path d="M9.5 16H14" />
        </svg>
        My Notes
      </h1>
    </a>
    <a href="../../" class="back">
      <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-move-left-icon lucide-move-left">
        <path d="M6 8L2 12L6 16" />
        <path d="M2 12H22" />
      </svg>
      back to home page
    </a>
  </header>
  <main>
    
  </main>
</body>

</html>