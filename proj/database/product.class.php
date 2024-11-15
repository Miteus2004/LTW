<?php

declare(strict_types=1);

class Product
{
  public int $id;
  public string $name;
  public string $description;
  public float $price;
  public string $location;
  public int $numberimages;
  public int $user;
  public int $category;
  public int $condition;



  public function __construct(int $id, string $name, string $description, float $price, string $location, int $numberimages, int $user, int $category, int $condition)
  {
    $this->id = $id;
    $this->name = $name;
    $this->description = $description;
    $this->price = $price;
    $this->location = $location;
    $this->numberimages = $numberimages;
    $this->user = $user;
    $this->category = $category;
    $this->condition = $condition;
  }

  // all except userid products
  static function getProductsMain(PDO $db, int $userid): array
  {
    $stmt = $db->prepare("SELECT * FROM Product WHERE UserId <> ?");
    $stmt->execute(array($userid));

    $products = array();
    while ($product = $stmt->fetch()) {
      $products[] = new Product(
        $product['ProductId'],
        $product['Name'],
        $product['Description'],
        $product['Price'],
        $product['Location'],
        $product['NumberImages'],
        $product['UserId'],
        $product['CategoryId'],
        $product['ConditionId'],
      );
    }

    return $products;
  }

  //only userid products 
  static function getUserSellingProducts(PDO $db, int $userid): array
  {
    $stmt = $db->prepare('SELECT * FROM Product WHERE UserId = ?');
    $stmt->execute(array($userid));

    $products = array();
    while ($product = $stmt->fetch()) {
      $products[] = new Product(
        $product['ProductId'],
        $product['Name'],
        $product['Description'],
        (float) $product['Price'],
        $product['Location'],
        $product['NumberImages'],
        $product['UserId'],
        $product['CategoryId'],
        $product['ConditionId'],
      );
    }

    return $products;
  }

  static function getProductsOrderedFilteredMain(PDO $db, int $userId, string $search, int $minprice, int $maxprice, string $filter, string $order): array
  {
    $stmt = $db->prepare("SELECT * FROM Product WHERE UserId <> ? AND Name Like ? AND Price >= ? And Price <= ? $filter $order");
    $stmt->execute(array($userId, '%' . $search . '%', $minprice, $maxprice));

    $products = array();
    while ($product = $stmt->fetch()) {
      $products[] = new Product(
        $product['ProductId'],
        $product['Name'],
        $product['Description'],
        $product['Price'],
        $product['Location'],
        $product['NumberImages'],
        $product['UserId'],
        $product['CategoryId'],
        $product['ConditionId'],
      );
    }

    return $products;
  }

  static function getMyProductsOrderedFiltered(PDO $db, int $userId, string $search, int $minprice, int $maxprice, string $order): array
  {
    $stmt = $db->prepare("SELECT * FROM Product WHERE UserId = ? AND Name Like ? AND Price >= ? AND Price <= ? $order");
    $stmt->execute(array($userId, '%' . $search . '%', $minprice, $maxprice));

    $products = array();
    while ($product = $stmt->fetch()) {
      $products[] = new Product(
        $product['ProductId'],
        $product['Name'],
        $product['Description'],
        $product['Price'],
        $product['Location'],
        $product['NumberImages'],
        $product['UserId'],
        $product['CategoryId'],
        $product['ConditionId'],
      );
    }

    return $products;
  }


  static function getProduct(PDO $db, int $id): Product
  {
    $stmt = $db->prepare('SELECT * FROM Product WHERE ProductId = ?');
    $stmt->execute(array($id));

    $product = $stmt->fetch();
    return new Product(
      $product['ProductId'],
      $product['Name'],
      $product['Description'],
      $product['Price'],
      $product['Location'],
      $product['NumberImages'],
      $product['UserId'],
      $product['CategoryId'],
      $product['ConditionId'],
    );
  }

  static function getNewestUserProduct(PDO $db, int $id): Product
  {
    $stmt = $db->prepare('SELECT * FROM Product WHERE UserId = ? ORDER BY ProductId DESC LIMIT 1');
    $stmt->execute(array($id));

    $product = $stmt->fetch();
    return new Product(
      $product['ProductId'],
      $product['Name'],
      $product['Description'],
      $product['Price'],
      $product['Location'],
      $product['NumberImages'],
      $product['UserId'],
      $product['CategoryId'],
      $product['ConditionId'],
    );
  }



  static function newProduct(PDO $db, string $productName, int $id, int $category, int $condition, float $price, int $numberImages, string $description, string $location): bool
  {
    $stmt = $db->prepare('INSERT INTO Product (Name, Description, Price, Location, NumberImages, UserId, CategoryId, ConditionId) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');

    if ($stmt->execute(array($productName, $description, $price, $location, $numberImages, $id, $category, $condition)))
      return true;
    else
      return false;
  }


  static function updateImagesProduct(PDO $db, int $numberImages, int $id): bool
  {
    $stmt = $db->prepare('UPDATE Product SET NumberImages = ? WHERE ProductId = ?');

    if ($stmt->execute(array($numberImages, $id)))
      return true;
    else
      return false;
  }


  static function editProduct(PDO $db, int $id, string $productName, int $category, int $condition, float $price, int $numberImages, string $description): bool
  {

    $stmt = $db->prepare('UPDATE Product SET Name = ?, Description= ?, Price=?, NumberImages = ? , CategoryId = ?, ConditionId = ? Where ProductId = ?');
    if ($stmt->execute(array($productName, $description, $price, $numberImages, $category, $condition, $id)))
      return true;
    else
      return false;
  }


  static function deleteProduct(PDO $db, int $productId): bool
  {
    $stmt = $db->prepare('DELETE FROM Product WHERE ProductId = ?');
    if ($stmt->execute([$productId]))
      return true;
    else
      return false;
  }

}
