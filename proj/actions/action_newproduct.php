<?php

declare(strict_types=1);

require_once (__DIR__ . '/../utils/session.php');
require_once (__DIR__ . '/../database/connection.db.php');
require_once (__DIR__ . '/../database/product.class.php');
require_once (__DIR__ . '/../database/category.class.php');
require_once (__DIR__ . '/../database/condition.class.php');
require_once (__DIR__ . '/../database/user.class.php');


$session = new Session();

$db = getDatabaseConnection();

if (!isset($_POST['productName'], $_POST['category'], $_POST['condition'], $_POST['price'], $_POST['description'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}



$productName = $_POST['productName'];
$category = Category::searchCategory($db, $_POST['category']);
$condition = Condition::searchCondition($db, $_POST['condition']);
$price = (float) str_replace(',', '.', $_POST['price']);
$description = nl2br($_POST['description']);
$userid = $session->getId();
$user = User::getUser($db, $userid);

// Check if all required fields are not empty
if (empty($productName) || empty($category) || empty($condition) || empty($price) || empty($description)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

if (strlen($productName) > 20 || strlen($description) > 2000) {
    echo json_encode(['success' => false, 'message' => 'Text input too big.']);
    exit;
}

$newProduct = Product::newProduct($db, $_POST['productName'], $userid, $category->id, $condition->id, $price, 0, $description, $user->loc);
if (!$newProduct) {
    echo json_encode(['success' => false, 'message' => 'Error processing.']);
    exit;
}
$product = Product::getNewestUserProduct($db, $userid);

// Process photos
$photoNumber = 0;
$maxFileSize = 2 * 1024 * 1024; // 2MB
$allowedExtensions = ['jpg', 'jpeg', 'png'];

$fileTypesPattern = implode('|', $allowedExtensions);
$pathPattern = '/^\d+-\d+\.(' . $fileTypesPattern . ')$/';
$uploadDir = "../images/products/";

// Iterate through each uploaded file
foreach ($_FILES["files"]["tmp_name"] as $key => $temp_name) {
    $photoNumber++;
    $fileName = $_FILES["files"]["name"][$key];
    $fileSize = $_FILES["files"]["size"][$key];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if ($fileSize > $maxFileSize) {
        echo json_encode(['success' => false, 'message' => 'File size exceeds maximum allowed size (2MB)']);
        Product::deleteProduct($db, $product->id);
        exit;
    }

    if (!in_array($fileExtension, $allowedExtensions)) {
        echo json_encode(['success' => false, 'message' => 'Only JPG, JPEG, and PNG file types are allowed']);
        Product::deleteProduct($db, $product->id);
        exit;
    }

    if ($_FILES["files"]["error"][$key] != UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'Error uploading file']);
        Product::deleteProduct($db, $product->id);
        exit;
    }

    if (!(isset($_FILES["files"]["name"][$key]) && $_FILES["files"]["name"][$key] !== '')) {
        echo json_encode(['success' => false, 'message' => 'Uploaded empty file']);
        Product::deleteProduct($db, $product->id);
        exit;
    }

    $customName = $product->id . '-' . $photoNumber;
    $uploadFile = "../images/products/" . $customName . ".jpg";

    // Move the uploaded file to the specified directory with the custom name
    if (!move_uploaded_file($_FILES["files"]["tmp_name"][$key], $uploadFile)) {
        echo json_encode(['success' => false, 'message' => 'Failed to upload file']);
        exit;
    }

}

if ($photoNumber == 0) {
    echo json_encode(['success' => false, 'message' => 'At least one photo.']);
    Product::deleteProduct($db, $product->id);
    exit;
}

$updatedProduct = Product::updateImagesProduct($db, $photoNumber, $product->id);
if (!$updatedProduct) {
    echo json_encode(['success' => false, 'message' => 'Some error occurred']);
    Product::deleteProduct($db, $product->id);
    exit;
}

echo json_encode(['success' => true, 'message' => 'Created successfuly', 'productId' => $product->id]);
$session->addMessage('success', 'Product created with success');
