document.addEventListener("DOMContentLoaded", function () {
    if (window.location.pathname === "/pages/cart.php") {

        let product = document.querySelectorAll('.product-list .cart .sm-product');

        product.forEach(function(product){
            let deleteButton = product.querySelector('.sm-delete-btn');
            let productid = product.getAttribute('id');
            const price = product.querySelector('.sm-price').textContent;
            console.log(price);
            
            deleteButton.addEventListener('click', function(e){
                e.preventDefault();
                updateCart(extractid(productid), deleteButton, price);
            });


        });

        function updateCart(productId, button, priceProduct) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "../ajax/add_toCart.php", true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let response = JSON.parse(xhr.responseText);
                        if(response.status == "success"){
                            console.log("Success");
                            console.log(response.message);
                            button.closest('.sm-product').remove();
                            let price = document.querySelector('.checkout-section .bill');
                            console.log(price.textContent);
                            console.log(priceProduct);
                            price.textContent = (parseFloat(price.textContent) - parseFloat(priceProduct)).toFixed(2);
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
            xhr.send(JSON.stringify({productId: productId, type: 'delete'}));
        }


    }

    if (window.location.pathname === "/pages/confirmpay.php"){
        const btn = document.querySelector('#conf-purchase-btn');
        
        btn.addEventListener('click', function(){
            let payproducts = document.querySelectorAll('.pay-products');

            this.disabled = true;
            let products = {};

            payproducts.forEach(function(product){
                const id = product.getAttribute('id');
                const priceText = product.textContent.split('-').slice(-1)[0].trim(); // Trim any extra whitespace
                const priceValue = priceText.replace('€', '').trim(); // Remove '€' symbol and trim any extra whitespace
                products[extractid(id)] = parseFloat(priceValue);
                console.log(priceValue);    
            })

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../actions/action_confirmpurchase.php', true);
        xhr.setRequestHeader("Content-Type", "application/json");


    // Set up a callback function to handle the response
        xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) { // 4 means the request is done
            if (xhr.status === 200) { // 200 means a successful response
                try {
                    const response = JSON.parse(xhr.responseText);
                    console.log(response.data);
                    if(response['status'] == 'success'){
                        console.log('Purchase successful:', response);
                        window.location.href = '../pages/mainpage.php';
                    }   
                    else{
                        console.log("ERROR BUYING");
                    }
                } catch (e) {
                    console.error('Error parsing response JSON:', e);
                    alert('There was an error processing your purchase. Please try again.');
                }
            } else {
                console.error('There was a problem with the request:', xhr.statusText);
                alert('There was an error with your purchase. Please try again.');
            }
            // Re-enable the button to allow retry
            document.getElementById('confirmPurchaseBtn').disabled = false;
        }
    
    };
    xhr.send(JSON.stringify(products));

        });
        }

});