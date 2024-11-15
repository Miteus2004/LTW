<?php

require_once (__DIR__ . '/../utils/session.php');
$session = new Session();


if (!$session->isLoggedIn())
    die(header('Location: /'));
require_once (__DIR__ . '/../database/connection.db.php');
require_once (__DIR__ . '/../database/category.class.php');
require_once (__DIR__ . '/../database/adminChange.class.php');
require_once (__DIR__ . '/../database/user.class.php');



$data = json_decode(file_get_contents("php://input"), true);

$categoryName = $data['category'];
$remove = $data['remove'];

$db = getDatabaseConnection();
$user = USER::getUser($db, $session->getId());

if ($remove) {
    if (Category::deleteCategory($db, $categoryName)) {
        $message = "Removed category: " . $categoryName;
        if (AdminChange::newChange($db, $session->getId(), $message)) {
            echo json_encode(array("status" => "success", "user" => $user));
        } else
            echo json_encode(array("status" => "error"));
    } else
        echo json_encode(array("status" => "error"));
    $session->addMessage("error", "Some error occured!");
} else {

    $alreadyExists = false;
    $categories = Category::getCategories($db);
    foreach ($categories as $category) {
        if ($category->name == $categoryName) {
            $alreadyExists = true;
            break;
        }
    }
    if (!$alreadyExists) {
        if (Category::newCategory($db, $categoryName)) {
            $message = "Added Category: " . $categoryName;
            if (AdminChange::newChange($db, $user->id, $message)) {
                echo json_encode(array("status" => "success", "user" => $user));
            } else
                echo json_encode(array("status" => "error"));
        }
    } else {
        $session->addMessage("error", "Category already exists!");
        echo json_encode(array("status" => "error"));
    }
}

?>