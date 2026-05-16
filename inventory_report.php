<?php
    include ('database_connection.php');
    SESSION_START();

    date_default_timezone_set("Asia/Manila");

    $csv_export = "inventory_report_export.php";

    $inventory_search = "SELECT PK_INVENTORY_ITEM, INVENTORY_ITEM_CODE, INVENTORY_ITEM_NAME, SUM(INVENTORY_ITEM_AMOUNT) AS 'INVENTORY_ITEM_AMOUNT', INVENTORY_ITEM_UNITS, INVENTORY_ITEM_STOCK_MIN, SUM(INVENTORY_ITEM_COG) AS 'INVENTORY_ITEM_COG', DATE_FORMAT(DATE_ADD(INVENTORY_ITEM_TIMESTAMP, INTERVAL 8 HOUR), '%Y-%m-%d %r') AS 'INVENTORY_ITEM_TIMESTAMP' FROM tbl_inventory WHERE STATUS_INVENTORY_ITEM = 'ACTIVE' GROUP BY INVENTORY_ITEM_CODE, INVENTORY_ITEM_NAME, INVENTORY_ITEM_UNITS, INVENTORY_ITEM_STOCK_MIN";

    if (isset($_POST['searchdata'])){
        if (!empty($_POST['datestart'])){
            $filter_start = $_POST['datestart'];
            $inventory_search = "SELECT PK_INVENTORY_ITEM, INVENTORY_ITEM_CODE, INVENTORY_ITEM_NAME, INVENTORY_ITEM_AMOUNT, INVENTORY_ITEM_UNITS, INVENTORY_ITEM_STOCK_MIN, INVENTORY_ITEM_COG, DATE_FORMAT(DATE_ADD(INVENTORY_ITEM_TIMESTAMP, INTERVAL 8 HOUR), '%Y-%m-%d %r') AS 'INVENTORY_ITEM_TIMESTAMP' FROM tbl_inventory WHERE DATE(INVENTORY_ITEM_TIMESTAMP) >= '$filter_start' AND STATUS_INVENTORY_ITEM = 'ACTIVE' GROUP BY INVENTORY_ITEM_CODE, INVENTORY_ITEM_NAME, INVENTORY_ITEM_UNITS, INVENTORY_ITEM_STOCK_MIN";
        } else {
            $inventory_search = "SELECT PK_INVENTORY_ITEM, INVENTORY_ITEM_CODE, INVENTORY_ITEM_NAME, INVENTORY_ITEM_AMOUNT, INVENTORY_ITEM_UNITS, INVENTORY_ITEM_STOCK_MIN, INVENTORY_ITEM_COG, DATE_FORMAT(DATE_ADD(INVENTORY_ITEM_TIMESTAMP, INTERVAL 8 HOUR), '%Y-%m-%d %r') AS 'INVENTORY_ITEM_TIMESTAMP' FROM tbl_inventory WHERE STATUS_INVENTORY_ITEM = 'ACTIVE' GROUP BY INVENTORY_ITEM_CODE, INVENTORY_ITEM_NAME, INVENTORY_ITEM_UNITS, INVENTORY_ITEM_STOCK_MIN";
        }
    }

    $exec_showinventory = mysqli_query($databaseconn, $inventory_search);

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
        <title>Inventory Report - Buko Coolers Cafe</title>
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
            <h1>INVENTORY REPORT</h1>
            <div class="button_upper_search">
                <form class="list_forms_upper" method="POST" action="">
                    <div class="form-row">
                        <label for="dateStart">Date as of</label>
                        <input type="date" id="startdate" name="datestart">
                        <div class="form-side">
                            <button id="searchdate" name="searchdata">FILTER DATE(S)</button>
                             <?php
                                if (isset($filter_start)){
                                    $csv_export .= "?filterdate=" . urlencode($filter_start);
                                }
                            ?>
                            <a id="exportcsv" name="csvexport" href="<?php echo $csv_export;?>">EXPORT AS CSV</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table_container_search">
                <div style="overflow-y: auto; height: 470px;">
                    <table class="dataview">
                        <thead>
                            <tr>
                                <th>Inventory Code</th>
                                <th>Inventory Name</th>
                                <th>Inventory Amount Available</th>
                                <th>Inventory Units</th>
                                <th>Inventory Cost of Goods</th>
                                <th>Minimum Stock Level</th>
                                <th>Stock Level Status</th>
                                <th>Last Updated</th>
                            </tr>
                        </thead>
                        <tbody id="table_body">
                            <tr>
                                <?php if (mysqli_num_rows($exec_showinventory) > 0) {
                                        while($row = mysqli_fetch_assoc($exec_showinventory)){
                                            $ret_inventory_id = $row['PK_INVENTORY_ITEM'];
                                            $ret_inventory_code = $row['INVENTORY_ITEM_CODE'];
                                            $ret_inventory_name = $row['INVENTORY_ITEM_NAME'];
                                            $ret_inventory_amt = $row['INVENTORY_ITEM_AMOUNT'];
                                            $ret_inventory_units = $row['INVENTORY_ITEM_UNITS'];
                                            $ret_inventory_min = $row['INVENTORY_ITEM_STOCK_MIN'];
                                            $ret_inventory_cog = $row['INVENTORY_ITEM_COG'];
                                            $ret_inventory_datetime = $row['INVENTORY_ITEM_TIMESTAMP'];

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

                                            echo "<tr>";
                                            echo "<td><b>$ret_inventory_code</b></td>";
                                            echo "<td>$ret_inventory_name</td>";
                                            echo "<td>$ret_inventory_amt</td>";
                                            echo "<td>$ret_inventory_units</td>";
                                            echo "<td>₱" . $ret_inventory_cog . ".00</td>";
                                            echo "<td>$ret_inventory_min</td>";
                                            if ($ret_inventory_amt <= $ret_inventory_min){
                                                echo "<td style='color: red; font-weight: bold; text-decoration: underline;'>CRITICAL</td>";

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
                                                echo "<td style='color: orange; font-weight: bold; text-decoration: underline;'>REORDER</td>";

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
                                                 echo "<td style='color: green; font-weight: bold; text-decoration: underline;'>NORMAL</td>";
                                            }            
                                            echo "<td>$format_date $format_time</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr>
                                        <td colspan='7'>No records found from the specified date period.</td>
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
        <script type="text/javascript" src="scripts/datecontrol_formhandler.js"></script>
        <script type="text/javascript" src="templates/template_scripts/dropdown_script.js"></script>
        <script type="text/javascript" src="templates/template_scripts/sidenav_script.js"></script>
    </body>
</html>