<?php

declare(strict_types=1);

class Cart
{
    public int $user;
    public int $product;


    public function __construct(int $user, int $product)
    {
        $this->user = $user;
        $this->product = $product;
    }

    static function getUserCart(PDO $db, int $userId): array
    {
        $stmt = $db->prepare('
            SELECT * FROM ShoppingCart Where UserId = ?
        ');
        $stmt->execute([$userId]);

        $cart = array();

        while ($row = $stmt->fetch()) {
            $cart[] = new Cart(
                $row['UserId'],
                $row['ProductId']
            );
        }

        return $cart;
    }

    static function getProductCart(PDO $db, int $product, int $userId): ?Cart
    {
        $stmt = $db->prepare('
            SELECT * FROM ShoppingCart Where ProductId = ? and UserId = ?
        ');
        $stmt->execute([$product, $userId]);

        $cart = $stmt->fetch();


        if(!$cart){
            return null;
        }

        return new Cart(
            $cart['UserId'],
            $cart['ProductId']
        );
    }

    static function addToCart(PDO $db, $userId, $productId): bool
    {
        $stmt = $db->prepare('INSERT INTO ShoppingCart (Userid, ProductId) VALUES (?, ?)');
        if ($stmt->execute([$userId, $productId]))
            return true;
        else
            return false;
    }

    static function removeProduct(PDO $db, int $productId, int $userId): bool
    {
        $stmt = $db->prepare('DELETE FROM ShoppingCart WHERE ProductId = ? AND UserId = ?');
        if ($stmt->execute([$productId, $userId]))
            return true;
        else
            return false;
    }

    static function removeProductFromAllUsers(PDO $db, int $productId): bool
    {
        $stmt = $db->prepare('DELETE FROM ShoppingCart WHERE ProductId = ?');
        if ($stmt->execute([$productId]))
            return true;
        else
            return false;
    }


}
