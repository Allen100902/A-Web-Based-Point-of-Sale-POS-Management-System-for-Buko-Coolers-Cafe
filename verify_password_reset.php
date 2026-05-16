<?php
    date_default_timezone_set("Asia/Manila");
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Verify Password Reset - Buko Coolers Cafe</title>
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
            <form class="login_form" action="verify_password_reset_action.php" method="POST">
                <div class="form_row">
                    <h1>PASSWORD RESET</h1>
                </div>
                <div class="form_row">
                    <label for="username">Administrator Username</label>
                </div>
                <div class="form_row">
                    <input type="text" name="usrname" placeholder="Enter Username">
                </div>
                <div class="form_buttons">
                    <button class="verify_btn" name="verifyreset">VERIFY</button>
                    <a class="cancel_btn" href="index.php">CANCEL</a>
                </div>
            </form>
        </div>
    </body>
</html>