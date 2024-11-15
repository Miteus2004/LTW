<?php

declare(strict_types=1);

class History
{
  public int $id;
  public string $name;
  public float $price;
  public int $seller;
  public int $buyer;
  public string $date;




  public function __construct(int $id, string $name, float $price, int $seller, int $buyer, string $date)
  {
    $this->id = $id;
    $this->name = $name;
    $this->price = $price;
    $this->seller = $seller;
    $this->buyer = $buyer;
    $this->date = $date;
  }


  static function getSellHistory(PDO $db, int $userid): array
  {
    $stmt = $db->prepare('SELECT HistoryId, Name, Price, SellerId, BuyerId, SellDate FROM ProductHistory WHERE SellerId = ?');
    $stmt->execute(array($userid));

    $products = array();
    while ($product = $stmt->fetch()) {
      $products[] = new History(
        $product['HistoryId'],
        $product['Name'],
        $product['Price'],
        $product['SellerId'],
        $product['BuyerId'],
        $product['SellDate'],
      );
    }

    return $products;
  }

  static function getBuyHistory(PDO $db, int $userid): array
  {
    $stmt = $db->prepare('SELECT HistoryId, Name, Price, SellerId, BuyerId, SellDate FROM ProductHistory WHERE BuyerId = ?');
    $stmt->execute(array($userid));

    $products = array();
    while ($product = $stmt->fetch()) {
      $products[] = new History(
        $product['HistoryId'],
        $product['Name'],
        $product['Price'],
        $product['SellerId'],
        $product['BuyerId'],
        $product['SellDate'],
      );
    }

    return $products;
  }


  static function getHistoryBuyProductFilteredOrdered(PDO $db, int $userId, string $search, string $date, int $minprice, int $maxprice, string $order): array
  {
    $stmt = $db->prepare("SELECT * FROM ProductHistory WHERE BuyerId = ? AND Name Like ? AND SellDate LIKE ? AND Price >= ? AND Price <= ? $order");
    $stmt->execute(array($userId, '%' . $search . '%', '%' . $date . '%', $minprice, $maxprice));

    $products = array();
    while ($product = $stmt->fetch()) {
      $products[] = new History(
        $product['HistoryId'],
        $product['Name'],
        $product['Price'],
        $product['SellerId'],
        $product['BuyerId'],
        $product['SellDate'],
      );
    }

    return $products;
  }


  static function getHistorySellProductFilteredOrdered(PDO $db, int $userId, string $search, string $date, int $minprice, int $maxprice, string $order): array
  {
    $stmt = $db->prepare("SELECT * FROM ProductHistory WHERE SellerId = ? AND Name Like ? AND SellDate LIKE ? AND Price >= ? AND Price <= ? $order");
    $stmt->execute(array($userId, '%' . $search . '%', '%' . $date . '%', $minprice, $maxprice));

    $products = array();
    while ($product = $stmt->fetch()) {
      $products[] = new History(
        $product['HistoryId'],
        $product['Name'],
        $product['Price'],
        $product['SellerId'],
        $product['BuyerId'],
        $product['SellDate'],
      );
    }

    return $products;
  }




  static function newHistoryProduct(PDO $db, string $productName, float $price, int $seller, int $buyer): bool
  {
    $stmt = $db->prepare('INSERT INTO ProductHistory (Name, Price, SellerId, BuyerId, SellDate) VALUES (?, ?, ?, ?, ?)');

    if ($stmt->execute(array($productName, $price, $seller, $buyer, date('d-m-Y'))))
      return true;
    else
      return false;
  }

}
