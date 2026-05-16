<?php
    include "database_connection.php";
    SESSION_START();

    date_default_timezone_set("Asia/Manila");

    if (isset($_SESSION['PK_USER'])){
        $UID = $_SESSION['PK_USER'];
        $query = "SELECT * FROM tbl_users WHERE PK_USER = {$UID}";
        $UID_check = mysqli_query($databaseconn, $query);

        while ($row = mysqli_fetch_assoc($UID_check)){
            $return_uid = $row['PK_USER'];
            $return_userfname = $row['FIRST_NAME'];
            $return_userlname = $row['LAST_NAME'];
            $return_useremail = $row['EMAIL'];
            $return_userpnum = $row['PNUM'];
            $return_useraddr = $row['USR_ADD'];
            $return_username = $row['USERNAME'];
            $return_userpassword = $row['USER_PASSWORD'];
            $return_userrole = $row['ROLE'];
            $return_status = $row['EMPLOYEE_STATUS'];
            $return_user_udate = $row['LAST_USR_UDATE'];
        }
    }
    
    if (isset($_POST['confirmreset'])){
        $password_new = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];

        if ($password_new === $password_confirm){
            $query = "UPDATE tbl_users SET USER_PASSWORD = '{$password_confirm}', LAST_USR_UDATE = NOW() WHERE PK_USER = {$return_uid}";
            $reset_password = mysqli_query($databaseconn, $query);

            SESSION_UNSET();
            SESSION_DESTROY();
    
            header("Location: index.php");
        }
    }

    else if (isset($_POST['cancelreset'])){
        echo "<script type='text/javascript'>window.location.href = 'logout_action.php';</script>";
        exit();
    }
?>
<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reset Password - Buko Coolers Cafe</title>
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
            <form class="login_form" action="" method="POST">
                <div class="form_row">
                    <h1>RESET PASSWORD</h1>
                </div>
                <div class="form_row">
                    <label for="username">Enter New Password</label>
                    <input type="password" name="password" id="password_visible" placeholder="Enter New Password">
                </div>
                <div class="form_row">
                    <label for="username">Retype New Password</label>
                    <input type="password" name="password_confirm" id="password_visible_retype" placeholder="Retype New Password">
                </div>
                <div class="forgot_password">
                    <input type="checkbox" onclick="pass_show()"> Show Password
                </div>
                <div class="form_buttons">
                    <button class="resetpass" name="confirmreset">RESET PASSWORD</button>
                    <button class="cancelpass" name="cancelreset">CANCEL</button>
                </div>
            </form>
        </div>
        <script type="text/javascript" src="scripts/password_script.js"></script>
    </body>
</html>