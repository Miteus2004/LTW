<?php

require_once (__DIR__ . '/../utils/session.php');
$session = new Session();


if (!$session->isLoggedIn())
    die(header('Location: /'));
require_once (__DIR__ . '/../database/connection.db.php');
require_once (__DIR__ . '/../database/adminChange.class.php');
require_once (__DIR__ . '/../database/user.class.php');
require_once (__DIR__ . '/../database/product.class.php');



$data = json_decode(file_get_contents("php://input"), true);

$productId = $data['productId'];

$db = getDatabaseConnection();
$user = User::getUser($db, $session->getId());
$product = Product::getProduct($db, $productId);
// Delete product from dataBase


if (Product::deleteProduct($db, $productId)) {
    $status = "success";
    // Check if user deleting is owner
    for ($i=0; $i < $product->numberimages; $i++){
        $filePath = "../images/products/" . $product->id . "-" . ($i+1) . ".jpg";
        if (file_exists($filePath)){
            unlink($filePath);
        }
    }

    if ($product->user != $user->id) {
        $ownerName = User::getUser($db, $product->user)->name;
        $description = "Deleted product of " . $ownerName;
        if (!AdminChange::newChange($db, $user->id, $description)) {
            $status = "error";
        }
    }
} else {
    $status = "error";
}

echo json_encode(array("status" => $status));
if ($status == "error")
    $session->addmessage("error", "some error occured...");
else
    $session->addmessage("success", "Product deleted with success!");


?>