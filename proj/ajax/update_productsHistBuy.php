<?php

require_once (__DIR__ . '/../utils/session.php');
$session = new Session();


if (!$session->isLoggedIn())
    die(header('Location: /'));
require_once (__DIR__ . '/../database/connection.db.php');
require_once (__DIR__ . '/../database/history.class.php');
require_once (__DIR__ . '/../database/user.class.php');



$data = json_decode(file_get_contents("php://input"), true);

$namesearch = $data['name'];
$datesearch = $data['date'];
$sortOption = $data['sortOption'];
$minprice = $data['minprice'];
$maxprice = $data['maxprice'];

if(!$maxprice){
$maxprice = PHP_INT_MAX;
}

if(!$minprice){
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
$filteredProductListHTML = History::getHistoryBuyProductFilteredOrdered($db, $session->getId(), $namesearch, $datesearch, $minprice, $maxprice, $orderquerry);

$users = [];

foreach ($filteredProductListHTML as $buy) {

    $users[$buy->id] = User::getUser($db, $buy->seller);

}

echo json_encode(array('productListHTML' => $filteredProductListHTML, 'users' => $users));
?>