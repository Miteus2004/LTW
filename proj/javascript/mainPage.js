



//dropdown menu
let dropdownMenuTimeout;

document.getElementById("user").addEventListener("mouseover", function () {
    clearTimeout(dropdownMenuTimeout);
    document.getElementById("dropdown-menu").style.display = "block";
});

document.getElementById("user").addEventListener("mouseout", function () {
    dropdownMenuTimeout = setTimeout(function () {
        document.getElementById("dropdown-menu").style.display = "none";
    }, 300); // Adjust the delay time as needed
});

document.getElementById("dropdown-menu").addEventListener("mouseover", function () {
    clearTimeout(dropdownMenuTimeout);
});

document.getElementById("dropdown-menu").addEventListener("mouseout", function () {
    dropdownMenuTimeout = setTimeout(function () {
        document.getElementById("dropdown-menu").style.display = "none";
    }, 300); // Adjust the delay time as needed
});



//toggle menus (left/right) -- profile
let btnprofile = document.getElementById('btn-profile')

function leftClickProfile() {
    btnprofile.style.left = '0'
    for (let i = 0; i < rightside.length; i++) {
        rightside[i].style.display = "none";
    }
    for (let i = 0; i < leftside.length; i++) {
        leftside[i].style.display = "block";
    }
}

function rightClickProfile() {
    btnprofile.style.left = '8.55vw'
    for (let i = 0; i < rightside.length; i++) {
        rightside[i].style.display = "block";
    }
    for (let i = 0; i < leftside.length; i++) {
        leftside[i].style.display = "none";
    }
}


// logout

function logout() {
    document.getElementById("logoutForm").submit();
}


// filters and search bar

