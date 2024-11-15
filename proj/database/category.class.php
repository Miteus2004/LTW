<?php

declare(strict_types=1);

class Category
{
  public int $id;
  public string $name;




  public function __construct(int $id, string $name)
  {
    $this->id = $id;
    $this->name = $name;
  }


  static function getCategories(PDO $db): array
  {
    $stmt = $db->prepare('SELECT CategoryId, Name FROM Category');
    $stmt->execute();

    $categories = array();
    while ($category = $stmt->fetch()) {
      $categories[] = new Category(
        $category['CategoryId'],
        $category['Name'],
      );
    }

    return $categories;
  }

  static function getCategory(PDO $db, int $id): Category
  {
    $stmt = $db->prepare('SELECT CategoryId, Name FROM Category WHERE CategoryId LIKE ?');
    $stmt->execute(array($id));

    $category = $stmt->fetch();

    return new Category(
      $category['CategoryId'],
      $category['Name'],
    );
  }

  static function searchCategory(PDO $db, string $name): ?Category
  {
    $stmt = $db->prepare('SELECT CategoryId, Name FROM Category WHERE Name LIKE ?');
    $stmt->execute(array($name));

    $category = $stmt->fetch();
    if ($category) {
      return new Category(
        $category['CategoryId'],
        $category['Name'],
      );
    } else
      return null;

  }

  static function newCategory(PDO $db, $categoryName): bool
  {
    $stmt = $db->prepare('INSERT INTO Category (Name) VALUES (?)');
    if ($stmt->execute([$categoryName]))
      return true;
    else
      return false;
  }

  static function deleteCategory(PDO $db, $categoryName): bool
  {
    $stmt = $db->prepare('DELETE FROM Category WHERE Name = ?');
    if ($stmt->execute([$categoryName]))
      return true;
    else
      return false;
  }


}
