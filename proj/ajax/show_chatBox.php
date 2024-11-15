<?php

require_once (__DIR__ . '/../utils/session.php');
$session = new Session();


if (!$session->isLoggedIn())
    die(header('Location: /'));
require_once (__DIR__ . '/../database/connection.db.php');
require_once (__DIR__ . '/../database/message.class.php');



$data = json_decode(file_get_contents("php://input"), true);

$interesteduser = intval($data['user']);
$productid = $data['product'];




$db = getDatabaseConnection();


if($interesteduser){    
    $chatMessages = Message::getProductUserMessages($db, $productid, $interesteduser);
}

else{
    $chatMessages = Message::getProductUserMessages($db, $productid, $session->getId());

}



echo json_encode(array('messageListHTML' => $chatMessages));
?>