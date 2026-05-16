<?php
    include ('database_connection.php');
    SESSION_START();

    date_default_timezone_set("Asia/Manila");

    $show_prodsales_mo = "SELECT SUM(ORDER_PRICE * ORDER_PRODUCT_QTY) AS 'ALL_TOTAL_SALES_MONTH' FROM tbl_order_items WHERE DATE_ADD(ORDER_ITEM_TIMESTAMP, INTERVAL 8 HOUR) >= DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
    AND DATE_ADD(ORDER_ITEM_TIMESTAMP, INTERVAL 8 HOUR) <  DATE_FORMAT(CURRENT_DATE() + INTERVAL 1 MONTH, '%Y-%m-01');";
    $exec_show_prodsales_mo = mysqli_query($databaseconn, $show_prodsales_mo);

    $notif_query = "SELECT NOTIF_TITLE, NOTIF_INFO, DATE_FORMAT(DATE_ADD(NOTIF_TIMESTAMP, INTERVAL 8 HOUR), '%Y-%m-%d %r') AS 'NOTIF_TIMESTAMP' from tbl_notifications WHERE DATE(NOTIF_TIMESTAMP) = CURRENT_DATE() ORDER BY NOTIF_ID DESC;";
    $exec_notifquery = mysqli_query($databaseconn, $notif_query);

    $inventory_search = "SELECT PK_INVENTORY_ITEM, INVENTORY_ITEM_CODE, INVENTORY_ITEM_NAME, SUM(INVENTORY_ITEM_AMOUNT) AS 'INVENTORY_ITEM_AMOUNT', INVENTORY_ITEM_UNITS, INVENTORY_ITEM_STOCK_MIN, INVENTORY_ITEM_COG, DATE_FORMAT(DATE_ADD(INVENTORY_ITEM_TIMESTAMP, INTERVAL 8 HOUR), '%Y-%m-%d %r') AS 'INVENTORY_ITEM_TIMESTAMP' FROM tbl_inventory WHERE STATUS_INVENTORY_ITEM = 'ACTIVE' GROUP BY INVENTORY_ITEM_CODE, INVENTORY_ITEM_NAME";
    $exec_showinventory = mysqli_query($databaseconn, $inventory_search);

    $curr_date = date("Y-m-d");
    $show_inventory_inv = "SELECT PK_INVENTORY_ITEM, INVENTORY_ITEM_CODE, INVENTORY_ITEM_NAME, INVENTORY_ITEM_AMOUNT, INVENTORY_ITEM_UNITS, INVENTORY_ITEM_STOCK_MIN, INVENTORY_ITEM_COG, INVENTORY_ITEM_EXP, DATE_FORMAT(DATE_ADD(INVENTORY_ITEM_TIMESTAMP, INTERVAL 8 HOUR), '%Y-%m-%d %r') AS 'INVENTORY_ITEM_TIMESTAMP', STATUS_INVENTORY_ITEM FROM tbl_inventory WHERE STATUS_INVENTORY_ITEM = 'ACTIVE' ORDER BY INVENTORY_ITEM_EXP ASC;";
    $exec_showinventory_inv = mysqli_query($databaseconn, $show_inventory_inv);

    $best_seller_query = "SELECT ORDER_PRODUCT_CATEGORY, ORDER_PRODUCT_NAME, SUM(ORDER_PRODUCT_QTY) AS 'PROD_QTY', ORDER_PRICE, 
    SUM(ORDER_PRICE * ORDER_PRODUCT_QTY) AS 'TOTAL_SALES' FROM `tbl_order_items` GROUP BY 
    ORDER_PRODUCT_NAME ORDER BY TOTAL_SALES DESC";
    $exec_best_seller = mysqli_query($databaseconn, $best_seller_query);

    $best_seller_total_query =  "SELECT SUM(ORDER_PRICE * ORDER_PRODUCT_QTY) AS 'ALL_TOTAL_SALES' FROM tbl_order_items";
    $exec_best_seller_total = mysqli_query($databaseconn, $best_seller_total_query);

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
        <title>Dashboard - Buko Coolers Cafe</title>
        <link rel="stylesheet" type="text/css" href="styles/dashboard.css">
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


        <div class="main">
            <div class="dashboard">
                <h1>DASHBOARD</h1>
            </div>
            <div class="total_sales_date_filter">
                <h2>Current Sales of the Month</h2>
                <?php
                    $CURR_MONTH_CHECK = date("F Y");

                    echo "<h3>$CURR_MONTH_CHECK</h3>";
                    while($row = mysqli_fetch_assoc($exec_show_prodsales_mo)){
                        $ret_total_sales_month = $row['ALL_TOTAL_SALES_MONTH'];
                    }

                    if ($ret_total_sales_month > 0){
                        echo "<p>₱". $ret_total_sales_month . "</p>";
                    } else {
                        echo "<p>₱0.00</p>";
                    }
                    
                ?>
            </div>
            <div class="incoming_notifications">
                <h2>Notifications for Today</h2>
                <div style="overflow-y: auto; height: 180px;">
                    <ul>
                    <?php
                        if (mysqli_num_rows($exec_notifquery) > 0) {
                            while ($row = mysqli_fetch_assoc($exec_notifquery)){
                                $return_notif_title = $row['NOTIF_TITLE'];
                                $return_notif_info = $row['NOTIF_INFO'];;
                                $return_notif_timestamp = $row['NOTIF_TIMESTAMP'];
                                

                                if ($return_notif_title === "Critical Stock Level Reminder"){
                                    echo "<li><h3><i class='material-icons'>info</i> $return_notif_timestamp: $return_notif_title</h3><p>$return_notif_info<br><a href='inventory_report.php'><b>Go to Inventory Report</b></a> <i class='material-icons'>exit_to_app</i></p></li>";
                                } else if ($return_notif_title === "Replenish Stock Level Reminder"){
                                    echo "<li><h3><i class='material-icons'>warning</i> $return_notif_timestamp: $return_notif_title</h3><p>$return_notif_info<br><a href='inventory_report.php'><b>Go to Inventory Report</b></a> <i class='material-icons'>exit_to_app</i></p></li>";
                                } else if ($return_notif_title === "Out of Stock Raw Material Reminder"){
                                    echo "<li><h3><i class='material-icons'>info</i> $return_notif_timestamp: $return_notif_title</h3><p>$return_notif_info<br><a href='inventory_raw_list.php'><b>Go to Raw Material Inventory</b></a> <i class='material-icons'>exit_to_app</i></p></li>";
                                } else if ($return_notif_title === "Expired Raw Material Reminder"){
                                    echo "<li><h3><i class='material-icons'>info</i> $return_notif_timestamp: $return_notif_title</h3><p>$return_notif_info<br><a href='inventory_raw_list.php'><b>Go to Raw Material Inventory</b></a> <i class='material-icons'>exit_to_app</i></p></li>";
                                } else if ($return_notif_title === "Expiring Today Raw Material Warning"){
                                    echo "<li><h3><i class='material-icons'>warning</i> $return_notif_timestamp: $return_notif_title</h3><p>$return_notif_info<br><a href='inventory_raw_list.php'><b>Go to Raw Material Inventory</b></a> <i class='material-icons'>exit_to_app</i></p></li>";
                                } else if ($return_notif_title === "Expiring Raw Material Warning"){
                                    echo "<li><h3><i class='material-icons'>warning</i> $return_notif_timestamp: $return_notif_title</h3><p>$return_notif_info<br><a href='inventory_raw_list.php'><b>Go to Raw Material Inventory</b></a> <i class='material-icons'>exit_to_app</i></p></li>";
                                } else if ($return_notif_title === "Out of Stock Pastry Item Reminder"){
                                    echo "<li><h3><i class='material-icons'>info</i> $return_notif_timestamp: $return_notif_title</h3><p>$return_notif_info<br><a href='inventory_pastry_list.php'><b>Go to Pastry Inventory</b></a> <i class='material-icons'>exit_to_app</i></p></li>";
                                } else if ($return_notif_title === "Expired Pastry Item Reminder"){
                                    echo "<li><h3><i class='material-icons'>info</i> $return_notif_timestamp: $return_notif_title</h3><p>$return_notif_info<br><a href='inventory_raw_list.php'><b>Go to Pastry Inventory</b></a> <i class='material-icons'>exit_to_app</i></p></li>";
                                } else if ($return_notif_title === "Expiring Today Pastry Item Warning"){
                                    echo "<li><h3><i class='material-icons'>warning</i> $return_notif_timestamp: $return_notif_title</h3><p>$return_notif_info<br><a href='inventory_pastry_list.php'><b>Go to Pastry Inventory</b></a> <i class='material-icons'>exit_to_app</i></p></li>";
                                } else if ($return_notif_title === "Expiring Pastry Item Warning"){
                                    echo "<li><h3><i class='material-icons'>info</i> $return_notif_timestamp: $return_notif_title</h3><p>$return_notif_info<br><a href='inventory_pastry_list.php'><b>Go to Pastry Inventory</b></a> <i class='material-icons'>exit_to_app</i></p></li>";
                                } else if ($return_notif_title === "New Order Alert") {
                                    echo "<li><h3><i class='material-icons'>info</i> $return_notif_timestamp: $return_notif_title</h3><p>$return_notif_info<br><a href='order_history.php'><b>Go to Order History</b></a> <i class='material-icons'>exit_to_app</i></p></li>";
                                }
                            }
                        } else {
                            echo "<li>No notifications.</li>";
                        }
                    ?>
                    </ul>
                </div>
            </div>
            <div class="stock_level_rem">
                <h2>Stock Level Reminders</h2>
                <div style="overflow-y: auto; height: 180px;">
                    <table class="stock_rem">
                        <thead>
                            <tr>
                                <th>Inventory Code</th>
                                <th>Inventory Name</th>
                                <th>Inventory Amount Available</th>
                                <th>Inventory Units</th>
                                <th>Minimum Stock Level</th>
                                <th>Stock Level Status</th>
                                <!-- <th>Last Updated</th> -->
                            </tr>
                        </thead>
                        <tbody id="reportTableBody">
                            <?php 
                                if (mysqli_num_rows($exec_showinventory) > 0) {
                                    $stock_status_flag = false;

                                    while($row = mysqli_fetch_assoc($exec_showinventory)){
                                        $ret_inventory_id = $row['PK_INVENTORY_ITEM'];
                                        $ret_inventory_code = $row['INVENTORY_ITEM_CODE'];
                                        $ret_inventory_name = $row['INVENTORY_ITEM_NAME'];
                                        $ret_inventory_amt = $row['INVENTORY_ITEM_AMOUNT'];
                                        $ret_inventory_units = $row['INVENTORY_ITEM_UNITS'];
                                        $ret_inventory_min = $row['INVENTORY_ITEM_STOCK_MIN'];
                                        $ret_inventory_cog = $row['INVENTORY_ITEM_COG'];
                                        $ret_inventory_datetime = $row['INVENTORY_ITEM_TIMESTAMP'];
                                        $STOCK_STATUS_CODE = "";

                                        $date_format_convert = date_create($ret_inventory_datetime);
                                        $format_date = date_format($date_format_convert, "Y-m-d");
                                        $time_format_convert = date_create($ret_inventory_datetime);
                                        $format_time = date_format($time_format_convert, "h:i A");
                                        
                                        $warning_value = 0.2;
                                        $warning_stock_parameter = $ret_inventory_min + ($ret_inventory_min * $warning_value);

                                        if (fmod($warning_stock_parameter, 1) !== 0.0){
                                            $warning_stock = intval($warning_stock_parameter) + 1;
                                        } else {
                                            $warning_stock = intval($warning_stock_parameter);
                                        }

                                        if ($ret_inventory_amt <= $ret_inventory_min){
                                            $STOCK_STATUS_CODE = "CRITICAL";
                                            $notif_title = "Critical Stock Level Reminder";
                                            $notif_info = $ret_inventory_code . " " . $ret_inventory_name . " has reached its critical threshold of " . $ret_inventory_min . " " . $ret_inventory_units . ". 
                                            The current available stock is " . $ret_inventory_amt . " " . $ret_inventory_units . ". Please take appropriate action to avoid insufficient stock of inventory material.";
                                            
                                            $notif_title_esc = mysqli_real_escape_string($databaseconn, $notif_title);
                                            $notif_info_esc = mysqli_real_escape_string($databaseconn, $notif_info);

                                            $check_exist_notif = "SELECT * FROM tbl_notifications WHERE NOTIF_TITLE = '$notif_title_esc' AND NOTIF_INFO = '$notif_info_esc'";
                                            $execute_check_notif = mysqli_query($databaseconn, $check_exist_notif);

                                            if (mysqli_num_rows($execute_check_notif) == 0){
                                                $notif_query = "INSERT INTO tbl_notifications (NOTIF_TITLE, NOTIF_INFO) VALUES ('{$notif_title}','{$notif_info}')";
                                                $execute_notif = mysqli_query($databaseconn, $notif_query);
                                            }
                                        } else if ($ret_inventory_amt <= $warning_stock){
                                            $STOCK_STATUS_CODE = "REORDER";
                                            $notif_title = "Replenish Stock Level Reminder";
                                            $notif_info = $ret_inventory_code . " " . $ret_inventory_name . " has reached its warning threshold of " . $warning_stock . " " . $ret_inventory_units . ". 
                                            The current available stock is " . $ret_inventory_amt . " " . $ret_inventory_units . ". Please replenish stock.";
                                            
                                            $notif_title_esc = mysqli_real_escape_string($databaseconn, $notif_title);
                                            $notif_info_esc = mysqli_real_escape_string($databaseconn, $notif_info);

                                            $check_exist_notif = "SELECT * FROM tbl_notifications WHERE NOTIF_TITLE = '$notif_title_esc' AND NOTIF_INFO = '$notif_info_esc'";
                                            $execute_check_notif = mysqli_query($databaseconn, $check_exist_notif);

                                            if (mysqli_num_rows($execute_check_notif) == 0){
                                                $notif_query = "INSERT INTO tbl_notifications (NOTIF_TITLE, NOTIF_INFO) VALUES ('{$notif_title}','{$notif_info}')";
                                                $execute_notif = mysqli_query($databaseconn, $notif_query);
                                            }
                                        } else {
                                            $STOCK_STATUS_CODE = "NORMAL";
                                        }

                                        if ($STOCK_STATUS_CODE === "CRITICAL" || $STOCK_STATUS_CODE === "REORDER"){
                                            $stock_status_flag = true;

                                            echo "<tr>";
                                            echo "<td><b>$ret_inventory_code</b></td>";
                                            echo "<td>$ret_inventory_name</td>";

                                            if ($ret_inventory_amt >= 1000 && $ret_inventory_units === "Mililiters"){
                                                $conv_unit_ml = $ret_inventory_amt / 1000;
                                                echo "<td>$conv_unit_ml</td>";
                                            } else if ($ret_inventory_amt >= 1000 && $ret_inventory_units === "Grams") {
                                                $conv_unit_gram = $ret_inventory_amt * 0.001;
                                                echo "<td>$conv_unit_gram</td>";
                                            } else {
                                                echo "<td>$ret_inventory_amt</td>";
                                            }

                                            if ($ret_inventory_amt >= 1000 && $ret_inventory_units === "Mililiters"){
                                                echo "<td>Liters</td>";
                                            } else if ($ret_inventory_amt >= 1000 && $ret_inventory_units === "Grams") {
                                                echo "<td>Kilograms</td>";
                                            } else {
                                                if ($ret_inventory_units === "Grams"){
                                                    echo "<td>Grams</td>";
                                                } else if ($ret_inventory_units === "Mililiters") {
                                                    echo "<td>Mililiters</td>";
                                                } else if ($ret_inventory_units === "Piece") {
                                                    echo "<td>Piece</td>";
                                                }
                                            }

                                            echo "<td>$ret_inventory_min</td>";

                                            if ($STOCK_STATUS_CODE === "CRITICAL"){
                                                echo "<td style='color: red; font-weight: bold; text-decoration: underline;'>CRITICAL</td>";
                                            } else if ($STOCK_STATUS_CODE === "REORDER"){
                                                echo "<td style='color: orange; font-weight: bold; text-decoration: underline;'>REORDER</td>";
                                            }
                                            
                                            // echo "<td>$format_date $format_time</td>";
                                            echo "</tr>";
                                        }
                                    }

                                    if (!$stock_status_flag) {
                                        echo "<tr>
                                        <td colspan='6'>No Inventory Material items found.</td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr>
                                        <td colspan='6'>No Inventory Material items found.</td>
                                        </tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="expiry_outstock_rem">
                <h2>Expiry/Out of Stock Reminders</h2>
                <div style="overflow-y: auto; height: 180px;">
                    <table class="expiry_outstock_tbl_rem">
                        <thead>
                            <tr>
                                <th>Inventory Material Code</th>
                                <th>Inventory Material Description</th>
                                <th>Inventory Material Amount Available</th>
                                <th>Inventory Material Units</th>
                                <th>Inventory Material Cost of Goods</th>
                                <th>Inventory Material Expiry Date</th>
                                <th>Status</th>
                                <!-- <th>Last Updated</th> -->
                            </tr>
                        </thead>
                        <tbody id="reportTableBody">
                            <?php 
                                if (mysqli_num_rows($exec_showinventory_inv) > 0) {
                                    $inventory_status_flag = false;

                                    while($row = mysqli_fetch_assoc($exec_showinventory_inv)){
                                        $ret_inv_id = $row['PK_INVENTORY_ITEM'];
                                        $ret_inv_code = $row['INVENTORY_ITEM_CODE'];
                                        $ret_inv_name = $row['INVENTORY_ITEM_NAME'];
                                        $ret_inv_amt = $row['INVENTORY_ITEM_AMOUNT'];
                                        $ret_inv_units = $row['INVENTORY_ITEM_UNITS'];
                                        $ret_inv_cog = $row['INVENTORY_ITEM_COG'];
                                        $ret_inv_exp = $row['INVENTORY_ITEM_EXP'];
                                        $ret_inv_udate = $row['INVENTORY_ITEM_TIMESTAMP'];
                                        $ret_inv_status = $row['STATUS_INVENTORY_ITEM'];
                                        $INVENTORY_STATUS_CODE = "";

                                        $expire_reminder_timestamp = strtotime($ret_inv_exp) - (3 * 24 * 60 * 60);
                                        $expire_reminder_trigger = date("Y-m-d", $expire_reminder_timestamp);

                                        if ($ret_inv_amt == 0){
                                            $INVENTORY_STATUS_CODE = "OUT OF STOCK";

                                            $notif_title = "Out of Stock Raw Material Reminder";
                                            $notif_info = $ret_inv_code . " " . $ret_inv_name . " has ran Out of Stock.";
                                            
                                            $notif_title_esc = mysqli_real_escape_string($databaseconn, $notif_title);
                                            $notif_info_esc = mysqli_real_escape_string($databaseconn, $notif_info);

                                            $check_exist_notif = "SELECT * FROM tbl_notifications WHERE NOTIF_TITLE = '$notif_title_esc' AND NOTIF_INFO = '$notif_info_esc'";
                                            $execute_check_notif = mysqli_query($databaseconn, $check_exist_notif);

                                            if (mysqli_num_rows($execute_check_notif) == 0){
                                                $notif_query = "INSERT INTO tbl_notifications (NOTIF_TITLE, NOTIF_INFO) VALUES ('{$notif_title}','{$notif_info}')";
                                                $execute_notif = mysqli_query($databaseconn, $notif_query);
                                            }

                                        } else if ($curr_date > $ret_inv_exp) {
                                            $INVENTORY_STATUS_CODE = "EXPIRED";
                                            
                                            $notif_title = "Expired Raw Material Reminder";
                                            $notif_info = $ret_inv_code . " " . $ret_inv_name . " has already expired. Please take appropriate action to avoid usage of expired raw material.";
                                            
                                            $notif_title_esc = mysqli_real_escape_string($databaseconn, $notif_title);
                                            $notif_info_esc = mysqli_real_escape_string($databaseconn, $notif_info);

                                            $check_exist_notif = "SELECT * FROM tbl_notifications WHERE NOTIF_TITLE = '$notif_title_esc' AND NOTIF_INFO = '$notif_info_esc'";
                                            $execute_check_notif = mysqli_query($databaseconn, $check_exist_notif);

                                            if (mysqli_num_rows($execute_check_notif) == 0){
                                                $notif_query = "INSERT INTO tbl_notifications (NOTIF_TITLE, NOTIF_INFO) VALUES ('{$notif_title}','{$notif_info}')";
                                                $execute_notif = mysqli_query($databaseconn, $notif_query);
                                            }

                                        } else if ($curr_date == $ret_inv_exp) {
                                            $INVENTORY_STATUS_CODE = "EXPIRY TODAY";

                                            $notif_title = "Expiring Today Raw Material Warning";
                                            $notif_info = $ret_inv_code . " " . $ret_inv_name . " will expire today. Please take appropriate action to avoid expiring the raw material.";
                                            
                                            $notif_title_esc = mysqli_real_escape_string($databaseconn, $notif_title);
                                            $notif_info_esc = mysqli_real_escape_string($databaseconn, $notif_info);

                                            $check_exist_notif = "SELECT * FROM tbl_notifications WHERE NOTIF_TITLE = '$notif_title_esc' AND NOTIF_INFO = '$notif_info_esc'";
                                            $execute_check_notif = mysqli_query($databaseconn, $check_exist_notif);

                                            if (mysqli_num_rows($execute_check_notif) == 0){
                                                $notif_query = "INSERT INTO tbl_notifications (NOTIF_TITLE, NOTIF_INFO) VALUES ('{$notif_title}','{$notif_info}')";
                                                $execute_notif = mysqli_query($databaseconn, $notif_query);
                                            }

                                        } else if ($curr_date >= $expire_reminder_trigger) {
                                            $INVENTORY_STATUS_CODE = "EXPIRY WARNING";

                                            $notif_title = "Expiring Raw Material Warning";
                                            $notif_info = $ret_inv_code . " " . $ret_inv_name . " will expire in 3 days. Please take appropriate action to avoid expiring the raw material.";
                                            
                                            $notif_title_esc = mysqli_real_escape_string($databaseconn, $notif_title);
                                            $notif_info_esc = mysqli_real_escape_string($databaseconn, $notif_info);

                                            $check_exist_notif = "SELECT * FROM tbl_notifications WHERE NOTIF_TITLE = '$notif_title_esc' AND NOTIF_INFO = '$notif_info_esc'";
                                            $execute_check_notif = mysqli_query($databaseconn, $check_exist_notif);

                                            if (mysqli_num_rows($execute_check_notif) == 0){
                                                $notif_query = "INSERT INTO tbl_notifications (NOTIF_TITLE, NOTIF_INFO) VALUES ('{$notif_title}','{$notif_info}')";
                                                $execute_notif = mysqli_query($databaseconn, $notif_query);
                                            }
                                        } else {
                                            $INVENTORY_STATUS_CODE = "AVAILABLE";
                                        }

                                        if ($INVENTORY_STATUS_CODE === "OUT OF STOCK" || $INVENTORY_STATUS_CODE === "EXPIRED" || $INVENTORY_STATUS_CODE === "EXPIRY TODAY" || $INVENTORY_STATUS_CODE === "EXPIRY WARNING"){
                                            $inventory_status_flag = true;    

                                            echo "<tr>";
                                            echo "<td><b>$ret_inv_code</b></td>";
                                            echo "<td>$ret_inv_name</td>";

                                        if ($ret_inv_amt >= 1000 && $ret_inv_units === "Mililiters"){
                                                $conv_unit_ml = $ret_inv_amt / 1000;
                                                echo "<td>$conv_unit_ml</td>";
                                            } else if ($ret_inv_amt >= 1000 && $ret_inv_units === "Grams") {
                                                $conv_unit_gram = $ret_inv_amt * 0.001;
                                                echo "<td>$conv_unit_gram</td>";
                                            } else {
                                                echo "<td>$ret_inv_amt</td>";
                                            }

                                            if ($ret_inv_amt >= 1000 && $ret_inv_units === "Mililiters"){
                                                echo "<td>Liters</td>";
                                            } else if ($ret_inv_amt >= 1000 && $ret_inv_units === "Grams") {
                                                echo "<td>Kilograms</td>";
                                            } else {
                                                if ($ret_inv_units === "Grams"){
                                                    echo "<td>Grams</td>";
                                                } else if ($ret_inv_units === "Mililiters") {
                                                    echo "<td>Mililiters</td>";
                                                } else if ($ret_inv_units === "Piece") {
                                                    echo "<td>Piece</td>";
                                                }
                                            }

                                            echo "<td>₱". $ret_inv_cog . ".00</td>";
                                            echo "<td>$ret_inv_exp</td>";

                                            if ($INVENTORY_STATUS_CODE === "OUT OF STOCK"){
                                                echo "<td style='color: red; font-weight: bold; text-decoration: underline;'>OUT OF STOCK</td>";
                                            } else if ($INVENTORY_STATUS_CODE === "EXPIRED"){
                                                echo "<td style='color: orange; font-weight: bold; text-decoration: underline;'>EXPIRED</td>";
                                            } else if ($INVENTORY_STATUS_CODE === "EXPIRY TODAY"){
                                                echo "<td style='color: orange; font-weight: bold; text-decoration: underline;'>EXPIRY TODAY</td>";
                                            } else if ($INVENTORY_STATUS_CODE === "EXPIRY WARNING"){
                                                echo "<td style='color: orange; font-weight: bold; text-decoration: underline;'>EXPIRY WARNING</td>";
                                            }
                                            
                                            // echo "<td>$ret_inv_udate</td>";

                                            echo "</tr>";
                                        }
                                    } 
                                    
                                    if (!$inventory_status_flag) {
                                        echo "<tr>
                                        <td colspan='7'>No Inventory Material items found.</td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr>
                                    <td colspan='7'>No Inventory Material items found.</td>
                                    </tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="sales_todate_prop">
                <h2>Sales to Date by Product Type</h2>
                <div style="overflow-y: auto; height: 180px;">
                    <table class="sales_prop">
                        <thead>
                            <tr>
                                <th>Product Category</th>
                                <th>Product Name</th>
                                <th>Unit Price</th>
                                <th>Total Sold</th>
                                <th>Total Sales</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody id="reportTableBody">
                            <?php 
                                while($row = mysqli_fetch_assoc($exec_best_seller_total)){
                                    $ret_total_sales = $row['ALL_TOTAL_SALES'];
                                }

                                while($row = mysqli_fetch_assoc($exec_best_seller)){
                                    $ret_best_cat = $row['ORDER_PRODUCT_CATEGORY'];
                                    $ret_best_prod = $row['ORDER_PRODUCT_NAME'];
                                    $ret_best_qty = $row['PROD_QTY'];
                                    $ret_best_unit = $row['ORDER_PRICE'];
                                    $ret_best_sales = $row['TOTAL_SALES'];

                                    $ret_total_sales_proportion = number_format((($ret_best_sales / $ret_total_sales) * 100), 2);

                                    echo "<tr>";
                                    echo "<td>$ret_best_cat</td>";
                                    echo "<td>$ret_best_prod</td>";
                                    echo "<td>₱$ret_best_unit</td>";
                                    echo "<td>$ret_best_qty</td>";
                                    echo "<td>₱$ret_best_sales</td>";
                                    echo "<td><b>$ret_total_sales_proportion%</b></td>";
                                    echo "</tr>";
                                }
                            ?>
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