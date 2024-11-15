<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/homeScreen.css">
    <link rel="stylesheet" href="../css/registerScreen.css">
    <script src="../javascript/login.js"></script>
    <script src="../javascript/utils.js"></script>


    <title>Register</title>
</head>

<body>


    <main>
        <div class="logo-home">
            <img src="../images/logo.png" alt="">
            <h1>SecondWave</h1>
        </div>
        <div class="content">
            <header>
                <a href="loginScreen.php" id="loginTitle"> Login</a>
                <a href="" id="registerTitle"> Register</a>
            </header>

            <form action="../actions/action_register.php" method="post">

                <input type="text" name="name" id="Name" class="input-box" autocomplete="off" placeholder="Name"><br>
                <input type="text" name="username" id="Username" class="input-box" autocomplete="off" placeholder="Username"><br>
                <input type="email" name="email" id="email" class="input-box" autocomplete="off" placeholder="Email"><br>
                <input type="text" name="loc" id="loc" class="input-box" autocomplete="off" placeholder="Location"><br>
                <input type="password" name="password" id="password" class="input-box" autocomplete="off" placeholder="Password"><br>
                <input type="password" id="confirmPassword" class="input-box" autocomplete="off" placeholder="Confirm Password"><br>

                <input type="submit" value="Enter" class="button">
            </form>
        </div>


    </main>


</body>

</html>