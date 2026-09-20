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
}