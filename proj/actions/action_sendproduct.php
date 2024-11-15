<?php
declare(strict_types=1);

require_once (__DIR__ . '/../utils/session.php');
$session = new Session();

require_once (__DIR__ . '/../database/connection.db.php');
require_once (__DIR__ . '/../database/deal.class.php');
require_once (__DIR__ . '/../database/product.class.php');
require_once (__DIR__ . '/../database/history.class.php');




$db = getDatabaseConnection();

$data = json_decode(file_get_contents("php://input"), true);

$response = [];
$response['data'] = $data;
$id =intval($data);

$deal = Deal::getProductDeal($db,$id);
$product = Product::getProduct($db, $id);

if(History::newHistoryProduct($db,$product->name,$deal->price,$product->user,$deal->buyer)){
    if(Deal::deleteDeal($db,$id)){
        if(Product::deleteProduct($db,$id)){
            $session->addMessage('success', 'Item Send!');
            $response['status'] = "success";
        }
        else{
            $session->addMessage('error', 'Some Problem Occurred. Try Again!');
            $response['status'] = "error";
        }
    }
    else{
        $session->addMessage('error', 'Some Problem Occurred. Try Again!');
        $response['status'] = "error";
    }
}
else{
    $session->addMessage('error', 'Some Problem Occurred. Try Again!');
    $response['status'] = "error";
}



echo json_encode($response);
?>


