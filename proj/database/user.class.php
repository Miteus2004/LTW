<?php

declare(strict_types=1);

class User
{
  public int $id;
  public string $name;
  public string $username;
  public string $email;
  public string $loc;
  public string $joinedDate;
  public int $admin;
  public int $hasPhoto;

  public function __construct(int $id, string $name, string $username, string $email, string $loc, string $joinedDate, int $admin, int $hasPhoto)
  {
    $this->id = $id;
    $this->name = $name;
    $this->username = $username;
    $this->email = $email;
    $this->loc = $loc;
    $this->joinedDate = $joinedDate;
    $this->admin = $admin;
    $this->hasPhoto = $hasPhoto;
  }


  static function getUsers(PDO $db): array
  {
    $stmt = $db->prepare('SELECT * FROM User');
    $stmt->execute();

    $users = array();
    while ($user = $stmt->fetch()) {
      $users[] = new User(
        $user['UserId'],
        $user['Name'],
        $user['Username'],
        $user['Email'],
        $user['Location'],
        $user['JoinedDate'],
        $user['Admin'],
        $user['Hasphoto']
      );
    }

    return $users;
  }




  static function getUser(PDO $db, int $id): User
  {
    $stmt = $db->prepare('SELECT * FROM User WHERE UserId = ?');
    $stmt->execute(array($id));

    $user = $stmt->fetch();

    return new User(
      $user['UserId'],
      $user['Name'],
      $user['Username'],
      $user['Email'],
      $user['Location'],
      $user['JoinedDate'],
      $user['Admin'],
      $user['Hasphoto'],
    );
  }

  static function setPhoto(PDO $db, int $id): bool
  {
    $stmt = $db->prepare('UPDATE User SET Hasphoto = 1 WHERE UserId = ?');

    if ($stmt->execute(array($id)))
      return true;
    else
      return false;
  }

  static function removePhoto(PDO $db, int $id): bool
  {
    $stmt = $db->prepare('UPDATE User SET Hasphoto = 0 WHERE UserId = ?');

    if ($stmt->execute(array($id)))
      return true;
    else
      return false;
  }

  static function getUserWithLogin(PDO $db, string $email, string $password): ?User
  {
    $stmt = $db->prepare('
        SELECT *
        FROM User 
        WHERE lower(email) = ?
      ');

    $stmt->execute(array(strtolower($email)));
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['Password'])) {
      return new User(
        $user['UserId'],
        $user['Name'],
        $user['Username'],
        $user['Email'],
        $user['Location'],
        $user['JoinedDate'],
        $user['Admin'],
        $user['Hasphoto'],
      );
    } else
      return null;
  }

  static function verifyUserViaId(PDO $db, int $id, string $password): ?User
  {
    $stmt = $db->prepare('
        SELECT *
        FROM User 
        WHERE UserId = ?
      ');

    $stmt->execute(array($id));
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['Password'])) {
      return new User(
        $user['UserId'],
        $user['Name'],
        $user['Username'],
        $user['Email'],
        $user['Location'],
        $user['JoinedDate'],
        $user['Admin'],
        $user['Hasphoto'],
      );
    } else
      return null;
  }

  static function registNewUser(PDO $db, string $name, string $username, string $email, string $password, string $loc): bool
  {
    $stmt = $db->prepare('INSERT INTO User (Name, Username, Email, Password, Location, JoinedDate, Admin) VALUES (?, ?, ?, ?, ?, ?, ?)');

    if ($stmt->execute(array($name, $username, $email, $password, $loc, date('d-m-Y'), 0)))
      return true;
    else
      return false;

  }

  static function editUserPassword(PDO $db, int $id, string $newPassword): bool
  {
    $stmt = $db->prepare('UPDATE User SET Password = ? WHERE UserId = ?');

    if ($stmt->execute(array($newPassword, $id)))
      return true;
    else
      return false;

  }

  static function editUser(PDO $db, int $id, string $name, string $username, string $email, string $loc): bool
  {
    $stmt = $db->prepare('UPDATE User SET Name = ?, Username = ?, Email = ?, Location = ? WHERE UserId = ?');

    if ($stmt->execute(array($name, $username, $email, $loc, $id)))
      return true;
    else
      return false;

  }


  static function searchUser(PDO $db, string $search): array
  {
    $stmt = $db->prepare('SELECT * FROM User WHERE Username LIKE ?');
    $stmt->execute(array('%' . $search . '%'));

    $users = array();
    while ($user = $stmt->fetch()) {
      $users[] = new User(
        $user['UserId'],
        $user['Name'],
        $user['Username'],
        $user['Email'],
        $user['Location'],
        $user['JoinedDate'],
        $user['Admin'],
        $user['Hasphoto'],
      );
    }

    return $users;
  }

  static function makeAdmin(PDO $db, int $id): bool
  {
    $stmt = $db->prepare('UPDATE User SET Admin = 1 WHERE UserId = ?');

    if ($stmt->execute(array($id)))
      return true;
    else
      return false;
  }

  static function removeAdmin(PDO $db, int $id): bool
  {
    $stmt = $db->prepare('UPDATE User SET Admin = 0 WHERE UserId = ?');

    if ($stmt->execute(array($id)))
      return true;
    else
      return false;
  }
}