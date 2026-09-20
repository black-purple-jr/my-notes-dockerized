<?php
require __DIR__ . "/../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

define("BASE_URL", $_ENV["BASE_URL"]);
