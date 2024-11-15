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
// Check if received all the inputs
if (!isset( $_POST['productName'], $_POST['category'], $_POST['condition'], $_POST['price'], $_POST['description'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Fetch information
$productId = intval($_GET['id']);
$productName = $_POST['productName'];
$category = Category::searchCategory($db, $_POST['category']);
$condition = Condition::searchCondition($db, $_POST['condition']);
$price = (float) str_replace(',', '.', $_POST['price']);
$description = nl2br($_POST['description']);

// Check if all required fields are not empty
if (empty($productName) || empty($category) || empty($condition) || empty($price) || empty($description)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

if (strlen($productName)>20 || strlen($description) > 2000){
    echo json_encode(['success' => false, 'message' => 'Text input too big.']);
    exit;
}

// Confirm that product belongs to user
if ((Product::getProduct($db, $productId)->user) != $session->getId()) {
    echo json_encode(['success' => false, 'message' => 'Invalid permission.']);
    exit;
}

// Process photos
$photoNumber = 0;
$maxFileSize = 2 * 1024 * 1024; // 2MB
$allowedExtensions = ['jpg', 'jpeg', 'png'];

$fileTypesPattern = implode('|', $allowedExtensions);

$pathPattern = '/^\.\.\/images\/products\/\d+-\d+\.(' . $fileTypesPattern . ')$/i';


foreach ($_POST["oldPhotos"] as $oldPhoto) {
    $photoNumber++;

    $destination_path = "../images/products/" . $productId . "-" . ($photoNumber) . ".jpg";
    if (!preg_match($pathPattern, $oldPhoto)) {
        echo json_encode(['success' => false, 'message' => 'pattern fail']);
        exit;
    }

    preg_match('/^\.\.\/images\/products\/(\d+)-\d+\.\w+$/', $oldPhoto, $photoProductId);
    if (intval($photoProductId[1]) != $productId) {
        echo json_encode(['success' => false, 'message' => 'No valid productId. ' . intval($photoProductId[1])]);
        exit;
    }

    if (!file_exists($oldPhoto)){
        echo json_encode(['success' => false, 'message' => 'Invalid photo input']);
        exit;
    }
    copy($oldPhoto, $destination_path);
}

foreach ($_FILES["files"]["tmp_name"] as $key => $temp_name) {
    $photoNumber++;
    $fileName = $_FILES["files"]["name"][$key];
    $fileSize = $_FILES["files"]["size"][$key];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if ($fileSize > $maxFileSize) {
        echo json_encode(['success' => false, 'message' => 'File size exceeds maximum allowed size (2MB)']);
        exit;
    }

    if (!in_array($fileExtension, $allowedExtensions)) {
        echo json_encode(['success' => false, 'message' => 'Only JPG, JPEG, and PNG file types are allowed']);
        exit;
    }

    if ($_FILES["files"]["error"][$key] != UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'Error uploading file']);
        exit;
    }

    if (isset($_FILES["files"]["name"][$key]) && $_FILES["files"]["name"][$key] !== '') {
        echo json_encode(['success' => false, 'message' => 'Uploaded empty file']);
        exit;
    }


    $customName = $productId . '-' . $photoNumber;
    $uploadFile = "../images/products/" . $customName . ".jpg";

    // Move the uploaded file to the specified directory with the custom name
    if (!move_uploaded_file($_FILES["files"]["tmp_name"][$key], $uploadFile)) {
        echo json_encode(['success' => false, 'message' => 'Failed to upload file']);
        exit;
    }
}

// Delete old photos that still exist
$oldNumberPhotos = Product::getProduct($db, $productId)->numberimages;
$totalToDelete = $oldNumberPhotos - $photoNumber;
for ($i = 0; $i < $totalToDelete; $i++) {
    $path = "../images/products/" . $productId . "-" . ($photoNumber + $i + 1) . ".jpg";
    if (file_exists($path))
        unlink($path);
}

// Update product
$updatedProduct = Product::editProduct($db, $productId, $productName, $category->id, $condition->id, $price, $photoNumber, $description);

// Check if product is updated successfully
if ($updatedProduct) {
    echo json_encode(['success' => true, 'message' => 'Product updated successfully', 'productId' => $productId]);
    exit;
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update product']);
    exit;
}
