<?php
  declare(strict_types = 1);

  class Wishlist {
    public int $user;
    public int $product;


    public function __construct(int $user, int $product)
    { 
        $this->user = $user;
        $this->product = $product;
    }

    static function getUserWishlist(PDO $db, int $userId) : array {
        $stmt = $db->prepare('
            SELECT * FROM Wishlist Where UserId = ?
        ');
        $stmt->execute([$userId]);
    
        $wish = array();
    
        while ($row = $stmt->fetch()) {
            $wish[] = new Wishlist(
                $row['UserId'],
                $row['ProductId']
            );
        }
    
        return $wish;
    }

    static function getProductWishlist(PDO $db, int $productId, int $userId): ?Wishlist
    {
        $stmt = $db->prepare('
            SELECT * FROM Wishlist Where UserId = ? AND ProductId = ? 
        ');
        $stmt->execute([$userId, $productId]);

        $wish = $stmt->fetch();

        if(!$wish){
            return null;
        }

        return new Wishlist(
            $wish['UserId'],
            $wish['ProductId']
        );
    }

    static function addToWishlist(PDO $db, $userId, $productId): bool
    {
        $stmt = $db->prepare('INSERT INTO Wishlist (UserId, ProductId) VALUES (?, ?)');
        if ($stmt->execute([$userId, $productId]))
            return true;
        else
            return false;
    }

    static function removeProduct(PDO $db, int $productId, int $userId): bool
    {
      $stmt = $db->prepare('DELETE FROM Wishlist WHERE ProductId = ? AND UserId = ?');
      if ($stmt->execute([$productId, $userId]))
        return true;
      else
        return false;
    }

    static function removeProductFromAllUser(PDO $db, int $productId): bool
    {
      $stmt = $db->prepare('DELETE FROM Wishlist WHERE ProductId = ?');
      if ($stmt->execute([$productId]))
        return true;
      else
        return false;
    }
}
?>