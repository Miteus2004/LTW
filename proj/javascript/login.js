


document.addEventListener("DOMContentLoaded", function () {

    if (window.location.pathname === "/pages/loginScreen.php") {


        const visibilityButton = document.getElementById("toggleLoginPasswordButton");

        let show = false;
        visibilityButton.addEventListener("click", function () {
            const textInput = document.getElementById("loginPasswordInput");
            const closedEye = document.getElementById("closedEye");
            const openEye = document.getElementById("openEye");
            if (show) {
                textInput.type = 'password';
                closedEye.style.display = 'inline';
                openEye.style.display = 'none';
                show = false;
            } else {
                textInput.type = 'text';
                closedEye.style.display = 'none';
                openEye.style.display = 'inline';
                show = true;
            }
        })

    }


}

)