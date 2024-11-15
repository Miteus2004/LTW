

document.addEventListener("DOMContentLoaded", function () {
    if (window.location.pathname === "/pages/productInfo.php") {

        
        let urlParams = new URLSearchParams(window.location.search);
        let product = urlParams.get('id');


        let parentElement = document.body;

        parentElement.addEventListener('click', function(event) {
            if (event.target && event.target.id === 'prod-shoppingcart') {
                console.log("CART button clicked");
                updateCartWish(product, true, event.target);
            }
    
            if (event.target && event.target.id === 'prod-wishlist') {
                console.log("WISH button clicked");
                updateCartWish(product, false, event.target);
            }
        });


    function updateCartWish(productId,cart, button) {
        console.log(productId);
        let xhr = new XMLHttpRequest();
        const url = cart ? "../ajax/add_toCart.php" : "../ajax/add_toWishlist.php";
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    let response = JSON.parse(xhr.responseText);
                    if(response.status === "success"){
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



        // DELETE PRODUCT
        const deleteIcon = document.getElementById("deleteIcon");
        if (deleteIcon) {
            deleteIcon.addEventListener("click", function () {
                
                console.log("ID: ", product);


                let xhr = new XMLHttpRequest();
                xhr.open("POST", "../ajax/delete_product.php", true);
                xhr.setRequestHeader("Content-Type", "application/json");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            let response = JSON.parse(xhr.responseText);
                            if (response.status !== "error") {
                                window.location.href = "mainpage.php";
                            };

                        } else {
                            console.error("Error fetching data. Status code: " + xhr.status);
                            console.error(xhr.responseText);
                        }
                    }
                };
                xhr.onerror = function () {
                    console.error("Network error occurred.");
                };
                xhr.send(JSON.stringify({ productId: product }));



            })


        }


        // Make the text input area of Chat vary in size
        const textarea = document.querySelector(".chat-textarea");
        if (textarea) {
            textarea.addEventListener("keyup", e => {
                textarea.style.height = "2rem";
                let scHeight = e.target.scrollHeight;
                textarea.style.height = `${scHeight}px`;
            });
        }


        // Image scroll
        const imagesContainer = document.getElementById("imagesContainer");
        const totalPhotos = imagesContainer.childElementCount;
        const photoGap = window.getComputedStyle(imagesContainer).gap;
        const containerWidth = document.querySelector(".container").clientWidth + parseFloat(photoGap);
        let photo = 0;
        const slider = document.querySelector("#imagesContainer");
        // Grab

        let isDragStart = false, prevPageX, prevScrollLeft;

        const dragStart = (e) => {
            isDragStart = true;
            prevPageX = e.pageX;
            prevScrollLeft = slider.scrollLeft;
        }

        const dragEnd = () => {
            isDragStart = false;
        }
        const dragging = (e) => {
            if (!isDragStart) return;
            let positionDiff = e.pageX - prevPageX;
            let scrollAmount = prevScrollLeft - Math.min(Math.abs(positionDiff) * 5, containerWidth) * Math.sign(positionDiff);
            scrollAmount = Math.min(scrollAmount, containerWidth * (totalPhotos - 1));
            if (scrollAmount < 0) scrollAmount = 0;
            photo = Math.round(scrollAmount / (containerWidth));
            slider.scrollLeft = containerWidth * photo;
            console.log(photo);
            console.log("Scroll: ", scrollAmount);
            checkButtons();
        }

        slider.addEventListener("mousedown", dragStart);
        slider.addEventListener("mouseleave", dragEnd);
        slider.addEventListener("mouseup", dragEnd);
        slider.addEventListener("mousemove", dragging);


        // Arrows


        const scrollLeft = () => {
            console.log("Left Arrow");
            slider.scrollLeft -= containerWidth;
            photo--;
            checkButtons();
        }

        const scrollRight = () => {
            console.log("Right Arrow");
            slider.scrollLeft += containerWidth;
            photo++;
            checkButtons();
        }

        const leftButton = document.getElementById('leftArrow');
        const rightButton = document.getElementById('rightArrow');
        checkButtons();
        leftButton.addEventListener('click', scrollLeft);
        rightButton.addEventListener('click', scrollRight);

        function checkButtons() {

            if (photo == 0) {
                leftButton.style.visibility = "hidden";
            }
            else leftButton.style.visibility = "visible";

            if (photo == totalPhotos - 1) {
                rightButton.style.visibility = "hidden";
            } else rightButton.style.visibility = "visible";

        }



        //Chat


        let inputChat = document.getElementById("chat-textarea");
        let sendButton = document.querySelector(".chat-input-prod .sendbutton");

        showChat();

        function trimWhitespaceAndLineBreaks(text) {
            return text.replace(/^\s+|\s+$/g, '');
        }


        sendButton.addEventListener('click',function() {
            if(inputChat.value.trim() != ""){
                updateChat(inputChat.value,0);
                inputChat.value = "";
            }
        });

        inputChat.addEventListener('keydown', function(e) {
            if (e.key === "Enter" && (e.ctrlKey ||e.shiftKey)) {
                // Prevent the default behavior of inserting a new line
                e.preventDefault();
                // Insert a line break at the current cursor position
                let cursorPosition = this.selectionStart;
                let message = this.value;
                let inputChat = message.substring(0, cursorPosition) + "\n" + message.substring(cursorPosition);
                this.value = inputChat;
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
                    updateChat(this.value,0);
                    // Clear the textarea after sending the message
                    this.value = "";
                }
            }

        });



        function acceptDeal(price) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "../ajax/store_valuesInSession.php", true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let response = JSON.parse(xhr.responseText);
                        if(response['status'] == 'success') {
                            window.location.href = "/pages/confirmpay.php"                     
                        }                 
                    } else {
                        console.error("Error fetching data. Status code: " + xhr.status);
                        console.error(xhr.responseText);
                    }
                }
            };
            xhr.onerror = function() {
                console.error("Network error occurred.");
            };
            xhr.send(JSON.stringify({product :product, price : price, type : 'acceptDealChat'}));
        }


        function showChat(scroll) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "../ajax/show_chatBox.php", true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let response = JSON.parse(xhr.responseText);
                        drawUIShowChat(response, scroll);

                    } else {
                        console.error("Error fetching data. Status code: " + xhr.status);
                        console.error(xhr.responseText);
                    }
                }
            };
            xhr.onerror = function() {
                console.error("Network error occurred.");
            };
            xhr.send(JSON.stringify({user : null, product : product}));
        }



        function updateChat(newMessage, type) {
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
            xhr.send(JSON.stringify({newMessage : newMessage ,user : null, product :product, type : type}));
        }

        function drawUIShowChat(response,scroll){
            let messageContainer = document.querySelector(".chat-box");

            messageContainer.innerHTML = "";

            if(response.messageListHTML.length === 0){
                let noProductFound = document.createElement("div");
                noProductFound.textContent = "Send your first message.";
                noProductFound.style.fontSize = "0.5rem";
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
                        messageTime.textContent = formatDate(message.date, true);
        
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

                        messageButton.addEventListener("click", function () {
                            let float = parseFloat((message.text).replace(',','.'))
                            acceptDeal(float);

                        });
                    }

                    messageContent.appendChild(messageText);
        
        
                    if(message.sender !== message.interested){
                        messageContent.classList.add("interested-message")
                    }
                    else{
                        messageContent.classList.add("my-message")    
                    }

                    messageContainer.appendChild(messageContent); 
                    
                

                });
            }
            if(scroll!=null){
                messageContainer.scrollTop = scroll;
            }
            else{
                messageContainer.scrollTop = messageContainer.scrollHeight;
    
            }

        }



    }


})