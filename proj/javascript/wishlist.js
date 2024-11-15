document.addEventListener("DOMContentLoaded", function () {
    if (window.location.pathname === "/pages/wishlist.php") {

        let product = document.querySelectorAll('.product-main');

        product.forEach(function(product){
            let deleteButton = product.querySelector('.prod-wishlist');
            let productid = product.getAttribute('id');
            
            console.log(deleteButton);

            deleteButton.addEventListener('click', function(e){
                console.log(deleteButton);
                e.preventDefault();
                updateWish(extractid(productid), deleteButton);
            });


        });

        function updateWish(productId, button) {
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
                            button.closest('.product-main').remove();
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
});