<?php

declare(strict_types=1);

require __DIR__ . "/DB.php";

class User extends DB
{
  public static function getUser(string $user): array | bool
  {
    $pdo = self::connect();
    $query = "SELECT * FROM users WHERE user_email = :user_email OR username = :username";

    $statement = $pdo->prepare($query);
    $statement->bindParam(":user_email", $user);
    $statement->bindParam(":username", $user);
    $statement->execute();

    $result = $statement->fetch(PDO::FETCH_ASSOC);

    return $result;
  }

  public static function getUserById(string $id): array | bool
  {
    $pdo = self::connect();
    $query = "SELECT * FROM users WHERE user_id = :user_id";

    $statement = $pdo->prepare($query);
    $statement->bindParam(":user_id", $id);
    $statement->execute();

    $result = $statement->fetch(PDO::FETCH_ASSOC);

    return $result;
  }

  public static function createUser(string $id, string $username, string $email, string $password)
  {
    $pdo = self::connect();
    $query = "INSERT INTO users (user_id, user_email, username, user_password) VALUES (:user_id, :user_email, :username, :user_password)";

    $statement = $pdo->prepare($query);
    $statement->bindParam(":user_email", $email);
    $statement->bindParam(":user_id", $id);
    $statement->bindParam(":user_password", $password);
    $statement->bindParam(":username", $username);
    $result = $statement->execute();

    return $result;
  }

  public static function createUserFromGoogle(string $user_id, string $user_email, string $username)
  {
    $pdo = self::connect();
    $query = "INSERT INTO users (user_id, user_email, username) VALUES (:user_id, :user_email, :username)";

    $statement = $pdo->prepare($query);
    $statement->bindParam(":user_email", $user_email);
    $statement->bindParam(":user_id", $user_id);
    $statement->bindParam(":username", $username);
    $result = $statement->execute();

    return $result;
  }

  // public static function updateUserCredentials(string $user_id, string $user_email, string $username, string $new_password) {}

  public static function setProfilePicture(string $id, string $pfp, string $mime)
  {
    $pdo = self::connect();
    $query = "UPDATE users SET profile_picture = :pfp, profile_picture_mime = :mime WHERE user_id = :id";

    $statement = $pdo->prepare($query);
    $statement->bindParam(":id", $id);
    $statement->bindParam(":pfp", $pfp);
    $statement->bindParam(":mime", $mime);
    $result = $statement->execute();

    return $result;
  }

  public static function deleteUser(string $user_id): bool
  {
    $pdo = self::connect();
    $query = "DELETE FROM users WHERE user_id = :user_id";

    $statement = $pdo->prepare($query);
    $statement->bindParam(":user_id", $user_id);
    $result = $statement->execute();

    return $result;
  }

  public static function userExists(string $user_id): array | bool
  {
    $pdo = self::connect();
    $query = "SELECT * FROM users WHERE user_id = :user_id";

    $statement = $pdo->prepare($query);
    $statement->bindParam(":user_id", $user_id);
    $statement->execute();

    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $result;
  }

  public static function usernameExists(string $username): array|bool
  {
    $pdo = self::connect();
    $query = "SELECT * FROM users WHERE username = :username";

    $statement = $pdo->prepare($query);
    $statement->bindParam(":username", $username);
    $statement->execute();

    $result = $statement->fetch(PDO::FETCH_ASSOC);

    return $result;
  }

  public static function setActivationToken(string $user_id, string $tokenHash): bool
  {
    $pdo = self::connect();
    $query = "UPDATE users SET activation_token = :token WHERE user_id = :user_id";
    $statement = $pdo->prepare($query);
    $statement->bindParam(":token", $tokenHash);
    $statement->bindParam(":user_id", $user_id);
    $result = $statement->execute();

    return $result;
  }

  public static function getUserByActivationTokenHash(string $tokenHash): array | bool
  {
    $pdo = self::connect();
    $query = "SELECT * FROM users WHERE activation_token = :token";
    $statement = $pdo->prepare($query);
    $statement->bindParam(":token", $tokenHash);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    return $result;
  }

  public static function activateUser(string $user_id): bool
  {
    $pdo = self::connect();
    $query = "UPDATE users SET is_activated = 1, activation_token = NULL WHERE user_id = :user_id";
    $statement = $pdo->prepare($query);
    $statement->bindParam(":user_id", $user_id);
    $result =  $statement->execute();

    return $result;
  }

  public static function isActivated(string $user_id): array | bool
  {
    $pdo = self::connect();
    $query = "SELECT is_activated from users WHERE user_id = :user_id";
    $statement = $pdo->prepare($query);
    $statement->bindParam(":user_id", $user_id);
    $result =  $statement->execute();

    return $result;
  }

  public static function resetTokenHash(string $reset_token_hash, string $reset_token_expires_at, string $email): bool
  {
    $pdo = self::connect();
    $query = "UPDATE users SET reset_token_hash = :reset_token_hash, reset_token_expires_at = :reset_token_expires_at WHERE user_email = :user_email";

    $statement = $pdo->prepare($query);
    $statement->bindParam(":reset_token_hash", $reset_token_hash);
    $statement->bindParam(":reset_token_expires_at", $reset_token_expires_at);
    $statement->bindParam(":user_email", $email);
    $result =  $statement->execute();

    return $result;
  }

  public static function getUserByResetTokenHash(string $tokenHash): array | bool
  {
    $pdo = self::connect();
    $query = "SELECT * FROM users WHERE reset_token_hash = :reset_token_hash AND reset_token_expires_at > NOW()";
    $statement = $pdo->prepare($query);
    $statement->bindParam(":reset_token_hash", $tokenHash);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    return $result;
  }

  public static function updatePassword(string $user_id, string $passwordHash): bool
  {
    $pdo = self::connect();
    $query = "UPDATE users SET user_password = :user_password, reset_token_hash = NULL, reset_token_expires_at = NULL WHERE user_id = :user_id";

    $statement = $pdo->prepare($query);
    $statement->bindParam(":user_password", $passwordHash);
    $statement->bindParam(":user_id", $user_id);
    $result = $statement->execute();

    return $result;
  }
}
