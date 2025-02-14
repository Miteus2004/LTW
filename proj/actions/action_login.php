<?php
declare(strict_types=1);

require_once (__DIR__ . '/../utils/session.php');
$session = new Session();

require_once (__DIR__ . '/../database/connection.db.php');
require_once (__DIR__ . '/../database/user.class.php');

$db = getDatabaseConnection();

$user = User::getUserWithLogin($db, $_POST['email'], $_POST['password']);

if ($user) {
  $session->setId($user->id);
  $session->setName($user->name);
  $session->addMessage('success', 'Login successful!');
  header('Location: ../pages/mainpage.php');
} else {
  $session->addMessage('error', 'Wrong password!');
  header('Location: ../pages/loginScreen.php');
}


