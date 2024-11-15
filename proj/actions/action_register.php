<?php

declare(strict_types=1);

require_once (__DIR__ . '/../utils/session.php');
$session = new Session();

require_once (__DIR__ . '/../database/connection.db.php');
require_once (__DIR__ . '/../database/user.class.php');

$db = getDatabaseConnection();

$options = ['cost' => 12];

$newUser = User::registNewUser($db, $_POST['name'], $_POST['username'], $_POST['email'], password_hash($_POST['password'], PASSWORD_DEFAULT, $options), $_POST['loc']);

if ($newUser) {
  $session->addMessage('success', 'Account Created successfully!');
  header('Location: ../pages/loginScreen.php');
} else {
  $session->addMessage('error', 'Some Problem Occurred. Try Again!');
}
?>