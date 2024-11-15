<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

if (!$session->isLoggedIn())
    die(header('Location: /'));


require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/product.class.php');
require_once(__DIR__ . '/../database/deal.class.php');


// Establish a database connection (you may need to modify this)
$db = getDatabaseConnection();

// Fetch current user information
$currentUserId = $session->getId();

$myuser = User::getUser($db, $currentUserId);

$product = Product::getProduct($db,intval($_GET['id']));

$deal = Deal::getProductDeal($db,$product->id);

$buyer = User::getUser($db,$deal->buyer)
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <title>SecondWave</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/mainPage.css">
    <link rel="stylesheet" href="../css/sendProduct.css">

    <script src="../javascript/sendProduct.js" defer></script>
    <script src="../javascript/utils.js" defer></script>

</head>

<body>
    <main id="sendproduct">
        <div id="header">
            <img src="../images/logo.png" alt="">
            <h1>SecondWave</h1>
        </div>
        <div id="details">
            <h3>Order Id: <?=$product->id?></h2>
                <p><strong>Seller: </strong> <?= $myuser->name ?></p>
                <p><strong>Buyer: </strong> <?= $buyer->name ?></p>
                <p><strong>Date: </strong> <?= $deal->date ?></p>
                <p><strong>Delivery Adress: </strong> <?= $buyer->loc ?></p>
                <p class="product" id="product-<?=$product->id?>"><strong>Product: </strong> <?= $product->name ?></p>
                <p id="total"><strong>Total:</strong> <?= $deal->price?>€</p>
        </div>
        <div id="btns">
            <button id="send-btn">Send</button>
            <button id="print-btn">Print</button>
        </div>

    </main>

</body>

</html>