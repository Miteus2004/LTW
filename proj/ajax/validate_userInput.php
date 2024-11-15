<?php

require_once (__DIR__ . '/../utils/session.php');
$session = new Session();


if (!$session->isLoggedIn())
    die(header('Location: /'));
require_once (__DIR__ . '/../database/connection.db.php');


$db = getDatabaseConnection();

echo json_encode(array("stuff" => "damn"));
exit;

$response = [];


$message = "";
switch ($type){

    case 'login':
        $response['email'] = "success";
        $response['password'] = "success";
        if ($_POST['email'] == ""){
            $response['email'] = "error";
            $response['valid'] = false;
            $message = "Please fill out all the fields";
        }elseif ($_POST['password'] == ""){
            $response['password'] = "error";
            $response['valid'] = false;
            $message = "Please fill out all the fields";
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $response['email'] = "error";
            $message = "Invalid email format.";
            $response['valid'] = false;
        } else {
            $response['password'] = "success";
            $response['email'] = "success";
            $message = "Login successful!";
            $response['valid'] = true;
        }
        break;
    default:
        $response['valid'] = false;
        $response['message'] = "Unkown error occurred";
        $message = "Some error ocurred!";
       break;
}

echo json_encode($response);
?>