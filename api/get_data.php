<?php
require "../config/session.php";
require "../models/Note.php";
require "../models/User.php";

header("Content-Type: application/json");

if (!isset($_SESSION["current_user_id"])) {
  http_response_code(401);
  echo json_encode(["error" => "Not authenticated"]);
  exit;
}

$current_user_id = $_SESSION["current_user_id"];
$check = User::userExists($current_user_id);

if (!$check) {
  http_response_code(401);
  echo json_encode(["error" => "User does not exist"]);
  exit;
}

function mysqlDateToString(string $mysqlDate): string
{
  $timestamp = strtotime($mysqlDate);
  return date("F j, Y", $timestamp);
}

// Support the same search behavior as index.php, via ?search=...
if (isset($_GET["search"]) && $_GET["search"] !== "") {
  $notes = Note::searchNote($current_user_id, $_GET["search"]);
} else {
  $notes = Note::getNotes($current_user_id);
}

$formatted = array_map(function ($note) {
  return [
    "note_id"      => $note["note_id"],
    "note_title"   => $note["note_title"],
    "note_content" => $note["note_content"],
    "note_date"    => mysqlDateToString($note["note_date"]),
  ];
}, $notes);

echo json_encode($formatted);
