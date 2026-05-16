<?php
    include ('database_connection.php');
    SESSION_START();

    date_default_timezone_set("Asia/Manila");

    $csv_export = "sales_report_export.php";
    $url_print = "sales_report_pdfexport.php";

    $show_orderlist = "SELECT tbl_orders.PK_ORDER_ID AS 'PK_ORDER_ID', tbl_orders.CUST_NAME AS 'CUST_NAME', DATE_FORMAT(DATE_ADD(tbl_orders.ORDER_TIMESTAMP, INTERVAL 8 HOUR), '%Y-%m-%d %r') AS 'ORDER_TIMESTAMP',
    SUM(tbl_order_items.ORDER_PRODUCT_QTY) AS 'TOTAL_QTY', tbl_orders.ORDER_SUBTOTAL AS 'SUBTOTAL', tbl_orders.ORDER_TOTAL_AMT AS 
    'TOTAL_SALES', tbl_orders.ORDER_DISCOUNT_TYPE AS 'DISCOUNT_TYPE', tbl_orders.ORDER_PAYMENT_METHOD AS 'PAYMENT_METHOD' FROM tbl_orders 
    RIGHT JOIN tbl_order_items ON tbl_orders.PK_ORDER_ID = tbl_order_items.PK_ORDER_ID GROUP BY tbl_orders.PK_ORDER_ID ORDER BY tbl_orders.PK_ORDER_ID DESC";
    
    $show_sum_head = "SELECT SUM(tbl_order_items.ORDER_PRODUCT_QTY) AS 'TOTAL_QTY', 
    (SELECT SUM(tbl_orders.ORDER_TOTAL_AMT) FROM tbl_orders) AS 'TOTAL_SALES' from tbl_orders JOIN tbl_order_items 
    ON tbl_orders.PK_ORDER_ID = tbl_order_items.PK_ORDER_ID";

    if (isset($_POST['searchdata'])){
        if (!empty($_POST['datestart']) && !empty($_POST['dateend'])){
            $filter_start = $_POST['datestart'];
            $filter_end = $_POST['dateend'];

            $show_orderlist = "SELECT tbl_orders.PK_ORDER_ID AS 'PK_ORDER_ID', tbl_orders.CUST_NAME AS 'CUST_NAME', DATE_FORMAT(DATE_ADD(tbl_orders.ORDER_TIMESTAMP, INTERVAL 8 HOUR), '%Y-%m-%d %r') AS 'ORDER_TIMESTAMP',
            SUM(tbl_order_items.ORDER_PRODUCT_QTY) AS 'TOTAL_QTY', tbl_orders.ORDER_SUBTOTAL AS 'SUBTOTAL', tbl_orders.ORDER_TOTAL_AMT AS 
            'TOTAL_SALES', tbl_orders.ORDER_DISCOUNT_TYPE AS 'DISCOUNT_TYPE', tbl_orders.ORDER_PAYMENT_METHOD AS 'PAYMENT_METHOD' FROM tbl_orders 
            RIGHT JOIN tbl_order_items ON tbl_orders.PK_ORDER_ID = tbl_order_items.PK_ORDER_ID WHERE DATE(tbl_orders.ORDER_TIMESTAMP) BETWEEN '$filter_start' AND '$filter_end' 
            GROUP BY tbl_orders.PK_ORDER_ID ORDER BY tbl_orders.PK_ORDER_ID DESC";

            $show_sum_head = "SELECT SUM(tbl_order_items.ORDER_PRODUCT_QTY) AS 'TOTAL_QTY', 
            (SELECT SUM(tbl_orders.ORDER_TOTAL_AMT) FROM tbl_orders WHERE DATE(tbl_orders.ORDER_TIMESTAMP) BETWEEN '$filter_start' AND '$filter_end') AS 'TOTAL_SALES' 
            from tbl_orders JOIN tbl_order_items ON tbl_orders.PK_ORDER_ID = tbl_order_items.PK_ORDER_ID WHERE DATE(tbl_orders.ORDER_TIMESTAMP) BETWEEN '$filter_start' AND '$filter_end' ORDER BY tbl_orders.PK_ORDER_ID DESC";
        }
    }

    $exec_showorderlist = mysqli_query($databaseconn, $show_orderlist);
    $exec_sum_head = mysqli_query($databaseconn, $show_sum_head);

    while($row = mysqli_fetch_assoc($exec_sum_head)){
        $ret_total_qty = $row['TOTAL_QTY'];
        $ret_total_sales = $row['TOTAL_SALES'];
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
        <title>Sales Report - Buko Coolers Cafe</title>
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
            <h1>SALES REPORT</h1>
            <div class="button_upper_search">
                <form class="list_forms_upper" method="POST" action="">
                    <div class="form-row">
                        <label for="dateStart">Start Date</label>
                        <input type="date" id="startdate" name="datestart">

                        <label for="dateEnd">End Date</label>
                        <input type="date" id="enddate" name="dateend">

                        <div class="form-side">
                            <button id="searchdate" name="searchdata">FILTER DATE(S)</button>
                            <?php
                                if (isset($filter_start) && isset($filter_end)){
                                    $csv_export .= "?filterdatestart=" . urlencode($filter_start) . "&filterdateend=" . urlencode($filter_end);
                                }
                            ?>
                            <?php
                                if (isset($filter_start) && isset($filter_end)){
                                    $url_print .= "?filterdatestart=" . urlencode($filter_start) . "&filterdateend=" . urlencode($filter_end);
                                }
                            ?>
                            <a id="exportreport" name="reportexport" href="<?php echo $url_print; ?>">PRINT REPORT</a>
                            <a id="exportcsv" name="csvexport" href="<?php echo $csv_export;?>">EXPORT AS CSV</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table_container_search">
                <div style="overflow-y: auto; height: 400px;">
                    <table class="dataview">
                        <thead>
                            <tr>
                                <th>Sales Invoice #</th>
                                <th>Customer Name</th>
                                <th>Sales Date</th>
                                <th>Sales Total Qty</th>
                                <th>Sales Discount Type</th>
                                <th>Sales Subtotal</th>
                                <th>Sales Discount</th>
                                <th>Sales Total</th>
                                <th>Sales Payment Method</th>
                                <th>Sales Items</th>
                            </tr>
                        </thead>
                        <tbody id="table_body">
                            <tr>
                                <?php
                                    if (mysqli_num_rows($exec_showorderlist) > 0) {
                                        while($row = mysqli_fetch_assoc($exec_showorderlist)){
                                            $ret_order_id = $row['PK_ORDER_ID'];
                                            $ret_order_cust = $row['CUST_NAME'];
                                            $ret_order_datetime = $row['ORDER_TIMESTAMP'];
                                            $ret_order_qty = $row['TOTAL_QTY'];
                                            $ret_order_disc = $row['DISCOUNT_TYPE'];
                                            $ret_total_sub = $row['SUBTOTAL'];
                                            $ret_order_sales = $row['TOTAL_SALES'];
                                            $ret_order_payment = $row['PAYMENT_METHOD'];

                                            $ret_order_datetime_format_create = date_create($ret_order_datetime);
                                            $ret_order_datetime_format = date_format($ret_order_datetime_format_create, "F d, Y h:i A");

                                            echo "<tr>";
                                            echo "<td style='text-align: center; font-weight: bold;'>$ret_order_id</td>";
                                            echo "<td>";
                                            if ($ret_order_cust === "") {
                                                echo "None";} else {
                                                echo $ret_order_cust;}
                                            echo "</td>";
                                            echo "<td>$ret_order_datetime_format</td>";
                                            echo "<td>$ret_order_qty</td>";
                                            echo "<td>$ret_order_disc</td>";
                                            echo "<td>₱" . $ret_total_sub ."</td>";
                                            echo "<td>₱" . $ret_total_sub - $ret_order_sales . ".00</td>";
                                            echo "<td>₱" . $ret_order_sales . "</td>";
                                            echo "<td>$ret_order_payment</td>";
                                            echo "<td><a href='sales_report_view.php?OrderID={$ret_order_id}'><i class='material-icons'>view_list</i></a></td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr>
                                        <td colspan='10'>No sales report as of this period.</td>
                                        </tr>";
                                    }
                                ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="list_forms_lower">
                <label for="paysales">Total Sales</label>
                <input type="text" name="total_sales_amt" id="total_sales" value="<?php if ($ret_total_sales != 0) {echo "₱" . $ret_total_sales;} else {echo "₱0.00";} ?>" readonly>
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