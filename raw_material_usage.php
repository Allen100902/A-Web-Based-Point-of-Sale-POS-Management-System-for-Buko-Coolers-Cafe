<?php
    include ('database_connection.php');
    SESSION_START();

    date_default_timezone_set("Asia/Manila");

    $inventory_search = "SELECT tbl_products_list.PROD_CATEGORY, tbl_products_list.PROD_NAME, tbl_product_ingredients.ING_CODE, tbl_product_ingredients.INGREDIENT_NAME, tbl_product_ingredients.INGREDIENT_AMOUNT, 
    tbl_product_ingredients.INGREDIENT_UNIT, 
    SUM(tbl_order_items.ORDER_PRODUCT_QTY) AS 'TOTAL_QTY', 
    SUM(tbl_order_items.ORDER_PRODUCT_QTY * tbl_product_ingredients.INGREDIENT_AMOUNT) AS 'TOTAL_USAGE' 
    FROM tbl_order_items 
    INNER JOIN tbl_products_list ON tbl_products_list.PROD_NAME = tbl_order_items.ORDER_PRODUCT_NAME
    INNER JOIN tbl_product_ingredients ON tbl_product_ingredients.PK_PROD_LIST = tbl_products_list.PK_PROD_LIST
    GROUP BY tbl_products_list.PROD_CATEGORY, tbl_products_list.PROD_NAME, tbl_product_ingredients.ING_CODE, tbl_product_ingredients.INGREDIENT_NAME, tbl_product_ingredients.INGREDIENT_AMOUNT, tbl_product_ingredients.INGREDIENT_UNIT 
    ORDER BY tbl_products_list.PROD_NAME DESC";
    
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
        <title>Raw Material Ingredient Usage - Buko Coolers Cafe</title>
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
            <h1>RAW MATERIAL INGREDIENT USAGE</h1>
            <div class="button_upper">
                <div class="form-side">
                    <a id="exportcsv" name="csvexport" href="raw_material_usage_report_export.php">EXPORT AS CSV</a>
                </div>
            </div>
            <div class="table_container">
                <div style="overflow-y: auto; height: 450px;">
                    <table class="dataview">
                        <thead>
                            <tr>
                                <th>Product Category</th>
                                <th>Product Name</th>
                                <th>Raw Material Code</th>
                                <th>Raw Material Description</th>
                                <th>Raw Material Amount</th>
                                <th>Raw Material Units</th>
                                <th>Total Qty Sold</th>
                                <th>Total Ingredient Usage Amount</th>
                                <th>Total Ingredient Usage Units</th>
                            </tr>
                        </thead>
                        <tbody id="table_body">
                            <tr>
                                <?php
                                    while($row = mysqli_fetch_assoc($exec_showinventory)){
                                        $ret_inventory_raw_prodcat = $row['PROD_CATEGORY'];
                                        $ret_inventory_raw_prodname = $row['PROD_NAME'];
                                        $ret_inventory_raw_ingcode = $row['ING_CODE'];
                                        $ret_inventory_raw_ingname = $row['INGREDIENT_NAME'];
                                        $ret_inventory_raw_amt = $row['INGREDIENT_AMOUNT'];
                                        $ret_inventory_raw_unit = $row['INGREDIENT_UNIT'];
                                        $ret_inventory_raw_qty = $row['TOTAL_QTY'];
                                        $ret_inventory_raw_usage = $row['TOTAL_USAGE'];

                                        echo "<tr>";
                                        echo "<td>$ret_inventory_raw_prodcat</td>";
                                        echo "<td>$ret_inventory_raw_prodname</td>";
                                        echo "<td><b>$ret_inventory_raw_ingcode</b></td>";
                                        echo "<td>$ret_inventory_raw_ingname</td>";
                                        echo "<td>$ret_inventory_raw_amt</td>";
                                        echo "<td>$ret_inventory_raw_unit</td>";
                                        echo "<td>$ret_inventory_raw_qty</td>";
                                        echo "<td>$ret_inventory_raw_usage</td>";
                                        echo "<td>$ret_inventory_raw_unit</td>";
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