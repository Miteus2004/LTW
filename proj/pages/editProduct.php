<?php

declare(strict_types=1);

require_once (__DIR__ . '/../utils/session.php');
$session = new Session();


if (!$session->isLoggedIn())
  die(header('Location: /'));
require_once (__DIR__ . '/../database/connection.db.php');
require_once (__DIR__ . '/../database/category.class.php');
require_once (__DIR__ . '/../database/condition.class.php');
require_once (__DIR__ . '/../database/user.class.php');



require_once (__DIR__ . '/../templates/common.tpl.php');
require_once (__DIR__ . '/../templates/product.tpl.php');

$db = getDatabaseConnection();

$user = User::getUser($db, $session->getId());
$conditions = Condition::getConditions($db);
$categories = Category::getCategories($db);
$product = Product::getProduct($db, intval($_GET['id']));
$productCategoryName = Category::getCategory($db, $product->category)->name;
$conditionName = Condition::getCondition($db, $product->condition)->name;

drawHeader($user);

drawNewProduct($categories, $conditions, $product, $productCategoryName, $conditionName, true);

drawFooter();

