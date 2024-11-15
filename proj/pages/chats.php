<?php

declare(strict_types=1);

require_once (__DIR__ . '/../utils/session.php');
$session = new Session();


if (!$session->isLoggedIn())
    die(header('Location: /'));
require_once (__DIR__ . '/../database/connection.db.php');
require_once (__DIR__ . '/../database/user.class.php');
require_once (__DIR__ . '/../database/product.class.php');
require_once (__DIR__ . '/../database/message.class.php');


require_once (__DIR__ . '/../templates/common.tpl.php');
require_once (__DIR__ . '/../templates/product.tpl.php');

$db = getDatabaseConnection();


$user = User::getUser($db, $session->getId());
$product = Product::getProduct($db, intval($_GET['id']));
$messages = Message::getProductUsersInterested($db, $product->id);
$interestedUsers = [];

foreach ($messages as $message) {
    $interestedUsers[$message->id] = User::getUser($db, $message->interested);
}




if($product->user == $user->id){
    drawHeader($user);

    drawProductChats($product, $interestedUsers, $messages);

    drawFooter();
}
else{
    header('Location: mainpage.php');

}


