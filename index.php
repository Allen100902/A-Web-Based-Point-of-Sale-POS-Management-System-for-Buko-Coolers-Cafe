<?php
    date_default_timezone_set("Asia/Manila");
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login - Buko Coolers Cafe</title>
        <link rel="stylesheet" type="text/css" href="styles/index.css">
        <link rel="stylesheet" type="text/css" href="styles/index_style.css">
        <link rel="icon" href="templates/designs/logo.png" type="image/x-icon">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>

    <body>
        <div class="index_container">
            <img src="templates/designs/logo_nonbg.png" alt="Buko Coolers Logo" class="logo">
        </div>
        <div class="login_container">
            <form class="login_form" action="login_action.php" method="POST">
                <div class="form_row">
                    <h1>LOGIN</h1>
                </div>
                <div class="form_row">
                    <label for="username">Username:</label>
                    <input type="text" name="usrname" placeholder="Enter Username">
                </div>
                <div class="form_row">
                    <label for="username">Password:</label>
                    <input type="password" name="password" id="password_visible" placeholder="Enter Password">
                </div>
                <div class="forgot_password">
                    <input type="checkbox" onclick="pass_show()"> Show Password
                </div>     
                <div class="form_buttons">
                    <button class="login_btn" name="confirmlogin">Login</button>
                    <a class="resetpass_btn" href="verify_password_reset.php">Forgot Password</a>
                </div>
            </form>
        </div>
        <script type="text/javascript" src="scripts/password_script.js"></script>
    </body>
</html>