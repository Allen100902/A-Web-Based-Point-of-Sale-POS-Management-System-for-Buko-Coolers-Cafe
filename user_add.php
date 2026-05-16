<?php
    include ('database_connection.php');
    SESSION_START();

    date_default_timezone_set("Asia/Manila");

    if (isset($_POST['save_ret'])){
        $USER_FNAME = $_POST['first_name'];
        $USER_LNAME = $_POST['last_name'];
        $USER_EMAIL = $_POST['email'];
        $USER_PNUM = $_POST['phone_num'];
        $USER_ADDR = $_POST['useraddr'];
        $USER_NAME = $_POST['user_name'];
        $USER_PASSWORD = $_POST['passwd'];
        $USER_ROLE = $_POST['role_select'];

        $USR_ADD = "INSERT INTO tbl_users(FIRST_NAME, LAST_NAME, EMAIL, PNUM, USR_ADD, USERNAME, USER_PASSWORD, ROLE) VALUES ('{$USER_FNAME}', '{$USER_LNAME}', '{$USER_EMAIL}', '{$USER_PNUM}', '{$USER_ADDR}', '{$USER_NAME}','{$USER_PASSWORD}','{$USER_ROLE}')";
        $EXEC_USR_ADD = mysqli_query($databaseconn, $USR_ADD);

        if (!$USR_ADD) {
            echo "ERR_DB_2: Operation failed: " . mysqli_error($databaseconn);
        } else {
            echo "<script type='text/javascript'>window.location.href = 'user_list.php';</script>";
            exit();
        }
    }

    if (isset($_SESSION['PK_USER'])){
        $loggeduser = $_SESSION['PK_USER'];
        $query_loggeduser = "SELECT * FROM tbl_users WHERE PK_USER = {$loggeduser} AND EMPLOYEE_STATUS = 'ACTIVE'";
        $loggeduser_check = mysqli_query($databaseconn, $query_loggeduser);

        while ($row = mysqli_fetch_assoc($loggeduser_check)){
            $return_userid = $row['PK_USER'];
            $return_userfname = $row['FIRST_NAME'];
            $return_userlname = $row['LAST_NAME'];
            $return_useremail = $row['EMAIL'];
            $return_userpnum = $row['PNUM'];
            $return_useraddr = $row['USR_ADD'];
            $return_username = $row['USERNAME'];
            $return_password = $row['USER_PASSWORD'];
            $return_role = $row['ROLE'];
            $return_status = $row['EMPLOYEE_STATUS'];
            $return_user_udate = $row['LAST_USR_UDATE'];
        }
    }
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add User Account - Buko Coolers Cafe</title>
        <link rel="stylesheet" type="text/css" href="styles/dashboard.css">
        <link rel="stylesheet" type="text/css" href="styles/dataentry_style.css">
        <link rel="stylesheet" type="text/css" href="styles/dashboard_style.css">
        <link rel="icon" href="templates/designs/logo.png" type="image/x-icon">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    </head>

    <body>
        <div class="main_container">
            <header>
                <div id="navleft" class="show_navleft">
                    <span class="toggle-btn" onclick="toggleNav()">&#9776;</span>
                </div>
                <div class="header-content">
                    <div class="header-left">
                        <img src="templates/designs/logo_nonbg.png" alt="Buko Coolers Logo" class="logo">
                        <h1>Buko Coolers Cafe</h1>  
                    </div>
                </div>
            </header>
            <div id="sidenav" class="side_nav">
                <?php if ($return_role === 'Administrator'){ 
                    echo "<a href='dashboard.php'><i class='material-icons'>home</i> Dashboard</a>
                    <button class='sales-dropdown' type='button'><i class='material-icons'>shopping_cart</i> Sales and Ordering <i class='material-icons'>arrow_drop_down</i></button>
                    <div class='dropdown_container'>
                        <a href='order_create.php'>Create Order</a>
                        <a href='order_history.php'>Order History</a>
                    </div>
                    <button class='reports-dropdown' type='button'><i class='material-icons'>show_chart</i> Reports and Analytics <i class='material-icons'>arrow_drop_down</i></button>
                    <div class='dropdown_container'>
                        <a href='sales_report_list.php'>Sales Report</a>
                        <a href='inventory_report.php'>Inventory Report</a>
                        <a href='raw_material_usage.php'>Raw Material Usage</a>
                        <a href='best_seller_analytics.php'>Best Sellers</a>
                    </div>
                    <button class='product-dropdown' type='button'><i class='material-icons'>storage</i> Product Management <i class='material-icons'>arrow_drop_down</i></button>
                    <div class='dropdown_container'>
                        <a href='product_category_list.php'>Product Category</a>
                        <a href='product_list.php'>Product List</a>
                        <a href='product_ingredient_list.php'>Ingredient Mapping</a>
                        <a href='stock_level_config_view.php'>Stock Levels</a>
                    </div>
                    <button class='inventory-dropdown' type='button'><i class='material-icons'>assignment</i> Inventory <i class='material-icons'>arrow_drop_down</i></button>
                    <div class='dropdown_container'>
                        <a href='inventory_raw_list.php'>Raw Materials</a>
                        <a href='inventory_pastry_list.php'>Pastries</a>
                    </div>
                    <button class='maint-dropdown' type='button'><i class='material-icons'>settings</i> Maintenance <i class='material-icons'>arrow_drop_down</i></button>
                    <div class='dropdown_container'>
                        <a href='inventory_item_list.php'>Inventory Item</a>
                        <a href='user_list.php'>User Management</a>
                        <a href='admin_notifications.php'>All Notifications</a>
                    </div>
                    <button class='archive-dropdown' type='button'><i class='material-icons'>archive</i> Archives <i class='material-icons'>arrow_drop_down</i></button>
                    <div class='dropdown_container'>
                        <a href='user_list_archive.php'>Users</a>
                        <a href='product_category_list_archive.php'>Product Category</a>
                        <a href='product_list_archive.php'>Product List</a>
                        <a href='inventory_raw_list_archive.php'>Inventory Raw Materials</a>
                        <a href='inventory_pastry_list_archive.php'>Inventory Pastry Item</a>
                        <a href='inventory_item_list_archive.php'>Inventory Item</a>
                    </div>";
                } else if ($return_role === 'Staff') {
                    echo "<a href='dashboard.php'><i class='material-icons'>home</i> Dashboard</a>
                    <button class='sales-dropdown' type='button'><i class='material-icons'>shopping_cart</i> Sales and Ordering <i class='material-icons'>arrow_drop_down</i></button>
                    <div class='dropdown_container'>
                        <a href='order_create.php'>Create Order</a>
                        <a href='order_history.php'>Order History</a>
                    </div>";
                }?>
                <a href="logout_action.php" onclick="return confirm('Are you sure do you want to logout?');"><i class="material-icons">exit_to_app</i> Logout</a>
            </div>
            <footer>
                <div class="footer1">
                    <h3></h3>  
                </div>
            </footer>
        </div>


        <div class="form_container">
            <h1>ADD USER ACCOUNT</h1>
            <div class="user_form">
                <form class="user_data_entry" method="POST" action="">
                    <fieldset class="form_users">
                        <legend><h3>User Information</h3></legend>
                        <div style="overflow-y: auto; height: 400px;">
                            <div class="form_row">
                                <label for="FirstName">First Name</label>
                                <input type="text" name="first_name" id="first_name" required>
                            </div>
                            <div class="form_row">
                                <label for="LastName">Last Name</label>
                                <input type="text" name="last_name" id="last_name" required>
                            </div>
                            <div class="form_row">
                                <label for="Email">Email</label>
                                <input type="text" name="email" id="email_add" required>
                            </div>
                            <div class="form_row">
                                <label for="PNum">Phone Number</label>
                                <input type="number" name="phone_num" id="phone" required>
                            </div>
                            <div class="form_row">
                                <label for="UserAddr">User Address</label>
                                <input type="text" name="useraddr" id="addr" required>
                            </div>
                            <div class="form_row">
                                <label for="UserName">Username</label>
                                <input type="text" name="user_name" id="user_name" required>
                            </div>
                            <div class="form_row">
                                <label for="Password">Password</label>
                                <input type="text" name="passwd" id="password" required>
                            </div> 
                            <div class="form_row">
                                <label for="Role">Role</label>
                                <select name="role_select" id="roles" required>
                                    <option value="">Select</option>
                                    <option value="Administrator">Administrator</option>
                                    <option value="Staff">Staff</option>
                                </select>
                            </div>
                        </div>
                    </fieldset>
                    <div class="form_buttons">
                        <button type="reset" class="reset_btn">Clear Fields</button>
                        <button type="submit" class="take_action" name="save_ret">Add User</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="header-right">
            <p>Logged as: <?php echo htmlspecialchars($return_username); ?> (<?php echo htmlspecialchars($return_role); ?>)</p>
        <div>
        <script type="text/javascript" src="templates/template_scripts/dropdown_script.js"></script>
        <script type="text/javascript" src="templates/template_scripts/sidenav_script.js"></script>
    </body>
</html>