<?php
    include "database_connection.php";
    SESSION_START();

    date_default_timezone_set("Asia/Manila");

    if (isset($_POST['usrname'])){

        function validate($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $validate_username = validate($_POST['usrname']);
    
        if (empty($validate_username)){
            echo "<script type='text/javascript'>alert('Username is required'); 
            window.location.href = '" . $_SERVER['HTTP_REFERER'] . "';
            </script>";
            exit();
        }

        else{
            $sql = "SELECT * FROM tbl_users WHERE USERNAME = '$validate_username' AND ROLE = 'Administrator' AND EMPLOYEE_STATUS = 'ACTIVE'";

            $res_query = mysqli_query($databaseconn, $sql);

            if (mysqli_num_rows($res_query) === 1){
                $row = mysqli_fetch_assoc($res_query);

                if ($row['USERNAME'] === $validate_username){
                    $_SESSION['PK_USER'] = $row['PK_USER'];
                    $_SESSION['USERNAME'] = $row['USERNAME'];
                    header('Location: reset_password.php');
                    echo "test";
                }
            }
            
            else{
                echo "<script type='text/javascript'>alert('ERR_AUTH_4: Non-administrator account detected! Contact the administrator to reset your staff account.'); 
                window.location.href = '" . $_SERVER['HTTP_REFERER'] . "';
                </script>";
                exit();
            }
        }
    }

    else{
        header("Location index.php");
        exit();
    }

?>