document.addEventListener("DOMContentLoaded", function () {


    let searchbar = document.querySelector("#header input");

    document.querySelector("#header form").addEventListener("submit", function (e) {
        // Prevent the default form submission behavior
        e.preventDefault();
    });

    searchbar.addEventListener("keydown", function (e) {
        if (e.key === "Enter") {
            let search = searchbar.value.trim();
            if (search !== "") {
                // Check if the current page is the main page
                if (window.location.pathname !== "/pages/mainpage.php") {
                    window.location.href = "../pages/mainpage.php?search=" + encodeURIComponent(search);
                }
            }
        }
    });




    function getTimeAgo(datetimeString) {
        var timeSentDate = new Date(datetimeString);
        var currentTime = new Date();
    
        var difference = currentTime - timeSentDate;
    
        var differenceInSeconds = Math.floor(difference / 1000);
        var differenceInMinutes = Math.floor(differenceInSeconds / 60);
        var differenceInHours = Math.floor(differenceInMinutes / 60);
        var differenceInDays = Math.floor(differenceInHours / 24);
    
        var timeSentString;
        if (differenceInDays > 0) {
            timeSentString = differenceInDays + ' days ago';
        } else if (differenceInHours > 0) {
            timeSentString = differenceInHours + ' hours ago';
        } else if (differenceInMinutes > 1) {
            timeSentString = differenceInMinutes + ' minutes ago';
        } else if (differenceInMinutes === 1) {
            timeSentString = differenceInMinutes + ' minute ago';
        } else {
            timeSentString = ' < 1 minute ago';
        }
    
        return timeSentString;
    }



    if (window.location.pathname === "/pages/mainpage.php") {
        let searchFeedback = document.querySelector("#search-feedback");
        let searchQuerySpan = document.querySelector("#search-query");
        let clearSearchButton = document.querySelector("#clear-search");
        let sortOptionSelect = document.querySelector(".ordermain .sortOption");
        let filtersCheckboxes = document.querySelectorAll(".filters ul input[type='checkbox']");
        let minpriceInput = document.querySelector(".filters .input-min");
        let maxpriceInput = document.querySelector(".filters .input-max");

        const urlParams = new URLSearchParams(window.location.search).get('search');

        if (urlParams != null) {
            searchbar.value = urlParams;
            performsearch(urlParams);
        }

        searchbar.addEventListener("keydown", function (e) {
            if (e.key === "Enter") {
                let search = searchbar.value.trim();
                if (search !== "") {
                    performsearch(search);
                    performsearch(search);

                    performsearch(search);

                }
            }
        });

        clearSearchButton.addEventListener("click", function () {
            searchbar.value = "";
            searchFeedback.style.opacity = 0;
            updateProducstMain("", sortOptionSelect.value, collectFilterOptions(), minpriceInput.value, maxpriceInput.value);
        });

        sortOptionSelect.addEventListener("change", function () {
            let sortOption = this.value;
            let filterOptions = collectFilterOptions();
            updateProducstMain(searchbar.value.trim(), sortOption, filterOptions, minpriceInput.value, maxpriceInput.value);
        });

        filtersCheckboxes.forEach(function (checkbox) {
            checkbox.addEventListener("change", function () {
                let sortOption = sortOptionSelect.value;
                let filterOptions = collectFilterOptions();
                updateProducstMain(searchbar.value.trim(), sortOption, filterOptions, minpriceInput.value, maxpriceInput.value);
            });
        });

        minpriceInput.addEventListener("keydown", function (e) {

            if(e.key === ',' || e.key === '.'){
                e.preventDefault();
            }
        });

        minpriceInput.addEventListener("keyup", function () {

            updateProducstMain("", sortOptionSelect.value, collectFilterOptions(),minpriceInput.value, maxpriceInput.value);
        });

        

        maxpriceInput.addEventListener("keydown", function (e) {

            if(e.key === ',' || e.key === '.'){
                e.preventDefault();
            }
        });

        maxpriceInput.addEventListener("keyup", function () {

            updateProducstMain("", sortOptionSelect.value, collectFilterOptions(),minpriceInput.value, maxpriceInput.value);
        });

        function collectFilterOptions() {
            let filterOptions = {};
            filtersCheckboxes.forEach(function (checkbox) {
                if (checkbox.checked) {
                    let filterName = checkbox.parentNode.parentNode.parentNode.querySelector(".filters-title h2").textContent.toLowerCase();
                    let filterValue = checkbox.nextElementSibling.textContent.trim();
                    if (!filterOptions[filterName]) {
                        filterOptions[filterName] = [];
                    }
                    filterOptions[filterName].push(filterValue);
                }
            });
            return filterOptions;
        }

        function performsearch(search) {
            searchQuerySpan.textContent = search;
            searchFeedback.style.opacity = 100;
            window.history.pushState({ path: window.location.pathname }, '', window.location.pathname)
            updateProducstMain(search, sortOptionSelect.value, collectFilterOptions(), minpriceInput.value, maxpriceInput.value);
        }

        function updateProducstMain(search, sortOption, filterOptions, minprice, maxprice) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "../ajax/update_productsMain.php", true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let response = JSON.parse(xhr.responseText);
                        drawUI(response);

                    } else {
                        console.error("Error fetching data. Status code: " + xhr.status);
                        console.error(xhr.responseText);
                    }
                }
            };
            xhr.onerror = function () {
                console.error("Network error occurred.");
            };
            xhr.send(JSON.stringify({ search: search, sortOption: sortOption, filters: filterOptions, minprice :minprice , maxprice :maxprice }));
        }


        function drawUI(response) {
            let productsContainer = document.querySelector(".products-container");

            productsContainer.innerHTML = "";
            if (response.productListHTML.length === 0) {
                let noProductFound = document.createElement("div");
                noProductFound.textContent = "No products found.";
                noProductFound.style.fontSize = "1rem";
                productsContainer.appendChild(noProductFound);
            } else {
                response.productListHTML.forEach(function (product) {
                    let productMain = document.createElement("div");
                    productMain.classList.add("product-main");

                    let productLink = document.createElement("a");
                    productLink.href = "../pages/productInfo.php?id=" + product.id;

                    let productImage = document.createElement("img");
                    productImage.src = "../images/products/" + product.id + "-1.jpg";
                    productImage.alt = "";

                    let productMainInfo = document.createElement("div");
                    productMainInfo.classList.add("product-main-info");

                    let productTitleActions = document.createElement("div");
                    productTitleActions.classList.add("product-main-info-fl");

                    let productName = document.createElement("h3");
                    productName.textContent = product.name;

                    let wishlistIcon = document.createElement("i");
                    wishlistIcon.classList.add("fa-solid", "fa-heart");
                    if(response.wishlist.includes(product.id)){
                        wishlistIcon.classList.add("wish-cart-selected");
                    }
                    wishlistIcon.id = "wishlist";


                    productTitleActions.appendChild(productName);
                    productTitleActions.appendChild(wishlistIcon);

                    let productPrice = document.createElement("h4");
                    productPrice.textContent = product.price + " €";

                    let productLocation = document.createElement("p");
                    productLocation.textContent = product.location;

                    productMainInfo.appendChild(productTitleActions);
                    productMainInfo.appendChild(productPrice);
                    productMainInfo.appendChild(productLocation);

                    productLink.appendChild(productImage);
                    productLink.appendChild(productMainInfo);

                    productMain.appendChild(productLink);

                    productsContainer.appendChild(productMain);
                });
            }

        }


        let product = document.querySelectorAll('.products-container .product-main');

        product.forEach(function(product){
            let wishButton = product.querySelector('#wishlist');
            let productid = product.getAttribute('id');
            

            wishButton.addEventListener('click', function(e){
                e.preventDefault();
                console.log(extractid(productid));
                updateCartWish(extractid(productid), wishButton);
            });



            
        });

        function updateCartWish(productId, button) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "../ajax/add_toWishlist.php", true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let response = JSON.parse(xhr.responseText);
                        if(response.status == "success"){
                            console.log("Success");
                            console.log(response.message);
                            if(response.type === "Add"){
                                button.classList.add("wish-cart-selected");
                            }
                            if(response.type === "Remove"){
                                button.classList.remove("wish-cart-selected");
                            }
                        }
                        else{
                            console.log("Error");
                            console.log(response.message);
                        }
                    } else {
                        console.error("Error fetching data. Status code: " + xhr.status);
                        console.error(xhr.responseText);
                    }
                }
            };
            xhr.onerror = function () {
                console.error("Network error occurred.");
            };
            xhr.send(JSON.stringify({productId: productId, type: 'add'}));
        }


    }

    if (window.location.pathname === "/pages/history.php") {

        
//toggle menus (left/right)

const toggleSlider = document.querySelector('.toggle-slider');
const btn = document.getElementById('btn');
const btnLeft = document.getElementsByClassName('left-call');
const btnRight = document.getElementsByClassName('right-call');

let isOn = false;

toggleSlider.addEventListener('click', () => {
  isOn = !isOn;
  if(isOn){
    for (let i = 0; i < btnRight.length; i++) {
        btnRight[i].style.display = "block";
    }
        for (let i = 0; i < btnLeft.length; i++) {
        btnLeft[i].style.display = "none";
    }
  }
  else{
    for (let i = 0; i < btnRight.length; i++) {
        btnRight[i].style.display = "none";
    }
    for (let i = 0; i < btnLeft.length; i++) {
        btnLeft[i].style.display = "block";
    }
  }

  btn.style.left = isOn ? '50%' : '0';
  btnRight.style.display = isOn ? 'block' : 'none';
  btnLeft.style.display = isOn ? 'none' : 'block';
  btnLeft.style.color = isOn ? 'black' : 'white';
  btnRight.style.color = isOn ? 'white' : 'black';

});


        let buynameSearch = document.querySelector(".left-call #name");
        let buydateSearch = document.querySelector(".left-call #date");
        let buyminpriceInput = document.querySelector(".left-call .input-min");
        let buymaxpriceInput = document.querySelector(".left-call .input-max");
        let buysortOptionSelect = document.querySelector(".left-call .order .sortOption");



        buynameSearch.addEventListener("keyup", function () {
            performsearchBuy();
        });

        buydateSearch.addEventListener("keyup", function () {
            performsearchBuy();
        });

        buyminpriceInput.addEventListener("keydown", function (e) {

            if(e.key === ',' || e.key === '.'){
                e.preventDefault();
            }
        });

        buyminpriceInput.addEventListener("keyup", function () {

            performsearchBuy();
        });

        buymaxpriceInput.addEventListener("keydown", function (e) {

            if(e.key === ',' || e.key === '.'){
                e.preventDefault();
            }
        });

        buymaxpriceInput.addEventListener("keyup", function () {

            performsearchBuy();
        });

        buysortOptionSelect.addEventListener("change", function () {
            performsearchBuy();
        });

        function performsearchBuy() {
            updateProductsHistoryBuy(buynameSearch.value, buysortOptionSelect.value, formatDate(buydateSearch.value,false), buyminpriceInput.value, buymaxpriceInput.value);
        }

        function updateProductsHistoryBuy(name, sortOption, date, minprice, maxprice) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "../ajax/update_productsHistBuy.php", true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let response = JSON.parse(xhr.responseText);
                        drawUIHistBuy(response);

                    } else {
                        console.error("Error fetching data. Status code: " + xhr.status);
                        console.error(xhr.responseText);
                    }
                }
            };
            xhr.onerror = function () {
                console.error("Network error occurred.");
            };
            xhr.send(JSON.stringify({ name: name, sortOption: sortOption, date: date, minprice: minprice, maxprice: maxprice }));
        }

        function drawUIHistBuy(response) {
            let productsContainer = document.querySelector("#buy-products");

            productsContainer.innerHTML = "";
            if (response.productListHTML.length === 0) {
                let noProductFound = document.createElement("div");
                noProductFound.textContent = "No products found.";
                noProductFound.style.fontSize = "1rem";
                productsContainer.appendChild(noProductFound);
            } else {
                response.productListHTML.forEach(function (product) {
                    let productMain = document.createElement("div");
                    productMain.classList.add("product-history");

                    let productImage = document.createElement("img");
                    productImage.src = "../images/products/" + product.id + "-1.jpg";
                    productImage.alt = "";

                    let productInfo = document.createElement("div");
                    productInfo.classList.add("product-info-history");

                    let productTitleActions = document.createElement("div");
                    productTitleActions.classList.add("product-info-fl-history");

                    let productName = document.createElement("h4");
                    productName.textContent = product.name;
                    let productPrice = document.createElement("h4");
                    productPrice.textContent = product.price + " €";


                    productTitleActions.appendChild(productName);
                    productTitleActions.appendChild(productPrice);

                    let productSecLine = document.createElement("div");
                    productSecLine.classList.add("product-info-sl-history");

                    let sellerName = document.createElement("p");
                    sellerName.textContent = "Seller: ";

                    let sellerLink = document.createElement("a");
                    sellerLink.href = "../pages/profile.php?id=" + response.users[product.id].id;
                    sellerLink.textContent = response.users[product.id].name;
                    sellerName.appendChild(sellerLink);

                    let sellDate = document.createElement("p");
                    sellDate.textContent = product.date;

                    productSecLine.appendChild(sellerName);
                    productSecLine.appendChild(sellDate);

                    productInfo.appendChild(productTitleActions);
                    productInfo.appendChild(productSecLine);


                    productMain.appendChild(productImage);
                    productMain.appendChild(productInfo);

                    let hr = document.createElement("hr");

                    productsContainer.appendChild(productMain);
                    productsContainer.appendChild(hr);
                });
            }
        }

        let sellnameSearch = document.querySelector(".right-call #name");
        let selldateSearch = document.querySelector(".right-call #date");
        let sellminpriceInput = document.querySelector(".right-call .input-min");
        let sellmaxpriceInput = document.querySelector(".right-call .input-max");
        let sellsortOptionSelect = document.querySelector(".right-call .order .sortOption");



        sellnameSearch.addEventListener("keyup", function () {
            performsearchHistSell();
        });

        selldateSearch.addEventListener("keyup", function () {
            performsearchHistSell();
        });

        sellminpriceInput.addEventListener("keydown", function (e) {

            if(e.key === ',' || e.key === '.'){
                e.preventDefault();
            }
        });

        sellminpriceInput.addEventListener("keyup", function () {

            performsearchHistSell();
        });

        sellmaxpriceInput.addEventListener("keydown", function (e) {

            if(e.key === ',' || e.key === '.'){
                e.preventDefault();
            }
        });

        sellmaxpriceInput.addEventListener("keyup", function () {

            performsearchHistSell();
        });

        sellsortOptionSelect.addEventListener("change", function () {
            performsearchHistSell();
        });

        function performsearchHistSell() {

            updateProducstHistorySell(sellnameSearch.value, sellsortOptionSelect.value, formatDate(selldateSearch.value,false), sellminpriceInput.value, sellmaxpriceInput.value);
        }

        function updateProducstHistorySell(name, sortOption, date, minprice, maxprice) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "../ajax/update_productsHistSell.php", true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let response = JSON.parse(xhr.responseText);
                        drawUIHistSell(response);

                    } else {
                        console.error("Error fetching data. Status code: " + xhr.status);
                        console.error(xhr.responseText);
                    }
                }
            };
            xhr.onerror = function () {
                console.error("Network error occurred.");
            };
            xhr.send(JSON.stringify({ name: name, sortOption: sortOption, date: date, minprice: minprice, maxprice: maxprice }));
        }

        function drawUIHistSell(response) {
            let productsContainer = document.querySelector("#sell-products");

            productsContainer.innerHTML = "";
            if (response.productListHTML.length === 0) {
                let noProductFound = document.createElement("div");
                noProductFound.textContent = "No products found.";
                productsContainer.appendChild(noProductFound);
            } else {
                response.productListHTML.forEach(function (product) {
                    let productMain = document.createElement("div");
                    productMain.classList.add("product-history");

                    let productImage = document.createElement("img");
                    productImage.src = "../images/products/" + product.id + "-1.jpg";
                    productImage.alt = "";

                    let productInfo = document.createElement("div");
                    productInfo.classList.add("product-info-history");

                    let productTitleActions = document.createElement("div");
                    productTitleActions.classList.add("product-info-fl-history");

                    let productName = document.createElement("h4");
                    productName.textContent = product.name;
                    let productPrice = document.createElement("h4");
                    productPrice.textContent = product.price + " €";


                    productTitleActions.appendChild(productName);
                    productTitleActions.appendChild(productPrice);

                    let productSecLine = document.createElement("div");
                    productSecLine.classList.add("product-info-sl-history");

                    let sellerName = document.createElement("p");
                    sellerName.textContent = "Buyer: ";

                    let sellerLink = document.createElement("a");
                    sellerLink.href = "../pages/profile.php?id=" + response.users[product.id].id;
                    sellerLink.textContent = response.users[product.id].name;
                    sellerName.appendChild(sellerLink);

                    let sellDate = document.createElement("p");
                    sellDate.textContent = product.date;

                    productSecLine.appendChild(sellerName);
                    productSecLine.appendChild(sellDate);

                    productInfo.appendChild(productTitleActions);
                    productInfo.appendChild(productSecLine);


                    productMain.appendChild(productImage);
                    productMain.appendChild(productInfo);

                    let hr = document.createElement("hr");

                    productsContainer.appendChild(productMain);
                    productsContainer.appendChild(hr);
                });
            }
        }
    }

    if (window.location.pathname === "/pages/myproducts.php") {

        
//toggle menus (left/right)

const toggleSlider = document.querySelector('.toggle-slider');
const btn = document.getElementById('btn');
const btnLeft = document.getElementsByClassName('left-call');
const btnRight = document.getElementsByClassName('right-call');

let isOn = false;

toggleSlider.addEventListener('click', () => {
  isOn = !isOn;
  if(isOn){
    for (let i = 0; i < btnRight.length; i++) {
        btnRight[i].style.display = "block";
    }
        for (let i = 0; i < btnLeft.length; i++) {
        btnLeft[i].style.display = "none";
    }
  }
  else{
    for (let i = 0; i < btnRight.length; i++) {
        btnRight[i].style.display = "none";
    }
    for (let i = 0; i < btnLeft.length; i++) {
        btnLeft[i].style.display = "block";
    }
  }

  btn.style.left = isOn ? '50%' : '0';
  btnRight.style.display = isOn ? 'block' : 'none';
  btnLeft.style.display = isOn ? 'none' : 'block';
  btnLeft.style.color = isOn ? 'black' : 'white';
  btnRight.style.color = isOn ? 'white' : 'black';

});


        let mysellnameSearch = document.querySelector(".left-call #name");
        let mysellminpriceInput = document.querySelector(".left-call .input-min");
        let mysellmaxpriceInput = document.querySelector(".left-call .input-max");
        let mysellsortOptionSelect = document.querySelector(".left-call .order .sortOption");


        mysellnameSearch.addEventListener("keyup", function () {
            performsearchMySell();
        });

        mysellminpriceInput.addEventListener("keydown", function (e) {

            if(e.key === ',' || e.key === '.'){
                e.preventDefault();
            }
        });

        mysellminpriceInput.addEventListener("keyup", function () {

            performsearchMySell();
        });

        mysellmaxpriceInput.addEventListener("keydown", function (e) {

            if(e.key === ',' || e.key === '.'){
                e.preventDefault();
            }
        });

        mysellmaxpriceInput.addEventListener("keyup", function () {

            performsearchMySell();
        });

        mysellsortOptionSelect.addEventListener("change", function () {
            performsearchMySell();
        });

        function performsearchMySell() {
            updateProductsMyProdSell(mysellnameSearch.value, mysellsortOptionSelect.value, mysellminpriceInput.value, mysellmaxpriceInput.value);
        }

        function updateProductsMyProdSell(name, sortOption, minprice, maxprice) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "../ajax/update_productsMyProdSell.php", true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let response = JSON.parse(xhr.responseText);
                        drawUIMySell(response);

                    } else {
                        console.error("Error fetching data. Status code: " + xhr.status);
                        console.error(xhr.responseText);
                    }
                }
            };
            xhr.onerror = function () {
                console.error("Network error occurred.");
            };
            xhr.send(JSON.stringify({ name: name, sortOption: sortOption, minprice: minprice, maxprice: maxprice }));
        }

        function drawUIMySell(response) {
            let productsContainer = document.querySelector("#myproducts-sell");

            productsContainer.innerHTML = "";
            if (response.productListHTML.length === 0) {
                let noProductFound = document.createElement("div");
                noProductFound.textContent = "No products found.";
                productsContainer.appendChild(noProductFound);
            } else {
                response.productListHTML.forEach(function (product) {
                    let productMain = document.createElement("div");
                    productMain.classList.add("product-sell");

                    let productImage = document.createElement("img");
                    productImage.src = "../images/products/" + product.id + "-1.jpg";
                    productImage.alt = "";

                    let productLink = document.createElement("a");
                    productLink.href = "../pages/productInfo.php?id=" + product.id;
                    productLink.classList.add("product-sell-info")

                    let productName = document.createElement("h4");
                    productName.textContent = product.name;
                    let productPrice = document.createElement("h4");
                    productPrice.textContent = product.price + " €";


                    productLink.appendChild(productName);
                    productLink.appendChild(productPrice);

                    let productbtns = document.createElement("div");
                    productbtns.classList.add("product-sell-btns");

                    let editbutton = document.createElement("button");
                    editbutton.textContent = "Edit";

                    let chatsbutton = document.createElement("button");

                    let chatsLink = document.createElement("a");
                    chatsLink.href = "../pages/chats.php?id=" + product.id;
                    chatsLink.textContent = "Chats";
                    chatsbutton.appendChild(chatsLink);

                    productbtns.appendChild(editbutton);
                    productbtns.appendChild(chatsbutton);


                    productMain.appendChild(productImage);
                    productMain.appendChild(productLink);
                    productMain.appendChild(productbtns);

                    let hr = document.createElement("hr");

                    productsContainer.appendChild(productMain);
                    productsContainer.appendChild(hr);
                });
            }
        }

        let shipnameSearch = document.querySelector(".right-call #name");
        let shipdateSearch = document.querySelector(".right-call #date");
        let shipminpriceInput = document.querySelector(".right-call .input-min");
        let shipmaxpriceInput = document.querySelector(".right-call .input-max");
        let shipsortOptionSelect = document.querySelector(".right-call .order .sortOption");



        shipnameSearch.addEventListener("keyup", function () {
            performsearchMyShip();
        });

        shipdateSearch.addEventListener("keyup", function () {
            performsearchMyShip();
        });

        shipminpriceInput.addEventListener("keydown", function (e) {

            if(e.key === ',' || e.key === '.'){
                e.preventDefault();
            }
        });

        shipminpriceInput.addEventListener("keyup", function () {

            performsearchMyShip();
        });

        shipmaxpriceInput.addEventListener("keydown", function (e) {

            if(e.key === ',' || e.key === '.'){
                e.preventDefault();
            }
        });

        shipmaxpriceInput.addEventListener("keyup", function () {

            performsearchMyShip();
        });

        shipsortOptionSelect.addEventListener("change", function () {
            performsearchMyShip();
        });

        function performsearchMyShip() {

            updateProducstMyProdShip(shipnameSearch.value, shipsortOptionSelect.value, formatDate(shipdateSearch.value,false), shipminpriceInput.value, shipmaxpriceInput.value);
        }

        function updateProducstMyProdShip(name, sortOption, date, minprice, maxprice) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "../ajax/update_productsMyProdShip.php", true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let response = JSON.parse(xhr.responseText);
                        drawUIMyShip(response);

                    } else {
                        console.error("Error fetching data. Status code: " + xhr.status);
                        console.error(xhr.responseText);
                    }
                }
            };
            xhr.onerror = function () {
                console.error("Network error occurred.");
            };
            xhr.send(JSON.stringify({ name: name, sortOption: sortOption, date: date, minprice: minprice, maxprice: maxprice }));
        }

        function drawUIMyShip(response) {
            let productsContainer = document.querySelector("#myproducts-ship");

            productsContainer.innerHTML = "";
            if (response.productListHTML.length === 0) {
                let noProductFound = document.createElement("div");
                noProductFound.textContent = "No products found.";
                productsContainer.appendChild(noProductFound);
            } else {
                response.productListHTML.forEach(function (product) {
                    let productMain = document.createElement("div");
                    productMain.classList.add("product-ship");

                    let productImage = document.createElement("img");
                    productImage.src = "../images/products/" + product.id + "-1.jpg";
                    productImage.alt = "";

                    let productInfo = document.createElement("div");
                    productInfo.classList.add("product-ship-info");

                    let productLink = document.createElement("a");
                    productLink.href = "../pages/productInfo.php?id=" + product.id;
                    productLink.classList.add("product-ship-info-fl")

                    let productName = document.createElement("h4");
                    productName.textContent = product.name;
                    let productPrice = document.createElement("h4");
                    productPrice.textContent = product.price + " €";


                    productLink.appendChild(productName);
                    productLink.appendChild(productPrice);

                    let productSecLine = document.createElement("div");
                    productSecLine.classList.add("product-ship-info-sl");

                    let buyerName = document.createElement("p");
                    buyerName.textContent = "Buyer: ";

                    let buyerLink = document.createElement("a");
                    buyerLink.href = "../pages/profile.php?id=" + response.users[product.id].id;
                    buyerLink.textContent = response.users[product.id].name;
                    buyerName.appendChild(buyerLink);

                    let buyDate = document.createElement("p");
                    buyDate.textContent = response.deals[product.id].date;

                    productSecLine.appendChild(buyerName);
                    productSecLine.appendChild(buyDate);

                    productInfo.appendChild(productLink);
                    productInfo.appendChild(productSecLine);

                    let productbtns = document.createElement("div");
                    productbtns.classList.add("product-ship-btns");

                    let sendbutton = document.createElement("button");
                    sendbutton.textContent = "Send";

                    productbtns.appendChild(sendbutton);


                    productMain.appendChild(productImage);
                    productMain.appendChild(productInfo);
                    productMain.appendChild(productbtns);

                    let hr = document.createElement("hr");

                    productsContainer.appendChild(productMain);
                    productsContainer.appendChild(hr);
                });
            }
        }
    }

    if(window.location.pathname === "/pages/profile.php"){

        
//toggle menus (left/right)

const toggleSlider = document.querySelector('.toggle-slider');
const btn = document.getElementById('btn');
const btnLeft = document.getElementsByClassName('left-call');
const btnRight = document.getElementsByClassName('right-call');

let isOn = false;

toggleSlider.addEventListener('click', () => {
  isOn = !isOn;
  if(isOn){
    for (let i = 0; i < btnRight.length; i++) {
        btnRight[i].style.display = "block";
    }
        for (let i = 0; i < btnLeft.length; i++) {
        btnLeft[i].style.display = "none";
    }
  }
  else{
    for (let i = 0; i < btnRight.length; i++) {
        btnRight[i].style.display = "none";
    }
    for (let i = 0; i < btnLeft.length; i++) {
        btnLeft[i].style.display = "block";
    }
  }

  btn.style.left = isOn ? '50%' : '0';
  btnRight.style.display = isOn ? 'block' : 'none';
  btnLeft.style.display = isOn ? 'none' : 'block';
  btnLeft.style.color = isOn ? 'black' : 'white';
  btnRight.style.color = isOn ? 'white' : 'black';

});

        let profilenameSearch = document.querySelector(".left-call #name");
        let profileminpriceInput = document.querySelector(".left-call .input-min");
        let profilemaxpriceInput = document.querySelector(".left-call .input-max");
        let profilesortOptionSelect = document.querySelector(".left-call .order .sortOption");

        let urlParams = new URLSearchParams(window.location.search);
        let user = urlParams.get('id');
        console.log(user);


        profilenameSearch.addEventListener("keyup", function() {
            performsearchProfile();
        });

        profileminpriceInput.addEventListener("keydown", function (e) {

            if(e.key === ',' || e.key === '.'){
                e.preventDefault();
            }
        });

        profileminpriceInput.addEventListener("keyup", function () {

            performsearchProfile();
        });

        profilemaxpriceInput.addEventListener("keydown", function (e) {

            if(e.key === ',' || e.key === '.'){
                e.preventDefault();
            }
        });

        profilemaxpriceInput.addEventListener("keyup", function () {

            performsearchProfile();
        });
        
        profilesortOptionSelect.addEventListener("change", function() {
            performsearchProfile();
        });

        function performsearchProfile(){
            updateProductsProfile(profilenameSearch.value,profilesortOptionSelect.value,profileminpriceInput.value,profilemaxpriceInput.value);
        }

        function updateProductsProfile(name, sortOption, minprice, maxprice) {
            console.log(user);
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "../ajax/update_productsProfile.php", true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let response = JSON.parse(xhr.responseText);
                        drawUIProfile(response);
    
                    } else {
                        console.error("Error fetching data. Status code: " + xhr.status);
                        console.error(xhr.responseText);
                    }
                }
            };
            xhr.onerror = function() {
                console.error("Network error occurred.");
            };
            xhr.send(JSON.stringify({name: name, profileuser : user, sortOption: sortOption, minprice: minprice, maxprice: maxprice}));
        }

        function drawUIProfile(response){
            let productsContainer = document.querySelector("#profile-products");
        
            productsContainer.innerHTML = "";
            if(response.productListHTML.length === 0){
                let noProductFound = document.createElement("div");
                noProductFound.textContent = "No products found.";
                productsContainer.appendChild(noProductFound);
            } else {
                response.productListHTML.forEach(function(product) {
                    let productMain = document.createElement("a");
                    productMain.classList.add("product-profile");
                    productMain.href = "../pages/productInfo.php?id=" + product.id;

                    let productImage = document.createElement("img");
                    productImage.src = "../images/products/" + product.id + "-1.jpg";
                    productImage.alt = "";

                    let productInfo = document.createElement("div");
                    productInfo.classList.add("product-profile-info");

                    let productName = document.createElement("h4");
                    productName.textContent = product.name;
                    let productPrice = document.createElement("h4");
                    productPrice.textContent = product.price + " €";

                    productInfo.appendChild(productName);
                    productInfo.appendChild(productPrice);

                    productMain.appendChild(productImage);
                    productMain.appendChild(productInfo);
            
                    let hr = document.createElement("hr"); 
            
                    productsContainer.appendChild(productMain);
                    productsContainer.appendChild(hr);
                });
            }
        }

    }


    if(window.location.pathname === "/pages/chats.php"){

        let userSide = document.querySelectorAll(".chat-user");
        let chatSide = document.querySelector(".chat-container-chat");
        let sendButton = document.querySelector(".chat-input .sendbutton");
        let dealButton = document.querySelector(".chat-input .dealbutton");
        let newMessage = document.querySelector(".chat-input textarea");
        let chatuserid;
        let userid;

        let urlParams = new URLSearchParams(window.location.search);
        let product = urlParams.get('id');


        userSide.forEach(function(userChat) {
            userChat.addEventListener('click', function() {
                userid = userChat.getAttribute('id').substring(5);
                let userImageSrc = userChat.querySelector('img').getAttribute('src');
                let userName = userChat.querySelector('h4').textContent;
                
                
                // Update chat header
                let chatHeader = document.querySelector('.chat-header');

                if(!chatHeader){
                    chatuserid = userid;
                    let newChatHeader = document.createElement("div");
                    newChatHeader.classList.add("chat-header");    

                    let imageHeader = document.createElement("img");
                    imageHeader.src = userImageSrc;

                    let nameHeader = document.createElement("h4");
                    nameHeader.textContent = userName;

                    newChatHeader.appendChild(imageHeader);
                    newChatHeader.appendChild(nameHeader);

                    let newChatInput = document.createElement("div");
                    newChatInput.classList.add("chat-input");    

                    let buttonDealInput = document.createElement("button");
                    buttonDealInput.classList.add("dealbutton");
                    buttonDealInput.textContent = "Deal";

                    let input = document.createElement("textarea");
                    input.setAttribute('placeholder', 'Type a message');

                    let buttonSendInput = document.createElement("button");
                    buttonSendInput.classList.add("sendbutton");
                    buttonSendInput.innerHTML = "&uarr;";

                    newChatInput.appendChild(buttonDealInput);
                    newChatInput.appendChild(input);
                    newChatInput.appendChild(buttonSendInput);

                    newchatBox = document.createElement("div");
                    newchatBox.classList.add("chat-box");

                    chatSide.appendChild(newChatHeader);
                    chatSide.appendChild(newchatBox);
                    chatSide.appendChild(newChatInput);

                    dealButton = document.querySelector(".chat-input .dealbutton");
                    sendButton = document.querySelector(".chat-input .sendbutton");
                    newMessage = document.querySelector(".chat-input textarea");

                    sendButton.addEventListener('click',function() {
                        if(newMessage.value.trim() != ""){
                            updateChat(newMessage.value, userid,0);
                            newMessage.value = "";
                        }
                    });

                    newMessage.addEventListener('keydown', function(e) {
                        if (e.key === "Enter" && (e.ctrlKey ||e.shiftKey)) {
                            // Prevent the default behavior of inserting a new line
                            e.preventDefault();
                            // Insert a line break at the current cursor position
                            let cursorPosition = this.selectionStart;
                            let message = this.value;
                            let newMessage = message.substring(0, cursorPosition) + "\n" + message.substring(cursorPosition);
                            this.value = newMessage;
                            // Adjust the cursor position
                            this.selectionStart = cursorPosition + 1;
                            this.selectionEnd = cursorPosition + 1;
                        } else if (e.key === "Enter") {
                            // Prevent the default behavior (form submission)
                            e.preventDefault();
                            this.value = trimWhitespaceAndLineBreaks(this.value);
                            // Send the message when only "Enter" key is pressed
                            if (this.value !== "") {
                                console.log(this.value);
                                updateChat(this.value, userid,0);
                                // Clear the textarea after sending the message
                                this.value = "";
                            }
                        }

                    });

                    dealButton.addEventListener("click", function() {
                        showDealBox();
                    });

                    showChat(userid);

                }
                else{
                    if(chatuserid !== userid){
                        chatuserid = userid;
                        chatHeader.querySelector('img').setAttribute('src', userImageSrc);
                        chatHeader.querySelector('h4').textContent = userName;
    

                        let messagesBody = document.querySelector('.chat-box');
                        messagesBody.innerHTML = '';


                        showChat(userid);
                    }
                }

            });
        });



        function showDealBox() {

            let dealBoxOpen = document.querySelector(".deal-box");
            if (dealBoxOpen) {
                // If deal box is already open, close it by removing it from the DOM
                dealBoxOpen.remove();
                return; // Stop further execution
            }
            // Create box elements
            let dealBox = document.createElement("div");
            dealBox.classList.add("deal-box");
        
            let titleBoxDeal = document.createElement("h4");
            titleBoxDeal.textContent = "Deal";
        
            let priceInput = document.createElement("input");
            priceInput.setAttribute("type", "text");
        
            let submitButton = document.createElement("button");
            submitButton.textContent = "Submit";
        
            // Append elements to the deal box
            dealBox.appendChild(titleBoxDeal);
            dealBox.appendChild(priceInput);
            dealBox.appendChild(submitButton);
        
            // Append the deal box to the chat side
            chatSide.appendChild(dealBox);
        
            // Show the deal box
            dealBox.style.display = "block";
        
            // Event listener for submit button
            submitButton.addEventListener("click", function() {
                let price = priceInput.value;

                if (!validatePriceInput(price)) {
                    alert("Please enter a valid price with two decimal places.");
                    return; // Stop further execution if validation fails
                }
                else{
                    
                    updateChat(price,userid,1);
                }

                // Handle submission (e.g., send data to server)
                console.log("Price:", price);
                // Optionally, you can hide the deal box after submission
                dealBox.style.display = "none";

            });
            
        }
        

        function showChat(userId,scroll) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "../ajax/show_chatBox.php", true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let response = JSON.parse(xhr.responseText);
                        drawUIShowChat(response,scroll);
    
                    } else {
                        console.error("Error fetching data. Status code: " + xhr.status);
                        console.error(xhr.responseText);
                    }
                }
            };
            xhr.onerror = function() {
                console.error("Network error occurred.");
            };
            xhr.send(JSON.stringify({user : userId, product : product}));
        }

        function updateChat(newMessage, userId, type) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "../ajax/update_chatBox.php", true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let response = JSON.parse(xhr.responseText);
                        console.log(response);
                        drawUIShowChat(response);
                    } else {
                        console.error("Error fetching data. Status code: " + xhr.status);
                        console.error(xhr.responseText);
                    }
                }
            };
            xhr.onerror = function() {
                console.error("Network error occurred.");
            };
            xhr.send(JSON.stringify({newMessage : newMessage ,user : userId, product :product, type : type}));
        }

        function drawUIShowChat(response, scroll){
            let messageContainer = document.querySelector(".chat-box");

            messageContainer.innerHTML = "";

            if(response.messageListHTML.length === 0){
                let noProductFound = document.createElement("div");
                noProductFound.textContent = "No Messages found.";
                messageContainer.appendChild(noProductFound);
            } else {
                response.messageListHTML.forEach(message=> {

                    let messageContent = document.createElement("div");
                    let messageText = document.createElement("p");


                    if(message.type == 0){
                        messageContent.classList.add("message")
    
                        messageText.innerText = message.text;

                        let messageTime = document.createElement("span");
                        messageTime.classList.add("msg-time");
                        messageTime.textContent = formatDate(message.date,true);
        
                        let messagebr = document.createElement("br");
        
                        messageText.appendChild(messagebr);
                        messageText.appendChild(messageTime);
                    }
                    else{
                        messageContent.classList.add("deal")
    
                        messageText.innerText = message.text + " €";
                        let messageButton = document.createElement("button");
                        messageButton.classList.add("btn-deal");
                        messageButton.textContent = "Accept Deal";
        
                        let messagebr = document.createElement("br");
        
                        messageText.appendChild(messagebr);
                        messageText.appendChild(messageButton);
                    }

                    messageContent.appendChild(messageText);
        
        
                    if(message.sender === message.interested){
                        messageContent.classList.add("interested-message")
                    }
                    else{
                        messageContent.classList.add("my-message")    
                    }
    
                    messageContainer.appendChild(messageContent); 
                    
                

                });

                const lastmessage = response.messageListHTML[response.messageListHTML.length -1];
                let selectordate = `.chat-container-users #user-${lastmessage.interested} .details .details-fl p`;
                let selectorname = `.chat-container-users #user-${lastmessage.interested} .details .details-sl p`;
                let newdate = document.querySelector(selectordate);
                let newname = document.querySelector(selectorname);
          
                newdate.textContent = getTimeAgo(lastmessage.date);

                if(lastmessage.sender == lastmessage.product){
                    newname.textContent = 'You: ' + lastmessage.text;
                }
                else{
                    newname.textContent = lastmessage.text;
                }


            }
            if(scroll!=null){
                messageContainer.scrollTop = scroll;
            }
            else{
                messageContainer.scrollTop = messageContainer.scrollHeight;

            }

        }
    }

});









