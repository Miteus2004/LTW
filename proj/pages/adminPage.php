<?php

declare(strict_types=1);

require_once (__DIR__ . '/../utils/session.php');
$session = new Session();


if (!$session->isLoggedIn())
    die(header('Location: /'));
require_once (__DIR__ . '/../database/connection.db.php');
require_once (__DIR__ . '/../database/user.class.php');
require_once (__DIR__ . '/../database/category.class.php');
require_once (__DIR__ . '/../database/condition.class.php');
require_once (__DIR__ . '/../database/adminChange.class.php');


require_once (__DIR__ . '/../templates/common.tpl.php');
require_once (__DIR__ . '/../templates/user.tpl.php');

$db = getDatabaseConnection();


$myuser = User::getUser($db, $session->getId());
$conditions = Condition::getConditions($db);
$categories = Category::getCategories($db);
$users = User::getUsers($db);

$usersDictionary = [];

foreach ($users as $user) {
    $usersDictionary[$user->id] = $user;
}

$changes = array_reverse(AdminChange::getChanges($db));

drawHeader($myuser);

drawAdminPage($conditions, $categories, $usersDictionary, $changes);

drawFooter();

