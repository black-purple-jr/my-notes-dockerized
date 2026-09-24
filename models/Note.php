<?php

declare(strict_types=1);

require __DIR__ . "DB.php";


class Note extends DB
{
  public static function addNote(string $user_id, string $note_id, string $note_title, string $note_content, string $note_date): bool
  {
    $pdo = self::connect();

    $query = "INSERT INTO notes (note_id, note_title, note_content, note_date, user_id) VALUES (:note_id, :note_title, :note_content, :note_date, :user_id);";
    $statement = $pdo->prepare($query);

    $statement->bindParam(":note_id", $note_id);
    $statement->bindParam(":user_id", $user_id);
    $statement->bindParam(":note_title", $note_title);
    $statement->bindParam(":note_content", $note_content);
    $statement->bindParam(":note_date", $note_date);
    $result = $statement->execute();

    $statement = null;
    $pdo = null;

    return $result;
  }
  public static function getNote(string $user_id, string $note_id): array
  {
    $pdo = self::connect();

    $query = "SELECT * FROM notes WHERE note_id = :note_id AND user_id = :user_id;";
    $statement = $pdo->prepare($query);

    $statement->bindParam(":note_id", $note_id);
    $statement->bindParam(":user_id", $user_id);
    $statement->execute();

    $result = $statement->fetch(PDO::FETCH_ASSOC);

    $statement = null;
    $pdo = null;

    return $result;
  }
  public static function getNotes(string $user_id): array
  {
    $pdo = self::connect();

    $query = "SELECT * FROM notes WHERE user_id = :user_id ORDER BY note_date DESC";
    $statement = $pdo->prepare($query);

    $statement->bindParam(":user_id", $user_id);

    $result = $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    $statement = null;
    $pdo = null;

    return $result;
  }
  public static function getNotesCount(string $user_id): int
  {
    $pdo = self::connect();

    $query = "SELECT COUNT(*) AS total FROM notes WHERE user_id = :user_id";
    $statement = $pdo->prepare($query);

    $statement->bindParam(":user_id", $user_id);

    $result = $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    $statement = null;
    $pdo = null;

    return $result["total"];
  }
  public static function editNote(string $user_id, string $note_id, string $note_title, string $note_content, string $note_date): bool
  {
    $pdo = self::connect();

    $query = "UPDATE notes SET note_title = :note_title , note_content = :note_content , note_date = :note_date WHERE note_id = :note_id AND user_id = :user_id;";
    $statement = $pdo->prepare($query);

    $statement->bindParam(":note_id", $note_id);
    $statement->bindParam(":user_id", $user_id);
    $statement->bindParam(":note_title", $note_title);
    $statement->bindParam(":note_content", $note_content);
    $statement->bindParam(":note_date", $note_date);
    $result = $statement->execute();

    $statement = null;
    $pdo = null;

    return $result;
  }
  public static function deleteNote(string $user_id, string $note_id): bool
  {
    $pdo = self::connect();

    $query = "DELETE FROM notes WHERE note_id = :note_id AND user_id = :user_id;";
    $statement = $pdo->prepare($query);

    $statement->bindParam(":note_id", $note_id);
    $statement->bindParam(":user_id", $user_id);
    $result = $statement->execute();

    $statement = null;
    $pdo = null;

    return $result;
  }
  public static function searchNote(string $user_id, string $keyword): array
  {
    $pdo = self::connect();

    $query = "SELECT * FROM notes WHERE user_id = :user_id AND (note_title LIKE :keyword OR note_content LIKE :keyword);";
    $statement = $pdo->prepare($query);

    $keyword_like = "%$keyword%";
    $statement->bindParam(":keyword", $keyword_like);
    $statement->bindParam(":user_id", $user_id);

    $result = $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    $statement = null;
    $pdo = null;

    return $result;
  }
}
