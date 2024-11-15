<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

if (!$session->isLoggedIn())
    die(header('Location: /'));


require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/product.class.php');
require_once(__DIR__ . '/../database/cart.class.php');


require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/product.tpl.php');
require_once(__DIR__ . '/../templates/user.tpl.php');

// Establish a database connection (you may need to modify this)
$db = getDatabaseConnection();

// Fetch current user information
$currentUserId = $session->getId();

$myuser = User::getUser($db, $currentUserId);

$products = [];

$dealPrice = null;

$accepDealChat = $_SESSION['acceptDealChat'];

if ($accepDealChat != null) {
    $productid = $accepDealChat['product'];
    $dealPrice = $accepDealChat['price'];
    $product = Product::getProduct($db, intval($productid));
    $seller = User::getUser($db, intval($product->user));
    $products[] = $product;
    $products[] = $seller;
    unset($_SESSION['acceptDealChat']);

} else {
    $cartproducts = Cart::getUserCart($db, $currentUserId);

    foreach ($cartproducts as $cartproduct) {
        $productsInfo = [];
        $product = Product::getProduct($db, $cartproduct->product);
        $seller = User::getUser($db, $product->user);
        $productsInfo[] = $product;
        $productsInfo[] = $seller;
        $products[] = $productsInfo;
    }
}


drawHeader($myuser);

drawPayment($myuser, $products, $dealPrice);

drawFooter();
