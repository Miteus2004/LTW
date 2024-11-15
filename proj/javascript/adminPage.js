document.addEventListener("DOMContentLoaded", function () {
    if (window.location.pathname === "/pages/adminPage.php") {

        // CATEGORY STUFF

        // AJAX communication
        function processCategory(categoryName, remove, callBack) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "../ajax/update_categoriesAdmin.php", true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let response = JSON.parse(xhr.responseText);
                        console.log("The response:", response);
                        console.log("The response compared", response[0] !== "error");
                        if (response.status !== "error") {
                            callBack(categoryName);
                            updateHistory(remove, categoryName, response.user);
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
            xhr.send(JSON.stringify({ category: categoryName, remove: remove }));
        }

        // Update the History
        function updateHistory(remove, categoryName, user) {
            const history = document.getElementById("adminlog");
            const wrapper = document.createElement("div");
            wrapper.classList.add("admin-change");
            const userImage = document.createElement("img");
            if (user.hasPhoto) userImage.src = "../images/users/" + user.id + ".jpg";
            else userImage.src = "../images/users/default.jpg";
            const info = document.createElement("div");
            info.classList.add("admin-change-details");
            const userName = document.createElement("h3");
            console.log("The username: ", user.username);
            userName.textContent = user.username;
            const change = document.createElement("p");
            if (remove) {
                change.textContent = "Removed Category: " + categoryName;
            } else {
                change.textContent = "Added Category: " + categoryName;
            }
            info.appendChild(userName);
            info.appendChild(change);
            wrapper.appendChild(userImage);
            wrapper.appendChild(info);
            history.insertBefore(wrapper, history.firstElementChild);

        }
        // Put the event listener in all the X's already
        const categoryList = document.querySelector(".admin-categories");
        const eliminateButton = document.querySelectorAll(".admin-categories i");
        eliminateButton.forEach(button => {
            button.addEventListener("click", function () {
                const categoryName = button.previousElementSibling.textContent;
                const wrapper = button.parentElement;
                console.log("The name: ", categoryName);
                processCategory(categoryName, true, () => { categoryList.removeChild(wrapper) });
            })
        })




        function addCategory(categoryName) {

            const newCategory = document.createElement("div");
            newCategory.classList.add("admin-categories-choices");
            const removeIcon = document.createElement("i");
            removeIcon.classList.add("fa-solid", "fa-xmark");
            const name = document.createElement("p");
            name.textContent = categoryName;

            newCategory.append(name);
            newCategory.append(removeIcon);
            const categoryList = document.querySelector(".admin-categories");
            categoryList.append(newCategory);

            // Add button logic to remove category
            removeIcon.addEventListener("click", function () {
                processCategory(categoryName, true, () => { categoryList.removeChild(newCategory) });
            })
        }

        const categoryInput = document.getElementById("newCategoryInput");

        categoryInput.addEventListener("keypress", function (event) {

            if (event.key === "Enter") {

                const categoryName = categoryInput.value;
                if (categoryName === "") return;
                categoryInput.value = "";

                processCategory(categoryName, false, addCategory)
            }
        })


        // User stuff

        function drawUsers(users) {
            const usersList = document.getElementById("users");
            usersList.innerHTML = "";
            if (users.length === 0) {
                const message = document.createElement("p");
                message.textContent = "No Users found!";
                usersList.appendChild(message);
            } else {

                users.forEach(user => {
                    const userWrapper = document.createElement("div");
                    userWrapper.classList.add("user");
                    const userImage = document.createElement("img");
                    if (user.hasPhoto) userImage.src = "../images/users/" + user.id + ".jpg";
                    else userImage.src = "../images/users/default.jpg";
                    const userName = document.createElement("h3");
                    userName.textContent = user.username;
                    userWrapper.appendChild(userImage);
                    userWrapper.appendChild(userName);
                    usersList.appendChild(userWrapper);
                })
            }
        }


        const userInput = document.getElementById("userSearchInput");
        userInput.addEventListener("keyup", function () {
            let search = userInput.value;
            console.log(search);
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "../ajax/update_usersAdmin.php", true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let response = JSON.parse(xhr.responseText);
                        if (response.status !== "error") {
                            drawUsers(response.users);
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
            xhr.send(JSON.stringify({ search: search }));
        })
    }

}
)
