<?php

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();


if (!$session->isLoggedIn())
    die(header('Location: /'));
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/product.class.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/deal.class.php');



$data = json_decode(file_get_contents("php://input"), true);

$namesearch = $data['name'];
$datesearch = $data['date'];
$sortOption = $data['sortOption'];
$minprice = $data['minprice'];
$maxprice = $data['maxprice'];

if (!$maxprice) {
    $maxprice = PHP_INT_MAX;
}

if (!$minprice) {
    $minprice = 0;
}

$db = getDatabaseConnection();

$orderquerry = '';


switch ($sortOption) {
    case 'cheapest':
        $orderquerry = " ORDER BY Price ASC";
        break;

    case 'expensive':
        $orderquerry = " ORDER BY Price DESC";
        break;

    case 'a-z':
        $orderquerry = " ORDER BY Name ASC";
        break;

    case 'z-a':
        $orderquerry = " ORDER BY Name DESC";
        break;
    default:
        $orderquerry = "";
        break;
}
$filteredProductListHTML = Product::getMyProductsOrderedFiltered($db, $session->getId(), $namesearch, $minprice, $maxprice, $orderquerry);
$shipProducts = [];
$users = [];
$deals = [];

foreach ($filteredProductListHTML as $product) {
    $deal = Deal::getProductDealFilterDate($db, $product->id, $datesearch);
    if ($deal) {
        $shipProducts[] = $product;
        $deals[$product->id] = $deal;
        $users[$product->id] = User::getUser($db, $deal->buyer);
    }
}

echo json_encode(array('productListHTML' => $shipProducts, 'users' => $users, 'deals' => $deals));
