<?php

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();


if (!$session->isLoggedIn())
    die(header('Location: /'));
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/wishlist.class.php');



$data = json_decode(file_get_contents("php://input"), true);


if (!isset($data['productId']) || !isset($data['type'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    exit;
}


$productId = intval($data['productId']);
$type = $data['type'];

$db = getDatabaseConnection();

$response = array();


switch ($type) {
    case 'add':
        if (!Wishlist::getProductWishlist($db, $productId, $session->getId())) {
            if (Wishlist::addToWishlist($db, $session->getId(), $productId)) {
                $response["status"] = "success";
                $response["message"] = "Product Added Successfully to Wishlist!";
                $response["type"] = "Add";

            } else {
                $response["status"] = "error";
                $response["message"] = "Some erro occured adding product...";

            }
        } else {
            if(Wishlist::removeProduct($db, $productId, $session->getId())){
                $response["status"] = "success";
                $response["message"] = "Product Removed Successfully from Wishlist!";
                $response["type"] = "Remove";

            }
            else{
                $response["status"] = "error";
                $response["message"] = "Some erro occured removing product...";
            }
        }
        break;

    case 'delete':
        if (Wishlist::removeProduct($db, $productId, $session->getId())) {
            $response["status"] = "success";
            $response["message"] = "Product Removed Successfully from Wishlist!";
            $response["type"] = "Remove";

        } else {
            $response["status"] = "error";
            $response["message"] = "Some erro occured removing product...";
        }

        break;

    default:
        break;
}


echo json_encode($response);
