<?php

require_once (__DIR__ . '/../utils/session.php');
$session = new Session();


if (!$session->isLoggedIn())
    die(header('Location: /'));
require_once (__DIR__ . '/../database/connection.db.php');
require_once (__DIR__ . '/../database/user.class.php');
require_once (__DIR__ . '/../database/adminChange.class.php');


$data = json_decode(file_get_contents("php://input"), true);
$db = getDatabaseConnection();
$userId = $session->getId();
function handleFailure($message, $session)
{
    echo json_encode(["status" => "error", "msg" => $message]);
    $session->addMessage("error", $message);
    exit;
}

if ($data) {
    $response = array();


    $type = $data['type'];
    $response['data'] = $data;



    switch ($type) {
        case 'password':

            $options = ['cost' => 12];
            if (User::verifyUserViaId($db, $session->getId(), $data['oldPassword'])) {
                $editProfile = User::editUserPassword($db, $session->getId(), password_hash($data['newPassword'], PASSWORD_DEFAULT, $options));

                if ($editProfile) {
                    $session->addMessage('success', 'Profile Edited Successfully!');
                    echo json_encode(array("status" => "success"));
                } else {
                    $session->addMessage('error', 'Some Problem Occurred. Try Again!');
                    echo json_encode(array("status" => "error"));
                }
            } else {
                $session->addMessage('error', 'Old Password does not match');
                echo json_encode(array("status" => "error"));
            }
            break;
        case 'info':

            $editProfile = User::editUser($db, $session->getId(), $data['name'], $data['userName'], $data['email'], $data['location']);
            if ($editProfile) {
                $user = User::getUser($db, $session->getId());
                $session->addMessage('success', 'Profile Edited Successfully!');
                echo json_encode(array("status" => "success", "user" => $user));
            } else {
                $session->addMessage('error', 'Some Problem Occurred. Try Again!');
                echo json_encode(array("status" => "error"));
            }

            break;
        case 'admin':
            
            $affectedUser = User::getUser($db, $data['user']);
            $user = User::getUser($db, $userId);

            if ($affectedUser->admin){
                User::removeAdmin($db, $affectedUser->id);
                AdminChange::newChange($db, $userId, "Removed " . $affectedUser->name . " from Admin");
            }else{
                User::makeAdmin($db, $affectedUser->id);
                AdminChange::newChange($db, $userId, "Made " . $affectedUser->name . " Admin");
            }
            break;
        default:
            handleFailure("Nao entrou casos", $session->getId());

            break;
    }
    echo json_encode($response);

} else {

    // Assuming $session is already defined
    $userId = $session->getId();
    $user = User::getUser($db, $userId);
    $destination = "../images/users/{$userId}.jpg";

    if (isset($_FILES["newImage"])) {

        if ($_FILES['newImage']['error'] == UPLOAD_ERR_INI_SIZE) {
            handleFailure("Photo uploaded too big", $session);
        }
        if ($_FILES['newImage']['error']) {
            handleFailure("Some problem with inputFile", $session);
        }
    }

    if ($user->hasPhoto) {
        // Remove old photo if exists
        if (file_exists($destination)) {
            unlink($destination);
        }
        User::removePhoto($db, $userId);
    }

    if (isset($_FILES["newImage"])) {
        // Get file details

        $file = $_FILES["newImage"];


        // Move uploaded file to desired location
        if (move_uploaded_file($file["tmp_name"], $destination)) {
            // Handle success
            User::setPhoto($db, $userId);
            $newUser = User::getUser($db, $userId);
            echo json_encode(["success" => true, "message" => "The file has been uploaded with custom name: " . $customFileName, 'userId' => $userId, 'hasPhoto' => 1]);
        } else
            handleFailure("There was an error uploading file", $session);

    } else {
        echo json_encode(["success" => true, "message" => "File deleted", 'userId' => $userId, 'hasPhoto' => 0]);
        $session->addMessage("success", "Deletion sucessful");
    }
}








?>