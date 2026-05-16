<?php
    include "database_connection.php";
    SESSION_START();

    date_default_timezone_set("Asia/Manila");

    if (isset($_POST['usrname']) && isset($_POST['password'])){

        function validate($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $validate_username = validate($_POST['usrname']);
        $validate_password = validate($_POST['password']);
    
        if (empty($validate_username)){
            echo "<script type='text/javascript'>alert('ERR_AUTH_1: Username is required'); 
            window.location.href = '" . $_SERVER['HTTP_REFERER'] . "';
            </script>";
            exit();
        }

        else if (empty($validate_password)){
            echo "<script type='text/javascript'>alert('ERR_AUTH_2: Password is required'); 
            window.location.href = '" . $_SERVER['HTTP_REFERER'] . "';
            </script>";
            exit();
        }

        else{
            $sql = "SELECT * FROM tbl_users WHERE USERNAME = '$validate_username' AND USER_PASSWORD = '$validate_password' AND EMPLOYEE_STATUS = 'ACTIVE'";

            $res_query = mysqli_query($databaseconn, $sql);

            if (mysqli_num_rows($res_query) === 1){
                $row = mysqli_fetch_assoc($res_query);

                if ($row['USERNAME'] === $validate_username && $row['USER_PASSWORD']){
                    $_SESSION['PK_USER'] = $row['PK_USER'];
                    $_SESSION['USERNAME'] = $row['USERNAME'];
                    header('Location: dashboard.php');
                }
    
                else{
                    echo "<script type='text/javascript'>alert('ERR_AUTH_3: Invalid username or password!'); 
                    window.location.href = '" . $_SERVER['HTTP_REFERER'] . "';
                    </script>";
                    exit();
                }
            }
            
            else{
                echo "<script type='text/javascript'>alert('ERR_AUTH_3: Invalid username or password!'); 
                window.location.href = '" . $_SERVER['HTTP_REFERER'] . "';
                </script>";
                exit();
            }
        }
    }

    else{
        header("Location: index.php");
        exit();
    }

?>