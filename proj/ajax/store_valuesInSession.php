<?php
require_once(__DIR__ . '/../utils/session.php');
$session = new Session();


$data = json_decode(file_get_contents("php://input"), true);

$status = "success";


if ($data !== null) {
    $type = $data['type'];


    switch ($type){
        case 'acceptDealChat':
            $product = $data['product'];
            $price = $data['price'];
            $_SESSION['acceptDealChat'] = ['product' => $product, 'price' => $price];
            break;
        default:
        break;
    }


} else {
    $status = "error";
}



echo json_encode(['status' => $status]);
?>
