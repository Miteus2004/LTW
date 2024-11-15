<?php

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();


if (!$session->isLoggedIn())
    die(header('Location: /'));
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/product.class.php');
require_once(__DIR__ . '/../database/deal.class.php');


$data = json_decode(file_get_contents("php://input"), true);

$namesearch = $data['name'];
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

$sellProducts = [];

foreach ($filteredProductListHTML as $product) {
    $deal = Deal::getProductDeal($db, $product->id);
    if (!$deal) {
        $sellProducts[] = $product;
    }
}

echo json_encode(array('productListHTML' => $sellProducts));
