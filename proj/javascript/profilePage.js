


if (window.location.pathname === "/pages/profile.php") {

    let editingProfile = false;
    let changingPassword = false;


    function toggleVisibility(elements) {
        for (let i = 0; i < elements.length; i++) {
            let computedStyle = window.getComputedStyle(elements[i]);
            if (computedStyle.display === "none") {
                elements[i].style.display = "block"; // Show the element
            } else {
                elements[i].style.display = "none"; // Hide the element
            }
        }
    }

    function editProfileToggle() {
        if (changingPassword) return;
        editingProfile = !editingProfile;
        let elements = document.getElementsByClassName("editVisibility profile-details");
        if (elements)
            toggleVisibility(elements);
    }

    function changePasswordToggle() {
        if (editingProfile) return;
        changingPassword = !changingPassword;
        let elements = document.getElementsByClassName("editVisibility password");
        if (elements)
            toggleVisibility(elements);
    }




    document.addEventListener("DOMContentLoaded", function () {

        // Deal with change profile buttons
        const profileImage = document.getElementById("profilePicture");
        if (profileImage.src.split('/').pop().split('.')[0] === "default") {
            const icon = document.getElementById("deleteProfileImageIcon");
            if (icon) icon.style.display = "none";
        }
        // Deal with change Admin Status buttons
        const adminButton = document.getElementById("toogleAdminButton");
        if (adminButton) {
            adminButton.addEventListener("click", function () {
                console.log("Clikced the admin abutton");

                if (adminButton.classList.contains("fa-lock")) {
                    // If the current class is fa-lock, switch to fa-lock-open
                    adminButton.classList.remove("fa-lock");
                    adminButton.classList.add("fa-lock-open");
                } else {
                    // If the current class is fa-lock-open, switch to fa-lock
                    adminButton.classList.remove("fa-lock-open");
                    adminButton.classList.add("fa-lock");
                }
                let urlParams = new URLSearchParams(window.location.search);
                sendChanges({ type: 'admin', user: urlParams.get('id') });

            });


        }

        function rebuildProfile(user) {
            console.log("rebuilding");
            const name = document.querySelector("#profileUserInfo .name");
            const userName = document.querySelector("#profileUserInfo .userName");
            const email = document.querySelector("#profileUserInfo .email");
            const location = document.querySelector("#profileUserInfo .location");

            name.textContent = user.name;
            userName.textContent = user.username;
            email.textContent = user.email;
            location.textContent = user.loc;
        }

        function rebuildProfilePicture(hasPhoto, userId) {
            console.log("rebuilding profile: ", hasPhoto);
            const profilePicture = document.getElementById("profilePicture");

            const timestamp = new Date().getTime();
            profilePicture.src = (hasPhoto ? "../images/users/" + userId + ".jpg" : "../images/users/default.jpg") + "?v=" + timestamp;

            const deleteIcon = document.getElementById("deleteProfileImageIcon");
            if (hasPhoto) {
                deleteIcon.style.display = "Inline-block";
            } else {
                deleteIcon.style.display = "none";
            }

        }

        function clearInputs(inputs) {
            inputs.forEach(input => {
                input.value = "";
            })
        }

        function sendChanges(values) {
            console.log(values);
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "../ajax/update_userProfile.php", true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        console.log("the response:", xhr.responseText);
                        let response = JSON.parse(xhr.responseText);
                        if (response.status !== "error") {
                            if (values.type === "info") rebuildProfile(response.user);
                            if (values.type === "password") console.log(response.changes);
                            if (values.type === "img") rebuildProfilePicture(response.hasPhoto, response.userId)
                        };
                        console.log(response);

                    } else {
                        console.error("Error fetching data. Status code: " + xhr.status);
                        console.error(xhr.responseText);
                    }
                }
            };
            xhr.onerror = function () {
                console.error("Network error occurred.");
            };
            xhr.send(JSON.stringify(values));
        }

        function sendChangesForms(values) {
            console.log(values);
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "../ajax/update_userProfile.php", true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        console.log("the response:", xhr.responseText);
                        let response = JSON.parse(xhr.responseText);
                        console.log(response);
                        if (response.success) {
                            console.log("Inside your ass");
                            rebuildProfilePicture(response.hasPhoto, response.userId);
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
            xhr.send(values);
        }

        const saveEdit = document.getElementById("saveProfileEditButton");
        const cancelEdit = document.getElementById("cancelProfileEditButton");
        saveEdit.addEventListener("click", function () {
            editProfileToggle();

            values = {
                type: "info",
                name: document.querySelector("#profileEditInputs .name").value,
                userName: document.querySelector("#profileEditInputs .userName").value,
                email: document.querySelector("#profileEditInputs .email").value,
                location: document.querySelector("#profileEditInputs .location").value
            };
            sendChanges(type);
        })

        cancelEdit.addEventListener("click", function () {
            editProfileToggle();

            document.querySelector("#profileEditInputs .name").value = document.querySelector("#profileUserInfo .name").textContent;
            document.querySelector("#profileEditInputs .userName").value = document.querySelector("#profileUserInfo .userName").textContent;
            document.querySelector("#profileEditInputs .email").value = document.querySelector("#profileUserInfo .email").textContent;
            document.querySelector("#profileEditInputs .location").value = document.querySelector("#profileUserInfo .location").textContent;

        })

        const savePasswordChange = document.getElementById("saveProfilePasswordChange");
        const cancelPasswordChange = document.getElementById("cancelProfilePasswordChange");
        savePasswordChange.addEventListener("click", function () {
            changePasswordToggle();
            values = {
                type: 'password',
                oldPassword: document.querySelector("#profilePasswordInputs .oldPasswordInput").value,
                newPassword: document.querySelector("#profilePasswordInputs .newPasswordInput").value
            }
            sendChanges(values);
        })

        cancelPasswordChange.addEventListener("click", function () {
            changePasswordToggle();
            let inputs = document.querySelectorAll("#profilePasswordInputs input");
            clearInputs(inputs);
        })

        const deleteProfileImageIcon = document.getElementById("deleteProfileImageIcon");
        const editProfileImageIcon = document.getElementById("editProfileImageIcon");

        if (deleteProfileImageIcon) {
            deleteProfileImageIcon.addEventListener("click", function () {
                sendChangesForms(new FormData());
            })
        }
        if (editProfileImageIcon) {

            editProfileImageIcon.addEventListener("click", function () {
                console.log("Editing");
                // Create temp input type file
                const tempInput = document.createElement("input");
                tempInput.name = "imageFile";
                tempInput.type = "file";
                tempInput.accept = "image/png, image/jpg, image/jpeg";
                tempInput.setAttribute("enctype", "multipart/form-data");

                tempInput.addEventListener("change", (e) => {
                    let file = e.target.files[0];
                    console.log("The file:", file);
                    let formData = new FormData();
                    formData.append('type', 'img');
                    formData.append('newImage', file);

                    sendChangesForms(formData);
                })
                tempInput.click();
            })

        }

        let profilenameSearch = document.querySelector(".left-call #name");
        let profileminpriceInput = document.querySelector(".left-call .input-min");
        let profilemaxpriceInput = document.querySelector(".left-call .input-max");
        let profilesortOptionSelect = document.querySelector(".left-call .order .sortOption");


        profilenameSearch.addEventListener("keyup", function () {
            performsearchProfile();
        });

        profileminpriceInput.addEventListener("keyup", function () {
            if (profileminpriceInput.value !== "") {
                performsearchProfile();
            }
        });

        profilemaxpriceInput.addEventListener("keyup", function () {
            if (profilemaxpriceInput.value !== "") {
                performsearchProfile();
            }
        });

        profilesortOptionSelect.addEventListener("change", function () {
            performsearchProfile();
        });

        function performsearchProfile() {
            updateProductsProfile(profilenameSearch.value, profilesortOptionSelect.value, profileminpriceInput.value, profilemaxpriceInput.value);
        }

        function updateProductsProfile(name, sortOption, minprice, maxprice) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "../ajax/update_productsProfile.php", true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let response = JSON.parse(xhr.responseText);
                        console.log(response);
                        drawUIProfile(response);

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

        function drawUIProfile(response) {
            let productsContainer = document.querySelector("#profile-products");

            productsContainer.innerHTML = "";
            if (response.productListHTML.length === 0) {
                let noProductFound = document.createElement("div");
                noProductFound.textContent = "No products found.";
                productsContainer.appendChild(noProductFound);
            } else {
                response.productListHTML.forEach(function (product) {
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
    })

}