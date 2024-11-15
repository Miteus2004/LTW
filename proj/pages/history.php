<?php

declare(strict_types=1);

require_once (__DIR__ . '/../utils/session.php');
$session = new Session();


if (!$session->isLoggedIn())
    die(header('Location: /'));
require_once (__DIR__ . '/../database/connection.db.php');
require_once (__DIR__ . '/../database/user.class.php');
require_once (__DIR__ . '/../database/product.class.php');
require_once (__DIR__ . '/../database/category.class.php');
require_once (__DIR__ . '/../database/condition.class.php');
require_once (__DIR__ . '/../database/deal.class.php');
require_once (__DIR__ . '/../database/history.class.php');


require_once (__DIR__ . '/../templates/common.tpl.php');
require_once (__DIR__ . '/../templates/product.tpl.php');
require_once (__DIR__ . '/../templates/user.tpl.php');

$db = getDatabaseConnection();


$user = User::getUser($db, $session->getId());
$buyed = History::getBuyHistory($db, $session->getId());
$selled = History::getSellHistory($db, $session->getId());
$users = [];

foreach ($buyed as $buy) {

    $users[$buy->id] = User::getUser($db, $buy->seller);

}

foreach ($selled as $sell) {

    $users[$sell->id] = User::getUser($db, $sell->buyer);

}


drawHeader($user);

drawHistory($users, $buyed, $selled);

drawFooter();
