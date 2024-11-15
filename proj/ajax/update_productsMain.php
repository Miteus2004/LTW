<?php

require_once (__DIR__ . '/../utils/session.php');
$session = new Session();


if (!$session->isLoggedIn())
    die(header('Location: /'));
require_once (__DIR__ . '/../database/connection.db.php');
require_once (__DIR__ . '/../database/product.class.php');
require_once (__DIR__ . '/../database/category.class.php');
require_once (__DIR__ . '/../database/condition.class.php');
require_once (__DIR__ . '/../database/deal.class.php');
require_once (__DIR__ . '/../database/wishlist.class.php');




$data = json_decode(file_get_contents("php://input"), true);
$search = $data['search'];
$sortOption = $data['sortOption'];
$filters = $data['filters'];
$minprice = $data['minprice'];
$maxprice = $data['maxprice'];

if (!$maxprice) {
    $maxprice = PHP_INT_MAX;
}

if (!$minprice) {
    $minprice = 0;
}

$db = getDatabaseConnection();
$catids = [];
$condids = [];
$filterquerry = '';
$orderquerry = '';

if (isset($filters['categories'])) {
    foreach ($filters['categories'] as $category) {
        $catids[] = (Category::searchCategory($db, $category))->id;
    }
    $filterquerry .= " AND CategoryId IN (" . implode(',', array_map('intval', $catids)) . ")";
}

if (isset($filters['conditions'])) {
    foreach ($filters['conditions'] as $condition) {
        $condids[] = (Condition::searchCondition($db, $condition))->id;
    }
    $filterquerry .= " AND ConditionId IN (" . implode(',', array_map('intval', $condids)) . ")";
}

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
$filteredProductListHTML = Product::getProductsOrderedFilteredMain($db, $session->getId(), $search, $minprice, $maxprice, $filterquerry, $orderquerry);

$sellProducts = [];
$wishlist = [];

foreach ($filteredProductListHTML as $product) {
    $deal = Deal::getProductDeal($db, $product->id);
    if (!$deal) {
        if(Wishlist::getProductWishlist($db,$product->id,$session->getId())){
            $wishlist[] = $product->id;
        }
        $sellProducts[] = $product;
        
    }
}



echo json_encode(array('productListHTML' => $sellProducts, 'wishlist' => $wishlist));
?>