<?php
    include ('database_connection.php');
    SESSION_START();

    date_default_timezone_set("Asia/Manila");

    $archive_check = "SELECT PROD_CATEGORY, PROD_NAME, PROD_PRICE, PROD_IMAGE, STATUS, DATE_FORMAT(DATE_ADD(PROD_TIMESTAMP, INTERVAL 8 HOUR), '%Y-%m-%d') AS 'PROD_TIMESTAMP' FROM tbl_products_list WHERE STATUS = 'ARCHIVED' ORDER BY PROD_TIMESTAMP DESC";
    $exec_showarchive = mysqli_query($databaseconn, $archive_check);

    if (isset($_SESSION['PK_USER'])){
        $loggeduser = $_SESSION['PK_USER'];
        $query_loggeduser = "SELECT * FROM tbl_users WHERE PK_USER = {$loggeduser} AND EMPLOYEE_STATUS = 'ACTIVE'";
        $loggeduser_check = mysqli_query($databaseconn, $query_loggeduser);

        while ($row = mysqli_fetch_assoc($loggeduser_check)){
            $return_user_id = $row['PK_USER'];
            $return_user_fname = $row['FIRST_NAME'];
            $return_user_lname = $row['LAST_NAME'];
            $return_user_email = $row['EMAIL'];
            $return_user_pnum = $row['PNUM'];
            $return_user_addr = $row['USR_ADD'];
            $return_user_name = $row['USERNAME'];
            $return_user_password = $row['USER_PASSWORD'];
            $return_role = $row['ROLE'];
            $return_user_status = $row['EMPLOYEE_STATUS'];
            $return_user_udate = $row['LAST_USR_UDATE'];
        }

        $show_users = "SELECT * FROM tbl_users WHERE NOT PK_USER = {$loggeduser}";
        $exec_showusers = mysqli_query($databaseconn, $show_users);
    }
?>
<!DOCTYPE html>
    <html lang="en">
    <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Products Archive - Buko Coolers Cafe</title>
        <link rel="stylesheet" type="text/css" href="styles/dashboard.css">
        <link rel="stylesheet" type="text/css" href="styles/viewdata_style.css">
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
            <h1>PRODUCTS ARCHIVE</h1>
            <div class="table_container_nouppr">
                <div style="overflow-y: auto; height: 450px;">
                    <table class="dataview">
                        <thead>
                            <tr>
                                <th>Product Category</th>
                                <th>Product Name</th>
                                <th>Product Price</th>
                                <th>Product Image</th>
                                <th>Product Status</th>
                                <th>Archive Date</th>
                            </tr>
                        </thead>
                        <tbody id="table_body">
                            <tr>
                                <?php
                                    if (mysqli_num_rows($exec_showarchive) > 0) {
                                        while($row = mysqli_fetch_assoc($exec_showarchive)){
                                            $ret_prod_cat = $row['PROD_CATEGORY'];
                                            $ret_prod_name = $row['PROD_NAME'];
                                            $ret_prod_price = $row['PROD_PRICE'];
                                            $ret_prod_image = $row['PROD_IMAGE'];
                                            $ret_status = $row['STATUS'];
                                            $ret_archive_date = $row['PROD_TIMESTAMP'];

                                            echo "<tr>";
                                            echo "<td>$ret_prod_cat</td>";
                                            echo "<td>$ret_prod_name</td>";
                                            echo "<td>₱" . $ret_prod_price . "</td>";
                                            echo "<td><img src='$ret_prod_image' width='100' height='100' alt='Image Error.'></td>";
                                            echo "<td>$ret_status</td>";
                                            echo "<td>$ret_archive_date</td>";
                                        }
                                    } else {
                                        echo "<tr>
                                            <td colspan='6'>No archived product categories found.</td>
                                            </tr>";
                                    }
                                ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="header-right">
            <p>Logged as: <?php echo htmlspecialchars($return_user_name); ?> (<?php echo htmlspecialchars($return_role); ?>)</p>
        <div>
        <script type="text/javascript" src="scripts/datecontrol_formhandler.js"></script>
        <script type="text/javascript" src="templates/template_scripts/dropdown_script.js"></script>
        <script type="text/javascript" src="templates/template_scripts/sidenav_script.js"></script>
    </body>
</html>