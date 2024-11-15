<?php

require_once (__DIR__ . '/../utils/session.php');
$session = new Session();


if (!$session->isLoggedIn())
    die(header('Location: /'));
require_once (__DIR__ . '/../database/connection.db.php');
require_once (__DIR__ . '/../database/user.class.php');



$data = json_decode(file_get_contents("php://input"), true);

$search = $data['search'];

$db = getDatabaseConnection();

$users = USER::searchUser($db, $search);
echo json_encode(array("status" => "success", "users" => $users));



?>