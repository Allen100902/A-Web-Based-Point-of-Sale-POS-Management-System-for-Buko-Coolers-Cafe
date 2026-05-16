<?php
    include ('database_connection.php');
    SESSION_START();

    date_default_timezone_set("Asia/Manila");

    if (isset($_POST['save_ret'])){
        $RM_CODE = $_POST['rawcode'];
        $RM_NAME = $_POST['rawname'];
        $RM_AMT = $_POST['rawamt'];
        $RM_UNIT = $_POST['rawunits'];
        $RM_COG = $_POST['rawcog'];
        $RM_EXP = $_POST['rawexp'];

        $expire_check = "SELECT * FROM tbl_inventory WHERE INVENTORY_ITEM_CODE = '{$RM_CODE}' AND INVENTORY_ITEM_NAME = '{$RM_NAME}' AND INVENTORY_ITEM_EXP = '{$RM_EXP}' AND STATUS_INVENTORY_ITEM = 'ACTIVE' LIMIT 1";
        $expire_check_exist_exec = mysqli_query($databaseconn, $expire_check);

        if (mysqli_num_rows ($expire_check_exist_exec) > 0){
            $expire_exist_data = mysqli_fetch_assoc($expire_check_exist_exec);
            $append_amt = $expire_exist_data['INVENTORY_ITEM_AMOUNT'] + $RM_AMT;

            $update_inv_amt = "UPDATE tbl_inventory SET INVENTORY_ITEM_AMOUNT = {$append_amt}, INVENTORY_ITEM_TIMESTAMP = NOW() WHERE PK_INVENTORY_ITEM = {$expire_exist_data['PK_INVENTORY_ITEM']}";
            $update_inv_exec = mysqli_query ($databaseconn, $update_inv_amt);

            if (!$update_inv_exec) {
                echo "ERR_DB_2: Operation failed: " . mysqli_error($databaseconn);
            } else {
                echo "<script type='text/javascript'>window.location.href = 'inventory_raw_list.php';</script>";
                exit();
            }
        } else {
            $RM_INSERT = "INSERT INTO tbl_inventory(INVENTORY_ITEM_CODE, INVENTORY_ITEM_NAME, INVENTORY_ITEM_AMOUNT, INVENTORY_ITEM_UNITS, INVENTORY_ITEM_COG, INVENTORY_ITEM_EXP) VALUES ('{$RM_CODE}', '{$RM_NAME}', '{$RM_AMT}', '{$RM_UNIT}', '{$RM_COG}', '{$RM_EXP}')";
            $EXEC_RM_INSERT = mysqli_query($databaseconn, $RM_INSERT);

            if (!$RM_INSERT) {
                echo "ERR_DB_2: Operation failed: " . mysqli_error($databaseconn);
            } else {
                echo "<script type='text/javascript'>window.location.href = 'inventory_raw_list.php';</script>";
                exit();
            }
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
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add Raw Material Item Inventory - Buko Coolers Cafe</title>
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
            <h1>ADD RAW MATERIAL ITEM INVENTORY</h1>
            <div class="user_form">
                <form class="user_data_entry" method="POST" action="">
                    <fieldset class="form_inventory">
                        <legend><h3>Item Information</h3></legend>
                        <div style="overflow-y: auto; height: 350px;">
                            <div class="form_row">
                                <label for="raw_itemcode">Raw Material Code</label>
                                <select id="raw_prodlist" name="rawcode" required>
                                    <option value="">Select Raw Material Code</option>
                                    <?php
                                        $show_inventory_list = "SELECT PK_INVENTORY, INVENTORY_ITEM_CODE, INVENTORY_ITEM_DESCRIPTION, 
                                        INVENTORY_ITEM_STATUS, INVENTORY_ITEM_TIMESTAMP
                                        FROM tbl_inventory_materials_list WHERE INVENTORY_ITEM_CODE LIKE 'RAW%' AND INVENTORY_ITEM_STATUS = 'ACTIVE' ORDER BY tbl_inventory_materials_list.PK_INVENTORY DESC";
                                        $exec_show_inventory_list = mysqli_query($databaseconn, $show_inventory_list);

                                        $inv_listresult = [];

                                        while($row = mysqli_fetch_assoc($exec_show_inventory_list)){
                                            $ret_inv_code = $row['INVENTORY_ITEM_CODE'];
                                            $ret_inv_desc = $row['INVENTORY_ITEM_DESCRIPTION'];

                                            $inv_listresult[$ret_inv_code] = $ret_inv_desc;
                                    ?>
                                    <option value="<?php echo $ret_inv_code ?>"><?php echo $ret_inv_code?></option>
                                    <?php
                                        }
                                        echo "<script>var rawDescData = " . json_encode($inv_listresult) . ";</script>"
                                    ?>
                                </select>
                            </div>
                            <div class="form_row">
                                <label for="raw_itemname">Raw Material Name</label>
                                <input type="text" name="rawname" id="raw_name" readonly required>
                            </div>
                            <div class="form_row">
                                <label for="raw_itemamt">Raw Material Amount</label>
                                <input type="number" name="rawamt" id="raw_amt" min="1" max="99999" required>
                            </div>
                            <div class="form_row">
                                <label for="raw_itemunits">Units</label>
                                <select name="rawunits" id="raw_units" required>
                                    <option value="">Select</option>
                                    <option value="Grams">Grams</option>
                                    <option value="Mililiters">Mililiters</option>
                                    <option value="Piece">Piece</option>
                                </select>
                            </div>
                            <div class="form_row">
                                <label for="raw_itemcog">Cost of Goods</label>
                                <input type="number" name="rawcog" id="raw_cog" min="1" max="9999" required>
                            </div>
                            <div class="form_row">
                                <label for="raw_itemcog">Expiry Date</label>
                                <input type="date" id="expiredate" name="rawexp" required>
                            </div>
                        </div>
                    </fieldset>
                    <div class="form_buttons">
                        <button type="reset" class="reset_btn">Clear Fields</button>
                        <button type="submit" class="take_action" name="save_ret">Save and Return</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="header-right">
            <p>Logged as: <?php echo htmlspecialchars($return_username); ?> (<?php echo htmlspecialchars($return_role); ?>)</p>
        <div>
        <script type="text/javascript" src="scripts/function_inventory_raw_add_mindatehandler.js"></script>
        <script type="text/javascript" src="scripts/function_inventory_raw_add.js"></script>
        <script type="text/javascript" src="scripts/function_expiry.js"></script>
        <script type="text/javascript" src="templates/template_scripts/dropdown_script.js"></script>
        <script type="text/javascript" src="templates/template_scripts/sidenav_script.js"></script>
    </body>
</html>