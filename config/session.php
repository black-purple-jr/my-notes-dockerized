<?php

ini_set("session.use_only_cookies", 1);
ini_set("session.use_strict_mode", 1);
// session_set_cookie_params(0, '/', "10.217.113.233");

session_set_cookie_params([
  "lifetime" => 1800,
  "domain" => "my-notes.bpxdevjr.me",
  "path" => "/",
  "secure" => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
  "httponly" => true
]);

session_start();

if (isset($_SESSION["current_user_id"])) {
  if (!isset($_SESSION["last_regeneration"])) {
    regenerate_session_id_logedin();
  } else {
    $interval = 60 * 30;
    if (time() - $_SESSION["last_regeneration"] >= $interval) {
      regenerate_session_id_logedin();
    }
  }
} else {
  if (!isset($_SESSION["last_regeneration"])) {
    regenerate_session_id();
  } else {
    $interval = 60 * 30;
    if (time() - $_SESSION["last_regeneration"] >= $interval) {
      regenerate_session_id();
    }
  }
}

function regenerate_session_id()
{
  session_regenerate_id(true);
  $_SESSION["last_regeneration"] = time();
}

function regenerate_session_id_logedin()
{
  session_regenerate_id(true);
  $_SESSION["last_regeneration"] = time();
}
