<?php

declare(strict_types=1);

require_once(__DIR__ . '/../database/product.class.php');
require_once(__DIR__ . '/../utils/utils.php')
?>

<?php function drawProductsMain(array $products, array $filters, array $wishlist)
{ ?>
    <main class="main-aside">
        <aside>
            <div class="ordermain">
                <h4>Sort:</h4>
                <select class="sortOption">
                    <option value="none">None</option>
                    <option value="cheapest">Price: Cheapest</option>
                    <option value="expensive">Price: Expensive</option>
                    <option value="a-z">Name: A-Z</option>
                    <option value="z-a">Name: Z-A</option>
                </select>
            </div>
            <?php foreach ($filters as $key => $items) { ?>
                <div class="filters">
                    <input type="checkbox" class="input-hide" id="<?php echo strtolower(str_replace(' ', '-', $key)); ?>-check">
                    <label for="<?php echo strtolower(str_replace(' ', '-', $key)); ?>-check" class="filters-title">
                        <h2><?php echo ucfirst($key); ?></h2>
                        <i class="fa-solid fa-caret-up"></i>
                        <i class="fa-solid fa-caret-down"></i>
                    </label>
                    <?php if ($key == 'price') { ?>
                        <div class="price-input-main">
                            <div class="field">
                                <span>MIN</span>
                                <input type="number" class="input-min">
                            </div>
                            <div class="field">
                                <span>MAX</span>
                                <input type="number" class="input-max">
                            </div>
                        </div>
                    <?php } else { ?>
                        <ul>
                            <?php foreach ($items as $item) { ?>
                                <li>
                                    <input type="checkbox" id="<?php echo strtolower(str_replace(' ', '-', $item->name)); ?>-check">
                                    <label for="<?php echo strtolower(str_replace(' ', '-', $item->name)); ?>-check" class="custom-check">
                                        <?php echo $item->name; ?>
                                    </label>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                </div>
            <?php } ?>
        </aside>
        <div id="mainpage-maincontent">
            <div id="search-feedback">
                <span id="search-query"></span>
                <button id="clear-search">Clear</button>
            </div>
            <div class="products-container">
                <?php foreach ($products as $product) { ?>
                    <div id="product-<?= $product->id ?>" class="product-main">
                        <a href="../pages/productInfo.php?id=<?= $product->id ?>">
                            <img src="../images/products/<?= $product->id ?>-1.jpg" alt="">
                            <div class="product-main-info">
                                <div class="product-main-info-fl">
                                    <h3> <?php echo $product->name ?> </h3>
                                    <?php if (in_array($product, $wishlist)) { ?>
                                        <i id="wishlist" class="fa-solid fa-heart wish-cart-selected"></i>
                                    <?php } else { ?>
                                        <i id="wishlist" class="fa-solid fa-heart"></i>
                                    <?php }  ?>
                                </div>
                                <h4> <?php echo $product->price . ' €' ?> </h4>
                                <p> <?= $product->location ?> </p>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </main>
<?php } ?>


<?php function drawNewProduct(array $categories, array $conditions, $product, string $categoryName, string $conditionName, bool $editing)
{ ?>
    <main id="main-newProduct">
        <h1><?= $editing ? "Edit Product" : "New Product" ?></h1>
        <form id="productForms" action="../actions/<?= $editing ? "action_editproduct.php" : "action_newproduct.php" ?><?= $editing ? "?id=" . $product->id : "" ?>" method="post" enctype="multipart/form-data">

            <div id="basicInfo">
                <div class="contentWrapper">
                    <h2>Basic Information</h2>
                    <div class="inputWrapper">
                        <input type="text" name="productName" placeholder="Product name" id="productNameInput" class="countWords productInput" value=<?= $editing ? $product->name : "" ?>>
                        <div class="inputInfo">
                            <p class="errorMessage"></p>
                            <p class="numberLetters"><?= $editing ? strlen($product->name) : 0 ?>/20</p>
                        </div>
                    </div>
                    <br>
                    <div class="secondaryInfo">
                        <select name="category" id="categoryInput" class="category productInput">

                            <?php if (!$editing) { ?>
                                <option value="" selected disabled hidden> Select category </option>
                            <?php } ?>

                            <?php foreach ($categories as $category) { ?>
                                <?php if ($editing && $category->name == $categoryName) { ?>
                                    <option value="<?= strtolower($category->name) ?>" selected> <?= ucfirst($category->name) ?>
                                    </option>
                                <?php } else { ?>
                                    <option value="<?= strtolower($category->name) ?>"> <?= ucfirst($category->name) ?> </option>
                            <?php }
                            } ?>
                        </select>


                        <select name="condition" id="conditionInput" class="condition productInput">
                            <?php if (!$editing) { ?>
                                <option value="" selected disabled hidden> Select condition </option>
                            <?php } ?>

                            <?php foreach ($conditions as $condition) { ?>
                                <?php if ($editing && $condition->name == $conditionName) { ?>
                                    <option value="<?= strtolower($condition->name) ?>" selected> <?= ucfirst($condition->name) ?>
                                    </option>
                                <?php } else { ?>
                                    <option value="<?= strtolower($condition->name) ?>">
                                        <?= ucfirst($condition->name) ?>
                                    </option>
                            <?php }
                            } ?>
                        </select>

                        <div class="inputWrapper">
                            <input type="text" name="price" class="productInput" placeholder="Price" autocomplete="off" id="priceInput" value=<?= $editing ? $product->price : "" ?>>
                            <div class="inputInfo">
                                <p class="errorMessage">Default text</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="description">
                <div class="contentWrapper">
                    <h2>Description</h2>
                    <div class="inputWrapper">
                        <textarea name="description" class="countWords productInput" id="productDescriptionInput" placeholder="Write an appelative description of the product with the most important information"><?= $editing ? $product->description : "" ?></textarea>
                        <div class="inputInfo">
                            <p class="errorMessage"></p>
                            <p class="numberLetters"><?= $editing ? strlen($product->description) : 0 ?>/2000</p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="photos">
                <div class="contentWrapper">
                    <h2>Photos</h2>
                    <div class="inputInfo">
                        <p class="errorMessage">Default text</p>
                    </div>

                    <div id="imageList">
                        <?php if ($editing) {
                            for ($i = 1; $i <= $product->numberimages; $i++) { ?>
                                <?php $path = "../images/products/" . $product->id . "-" . $i . ".jpg" ?>
                                <div class="photoPreview">
                                    <img class="productImage" src=<?= $path ?> alt="">
                                    <i class="fa-solid fa-trash"></i>
                                    <input type="text" value=<?= $path ?> name="oldPhotos[]">
                                </div>
                        <?php }
                        } ?>
                        <label for="photoInput" id="photoUploadButton">
                            <i class="fa-solid fa-plus"></i>
                        </label>
                    </div>
                </div>
            </div>

            <div class="contentWrapper" id="btns">
                <button type="submit" id="createproductbtn">Create</button>
                <button type="button" onclick="cancelForm()">Cancel</button>
            </div>

        </form>
    </main>
<?php } ?>



<?php function drawProductInfo(User $myuser, User $productuser, Product $product, bool $deal, bool $wishlist, bool $cart)
{ ?>
    <main id="main-productInfo">
        <div id="mainStuff">

            <section id="productImage">
                <button id="leftArrow">&lt;</button>
                <div id="imagesContainer">
                    <?php for ($i = 1; $i <= $product->numberimages; $i++) { ?>
                        <div class="container">
                            <img src="../images/products/<?= $product->id . "-" . $i ?>.jpg" alt="Product Image" id="productImage">
                        </div>
                    <?php } ?>

                </div>
                <button id="rightArrow">&gt;</button>
            </section>

            <section id="productInfo">

                <h1> <?= ucwords($product->name) ?> </h1>
                <h2><?= $product->location ?></h2>
                <div id="infoBottom">
                    <p id="price"> <?= $product->price . '€' ?> </p>
                    <div id="icons">

                        <?php if (($product->user == $myuser->id || $myuser->admin) && !$deal) { ?>
                            <i id="deleteIcon" class="fa-solid fa-trash"></i>
                        <?php } ?>
                        <?php if ($product->user != $myuser->id) {
                            if ($wishlist) { ?>
                                <i id="prod-wishlist" class="fa-solid fa-heart wish-cart-selected"></i>
                            <?php } else { ?>
                                <i id="prod-wishlist" class="fa-solid fa-heart"></i>
                            <?php }
                            if ($cart) { ?>
                                <i id="prod-shoppingcart" class="fa-solid fa-cart-shopping wish-cart-selected"></i>

                            <?php } else { ?>
                                <i id="prod-shoppingcart" class="fa-solid fa-cart-shopping"></i>

                            <?php }  ?>
                        <?php } ?>
                    </div>
                </div>

            </section>
            <div class="chat-container">

                <?php if ($product->user != $myuser->id) { ?>

                    <div id="product-chat" class="chat-header">
                        <?php if ($productuser->hasPhoto) { ?>
                            <img src="../images/users/<?= $productuser->id ?>.jpg" alt="">
                        <?php } else { ?>
                            <img src="../images/users/default.jpg" alt="">
                        <?php } ?>
                        <h4><?= ucwords($productuser->name) ?></h4>

                    </div>
                    <div class="chat-box"></div>
                    <div class="chat-input-prod">
                        <textarea id="chat-textarea" placeholder="Type a message"></textarea>
                        <button class="sendbutton">&uarr;</button>
                    </div>

                <?php }?>

            </div>



            <section id="description">
                <h1>Description</h1>
                <p> <?= (str_replace('<br>', "\n", $product->description)) ?></p>
            </section>
    </main>
<?php } ?>


<?php function drawMyProducts(array $sell, array $ship, array $deals, array $buyers)
{ ?>
    <main>
        <div class="form-box">
            <div class="toggle-slider">
                <div id="btn"></div>
                <button type="button" class="toggle-btn">To Sell</button>
                <button type="button" class="toggle-btn">To ship</button>
            </div>
        </div>
        <div class="myproducts">
            <div class="left-call">
                <h2>Products to Sell</h2>
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
                <div id="myproducts-sell">
                    <?php if (!empty($sell)) { ?>
                        <?php foreach ($sell as $product) { ?>
                            <div class="product-sell">
                                <img src="../images/products/<?= $product->id ?>-1.jpg" alt="">
                                <a href="../pages/productInfo.php?id=<?= $product->id ?>" class="product-sell-info">
                                    <h4><?= $product->name ?></h4>
                                    <h4> <?= $product->price . '€' ?> </h4>
                                </a>
                                <div class="product-sell-btns">
                                    <button><a href="../pages/editProduct.php?id=<?= $product->id ?>">Edit</a></button>
                                    <button><a href="../pages/chats.php?id=<?= $product->id ?>">Chats</a></button>
                                </div>
                            </div>
                            <hr>
                        <?php } ?>
                    <?php } else { ?>
                        <p>You're not selling anything!</p>
                    <?php } ?>
                </div>
            </div>
            <div class="right-call">
                <h2>Products to Ship</h2>
                <div class="product-filters">
                    <h3>Filters</h3>
                    <input id="name" type="search" placeholder="Product Name">
                    <input id="date" type="date">
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
                <div id="myproducts-ship">
                    <?php if (!empty($ship)) { ?>
                        <?php foreach ($ship as $product) { ?>
                            <div class="product-ship">
                                <a href="../pages/productInfo.php?id=<?= $product->id ?>">
                                    <img src="../images/products/<?= $product->id ?>-1.jpg" alt="">
                                </a>
                                <div class="product-ship-info">
                                    <a href="../pages/productInfo.php?id=<?= $product->id ?>" class="product-ship-info-fl">
                                        <h4><?= $product->name ?></h4>
                                        <h4> <?= $deals[$product->id]->price . '€' ?> </h4>
                                    </a>
                                    <div class="product-ship-info-sl">
                                        <p>Buyer: <a href="../pages/profile.php?id=<?= $buyers[$product->id]->id ?>"><?= $buyers[$product->id]->name ?></a>
                                        </p>
                                        <p><?= $deals[$product->id]->date ?></p>
                                    </div>
                                </div>
                                <div class="product-ship-btns">
                                <button><a href="../pages/sendProduct.php?id=<?= $product->id ?>">Send</a></button>
                                </div>
                            </div>
                            <hr>
                        <?php } ?>
                    <?php } else { ?>
                        <p>You have nothing to send!</p>
                    <?php } ?>
                </div>
            </div>
        </div>

    </main>
<?php } ?>


<?php
function drawHistory(array $users, array $buyed, array $selled)
{
?>
    <main>
        <div class="form-box">
            <div class="toggle-slider">
                <div id="btn"></div>
                <button type="button" class="toggle-btn" onclick="leftClick()">Buyed</button>
                <button type="button" class="toggle-btn" onclick="rightClick()">Selled</button>
            </div>
        </div>

        <div id="history">
            <div class="left-call">
                <h2>Buyed Products History</h2>
                <div class="product-filters">
                    <h3>Filters</h3>
                    <input id="name" type="search" placeholder="Product Name">
                    <input id="date" type="date">
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
                <div id="buy-products">
                    <?php if (!empty($buyed)) { ?>
                        <?php foreach ($buyed as $product) { ?>
                            <div class="product-history">
                                <img src="../images/products/<?= $product->id ?>-1.jpg" alt="">
                                <div class="product-info-history">
                                    <div class="product-info-fl-history">
                                        <h4><?= $product->name ?></h4>
                                        <h4><?= $product->price . ' €' ?></h4>
                                    </div>
                                    <div class="product-info-sl-history">
                                        <p>Seller: <a href="../pages/profile.php?id=<?= $users[$product->id]->id ?>"><?= $users[$product->id]->name ?></a>
                                        </p>
                                        <p><?= $product->date ?></p>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        <?php } ?>
                    <?php } else { ?>
                        <p>You have not bought anything!</p>
                    <?php } ?>
                </div>
            </div>

            <div class="right-call">
                <h2>Selled Products History</h2>
                <div class="product-filters">
                    <h3>Filters</h3>
                    <input id="name" type="search" placeholder="Product Name">
                    <input id="date" type="date">
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
                <div id="sell-products">
                    <?php if (!empty($selled)) { ?>
                        <?php foreach ($selled as $product) { ?>
                            <div class="product-history">
                                <img src="../images/products/<?= $product->id ?>-1.jpg" alt="">
                                <div class="product-info-history">
                                    <div class="product-info-fl-history">
                                        <h4><?= $product->name ?></h4>
                                        <h4><?= $product->price . ' €' ?></h4>
                                    </div>
                                    <div class="product-info-sl-history">
                                        <p>Buyer: <a href="../pages/profile.php?id=<?= $users[$product->id]->id ?>"><?= $users[$product->id]->name ?></a>
                                        </p>
                                        <p><?= $product->date ?></p>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        <?php } ?>
                    <?php } else { ?>
                        <p>You have not sold anything!</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </main>
<?php } ?>


<?php function drawProductChats(Product $product, array $users, array $lastmessages)
{ ?>
    <main class="chatpage">
        <div id="product-header-chats">
            <img src="../images/products/<?= $product->id ?>-1.jpg" alt="">
            <div id="product-info-header-chats">
                <h2><?= $product->name ?></h2>
                <h3><?= $product->price . '€' ?></h3>
            </div>
        </div>
        <div class="chat-container-users">
            
            <?php if(count($lastmessages) != 0){
            foreach ($lastmessages as $message) { ?>

                <div id="user-<?= $users[$message->id]->id ?>" class="chat-user">


                    <?php if ($users[$message->id]->hasPhoto) { ?>
                        <img src="../images/users/<?= $users[$message->id]->id ?>.jpg" alt="">
                    <?php } else { ?>
                        <img src="../images/users/default.jpg" alt="">
                    <?php } ?>

                    <div class="details">
                        <div class="details-fl">
                            <h4><?= $users[$message->id]->name ?></h4>
                            <p class="time"><?= getTimeAgo($message->date) ?></p>
                        </div>
                        <div class="details-sl">
                            <?php if ($message->sender == $message->product) { ?>
                                <p>You: <?= $message->text ?></p>
                            <?php } else { ?>
                                <p><?= $message->text ?></p>
                            <?php } ?>

                        </div>
                    </div>
                </div>
            <?php } } else { ?>
                <h4>You have no messages.</h4>
            <?php } ?>
        </div>
        <div class="chat-container-chat">

        </div>
    </main>
<?php } ?>