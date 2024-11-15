

if (window.location.pathname === "/pages/editProduct.php" || window.location.pathname === "/pages/newProduct.php") {
    console.log("Entered");

    document.addEventListener("DOMContentLoaded", function () {
        // Error handling

        function showInputError(input, message = "") {
            console.log("Error detected");
            input.style.borderBottom = "4px solid red";
            input.style.borderLeft = "none";
            input.style.borderTop = "none";
            input.style.borderRight = "none";

            var outputMessage = input.nextElementSibling.querySelector(".errorMessage");
            outputMessage.style.visibility = "visible";
            outputMessage.textContent = message;
        }

        function cleanInputError(input, correct) {
            input.style.border = "none";
            if (correct) input.style.borderBottom = "4px solid green";


            var outputMessage = input.nextElementSibling.querySelector(".errorMessage");
            outputMessage.style.visibility = "hidden";
        }

        function validSize(input) {
            var text = input.value;
            var textLength = text.length;
            var enterCount = text.split('\n').length - 1;
            var output = input.nextElementSibling.querySelector(".numberLetters");
            var limit = output.textContent.split("/")[1];
            output.textContent = textLength - enterCount + "/" + limit

            if (textLength > limit) {
                showInputError(input, "Too many letters")
                return true;
            } else if (textLength === 0) {
                cleanInputError(input, false);
                return true;
            } else {
                cleanInputError(input, true);
                return false;
            }
        }
        // Keep track of each input error
        let priceError = false;
        let nameError = false;
        let descriptionError = false;

        let nameInput = document.getElementById("productNameInput");
        let descriptionInput = document.getElementById("productDescriptionInput");
        const selectInput = document.querySelectorAll(".contentWrapper select");
        let numberInput = document.getElementById("priceInput");

        nameInput.addEventListener("input", function () {
            nameError = validSize(nameInput);
        })

        descriptionInput.addEventListener("input", function () {
            descriptionError = validSize(descriptionInput);
        })

        selectInput.forEach(input => {
            input.addEventListener("change", function () {
                input.style.border = "none";
            })
        })

        numberInput.addEventListener("input", function () {
            const regex = /^\d+(,\d{1,2})?$/;
            const price = numberInput.value;
            if (price === "") {
                cleanInputError(numberInput, false);
            } else {

                if (regex.test(price)) {
                    cleanInputError(numberInput, true);
                    priceError = false;
                } else {
                    priceError = true;
                    showInputError(numberInput, "Price format: 67,50");
                }
            }
        });



        // Photo logic

        const photosWrapper = document.querySelectorAll("#imageList .photoPreview");
        const imageList = document.getElementById("imageList");
        const uploadButton = document.getElementById("photoUploadButton");
        let addedPhotos = photosWrapper.length;
        let photoNumber = photosWrapper.length;
        let maximumPhotos = 10;
        let ignoredFiles = 0;
        const fileMaxSize = 2 * 1024 * 1024;

        // Process already existant images
        photosWrapper.forEach(wrap => {
            const deleteIcon = wrap.querySelector("i");
            deleteIcon.addEventListener("click", function () {
                wrap.remove();
                uploadButton.style.display = "flex";
                addedPhotos -= 1;
            })
        })

        uploadButton.addEventListener("click", () => {
            uploadButton.style.border = "none";
            // Create input for the images
            const tempInput = document.createElement("input");
            tempInput.type = "file";
            tempInput.accept = "image/png, image/jpg, image/jpeg"
            tempInput.multiple = true;

            // Define what to do when loading file
            tempInput.addEventListener("change", (e) => {
                ignoredFiles = 0;
                for (i = 0; i < e.target.files.length; i++) {
                    const image = e.target.files[i];
                    if (image.size > fileMaxSize) {
                        ignoredFiles++;
                        continue;
                    }

                    const reader = new FileReader();
                    reader.onload = () => {

                        // Create the actual input that is going to be saved
                        const input = document.createElement("input");
                        input.type = "file";
                        input.accept = "image/png, image/jpg, image/jpeg"
                        input.name = "files[]";
                        photoNumber += 1;
                        const fileList = new DataTransfer();
                        fileList.items.add(image);
                        input.files = fileList.files;
                        input.style.display = "none";


                        // Get and create image element
                        const imageUrl = reader.result;
                        const img = document.createElement("img");
                        img.classList.add("productImage");
                        img.src = imageUrl;

                        // Create trash icon
                        const icon = document.createElement("i");
                        icon.classList.add("fa-solid", "fa-trash");

                        // Create wrapper div
                        const imagePreviewElem = document.createElement("div");
                        imagePreviewElem.classList.add("photoPreview");

                        // Add elements to div
                        imagePreviewElem.append(img);
                        imagePreviewElem.append(icon);
                        imagePreviewElem.append(input);

                        // Insert new element to the page
                        imageList.insertBefore(imagePreviewElem, uploadButton);

                        // Create logic for button

                        icon.addEventListener("click", () => {
                            imageList.removeChild(imagePreviewElem);
                            uploadButton.style.display = "flex";
                            addedPhotos -= 1;
                        })

                    }
                    reader.readAsDataURL(image);
                    addedPhotos += 1;
                    console.log("atual - max");
                    console.log(addedPhotos, maximumPhotos);
                    if (addedPhotos == maximumPhotos) {
                        uploadButton.style.display = "none";
                        break;
                    }
                }
                console.log(ignoredFiles);
                if (ignoredFiles) {
                    showWarningMessage(ignoredFiles + " files ignored. Maximum size if 2MB.");
                }
            })
            tempInput.style.display = "none";
            tempInput.click();
        })


        const forms = document.getElementById("productForms");
        forms.addEventListener("submit", function (event) {
            event.preventDefault();
            let errorMessage = "";

            // Check the selects
            console.log(addedPhotos);
            if (addedPhotos === 0) {
                uploadButton.style.border = "2px solid red";
                errorMessage = "Please select at least one image";
            }

            const inputs = document.querySelectorAll(".productInput");
            inputs.forEach(input => {
                if (input.value === "") {
                    errorMessage = "Please fill all fields";
                    input.style.border = "2px solid red";
                }
            })

            if (priceError || nameError || descriptionError) {
                errorMessage = "Please fix the incorrect entries.";
            }


            if (errorMessage !== "") {
                showErrorMessage(errorMessage);
            } else {

                const action = forms.getAttribute("action");
                console.log(action);

                let xhr = new XMLHttpRequest();
                xhr.open("POST", action, true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            console.log(xhr.responseText);
                            let response = JSON.parse(xhr.responseText);
                            console.log(response);
                            if (!response.success) {
                                showErrorMessage("Error processing request");
                            }else{
                                window.location.href = "../pages/productInfo.php?id=" + response.productId;
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
                xhr.send(new FormData(forms));
            }

        })

    })

    function cancelForm() {
        window.location.href = "mainpage.php";
    }




}