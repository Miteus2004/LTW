<?php

declare(strict_types=1);

require_once(__DIR__ . '/../database/user.class.php')
?>

<?php function drawMyProfile(User $myuser, User $profileuser, array $products, array $buyhistory, array $sellhistory)
{ ?>
    <main class="main-aside">
        <aside id="profile-info">
            <div id="profilePictureWrapper">
                <?php if ($profileuser->hasPhoto) { ?>
                    <img id="profilePicture" src="../images/users/<?= $myuser->id ?>.jpg" alt="">
                <?php } else { ?>
                    <img id="profilePicture" src="../images/users/default.jpg" alt="">
                <?php } ?>
                <div id="icons-prof-image">
                    <?php if ($myuser->id == $profileuser->id) { ?>
                        <i id="deleteProfileImageIcon" class="fa-regular fa-square-minus"></i>
                        <i id="editProfileImageIcon" class="fa-regular fa-pen-to-square"></i>
                        <?php } else if ($myuser->admin) {
                        if ($profileuser->admin) { ?>
                            <i id="toogleAdminButton" class="fa-solid fa-lock-open"></i>
                        <?php } else { ?>
                            <i id="toogleAdminButton" class="fa-solid fa-lock"></i>
                    <?php }
                    } ?>
                </div>
            </div>
            <div id="profileUserInfo" class="editVisibility profile-details">
                <h2>Name</h2>
                <h4 class="name"> <?= ucwords($profileuser->name) ?> </h4>
                <h2>Username</h2>
                <h4 class="userName"> <?= $profileuser->username ?> </h4>
                <h2>Email</h2>
                <h4 class="email"> <?= $profileuser->email ?> </h4>
                <h2>Location</h2>
                <h4 class="location"> <?= $profileuser->loc ?> </h4>
                <?php if ($myuser == $profileuser) { ?>
                    <button id="profileButton" type="button" onclick="editProfileToggle()">Edit Profile</button>
                <?php } ?>
            </div>
            <div id="profileEditInputs" class="editVisibility profile-details input" action="../actions/action_editProfile.php" method="post">
                <h2>Name</h2>
                <input type="text" class="name" value="<?= ucwords($profileuser->name) ?>">
                <h2>Username</h2>
                <input type="text" class="userName" value="<?= $profileuser->username ?>">
                <h2>Email</h2>
                <input type="email" class="email" value="<?= $profileuser->email ?>">
                <h2>Location</h2>
                <input type="text" class="location" value="<?= $profileuser->loc ?>">
                <div class="confirm-buttons">
                    <button id="saveProfileEditButton" type="submit">Save</button>
                    <button id="cancelProfileEditButton" type="reset">Cancel</button>
                </div>
            </div>

            <div class="editVisibility password">
                <?php if ($myuser == $profileuser) { ?>
                    <button id="passwordButton" type="button" onclick="changePasswordToggle()">Change Password</button>
                <?php } ?>

            </div>

            <div id="profilePasswordInputs" class="editVisibility password input" action="../actions/action_changePassword.php" method="post">
                <h2>Password</h2>
                <input type="password" class="oldPasswordInput" placeholder="Old Password">
                <input type="password" class="newPasswordInput" placeholder="New Password">
                <div class="confirm-buttons">
                    <button id="saveProfilePasswordChange" type="submit">Save</button>
                    <button id="cancelProfilePasswordChange" type="reset">Cancel</button>
                </div>
            </div>
        </aside>
        <div id="profile-maincontent">
            <div class="form-box">
                <div class="toggle-slider">
                    <div id="btn"></div>
                    <button type="button" class="toggle-btn" onclick="leftClickProfile()">Products</button>
                    <button type="button" class="toggle-btn" onclick="rightClickProfile()">Statistics</button>
                </div>
            </div>
            <div class="products">
                <div class="left-call">
                    <h2>Products</h2>
                    <div class="product-filters">
                        <h3>Filters</h3>
                        <input id="name" type="search" placeholder="Product Name">
                        <div class="price-input">
                            <div class="field">
                                <span>MIN</span>
                                <input type="number" class="input-min">
                            </div>
                            <div class="separator">-</div>
                            <div class="field">
                                <span>MAX</span>
                                <input type="number" class="input-max">
                            </div>
                        </div>
                        <div class="order">
                            <p>Sort:</p>
                            <select class="sortOption">
                                <option value="none">None</option>
                                <option value="cheapest">Price: Cheapest</option>
                                <option value="expensive">Price: Expensive</option>
                                <option value="a-z">Name: A-Z</option>
                                <option value="z-a">Name: Z-A</option>
                            </select>
                        </div>
                    </div>
                    <div id="profile-products">
                        <?php if (!empty($products)) { ?>
                            <?php foreach ($products as $product) { ?>
                                <a href="../pages/productInfo.php?id=<?= $product->id ?>" class="product-profile">
                                    <img src="../images/products/<?= $product->id ?>-1.jpg" alt="">
                                    <div class="product-profile-info">
                                        <h4> <?= $product->name ?> </h4>
                                        <h4> <?= $product->price . '€' ?> </h4>
                                    </div>
                                </a>
                                <hr>
                            <?php } ?>
                        <?php } else { ?>
                            <p>No products available.</p>
                        <?php } ?>
                    </div>
                </div>
                <div class="right-call">
                    <h2>Statistics</h2>
                    <div id="statistics-wrapper">
                        <div class="statistic-info">
                            <h4>Products Selled:</h4>
                            <p><?= count($sellhistory) ?></p>
                        </div>
                        <?php if ($myuser == $profileuser) { ?>
                            <div class="statistic-info">
                                <h4>Money Earned:</h4>
                                <?php $total = 0;
                                for ($i = 0; $i < count($sellhistory); $i++) {
                                    $total += $sellhistory[$i]->price;
                                } ?>
                                <p> <?= (string) $total . '€' ?></p>
                            </div>
                        <?php } ?>
                        <div class="statistic-info">
                            <h4>Products Buyed:</h4>
                            <p><?= count($buyhistory) ?></p>
                        </div>
                        <?php if ($myuser == $profileuser) { ?>
                            <div class="statistic-info">
                                <h4>Money Spent:</h4>
                                <?php $total = 0;
                                for ($i = 0; $i < count($buyhistory); $i++) {
                                    $total += $buyhistory[$i]->price;
                                } ?>
                                <p> <?= (string) $total . '€' ?></p>
                            </div>
                        <?php } ?>
                        <div class="statistic-info">
                            <h4>Member since:</h4>
                            <p><?= $profileuser->joinedDate ?></p>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </main>
<?php } ?>


