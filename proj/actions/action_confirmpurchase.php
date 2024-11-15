<?php
declare(strict_types=1);

require_once (__DIR__ . '/../utils/session.php');
$session = new Session();

require_once (__DIR__ . '/../database/connection.db.php');
require_once (__DIR__ . '/../database/deal.class.php');
require_once (__DIR__ . '/../database/cart.class.php');
require_once (__DIR__ . '/../database/wishlist.class.php');



$db = getDatabaseConnection();

$data = json_decode(file_get_contents("php://input"), true);

$ok =true;
$response = [];
$response['data'] = $data;
foreach($data as $id => $price) {
    if(!Deal::newDeal($db, intval($id), $session->getId(), $price)){
        $response['error'] = floatval($price);
        $ok = false; 
        break;
    } 
    if(!Cart::removeProductFromAllUsers($db,intval($id))){
        $ok = false; 
        break;
    }
    if(!Wishlist::removeProductFromAllUser($db,intval($id))){
        $ok = false; 
        break;
    }
}


if ($ok) {
    $session->addMessage('success', 'Items Buyed!');
    $response['status'] = "success";
} else {
    $session->addMessage('error', 'Some Problem Occurred. Try Again!');
    $response['status'] = "error";

}


echo json_encode($response);
?>


