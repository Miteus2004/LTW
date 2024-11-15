<?php

declare(strict_types=1);

class Deal
{
    public float $price;
    public string $date;
    public int $product;
    public int $buyer;




    public function __construct(float $price, string $date, int $product, int $buyer)
    {
        $this->price = $price;
        $this->date = $date;
        $this->product = $product;
        $this->buyer = $buyer;
    }


    static function getDeals(PDO $db): array
    {
        $stmt = $db->prepare('SELECT Price, Date, ProductId, BuyerId FROM Deal');
        $stmt->execute();

        $deals = array();
        while ($deal = $stmt->fetch()) {
            $deals[] = new Deal(
                $deal['Price'],
                $deal['Date'],
                $deal['ProductId'],
                $deal['BuyerId'],
            );
        }

        return $deals;
    }

    static function getProductDeal(PDO $db, int $product): ?Deal
    {
        $stmt = $db->prepare('SELECT Price, Date, ProductId, BuyerId FROM Deal WHERE ProductId = ?');
        $stmt->execute(array($product));

        $deal = $stmt->fetch();
        if ($deal) {
            return new Deal(
                $deal['Price'],
                $deal['Date'],
                $deal['ProductId'],
                $deal['BuyerId'],
            );
        } else
            return null;
    }

    static function getProductDealFilterDate(PDO $db, int $product, string $date): ?Deal
    {
        $stmt = $db->prepare("SELECT * FROM Deal WHERE ProductId = ? AND Date LIKE ?");
        $stmt->execute(array($product, '%' . $date . '%'));

        $deal = $stmt->fetch();
        if ($deal) {
            return new Deal(
                $deal['Price'],
                $deal['Date'],
                $deal['ProductId'],
                $deal['BuyerId'],
            );
        } else
            return null;
    }

    static function newDeal(PDO $db, int $product, int $buyer, float $price): bool
    {
      $stmt = $db->prepare('INSERT INTO Deal (Price, Date, ProductId, BuyerId) VALUES (?, ?, ?, ?)');
  
      if ($stmt->execute(array($price, date('d-m-Y'), $product, $buyer))) return true;
      else return false;
    }

    static function deleteDeal(PDO $db, int $productId): bool
    {
      $stmt = $db->prepare('DELETE FROM Deal WHERE ProductId = ?');
      if ($stmt->execute([$productId]))
        return true;
      else
        return false;
    }

}