<?php function drawAdminPage(array $conditions, array $categories, array $users, array $changes)
{ ?>

    <main id="main-adminPage" class="main-aside">
        <div id="leftside-admin">
            <div>
                <h2>Categorias</h3>
                    <label for="newCategoryInput">New category: </label>
                    <input type="text" name="category" id="newCategoryInput" placeholder="New Category">
                    <div class="admin-categories">
                        <?php foreach ($categories as $category) { ?>
                            <div class="admin-categories-choices">
                                <p><?= $category->name ?></p>
                                <i class="fa-solid fa-xmark"></i>
                            </div>
                        <?php } ?>
                    </div>
            </div>
            <div id="users-section">
                <h2>Users</h2>
                <input type="text" id="userSearchInput" placeholder="Username">
                <div id="users" class="scrollabe-box">
                    <?php foreach ($users as $userId => $user) { ?>
                        <a href="../pages/profile.php?id=<?= $user->id ?>">
                            <div class="user">
                                <?php if ($user->hasPhoto) { ?>
                                    <img src="../images/users/<?= $user->id ?>.jpg" alt="">
                                <?php } else { ?>
                                    <img src="../images/users/default.jpg" alt="">
                                <?php } ?>
                                <h3><?= $user->username ?></h3>
                            </div>
                        </a>
                    <?php } ?>
                </div>
            </div>

        </div>


        <div id="adminlog">
            <?php foreach ($changes as $change) { ?>
                <a href="../pages/profile.php?id=<?= $change->userId ?>">
                    <div class="admin-change">
                        <?php if ($user->hasPhoto) { ?>
                            <img src="../images/users/<?= $user->id ?>.jpg" alt="">
                        <?php } else { ?>
                            <img src="../images/users/default.jpg" alt="">
                        <?php } ?>
                        <div class="admin-change-details">
                            <h3><?= $users[$change->userId]->username ?></h3>
                            <p><?= $change->description ?></p>
                        </div>
                    </div>
                </a>

            <?php } ?>

        </div>
    </main>
<?php } ?>


