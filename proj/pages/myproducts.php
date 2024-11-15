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


require_once (__DIR__ . '/../templates/common.tpl.php');
require_once (__DIR__ . '/../templates/product.tpl.php');
require_once (__DIR__ . '/../templates/user.tpl.php');

$db = getDatabaseConnection();


$user = User::getUser($db, $session->getId());
$products = Product::getUserSellingProducts($db, $session->getid());
$sellProducts = [];
$shipProducts = [];
$deals = [];
$buyers = [];

foreach ($products as $product) {
    $deal = Deal::getProductDeal($db, $product->id);
    if ($deal) {
        $shipProducts[] = $product;
        $deals[$product->id] = $deal;
        $buyers[$product->id] = User::getUser($db, $deal->buyer);
    } else {
        $sellProducts[] = $product;
    }
}



drawHeader($user);

drawMyProducts($sellProducts, $shipProducts, $deals, $buyers);

drawFooter();
