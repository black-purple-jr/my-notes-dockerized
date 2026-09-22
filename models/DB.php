<?php

declare(strict_types=1);
require __DIR__ . "/../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

class DB
{
  protected static function connect(): PDO
  {
    $server = $_ENV["DB_HOST"];
    $username = $_ENV["DB_USERNAME"];
    $password = $_ENV["DB_PASSWORD"];
    $database = $_ENV["DB_DATABASE"];
    $charset = "utf8mb4";

    try {
      $dsn = "mysql:host=$server;dbname=$database;charset=$charset";
      $pdo = new PDO($dsn, $username, $password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      return $pdo;
    } catch (Exception $e) {
      echo "Connection failed : " . $e->getMessage();
      throw $e;
    }
  }

  public static function db_init()
  {
    $pdo = self::connect();
    $query = "CREATE TABLE IF NOT EXISTS `users` (
  `user_id` varchar(50) NOT NULL,
  `user_email` varchar(150) DEFAULT NULL,
  `user_password` varchar(150) DEFAULT NULL,
  `is_activated` tinyint(1) NOT NULL DEFAULT 0,
  `activation_token` varchar(64) DEFAULT NULL,
  `reset_token_hash` varchar(240) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL,
  `username` varchar(250) DEFAULT NULL,
  `profile_picture` text DEFAULT NULL,
  `profile_picture_mime` varchar(50) DEFAULT NULL,

  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_email` (`user_email`),
  UNIQUE KEY `reset_token_hash` (`reset_token_hash`),
  UNIQUE KEY `reset_token_expires_at` (`reset_token_expires_at`),
  UNIQUE KEY `username` (`username`),

);

CREATE TABLE IF NOT EXISTS `notes` (
  `note_id` varchar(50) NOT NULL,
  `note_title` varchar(300) DEFAULT NULL,
  `note_content` text DEFAULT NULL,
  `note_date` datetime DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,

  PRIMARY KEY (`note_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL
)
";
    $statement = $pdo->prepare($query);
    $result = $statement->execute();
  }
}
// DB_OST=host.docker.internalH