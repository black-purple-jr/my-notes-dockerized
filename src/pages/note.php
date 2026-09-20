<?php
require "../../config/session.php";
require "../../config/router.php";
require "../../models/Note.php";
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

$note_id = $_GET["note-id"];
$user_id = $_SESSION["current_user_id"];

$note = Note::getNote($user_id, $note_id);

if (isset($_GET["action"])) {
  if ($_GET["action"] === "delete") {
    try {
      Note::deleteNote($user_id, $note_id);
      $_SESSION["toast"] = "delete-success";
      header("Location: " . BASE_URL);
      exit;
    } catch (Exception $e) {
      header("Location: " . BASE_URL);
      exit;
    }
  }
}


if (isset($_POST["new-title"]) || isset($_POST["new-content"])) {

  $new_title = $_POST["new-title"];
  if ($new_title === "") {
    $new_title = "Untitled Note";
  }
  $new_content = $_POST["new-content"];
  $new_date = date("Y-m-d H:i:s");
  try {
    $edited_note = Note::editNote($user_id, $note_id, $new_title, $new_content, $new_date);
    $_SESSION["toast"] = "edit-success";
    header("Location: " . BASE_URL);
    exit;
  } catch (Exception $e) {
    header("Location: " . BASE_URL);
    exit;
  }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $note["note_title"] ?> - My Notes</title>
  <link rel="icon" type="image/svg+xml" href="../../assets/favicon.svg">
  <link rel="stylesheet" href="../css/note.css">
  <script src="../js/note.js" defer></script>
</head>

<body>
  <header>
    <a href="../">
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
  <form action="" method="post">
    <label for="note-title">Note Title</label>
    <input type="text" name="new-title" id="note-title" value="<?php echo htmlspecialchars($note["note_title"]) ?>">

    <label for="note-content">Note Content</label>
    <textarea name="new-content" id="note-content"><?php echo htmlspecialchars($note['note_content']) ?></textarea>
    <div class="btn-group">
      <a href="../../" class="cancel">Cancel</a>
      <button class="update" type="submit">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-save-icon lucide-save">
          <path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
          <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
          <path d="M7 3v4a1 1 0 0 0 1 1h7" />
        </svg>
        Save Changes
      </button>
      <a href="note.php?note-id=<?php echo htmlspecialchars($note_id) ?>&action=delete"
        class="delete">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-icon lucide-trash">
          <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
          <path d="M3 6h18" />
          <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
        </svg>
        Delete
      </a>
    </div>
  </form>
</body>

</html>