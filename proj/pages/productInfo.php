<?php

declare(strict_types=1);

require_once (__DIR__ . '/../utils/session.php');
$session = new Session();


if (!$session->isLoggedIn())
    die(header('Location: /'));
require_once (__DIR__ . '/../database/connection.db.php');
require_once (__DIR__ . '/../database/category.class.php');
require_once (__DIR__ . '/../database/condition.class.php');
require_once (__DIR__ . '/../database/user.class.php');
require_once (__DIR__ . '/../database/deal.class.php');
require_once (__DIR__ . '/../database/wishlist.class.php');
require_once (__DIR__ . '/../database/cart.class.php');




require_once (__DIR__ . '/../templates/common.tpl.php');
require_once (__DIR__ . '/../templates/product.tpl.php');

$db = getDatabaseConnection();

$myuser = User::getUser($db, $session->getId());
$product = Product::getProduct($db, intval($_GET['id']));
$productOwner = User::getUser($db, $product->user);

$deal = Deal::getProductDeal($db, $product->id);

$wishlist = Wishlist::getProductWishlist($db,$product->id,$myuser->id);
$cart = Cart::getProductCart($db,$product->id,$myuser->id);


drawHeader($myuser);

drawProductInfo($myuser, $productOwner, $product,  $deal != null, $wishlist != null, $cart != null);

drawFooter();
