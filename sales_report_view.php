<?php
    include ('database_connection.php');
    SESSION_START();

    date_default_timezone_set("Asia/Manila");

    if (isset($_GET['OrderID'])){
        $get_orderid = $_GET['OrderID'];

        $order_id_list_sel_upr = "SELECT tbl_orders.PK_ORDER_ID AS 'PK_ORDER_ID', tbl_orders.CUST_NAME AS 'CUST_NAME', DATE_FORMAT(DATE_ADD(tbl_orders.ORDER_TIMESTAMP, INTERVAL 8 HOUR), '%Y-%m-%d %r') AS 'ORDER_TIMESTAMP', SUM(tbl_order_items.ORDER_PRODUCT_QTY) AS 'TOTAL_QTY', SUM(tbl_order_items.ORDER_PRICE * tbl_order_items.ORDER_PRODUCT_QTY) 
        AS 'TOTAL_SALES', tbl_orders.ORDER_DISCOUNT_TYPE AS 'DISCOUNT_TYPE', tbl_orders.ORDER_TOTAL_AMT AS 'TOTAL_DISCOUNT', tbl_orders.ORDER_PAYMENT_METHOD AS 'PAYMENT_METHOD', 
        tbl_orders.ORDER_GCASH_REF AS 'GCASH_REF' FROM tbl_orders RIGHT JOIN tbl_order_items ON tbl_orders.PK_ORDER_ID = tbl_order_items.PK_ORDER_ID WHERE tbl_orders.PK_ORDER_ID = $get_orderid GROUP BY tbl_orders.PK_ORDER_ID;";

        $exec_showorderid_upr = mysqli_query($databaseconn, $order_id_list_sel_upr);

        while($row = mysqli_fetch_assoc($exec_showorderid_upr)){
            $ret_order_id_upr = $row['PK_ORDER_ID'];
            $ret_order_cust_upr = $row['CUST_NAME'];
            $ret_order_datetime_upr = $row['ORDER_TIMESTAMP'];
            $ret_order_qty_upr = $row['TOTAL_QTY'];
            $ret_order_price_upr = $row['TOTAL_SALES'];
            $ret_order_disc_upr = $row['DISCOUNT_TYPE'];
            $ret_order_disc_total_upr = $row['TOTAL_DISCOUNT'];
            $ret_order_payment_upr = $row['PAYMENT_METHOD'];
            $ret_order_gcashref_upr = $row['GCASH_REF'];

            $gcash_numref = "None";
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
        <title>Sales Report Details - Buko Coolers Cafe</title>
        <link rel="stylesheet" type="text/css" href="styles/dashboard.css">
        <link rel="stylesheet" type="text/css" href="styles/viewdata_style.css">
        <link rel="stylesheet" type="text/css" href="styles/viewdata_withentry_style.css">
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
            <h2>SALES REPORT DETAILS</h2>
            <div class="form_row_order">
                <label for="Salesnum">Invoice Number</label>
                <input type="text" name="salesinv" id="salesnum" style="font-weight: bold;" value="<?php echo $ret_order_id_upr; ?>" readonly>

                <label for="custname">Customer Name</label>
                <input type="text" name="custname" id="cust_name" value="<?php if ($ret_order_cust_upr === "") {echo "None";} else {echo $ret_order_cust_upr;} ?>" readonly>

                <label for="Salesdate">Order Date</label>
                <input type="text" name="salesdt" id="salesdate" value="<?php echo $ret_order_datetime_upr; ?>" readonly>

                <label for="Salesqty">Total Qty</label>
                <input type="text" name="qtysale" id="salesqty" value="<?php echo $ret_order_qty_upr; ?>" readonly>
            </div>
            <div class="form_row_order_dual">
                <label for="Salestotal">Sales Subtotal</label>
                <input type="text" name="totsale" id="salestotal" value="<?php echo "₱" . $ret_order_price_upr; ?>" readonly>
            
                <label for="Salesdisc">Discount Type</label>
                <input type="text" name="disctype" id="salesdisc" value="<?php echo $ret_order_disc_upr; ?>" readonly>

                <label for="Salestotaldisc">Sales Discount</label>
                <input type="text" name="totsale" id="salestotal" value="<?php echo "₱" . $ret_order_price_upr - $ret_order_disc_total_upr . ".00";?>" readonly>

                <label for="Salestotalout">Sales Total</label>
                <input type="text" name="totsale" id="salestotal" value="<?php echo "₱" . $ret_order_disc_total_upr; ?>" readonly>

                <label for="SalesMethod">Payment Method</label>
                <input type="text" name="paymethod" id="salesmethod" value="<?php echo $ret_order_payment_upr; ?>" readonly>

                <label for="GCashRef">GCash Ref#</label>
                <input type="text" name="gcashref" id="gcashsales" value="<?php if ($ret_order_gcashref_upr === "") {echo "None";} else {echo $ret_order_gcashref_upr;} ?>" readonly>
            </div>
            <div class="table_container_dual">
                <div style="overflow-y: auto; height: 350px;">
                    <table class="dataview">
                        <thead>
                            <tr>
                                <th>Product Category</th>
                                <th>Product Name</th>
                                <th>Unit Price</th>
                                <th>Qty</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody id="table_body">
                            <tr>
                                <?php
                                    $order_id_list = "SELECT tbl_orders.PK_ORDER_ID, tbl_orders.ORDER_TIMESTAMP, tbl_order_items.ORDER_PRODUCT_CATEGORY, 
                                    tbl_order_items.ORDER_PRODUCT_NAME, tbl_order_items.ORDER_PRODUCT_QTY, tbl_order_items.ORDER_PRICE, (tbl_order_items.ORDER_PRICE * tbl_order_items.ORDER_PRODUCT_QTY) 
                                    AS 'TOTAL_PRICE' FROM tbl_orders RIGHT JOIN tbl_order_items ON tbl_orders.PK_ORDER_ID = tbl_order_items.PK_ORDER_ID WHERE tbl_order_items.PK_ORDER_ID = $get_orderid";
                            
                                    $exec_showorderlist = mysqli_query($databaseconn, $order_id_list);

                                    while($row = mysqli_fetch_assoc($exec_showorderlist)){
                                        $ret_order_id = $row['PK_ORDER_ID'];
                                        $ret_order_cat = $row['ORDER_PRODUCT_CATEGORY'];
                                        $ret_order_name = $row['ORDER_PRODUCT_NAME'];
                                        $ret_order_qty = $row['ORDER_PRODUCT_QTY'];
                                        $ret_order_price = $row['ORDER_PRICE'];
                                        $ret_order_price_all = $row['TOTAL_PRICE'];

                                        echo "<tr>";
                                        echo "<td>$ret_order_cat</td>";
                                        echo "<td>$ret_order_name</td>";
                                        echo "<td>₱" . $ret_order_price . "</td>";
                                        echo "<td>$ret_order_qty</td>";
                                        echo "<td>₱" . $ret_order_price_all . "</td>";
                                        echo "</tr>";
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