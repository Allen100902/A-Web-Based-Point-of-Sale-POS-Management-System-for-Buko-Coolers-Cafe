<?php
    include ('database_connection.php');
    SESSION_START();

    date_default_timezone_set("Asia/Manila");

    $show_inventory_rm = "SELECT PK_INVENTORY_ITEM, INVENTORY_ITEM_CODE, INVENTORY_ITEM_NAME, INVENTORY_ITEM_AMOUNT, INVENTORY_ITEM_UNITS, INVENTORY_ITEM_COG, INVENTORY_ITEM_EXP, DATE_FORMAT(DATE_ADD(INVENTORY_ITEM_TIMESTAMP, INTERVAL 8 HOUR), '%Y-%m-%d') AS 'INVENTORY_ITEM_TIMESTAMP', STATUS_INVENTORY_ITEM FROM tbl_inventory WHERE INVENTORY_ITEM_CODE LIKE 'RAW%' AND STATUS_INVENTORY_ITEM = 'ARCHIVED' ORDER BY INVENTORY_ITEM_EXP DESC";
    $exec_showinventory_rm = mysqli_query($databaseconn, $show_inventory_rm);

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
        <title>Raw Material Inventory Items Archive - Buko Coolers Cafe</title>
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
            <h1>RAW MATERIAL INVENTORY LIST ARCHIVE</h1>
            <div class="table_container_nouppr">
                <div style="overflow-y: auto; height: 470px;">
                    <table class="dataview">
                        <thead>
                            <tr>
                                <th>Raw Material Code</th>
                                <th>Raw Material Description</th>
                                <th>Raw Material Amount</th>
                                <th>Raw Material Units</th>
                                <th>Raw Material Cost of Goods</th>
                                <th>Raw Material Expiry Date</th>
                                <th>Last Archived</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody id="table_body">
                            <tr>
                                <?php


                                    if (mysqli_num_rows($exec_showinventory_rm) > 0) {
                                        while($row = mysqli_fetch_assoc($exec_showinventory_rm)){
                                            $ret_rm_id = $row['PK_INVENTORY_ITEM'];
                                            $ret_rm_code = $row['INVENTORY_ITEM_CODE'];
                                            $ret_rm_name = $row['INVENTORY_ITEM_NAME'];
                                            $ret_rm_amt = $row['INVENTORY_ITEM_AMOUNT'];
                                            $ret_rm_units = $row['INVENTORY_ITEM_UNITS'];
                                            $ret_rm_cog = $row['INVENTORY_ITEM_COG'];
                                            $ret_rm_exp = $row['INVENTORY_ITEM_EXP'];
                                            $ret_rm_udate = $row['INVENTORY_ITEM_TIMESTAMP'];
                                            $ret_rm_status = $row['STATUS_INVENTORY_ITEM'];
                    
                                            echo "<tr>";
                                            echo "<td>$ret_rm_code</td>";
                                            echo "<td>$ret_rm_name</td>";

                                            if ($ret_rm_amt >= 1000 && $ret_rm_units === "Mililiters"){
                                                $conv_unit_ml = $ret_rm_amt / 1000;
                                                echo "<td>$conv_unit_ml</td>";
                                            } else if ($ret_rm_amt >= 1000 && $ret_rm_units === "Grams") {
                                                $conv_unit_gram = $ret_rm_amt * 0.001;
                                                echo "<td>$conv_unit_gram</td>";
                                            } else {
                                                echo "<td>$ret_rm_amt</td>";
                                            }

                                            if ($ret_rm_amt >= 1000 && $ret_rm_units === "Mililiters"){
                                                echo "<td>Liters</td>";
                                            } else if ($ret_rm_amt >= 1000 && $ret_rm_units === "Grams") {
                                                echo "<td>Kilograms</td>";
                                            } else {
                                                if ($ret_rm_units === "Grams"){
                                                    echo "<td>Grams</td>";
                                                } else if ($ret_rm_units === "Mililiters") {
                                                    echo "<td>Mililiters</td>";
                                                } else if ($ret_rm_units === "Piece") {
                                                    echo "<td>Piece</td>";
                                                }
                                            }

                                            echo "<td>₱". $ret_rm_cog . ".00</td>";
                                            echo "<td>$ret_rm_exp</td>";
                                            echo "<td>$ret_rm_udate</td>";
                                            echo "<td>$ret_rm_status</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr>
                                        <td colspan='7'>No Archived Raw Material items found.</td>
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
            <p>Logged as: <?php echo htmlspecialchars($return_username); ?> (<?php echo htmlspecialchars($return_role); ?>)</p>
        <div>
        <script type="text/javascript" src="templates/template_scripts/dropdown_script.js"></script>
        <script type="text/javascript" src="templates/template_scripts/sidenav_script.js"></script>
    </body>
</html>