<?php function drawCart(User $user, array $products, $totprice)
{ ?>
    <main class="cart-section">
        <?php if (count($products) == 0) { ?>
            <h2>No products. Start buying now!!</h2>
            <a href="../pages/mainpage.php">
                <h3>Buy</h3>
            </a>
        <?php } else { ?>
            <div class="product-list">
                <p class="section-heading">your cart</p>
                <div class="cart">
                    <?php foreach ($products as $product) { ?>
                        <div id="product-<?= $product->id ?>" class="sm-product">
                            <img src="../images/products/<?= $product->id ?>-1.jpg" class="sm-product-img" alt="">
                            <div class="sm-text">
                                <p class="sm-product-name"><?= $product->name ?></p>
                                <p class="sm-des"><?= $product->location ?></p>
                            </div>
                            <p class="sm-price"><?= $product->price ?></p>
                            <button class="sm-delete-btn"><img src="imgs/close.png" alt=""></button>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="checkout-section">
                <div class="checkout-box">
                    <p class="text">your total bill,</p>
                    <h1 class="bill"><?= $totprice ?></h1>
                    <a href="../pages/confirmpay.php" class="checkout-btn">Pay</a>
                </div>
            </div>
        <?php } ?>
    </main>
<?php } ?>

<?php function drawWishlist(array $products)
{ ?>
    <main class="cart-section">
        <?php if (count($products) == 0) { ?>
            <h2>No products. Start like now!!</h2>
            <a href="../pages/mainpage.php">
                <h3>Like</h3>
            </a>
        <?php } else { ?>
            <div class="products-container">
                <?php foreach ($products as $product) { ?>
                    <div id="product-<?= $product->id ?>" class="product-main">
                        <a href="../pages/productInfo.php?id=<?= $product->id ?>">
                            <img src="../images/products/<?= $product->id ?>-1.jpg" alt="">
                            <div class="product-main-info">
                                <div class="product-main-info-fl">
                                    <h3> <?php echo $product->name ?> </h3>
                                    <i id="user-<?=$product->id?>" class="fa-solid fa-heart wish-cart-selected prod-wishlist "></i>
                                </div>
                                <h4> <?php echo $product->price . ' €' ?> </h4>
                                <p> <?= $product->location ?> </p>
                            </div>
                        </a>
                    </div>
            <?php }
            } ?>
    </main>
<?php } ?>

<?php function drawPayment(User $myuser, $products, $dealPrice)
{ ?>
    <main id="main-confirm">
        <div class="bill-section">
            <h1>Bill</h1>
            <p><strong>Buyer: </strong> <?= $myuser->name ?></p>
            <hr>
            <h2>Products</h2>
            <div id="confirm-products">
            <?php $total = 0;
            if ($dealPrice != null) {
                $total += $dealPrice ?>
                <p class="pay-products" id="product-<?= $products[0]->id ?>"><?= $products[0]->name ?> - <?= $dealPrice ?>€</p>
                <p><strong>Seller: </strong><?= $products[1]->name ?></p>
                <?php } else {
                foreach ($products as $productInfo) {
                    $total += $productInfo[0]->price ?>
                    <p class="pay-products" id="product-<?= $productInfo[0]->id ?>"><?= $productInfo[0]->name ?></p>
                    <p><strong>Price: </strong><?= $productInfo[0]->price ?>€</p>
                    <p><strong>Seller: </strong><?= $productInfo[1]->name ?></p>
                    <hr>
            <?php }
            } ?>
            </div>
            <hr>
            <p><strong>Total:</strong> <?= $total ?>€</p>
            <button id="conf-purchase-btn">Confirm Purchase</button>
        </div>
    </main>
<?php } ?>