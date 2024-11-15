<?php

declare(strict_types=1);

require_once (__DIR__ . '/../utils/session.php');
$session = new Session();


if (!$session->isLoggedIn())
  die(header('Location: /'));
require_once (__DIR__ . '/../database/connection.db.php');
require_once (__DIR__ . '/../database/user.class.php');
require_once (__DIR__ . '/../database/product.class.php');
require_once (__DIR__ . '/../database/category.class.php');
require_once (__DIR__ . '/../database/condition.class.php');
require_once (__DIR__ . '/../database/deal.class.php');
require_once (__DIR__ . '/../database/wishlist.class.php');




require_once (__DIR__ . '/../templates/common.tpl.php');
require_once (__DIR__ . '/../templates/product.tpl.php');

$db = getDatabaseConnection();


$user = User::getUser($db, $session->getId());
$products = Product::getProductsMain($db, $session->getid());
$conditions = Condition::getConditions($db);
$categories = Category::getCategories($db);
$filters = array(
  'price' => '',
  'categories' => $categories,
  'conditions' => $conditions
);

$sellProducts = [];

foreach ($products as $product) {
  $deal = Deal::getProductDeal($db, $product->id);
  if (!$deal) {
    $sellProducts[] = $product;
  }
}

$wishlist = [];

foreach($sellProducts as $product){
  if(Wishlist::getProductWishlist($db,$product->id,$user->id)) $wishlist[] = $product;
}



drawHeader($user);

drawProductsMain($sellProducts, $filters, $wishlist);

drawFooter();
