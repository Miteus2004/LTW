<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/e5d9e3bc4f.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/homeScreen.css">
    <link rel="stylesheet" href="../css/loginScreen.css">
    <script src="../javascript/login.js"></script>
    <script src="../javascript/utils.js"></script>

    <title>Login</title>
</head>

<body>


    <main>
        <div class="logo-home">
            <img src="../images/logo.png" alt="">
            <h1>SecondWave</h1>
        </div>
        <div class="content">
            <header>
                <a href="" id="loginTitle"> Login</a>
                <a href="registerScreen.php" id="Register Title"> Register</a>
            </header>

            <form action="../actions/action_login.php" method="post">

                <input type="email" id="loginEmailInput" name="email" class="input-box" autocomplete="off" placeholder="Email"><br>

                <div class="password">
                    <input type="password" id="loginPasswordInput" name="password" class="input-box visibility" autocomplete="off" placeholder="Password"><br>
                    <span class="eyes" id="toggleLoginPasswordButton">
                        <i class="fa-solid fa-eye" id="openEye"></i>
                        <i class="fa-solid fa-eye-slash" id="closedEye"></i>
                    </span>
                </div>

                <input type="submit" value="Enter" class="button">

            </form>
        </div>


    </main>


</body>

</html>