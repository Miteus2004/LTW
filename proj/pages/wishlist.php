<?php

declare(strict_types=1);

require_once (__DIR__ . '/../utils/session.php');
$session = new Session();

if (!$session->isLoggedIn()) {
    die(header('Location: /'));
}

require_once (__DIR__ . '/../database/connection.db.php');
require_once (__DIR__ . '/../database/user.class.php');
require_once (__DIR__ . '/../database/product.class.php');
require_once (__DIR__ . '/../database/cart.class.php');
require_once (__DIR__ . '/../database/wishlist.class.php');

require_once (__DIR__ . '/../templates/common.tpl.php');
require_once (__DIR__ . '/../templates/user.tpl.php');

$db = getDatabaseConnection();

$myuser = User::getUser($db, $session->getId());

$wishlistItems = Wishlist::getUserWishlist($db, $myuser->id);

$products = [];

foreach($wishlistItems as $item){
    $products[] = Product::getProduct($db,$item->product);

}

drawHeader($myuser);

drawWishlist($products);

drawFooter();
?>