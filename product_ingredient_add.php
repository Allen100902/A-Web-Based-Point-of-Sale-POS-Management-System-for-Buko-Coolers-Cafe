<?php
    include ('database_connection.php');
    SESSION_START();

    date_default_timezone_set("Asia/Manila");

    if (isset($_POST['save_ret'])){
        $PROD_INGREDIENT_FK = $_POST['prodlist'];
        $PROD_INGREDIENT_CODE = $_POST['ingcode'];
        $PROD_INGREDIENT_NAME = $_POST['ingname'];
        $PROD_INGREDIENT_AMT = $_POST['ingamt'];
        $PROD_INGREDIENT_UNIT = $_POST['ingunits'];

        $PROD_INGREDIENT_ADD = "INSERT INTO tbl_product_ingredients(PK_PROD_LIST, ING_CODE, INGREDIENT_NAME, INGREDIENT_AMOUNT, INGREDIENT_UNIT) VALUES ('{$PROD_INGREDIENT_FK}', '{$PROD_INGREDIENT_CODE}', '{$PROD_INGREDIENT_NAME}', '{$PROD_INGREDIENT_AMT}', '{$PROD_INGREDIENT_UNIT}')";

        $EXEC_PROD_INGREDIENT_ADD = mysqli_query($databaseconn, $PROD_INGREDIENT_ADD);

        if (!$PROD_INGREDIENT_ADD){
            echo "ERR_DB_2: Operation failed: " . mysqli_error($databaseconn);
        } else {
            echo "<script type='text/javascript'>window.location.href = 'product_ingredient_list.php';</script>";
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
        <title>Add Product Ingredient - Buko Coolers Cafe</title>
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
            <h1>ADD PRODUCT INGREDIENT DETAILS</h1>
            <div class="user_form">
                <form class="user_data_entry" method="POST" action="">
                    <fieldset class="form_product">
                        <legend><h3>Product Ingredient Information</h3></legend>
                        <div style="overflow-y: auto; height: 350px;">        
                            <div class="form_row">
                                <label for="prod_ing_code">Ingredient Code</label>
                                <select id="raw_prodlist" name="ingcode" required>
                                    <option value="">Select Raw Material Code</option>
                                    <?php
                                        $show_raw_mat_code = "SELECT MIN(INVENTORY_ITEM_CODE) AS INVENTORY_ITEM_CODE, INVENTORY_ITEM_NAME FROM tbl_inventory WHERE STATUS_INVENTORY_ITEM = 'ACTIVE' GROUP BY INVENTORY_ITEM_NAME ORDER BY INVENTORY_ITEM_CODE ASC;";

                                        $exec_showrawmat = mysqli_query($databaseconn, $show_raw_mat_code);
                                        $raw_listresult = [];

                                        while($row = mysqli_fetch_assoc($exec_showrawmat)){
                                            $ret_ing_code = $row['INVENTORY_ITEM_CODE'];
                                            $ret_ing_name = $row['INVENTORY_ITEM_NAME'];

                                            $raw_listresult[$ret_ing_code] = $ret_ing_name
                                    ?>
                                    <option value="<?php echo $ret_ing_code ?>"><?php echo $ret_ing_code?></option>
                                    <?php
                                        }
                                        echo "<script>var rawDescData = " . json_encode($raw_listresult) . ";</script>"
                                    ?>
                                </select>
                            </div>
                            <div class="form_row">
                                <label for="prod_list">Product</label>
                                <select id="product_list" name="prodlist" required>
                                    <option value="">Select Product</option>
                                    <?php
                                        $show_prod = "SELECT * FROM tbl_products_list WHERE STATUS = 'ACTIVE'";
                                        $exec_showprod = mysqli_query($databaseconn, $show_prod);

                                        $prod_listresult = [];
                                        while($row = mysqli_fetch_assoc($exec_showprod)){
                                            $ret_prod_id = $row['PK_PROD_LIST'];
                                            $ret_prod_cat = $row['PROD_CATEGORY'];
                                            $ret_prod_name = $row['PROD_NAME'];
                                            $ret_prod_price = $row['PROD_PRICE'];
                                            $ret_prod_status = $row['PROD_STATUS'];
                                            $ret_prod_image = $row['PROD_IMAGE'];
                                            $ret_status = $row['STATUS'];

                                            $prod_listresult[$ret_prod_id] = $ret_prod_name;
                                    ?>
                                    <option value="<?php echo $ret_prod_id ?>"><?php echo $ret_prod_cat . " - " . $row['PROD_NAME']; ?></option>
                                    <?php
                                        }
                                        echo "<script>var prodDescData = " . json_encode($prod_listresult) . ";</script>"
                                    ?>
                                </select>
                            </div>
                            <div class="form_row">
                                <label for="Prod_Ing_Name">Ingredient Name</label>
                                <input type="text" name="ingname" id="raw_name" readonly required>
                            </div>
                            <div class="form_row">
                                <label for="Prod_Ing_Amt">Amount Needed</label>
                                <input type="number" name="ingamt" id="prod_ing_amt" min="1" max="999" required>
                            </div>
                            <div class="form_row">
                                <label for="Prod_Ing_Units">Units</label>
                                <select name="ingunits" id="prod_ing_units">
                                    <option value="Grams">Grams</option>
                                    <option value="Mililiters">Mililiters</option>
                                    <option value="Piece">Piece</option>
                                    <option value="Cone">Cone</option>
                                </select>
                            </div>
                        </div>
                    </fieldset>
                    <div class="form_buttons">
                        <button type="reset" class="reset_btn">Clear Fields</button>
                        <button type="submit" class="take_action" name="save_ret">Add Product Ingredient Item</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="header-right">
            <p>Logged as: <?php echo htmlspecialchars($return_username); ?> (<?php echo htmlspecialchars($return_role); ?>)</p>
        <div>
        <script type="text/javascript" src="scripts/function_inventory_raw_add.js"></script>
        <script type="text/javascript" src="templates/template_scripts/dropdown_script.js"></script>
        <script type="text/javascript" src="templates/template_scripts/sidenav_script.js"></script>
    </body>
</html>