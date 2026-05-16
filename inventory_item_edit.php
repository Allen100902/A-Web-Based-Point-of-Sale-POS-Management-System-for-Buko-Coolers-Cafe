<?php
    include ('database_connection.php');
    SESSION_START();

    date_default_timezone_set("Asia/Manila");

    if (isset($_GET['InventoryItemID'])){
        $get_invitemid = $_GET['InventoryItemID'];

        $show_invitem = "SELECT * FROM tbl_inventory_materials_list WHERE PK_INVENTORY = $get_invitemid";
        $exec_show_invitem = mysqli_query($databaseconn, $show_invitem);

        while($row = mysqli_fetch_assoc($exec_show_invitem)){
            $ret_inv_id = $row['PK_INVENTORY'];
            $ret_inv_code = $row['INVENTORY_ITEM_CODE'];
            $ret_inv_desc = $row['INVENTORY_ITEM_DESCRIPTION'];
            $ret_inv_status = $row['INVENTORY_ITEM_STATUS'];
            $ret_inv_datetime = $row['INVENTORY_ITEM_TIMESTAMP'];
        }

        $get_numcode = preg_replace('/\D/', '', $ret_inv_code);
        $raw_material_category_selected_check = (stripos($ret_inv_code, 'RAW') !== false) ? 'selected' : '';
        $pastry_item_category_selected_check = (stripos($ret_inv_code, 'PAST') !== false) ? 'selected' : '';
    }

    if (isset($_POST['save_ret'])){
        $INVENTORY_MATERIAL_CATEGORY = $_POST['materialcat'];
        $INVENTORY_MATERIAL_CODE = $_POST['materialcode'];
        $INVENTORY_MATERIAL_DESCRIPTION = $_POST['materialdesc'];

        if ($INVENTORY_MATERIAL_CATEGORY == "Raw Material"){
            $rawmatcode = "RAW";
            $rawmatcode_concat = $rawmatcode . $INVENTORY_MATERIAL_CODE;

            $rawmaterialitem_udate = "UPDATE tbl_inventory_materials_list SET INVENTORY_ITEM_CODE='{$rawmatcode_concat}',INVENTORY_ITEM_DESCRIPTION='{$INVENTORY_MATERIAL_DESCRIPTION}', INVENTORY_ITEM_TIMESTAMP = NOW() WHERE PK_INVENTORY = '{$get_invitemid}'";
            $rawmaterialitem_udate_exec = mysqli_query($databaseconn, $rawmaterialitem_udate);

            if (!$rawmaterialitem_udate) {
                echo "ERR_DB_2: Operation failed: " . mysqli_error($databaseconn);
            } else {
                echo "<script type='text/javascript'>window.location.href = 'inventory_item_list.php';</script>";
                exit();
            }

        } else if ($INVENTORY_MATERIAL_CATEGORY == "Pastry Item") {
            $pastmatcode = "PAST";
            $pastmatcode_concat = $pastmatcode . $INVENTORY_MATERIAL_CODE;

            $pastmaterialitem_udate = "UPDATE tbl_inventory_materials_list SET INVENTORY_ITEM_CODE='{$pastmatcode_concat}',INVENTORY_ITEM_DESCRIPTION='{$INVENTORY_MATERIAL_DESCRIPTION}', INVENTORY_ITEM_TIMESTAMP = NOW() WHERE PK_INVENTORY = '{$get_invitemid}'";
            $pastmaterialitem_udate_exec = mysqli_query($databaseconn, $pastmaterialitem_udate);

            if (!$pastmaterialitem_udate) {
                echo "ERR_DB_2: Operation failed: " . mysqli_error($databaseconn);
            } else {
                echo "<script type='text/javascript'>window.location.href = 'inventory_item_list.php';</script>";
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
    <html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Inventory Item - Buko Coolers Cafe</title>
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
            <h1>EDIT INVENTORY ITEM</h1>
            <div class="user_form">
                <form class="user_data_entry" method="POST" action="">
                    <fieldset class="form_inventory_materials">
                        <legend><h3>Item Information</h3></legend>
                        <div class="form_row">
                            <label for="raw_itemunits">Category</label>
                            <select name="materialcat" id="material_cat" required>
                                <option value="">Select</option>
                                <option value="Raw Material" <?php echo $raw_material_category_selected_check; ?>>Raw Material</option>
                                <option value="Pastry Item" <?php echo $pastry_item_category_selected_check; ?>>Pastry Item</option>
                            </select>
                        </div>
                        <div class="form_row">
                            <label for="mat_code">Inventory Material Code</label>
                            <input type="number" name="materialcode" id="material_code" value="<?php echo htmlspecialchars($get_numcode)?>" min="1" required>
                        </div>
                        <div class="form_row">
                            <label for="mat_itemname">Inventory Material Description</label>
                            <input type="text" name="materialdesc" id="material_desc" value="<?php echo $ret_inv_desc?>" required>
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
        <script type="text/javascript" src="templates/template_scripts/dropdown_script.js"></script>
        <script type="text/javascript" src="templates/template_scripts/sidenav_script.js"></script>
    </body>
</html>