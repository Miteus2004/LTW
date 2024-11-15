<?php

declare(strict_types=1);

require_once (__DIR__ . '/../utils/session.php');
$session = new Session();


if (!$session->isLoggedIn())
    die(header('Location: /'));
require_once (__DIR__ . '/../database/connection.db.php');
require_once (__DIR__ . '/../database/user.class.php');
require_once (__DIR__ . '/../database/history.class.php');
require_once (__DIR__ . '/../database/product.class.php');
require_once (__DIR__ . '/../database/category.class.php');
require_once (__DIR__ . '/../database/condition.class.php');
require_once (__DIR__ . '/../database/deal.class.php');


require_once (__DIR__ . '/../templates/common.tpl.php');
require_once (__DIR__ . '/../templates/product.tpl.php');
require_once (__DIR__ . '/../templates/user.tpl.php');

$db = getDatabaseConnection();

$myuser = User::getUser($db, $session->getId());
$profileuser = User::getUser($db, intval($_GET['id']));
$alluserproducts = Product::getUserSellingProducts($db, $profileuser->id);
$buyhistory = History::getBuyHistory($db, $profileuser->id);
$sellhistory = History::getSellHistory($db, $profileuser->id);
$products = [];

foreach ($alluserproducts as $product) {
    $deal = Deal::getProductDeal($db, $product->id);
    if (!$deal) {
        $products[] = $product;
    }
}


drawHeader($myuser);

drawMyProfile($myuser, $profileuser, $products, $buyhistory, $sellhistory);

drawFooter();
