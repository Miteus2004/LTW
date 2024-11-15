<?php

declare(strict_types=1);
require_once (__DIR__ . '/../utils/session.php');
date_default_timezone_set('Europe/Lisbon');

?>

<?php function drawHeader(User $user)
{ ?>

    <!DOCTYPE html>
    <html lang="en-US">

    <head>
        <title>SecondWave</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/mainPage.css">
        <link rel="stylesheet" href="../css/createStyle.css">
        <link rel="stylesheet" href="../css/itemScreen.css">
        <link rel="stylesheet" href="../css/cart.css">
        <link rel="stylesheet" href="../css/confirmpay.css">

        <script src="https://kit.fontawesome.com/e5d9e3bc4f.js" crossorigin="anonymous"></script>
        <script src="../javascript/utils.js" defer></script>
        <script src="../javascript/mainPage.js" defer></script>
        <script src="../javascript/createProduct.js" defer></script>
        <script src="../javascript/itemScreen.js" defer></script>
        <script src="../javascript/adminPage.js" defer></script>
        <script src="../javascript/profilePage.js" defer></script>
        <script src="../javascript/cart.js" defer></script>
        <script src="../javascript/wishlist.js" defer></script>


    </head>

    <body>
        <header id="header">
            <a href="mainpage.php" id="logo">
                <img src="../images/logo.png" alt="">
            </a>
            <form id="searchForm" method="GET">
                <input type="text" id="searchInput" name="search" placeholder="O que procuras?">
            </form>

            <div id="icons">
                <a href="newProduct.php" id="new-product"> Sell </a>
                <?php if ($user->admin) { ?>
                    <a href="adminPage.php"> <i id="admin" class="fa-solid fa-screwdriver-wrench"></i> </a>
                <?php } ?>

                <a href="../pages/wishlist.php?id="<?=$user->id?>><i id="wishlist" class="fa-solid fa-heart"></i></a>

                <a href="../pages/cart.php?id="<?=$user->id?>><i id="shoppingcart" class="fa-solid fa-cart-shopping"></i></a>

                <i id="user" class="fa-solid fa-user"></i>
            </div>


            <div id="dropdown-menu" class="visibility">
                <div id="user-info">
                    <?php if ($user->hasPhoto) { ?>
                        <img src="../images/users/<?= $user->id ?>.jpg" alt="">
                    <?php } else { ?>
                        <img src="../images/users/default.jpg" alt="">
                    <?php } ?>
                    <h2> <?= ucwords($user->name) ?></h2>
                </div>
                <hr>
                <a href="../pages/profile.php?id=<?= $user->id ?>" class="dropdown-menu-options">
                    <i class="fa-solid fa-user"></i>
                    <p>My profile</p>
                    <span>></span>
                </a>
                <a href="../pages/myproducts.php" class="dropdown-menu-options">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <p>My Products</p>
                    <span>></span>
                </a>
                <a href="../pages/history.php" class="dropdown-menu-options">
                    <i class="fa-solid fa-chart-simple"></i>
                    <p>History</p>
                    <span>></span>
                </a>
                <a href="../pages/loginScreen.php" class="dropdown-menu-options" onclick="logout()">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <form action="../actions/action_logout.php" method="post" id="logoutForm"></form>
                    <p>Logout</p>
                    <span>></span>
                </a>

            </div>
            </div>
        </header>
        <div id="alertMessageBox">
        </div>



    <?php } ?>


    <?php function drawFooter()
    { ?>

        <footer id="footer">
            SecondWave &copy; 2024
        </footer>
    </body>

    </html>
<?php } ?>