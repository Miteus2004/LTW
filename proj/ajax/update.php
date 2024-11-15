<?php

require_once (__DIR__ . '/../utils/session.php');
$session = new Session();


if (!$session->isLoggedIn())
    die(header('Location: /'));
require_once (__DIR__ . '/../database/connection.db.php');
require_once (__DIR__ . '/../database/user.class.php');

// Check if file data exists in $_FILES array
if (isset($_FILES["newImage"])) {
    $db = getDatabaseConnection();

    // Assuming $session is already defined
    $userId = $session->getId();
    $user = User::getUser($db, $userId);

    if ($user->hasPhoto) {
        // Remove old photo if exists
        $destination = "../images/users/{$userId}.jpg";
        if (file_exists($destination)) {
            unlink($destination);
        }

        User::removePhoto($db, $userId);
    }

    // Get file details
    $file = $_FILES["newImage"];
    $targetDir = "../images/users/";
    $customFileName = $userId . ".jpg";
    $targetFile = $targetDir . $customFileName;

    // Move uploaded file to desired location
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        // Handle success
        echo json_encode(["success" => true, "message" => "The file has been uploaded with custom name: " . $customFileName]);
    } else {
        // Handle failure
        echo json_encode(["success" => false, "message" => "Sorry, there was an error uploading your file."]);
    }
} else {
    // Handle case where file data is missing
    echo json_encode(["success" => false, "message" => "File data is missing"]);
}
?>
