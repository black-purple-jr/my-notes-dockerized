<?php
require_once "./config/session.php";
require_once "./config/uuid.php";
require_once "./config/router.php";
require_once "./models/Note.php";
require_once "./models/User.php";
// require "./models/DB.php";
require_once "./vendor/autoload.php";

DB::db_init();

if (!isset($_SESSION["current_user_id"])) {
  header("Location: ./auth/auth.php");
  exit;
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$current_user_id = $_SESSION["current_user_id"];
$check = User::userExists($current_user_id);
$user = User::getUserById($current_user_id);

if (!$check) {
  header("Location: ./auth/auth.php");
  exit;
}



if (isset($_POST["note-title"]) && isset($_POST["note-content"])) {
  $note_title = $_POST["note-title"];

  if ($note_title === "") {
    $note_title = "Untitled Note";
  }

  $note_id = generate_uuidv4();
  $note_content = $_POST["note-content"];
  $note_date = date("Y-m-d H:i:s");

  if ($_POST["note-title"] !== "" || $_POST["note-content"] !== "") {
    Note::addNote($current_user_id, $note_id, $note_title, $note_content, $note_date);
    $_SESSION["toast"] = "add-success";
    header("Location: " . BASE_URL);
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home - My Notes</title>
  <link rel="icon" type="image/svg+xml" href="./assets/favicon.svg">
  <link rel="stylesheet" href="./src/css/main.css" />
  <script src="./src/js/home_page.js" defer></script>
  <script src="./src/js/toast.js"></script>
  <script src="./src/js/dropdown.js"></script>
  <script src="./api/get_data.js" defer></script>
</head>

<body>
  <div id="top"></div>
  <div class="page">
    <header>
      <a href="./">
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
      <div class="search-bar">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          class="lucide lucide-search-icon lucide-search">
          <path d="m21 21-4.34-4.34" />
          <circle cx="11" cy="11" r="8" />
        </svg>
        <form>
          <input type="text" placeholder="search..." id="search-bar-input" name="search">
        </form>
      </div>
      <div class="right">
        <button type="button" title="New Note">
          <svg width="19" height="19" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
              d="M8 2.75C8 2.47386 7.77614 2.25 7.5 2.25C7.22386 2.25 7 2.47386 7 2.75V7H2.75C2.47386 7 2.25 7.22386 2.25 7.5C2.25 7.77614 2.47386 8 2.75 8H7V12.25C7 12.5261 7.22386 12.75 7.5 12.75C7.77614 12.75 8 12.5261 8 12.25V8H12.25C12.5261 8 12.75 7.77614 12.75 7.5C12.75 7.22386 12.5261 7 12.25 7H8V2.75Z"
              fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path>
          </svg>
          <p>New Note</p>
        </button>
        <?php if ($user['profile_picture']): ?>
          <img src="data:<?= htmlspecialchars($user['profile_picture_mime']) ?>;base64,<?= $user['profile_picture'] ?>"
            alt="Profile picture" width="28" height="28" style="border-radius: 100px;" id="profile">
        <?php else: ?>
          <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-user-icon lucide-circle-user" id="profile" title="<?php echo htmlspecialchars($user["username"]); ?>">
            <circle cx="12" cy="12" r="10" />
            <circle cx="12" cy="10" r="3" />
            <path d="M7 20.662V19a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1.662" />
          </svg>
        <?php endif; ?>
      </div>
    </header>
    <div class="dropdown-menu">
      <a href="./src/pages/profile.php">
        <div class="user">
          <div class="user-pfp">
            <?php if ($user['profile_picture']): ?>
              <img src="data:<?= htmlspecialchars($user['profile_picture_mime']) ?>;base64,<?= $user['profile_picture'] ?>"
                alt="Profile picture" width="40" height="40" style="border-radius: 100px;">
            <?php else: ?>
              <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-user-icon lucide-circle-user" id="profile">
                <circle cx="12" cy="12" r="10" />
                <circle cx="12" cy="10" r="3" />
                <path d="M7 20.662V19a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1.662" />
              </svg>
            <?php endif; ?>
          </div>
          <div class="user-info">
            <div class="username">
              <?php echo htmlspecialchars($user["username"]); ?>
            </div>
            <div class="user-email">
              <?php echo htmlspecialchars($user["user_email"]); ?>
            </div>
          </div>
        </div>
      </a>
      <div class="options">
        <a href="./src/pages/profile.php" class="option">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--brand-main-color)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-user-icon lucide-square-user">
            <rect width="18" height="18" x="3" y="3" rx="2" />
            <circle cx="12" cy="10" r="3" />
            <path d="M7 21v-2a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2" />
          </svg>
          Go to your profile
        </a>
        <a href="./auth/logout.php" class="option logout">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#cf272f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out-icon lucide-log-out">
            <path d="m16 17 5-5-5-5" />
            <path d="M21 12H9" />
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
          </svg>
          Logout
        </a>
      </div>
    </div>
    <?php
    if (isset($_SESSION["toast"])) {
      $toast = $_SESSION["toast"];
      unset($_SESSION["toast"]);

      if ($toast === "delete-success") {
        echo "<script>showToast('Note deleted successfully.', 'success')</script>";
      } elseif ($toast === "delete-failure") {
        echo "<script>showToast('Failed to delete this note.', 'error')</script>";
      } elseif ($toast === "edit-success") {
        echo "<script>showToast('Note edited successfully.', 'success')</script>";
      } elseif ($toast === "edit-failure") {
        echo "<script>showToast('Failed to edit this note.', 'error')</script>";
      } elseif ($toast === "add-success") {
        echo "<script>showToast('Note added successfully.', 'success')</script>";
      } elseif ($toast === "add-failure") {
        echo "<script>showToast('Failed to add this note.', 'error')</script>";
      } elseif ($toast === "activation-resent") {
        echo "<script>showToast('Email sent, go check your Inbox.', 'info')</script>";
      }
    }
    ?>
    <div class="backdrop" id="backdrop"></div>
    <div class="new-note closed" id="new-note">
      <form method="post">
        <input type="hidden" name="note-id" id="note-id">
        <input type="text" placeholder="Note Title" name="note-title" id="note-title">
        <textarea name="note-content" placeholder="Note Content" id="note-content"></textarea>
        <div class="btn-container">
          <button type="button" class="cancel-btn">Cancel</button>
          <button type="submit" class="save-btn">Save</button>
        </div>
      </form>
    </div>
    <h2>
      <span>
        Welcome back
        <span style="color: var(--brand-main-color);"><?php echo $_SESSION["current_user_username"] ?></span>
      </span>
      <?php if (!$_SESSION["current_user_activated"]) {
        echo '<a href="./auth/resend_activation.php">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--brand-main-color)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-check-icon lucide-badge-check">
          <path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/>
          <path d="m9 12 2 2 4-4"/>
        </svg>
        activate your account
        </a>';
      }
      ?>

    </h2>
    <div class="cards-container">
      <div class="card-skeleton">
        <div class="skeleton skeleton-title"></div>
        <div style="display: flex; flex-direction: column; gap: .5rem;">
          <div class="skeleton skeleton-line"></div>
          <div class="skeleton skeleton-line"></div>
          <div class="skeleton skeleton-line short"></div>
        </div>
        <div class="skeleton skeleton-date"></div>
      </div>
      <div class="card-skeleton">
        <div class="skeleton skeleton-title"></div>
        <div style="display: flex; flex-direction: column; gap: .5rem;">
          <div class="skeleton skeleton-line"></div>
          <div class="skeleton skeleton-line"></div>
          <div class="skeleton skeleton-line short"></div>
        </div>
        <div class="skeleton skeleton-date"></div>
      </div>
      <div class="card-skeleton">
        <div class="skeleton skeleton-title"></div>
        <div style="display: flex; flex-direction: column; gap: .5rem;">
          <div class="skeleton skeleton-line"></div>
          <div class="skeleton skeleton-line"></div>
          <div class="skeleton skeleton-line short"></div>
        </div>
        <div class="skeleton skeleton-date"></div>
      </div>
      <div class="card-skeleton">
        <div class="skeleton skeleton-title"></div>
        <div style="display: flex; flex-direction: column; gap: .5rem;">
          <div class="skeleton skeleton-line"></div>
          <div class="skeleton skeleton-line"></div>
          <div class="skeleton skeleton-line short"></div>
        </div>
        <div class="skeleton skeleton-date"></div>
      </div>
      <div class="card-skeleton">
        <div class="skeleton skeleton-title"></div>
        <div style="display: flex; flex-direction: column; gap: .5rem;">
          <div class="skeleton skeleton-line"></div>
          <div class="skeleton skeleton-line"></div>
          <div class="skeleton skeleton-line short"></div>
        </div>
        <div class="skeleton skeleton-date"></div>
      </div>
      <div class="card-skeleton">
        <div class="skeleton skeleton-title"></div>
        <div style="display: flex; flex-direction: column; gap: .5rem;">
          <div class="skeleton skeleton-line"></div>
          <div class="skeleton skeleton-line"></div>
          <div class="skeleton skeleton-line short"></div>
        </div>
        <div class="skeleton skeleton-date"></div>
      </div>
      <div class="card-skeleton">
        <div class="skeleton skeleton-title"></div>
        <div style="display: flex; flex-direction: column; gap: .5rem;">
          <div class="skeleton skeleton-line"></div>
          <div class="skeleton skeleton-line"></div>
          <div class="skeleton skeleton-line short"></div>
        </div>
        <div class="skeleton skeleton-date"></div>
      </div>
      <div class="card-skeleton">
        <div class="skeleton skeleton-title"></div>
        <div style="display: flex; flex-direction: column; gap: .5rem;">
          <div class="skeleton skeleton-line"></div>
          <div class="skeleton skeleton-line"></div>
          <div class="skeleton skeleton-line short"></div>
        </div>
        <div class="skeleton skeleton-date"></div>
      </div>
      <div class="card-skeleton">
        <div class="skeleton skeleton-title"></div>
        <div style="display: flex; flex-direction: column; gap: .5rem;">
          <div class="skeleton skeleton-line"></div>
          <div class="skeleton skeleton-line"></div>
          <div class="skeleton skeleton-line short"></div>
        </div>
        <div class="skeleton skeleton-date"></div>
      </div>
    </div>
  </div>
  <a href="#top" class="hidden" id="top-btn" title="Go to the top of the page">
    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-chevron-up"
      viewBox="0 0 16 16">
      <path fill-rule="evenodd"
        d="M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708z" />
    </svg>
  </a>
</body>

</html>