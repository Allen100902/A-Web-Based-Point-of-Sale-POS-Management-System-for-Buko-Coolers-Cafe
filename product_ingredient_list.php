<?php
    include ('database_connection.php');
    SESSION_START();

    date_default_timezone_set("Asia/Manila");

    if (isset($_POST['add_prod'])){
        echo "<script type='text/javascript'>window.location.href = 'product_ingredient_add.php';</script>";
        exit();
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
        <title>Product Ingredient List - Buko Coolers Cafe</title>
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
            <h1>PRODUCT INGREDIENT LIST</h1>
            <div class="table_container_nouppr">
                <div style="overflow-y: auto; height: 470px;">
                    <table class="dataview">
                        <thead>
                            <tr>
                                <th>Product Category</th>
                                <th>Product Name</th>
                                <th>Product Ingredient Code</th>
                                <th>Product Ingredient Name</th>
                                <th>Product Ingredient Amount</th>
                                <th>Product Ingredient Units</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="table_body">
                            <tr>
                                <?php
                                    $show_prod_ingredients = "SELECT tbl_product_ingredients.PK_PROD_ING, tbl_product_ingredients.PK_PROD_LIST, tbl_products_list.PROD_CATEGORY, tbl_products_list.PROD_NAME, 
                                    tbl_product_ingredients.ING_CODE, tbl_product_ingredients.INGREDIENT_NAME, tbl_product_ingredients.INGREDIENT_AMOUNT, 
                                    tbl_product_ingredients.INGREDIENT_UNIT FROM tbl_products_list RIGHT JOIN tbl_product_ingredients ON tbl_products_list.PK_PROD_LIST = tbl_product_ingredients.PK_PROD_LIST 
                                    WHERE tbl_products_list.STATUS = 'ACTIVE' ORDER BY tbl_product_ingredients.PK_PROD_LIST ASC";
                                    $exec_showprod_ingredients = mysqli_query($databaseconn, $show_prod_ingredients);

                                    if (mysqli_num_rows($exec_showprod_ingredients) > 0){
                                        while($row = mysqli_fetch_assoc($exec_showprod_ingredients)){
                                            $ret_prod_ing_id = $row['PK_PROD_ING'];
                                            $ret_prod_id = $row['PK_PROD_LIST'];
                                            $ret_prod_cat = $row['PROD_CATEGORY'];
                                            $ret_prod_name = $row['PROD_NAME'];
                                            $ret_prod_ing_code = $row['ING_CODE'];
                                            $ret_prod_ing_name = $row['INGREDIENT_NAME'];
                                            $ret_prod_ing_amt = $row['INGREDIENT_AMOUNT'];
                                            $ret_prod_ing_unit = $row['INGREDIENT_UNIT'];

                                            echo "<tr>";
                                            echo "<td>$ret_prod_cat</td>";
                                            echo "<td>$ret_prod_name</td>";
                                            echo "<td><b>$ret_prod_ing_code</b></td>";
                                            echo "<td>$ret_prod_ing_name</td>";
                                            echo "<td>$ret_prod_ing_amt</td>";
                                            echo "<td>$ret_prod_ing_unit</td>";
                                            echo "<td><a href='product_ingredient_edit.php?ProdIngID={$ret_prod_ing_id}' onclick='return confirm(\"Are you sure do you want to confirm edit this product ingredient?\");';><i class='material-icons'>mode_edit</i></a> | <a href='product_ingredient_delete.php?ProdIngID={$ret_prod_ing_id}' onclick='return confirm(\"Are you sure do you want to confirm delete this product ingredient?\");'><i class='material-icons'>delete_forever</i></a>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr>
                                        <td colspan='7'>No Product Ingredients found.</td>
                                        </tr>";
                                    }
                                ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="button_under">
                <form class="list_forms_under" method="POST" action="">
                    <button id="prodadd" name="add_prod">ADD NEW PRODUCT INGREDIENT</button>
                </form>
            </div>
        </div>
        <div class="header-right">
            <p>Logged as: <?php echo htmlspecialchars($return_username); ?> (<?php echo htmlspecialchars($return_role); ?>)</p>
        <div>
        <script type="text/javascript" src="templates/template_scripts/dropdown_script.js"></script>
        <script type="text/javascript" src="templates/template_scripts/sidenav_script.js"></script>
    </body>
</html>