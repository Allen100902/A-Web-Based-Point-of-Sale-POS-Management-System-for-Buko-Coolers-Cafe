<?php
    include ('database_connection.php');
    SESSION_START();

    date_default_timezone_set("Asia/Manila");

    if (!isset($_SESSION['cartitem'])) {
        $_SESSION['cartitem'] = [];
    }

    if (isset($_POST['remove_item']) && isset($_POST['remove_sel_item'])){
        $remove_sel_itemid = $_POST['remove_sel_item'];
        
        if (isset($_SESSION['cartitem']) && is_array($_SESSION['cartitem'])){
            $order_itemlen = count($_SESSION['cartitem']);

            for($init = 0; $init < $order_itemlen; $init++){
                if ($_SESSION['cartitem'][$init]['prod_id'] == $remove_sel_itemid){
                    unset($_SESSION['cartitem'][$init]);
                    $_SESSION['cartitem'] = array_values($_SESSION['cartitem']);
                    break;
                }
            }
        }

        echo "<script type='text/javascript'>window.location.href = 'order_processing.php';</script>";
        exit();
    }

    if (isset($_POST['save_ret'])) {
        $PRODUCT_ID = $_POST['placeitem'];
        $PRODUCT_QTY = $_POST['order_qty'];
        $CART_QUERY = "SELECT * FROM tbl_products_list WHERE PK_PROD_LIST = '$PRODUCT_ID' AND PROD_STATUS = 'Available'";
        $CART_RESULT = mysqli_query($databaseconn, $CART_QUERY);

        if ($row = mysqli_fetch_assoc($CART_RESULT)) {
            $detect_item = false;

            foreach ($_SESSION['cartitem'] as &$cart_item) {
                if ($cart_item['prod_id'] == $PRODUCT_ID) {
                    $cart_item['prod_qty'] += $PRODUCT_QTY;
                    $detect_item = true;
                    break;
                }
            }

            if (!$detect_item) {
                $_SESSION['cartitem'][] = [
                    'prod_id' => $row['PK_PROD_LIST'],
                    'prod_category' => $row['PROD_CATEGORY'],
                    'prod_name' => $row['PROD_NAME'],
                    'prod_price' => $row['PROD_PRICE'],
                    'prod_qty' => $PRODUCT_QTY
                ];
            }
        }

        if (isset($_SESSION['REMOVE_ITEM'])) {
            unset($_SESSION['REMOVE_ITEM']);
        }

        echo "<script type='text/javascript'>window.location.href = 'order_processing.php';</script>";
        exit();
    }

    else if (isset($_POST['tender'])) {
        mysqli_begin_transaction($databaseconn);

        try {
            $INPUT_CUSTOMER_NAME = trim($_POST['cust_name']);

            if (empty($INPUT_CUSTOMER_NAME)){
                $INPUT_CUSTOMER_NAME = "None";
            }

            $INPUT_TOTAL_QTY = $_POST['total_order_qty'];
            $INPUT_SUBTOTAL = $_POST['subtotal'];
            $INPUT_DISCOUNT = $_POST['disc_type'];
            $INPUT_TOTALAMT = $_POST['totalamt'];
            $INPUT_PAYMENT = $_POST['payment_method'];

            $TENDER_AMT = isset($_POST['tender_amount']) ? $_POST['tender_amount'] : 0;
            $GCASH_REF = isset($_POST['gcash_refnum']) ? $_POST['gcash_refnum'] : '';
            $RETURN_CHANGE = isset($_POST['change']) ? $_POST['change'] : 0;

            if ($INPUT_PAYMENT == 'Cash' && $TENDER_AMT < $INPUT_TOTALAMT) {
                echo "<script>alert('ERR_ORDER_VAL_1: Transaction Failed: Insufficient payment amount!'); window.location.href = 'order_processing.php';</script>";
                exit();
            }

            $INPUT_CUSTOMER_NAME = mysqli_real_escape_string($databaseconn, $INPUT_CUSTOMER_NAME);
            $INSERT_ORDER_DATA = "INSERT INTO tbl_orders (CUST_NAME, ORDER_TIMESTAMP, ORDER_SUBTOTAL, ORDER_TOTAL_AMT, ORDER_DISCOUNT_TYPE, ORDER_PAYMENT_METHOD, ORDER_PAID_AMT, ORDER_CHANGE_AMT, ORDER_GCASH_REF) VALUES ('{$INPUT_CUSTOMER_NAME}', NOW(), '{$INPUT_SUBTOTAL}', '{$INPUT_TOTALAMT}', '{$INPUT_DISCOUNT}', '{$INPUT_PAYMENT}', '{$TENDER_AMT}', '{$RETURN_CHANGE}', '{$GCASH_REF}')";

            if (!mysqli_query($databaseconn, $INSERT_ORDER_DATA)) {
                $err = mysqli_error($databaseconn);
                echo "<script>alert('ERR_ORDER_TX_1: Transaction Failed, Failed to Add Order: {$err}'); window.location.href = 'order_processing.php';</script>";
                exit();
            }

            $LAST_INSERTED_ORDER_ID = mysqli_insert_id($databaseconn);

            if (!empty($_SESSION['cartitem'])) {
                foreach ($_SESSION['cartitem'] as &$cart_item) {
                    $PROD_CAT = mysqli_real_escape_string($databaseconn, $cart_item['prod_category']);
                    $PRODUCT_NAME = mysqli_real_escape_string($databaseconn, $cart_item['prod_name']);
                    $PRODUCT_QUANTITY = $cart_item['prod_qty'];
                    $PRODUCT_PRICE = $cart_item['prod_price'];
                    $PRODUCT_SUB = $PRODUCT_PRICE * $PRODUCT_QUANTITY;

                    $INSERT_ORDER_ITEMDATA = "INSERT INTO tbl_order_items (PK_ORDER_ID, ORDER_PRODUCT_CATEGORY, ORDER_PRODUCT_NAME, ORDER_PRODUCT_QTY, ORDER_PRICE, ORDER_ITEM_TIMESTAMP) VALUES ('{$LAST_INSERTED_ORDER_ID}', '{$PROD_CAT}', '{$PRODUCT_NAME}', '{$PRODUCT_QUANTITY}', '{$PRODUCT_PRICE}', NOW())";

                    if (!mysqli_query($databaseconn, $INSERT_ORDER_ITEMDATA)) {
                        $err = mysqli_error($databaseconn);
                        echo "<script>alert('ERR_ORDER_TX_2: Transaction Failed, Error inserting order items: {$err}'); window.location.href = 'order_processing.php';</script>";
                        exit();
                    }

                    $QUERY_INGREDIENTS = "SELECT tbl_product_ingredients.ING_CODE, tbl_product_ingredients.INGREDIENT_AMOUNT, tbl_products_list.PROD_NAME FROM tbl_product_ingredients JOIN tbl_products_list ON tbl_product_ingredients.PK_PROD_LIST = tbl_products_list.PK_PROD_LIST WHERE tbl_products_list.PROD_NAME = '{$PRODUCT_NAME}' AND tbl_products_list.PROD_STATUS = 'Available' AND tbl_products_list.STATUS = 'ACTIVE'";
                    $EXEC_INGREDIENTS_QUERY = mysqli_query($databaseconn, $QUERY_INGREDIENTS);

                    if (!$EXEC_INGREDIENTS_QUERY) {
                        $err = mysqli_error($databaseconn);
                        echo "<script>alert('ERR_ORDER_TX_3: Transaction Failed, Error fetching product ingredients: {$err}'); window.location.href = 'order_processing.php';</script>";
                        exit();
                    }

                    while($ingredient_row = mysqli_fetch_assoc($EXEC_INGREDIENTS_QUERY)){
                        $ING_CODE = $ingredient_row['ING_CODE'];
                        $ING_AMOUNT = $ingredient_row['INGREDIENT_AMOUNT'];
                        $DEDUCT_TOTAL = $ING_AMOUNT * $PRODUCT_QUANTITY;

                        while($DEDUCT_TOTAL > 0){
                            $BATCH_EXP_QUERY = "SELECT PK_INVENTORY_ITEM, INVENTORY_ITEM_NAME, INVENTORY_ITEM_AMOUNT, INVENTORY_ITEM_EXP, STATUS_INVENTORY_ITEM FROM tbl_inventory WHERE INVENTORY_ITEM_CODE = '$ING_CODE' AND INVENTORY_ITEM_AMOUNT > 0 AND STATUS_INVENTORY_ITEM = 'ACTIVE' ORDER BY INVENTORY_ITEM_EXP ASC LIMIT 1;";
                            $EXEC_BATCH_EXP = mysqli_query($databaseconn, $BATCH_EXP_QUERY);

                            if (!$EXEC_BATCH_EXP){
                                $err = mysqli_error($databaseconn);
                                echo "<script>alert('ERR_ORDER_TX_4: Transaction Failed, Error fetching inventory material expiry date: {$err}'); window.location.href = 'order_processing.php';</script>";
                                exit();
                            }

                            if (mysqli_num_rows($EXEC_BATCH_EXP) == 0){
                                break;
                            }

                            $BATCH_EXP_ROW = mysqli_fetch_assoc($EXEC_BATCH_EXP);
                            $PK_BATCH_EXP = $BATCH_EXP_ROW['PK_INVENTORY_ITEM'];
                            $BATCH_EXP_AMT = $BATCH_EXP_ROW['INVENTORY_ITEM_AMOUNT'];
                            $DATE_EXP_TRIGGER = $BATCH_EXP_ROW['INVENTORY_ITEM_EXP'];
                            $STATUS_CODE_CONFIRM = $BATCH_EXP_ROW['STATUS_INVENTORY_ITEM'];

                            if ($BATCH_EXP_AMT >= $DEDUCT_TOTAL){
                                $UDATED_AMT = $BATCH_EXP_AMT - $DEDUCT_TOTAL;
                                $DEDUCT_TOTAL = 0;
                            } else {
                                $UDATED_AMT = 0;
                                $DEDUCT_TOTAL -= $BATCH_EXP_AMT;
                            }

                            $UDATE_INVENTORY_INVENTORY_ITEM = "UPDATE tbl_inventory SET INVENTORY_ITEM_AMOUNT = '{$UDATED_AMT}', INVENTORY_ITEM_TIMESTAMP = NOW() WHERE PK_INVENTORY_ITEM = '{$PK_BATCH_EXP}' AND INVENTORY_ITEM_EXP = '{$DATE_EXP_TRIGGER}' AND STATUS_INVENTORY_ITEM = '{$STATUS_CODE_CONFIRM}'";

                            if (!mysqli_query($databaseconn, $UDATE_INVENTORY_INVENTORY_ITEM)){
                                $err = mysqli_error($databaseconn);
                                echo "<script>alert('ERR_ORDER_TX_5: Transaction Failed, Error updating inventory amount: {$err}'); window.location.href = 'order_processing.php';</script>";
                                exit();
                            }
                        }

                        $CHECK_REMAIN_STOCK = "SELECT SUM(INVENTORY_ITEM_AMOUNT) AS INVENTORY_ITEM_AMOUNT FROM tbl_inventory WHERE INVENTORY_ITEM_CODE = '{$ING_CODE}'";
                        $EXEC_STOCK_REMAIN_CHECK = mysqli_query($databaseconn, $CHECK_REMAIN_STOCK);

                        if (!$EXEC_STOCK_REMAIN_CHECK){
                            $err = mysqli_error($databaseconn);
                            echo "<script>alert('ERR_ORDER_TX_6: Transaction Failed, Error checking remaining total inventory amount: {$err}'); window.location.href = 'order_processing.php';</script>";
                            exit();
                        }

                        $ROW_STOCK = mysqli_fetch_assoc($EXEC_STOCK_REMAIN_CHECK);
                        $REMAIN_STOCK = $ROW_STOCK['INVENTORY_ITEM_AMOUNT'];

                        if ($REMAIN_STOCK <= 0){
                            $PROD_UNAVAIL_SET_AUTO = "UPDATE tbl_products_list JOIN tbl_product_ingredients ON tbl_products_list.PK_PROD_LIST = tbl_product_ingredients.PK_PROD_LIST SET tbl_products_list.PROD_STATUS = 'Not Available' WHERE tbl_product_ingredients.ING_CODE = '{$ING_CODE}'";

                            if (!mysqli_query($databaseconn, $PROD_UNAVAIL_SET_AUTO)) {
                                $err = mysqli_error($databaseconn);
                                echo "<script>alert('ERR_ORDER_TX_7: Transaction Failed, Error updating product availability: {$err}'); window.location.href = 'order_processing.php';</script>";
                                exit();
                            }
                        }
                    }
                }

                $product_active_check = "SELECT * FROM tbl_products_list WHERE PROD_STATUS = 'Available' AND STATUS = 'ACTIVE'";
                $exec_product_active_check = mysqli_query($databaseconn, $product_active_check);

                while($row = mysqli_fetch_assoc($exec_product_active_check)){
                    $ret_prod_id = $row['PK_PROD_LIST'];
                    $ret_prod_cat = $row['PROD_CATEGORY'];
                    $ret_prod_name = $row['PROD_NAME'];
                    $ret_prod_price = $row['PROD_PRICE'];
                    $ret_prod_status = $row['PROD_STATUS'];
                    $ret_prod_image = $row['PROD_IMAGE'];
                    $ret_status = $row['STATUS'];

                    $prod_unavail_setting = false;
                    $prod_ingredient_verify = false;
                
                    $product_ingredient_query = "SELECT tbl_product_ingredients.PK_PROD_LIST, tbl_product_ingredients.ING_CODE, tbl_product_ingredients.INGREDIENT_AMOUNT, 
                    tbl_product_ingredients.INGREDIENT_UNIT, SUM(tbl_inventory.INVENTORY_ITEM_AMOUNT) AS 'INVENTORY_ITEM_AMOUNT', tbl_inventory.INVENTORY_ITEM_STOCK_MIN, tbl_inventory.INVENTORY_ITEM_UNITS FROM 
                    tbl_product_ingredients JOIN tbl_inventory ON tbl_product_ingredients.ING_CODE = tbl_inventory.INVENTORY_ITEM_CODE WHERE tbl_product_ingredients.PK_PROD_LIST = '$ret_prod_id'
                    AND tbl_inventory.STATUS_INVENTORY_ITEM = 'ACTIVE' GROUP BY tbl_product_ingredients.ING_CODE, tbl_product_ingredients.INGREDIENT_AMOUNT";
                    $exec_prod_ingredients_query = mysqli_query($databaseconn, $product_ingredient_query);
                    
                    if (mysqli_num_rows($exec_prod_ingredients_query) === 0){
                        $prod_unavail_setting = true;
                    } else {
                        while ($row2 = mysqli_fetch_assoc($exec_prod_ingredients_query)){
                            $prod_ingredient_verify = true;
                            
                            $ret_prod_ingredient_pk = $row2['PK_PROD_LIST'];
                            $ret_ing_code = $row2['ING_CODE'];
                            $ret_ing_amt = $row2['INGREDIENT_AMOUNT'];
                            $ret_ing_unit = $row2['INGREDIENT_UNIT'];
                            $ret_inb_amt = $row2['INVENTORY_ITEM_AMOUNT'];
                            $ret_inb_stocklvl = $row2['INVENTORY_ITEM_STOCK_MIN'];
                            $ret_inb_units = $row2['INVENTORY_ITEM_UNITS'];

                            if ($ret_inb_amt < $ret_ing_amt){
                                $prod_unavail_setting = true;
                                break;
                            }
                        }
                    }

                    $update_prod_avail = $prod_unavail_setting ? "Not Available" : "Available";
                    $update_prod_avail_query = "UPDATE tbl_products_list SET PROD_STATUS = '$update_prod_avail' WHERE PK_PROD_LIST = '$ret_prod_id'";

                    if (!mysqli_query($databaseconn, $update_prod_avail_query)){
                        $err = mysqli_error($databaseconn);
                        echo "<script>alert('ERR_ORDER_TX_7: Transaction Failed, Error updating product availability: {$err}'); window.location.href = 'order_processing.php';</script>";
                        exit();
                    }
                }

                $notif_title = "New Order Alert";
                $notif_info = "New Order has been placed and paid by " . $INPUT_CUSTOMER_NAME ." with a total of " . $INPUT_TOTALAMT . ". Please check order " . $LAST_INSERTED_ORDER_ID . " for order information.";

                $notif_query = "INSERT INTO tbl_notifications (NOTIF_TITLE, NOTIF_INFO) VALUES ('{$notif_title}','{$notif_info}')";
                if (!mysqli_query($databaseconn, $notif_query)){
                    $err = mysqli_error($databaseconn);
                    echo "<script>alert('ERR_ORDER_TX_8: Transaction Failed, Error triggering notification: {$err}'); window.location.href = 'order_processing.php';</script>";
                    exit();
                }
                
            } else {
                echo "<script>alert('ERR_ORDER_VAL_2: Order items list is empty. No order items to insert.'); window.location.href = 'order_processing.php';</script>";
                exit();
            }

            mysqli_commit($databaseconn);
            unset($_SESSION['cartitem']);
            echo "<script>
                    alert('Payment successful! Sales Invoice Number: {$LAST_INSERTED_ORDER_ID}');
                    window.open('order_print.php?OrderNumID={$LAST_INSERTED_ORDER_ID}', '_blank');
                    window.open('sales_receipt_print.php?SalesNumID={$LAST_INSERTED_ORDER_ID}', '_blank');
                    window.location.href = 'order_processing.php';
                </script>";
            exit();
        
        } catch (Exception $ERROR){
            mysqli_rollback($databaseconn);
            echo "<script>alert('ERR_ORDER_TX_9: Error Performing Transaction: " . $ERROR->getMessage() . "'); window.location.href = 'order_processing.php';</script>";
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

    if (isset($_POST['confirmlogin']) && isset($_SESSION['PK_USER']) && $return_role === 'Staff'){
        if (isset($_POST['usrname']) && isset($_POST['password'])){

            function validate($data){
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }

            $validate_username = validate($_POST['usrname']);
            $validate_password = validate($_POST['password']);
        
            if (empty($validate_username)){
                echo "<script type='text/javascript'>alert('ERR_AUTH_1: Username is required'); 
                window.location.href = '" . $_SERVER['HTTP_REFERER'] . "';
                </script>";
                exit();
            }

            if (empty($validate_password)){
                echo "<script type='text/javascript'>alert('ERR_AUTH_2: Password is required'); 
                window.location.href = '" . $_SERVER['HTTP_REFERER'] . "';
                </script>";
                exit();
            }

            $sql = "SELECT * FROM tbl_users WHERE USERNAME = '$validate_username' AND USER_PASSWORD = '$validate_password' AND ROLE = 'Administrator' AND EMPLOYEE_STATUS = 'ACTIVE'";

            $res_query = mysqli_query($databaseconn, $sql);

            if (mysqli_num_rows($res_query) === 1){
                $row = mysqli_fetch_assoc($res_query);

                if ($row['USERNAME'] === $validate_username && $row['USER_PASSWORD']){
                    
                    $_SESSION['REMOVE_ITEM'] = true;

                    echo "<script type='text/javascript'>alert('Administrator Credentials correct. You can now void items from cart.'); 
                    window.location.href = '" . $_SERVER['HTTP_REFERER'] . "';
                    </script>";
                    exit();
                } else {
                    echo "<script type='text/javascript'>alert('ERR_AUTH_3: Invalid username or password!'); 
                    window.location.href = '" . $_SERVER['HTTP_REFERER'] . "';
                    </script>";
                    exit();
                }
            } else {
                echo "<script type='text/javascript'>alert('ERR_ORDER_VOID_1: Invalid administrator credentials detected! Permission denied!'); 
                window.location.href = '" . $_SERVER['HTTP_REFERER'] . "';
                </script>";
                exit();
            }
            
        }
    } else if (isset($_POST['confirmlogin']) && isset($_SESSION['PK_USER']) && $return_role === 'Administrator'){
        if (isset($_POST['password'])){

            function validate($data){
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }

            $validate_username = validate($return_username);
            $validate_password = validate($_POST['password']);

            if (empty($validate_password)){
                echo "<script type='text/javascript'>alert('ERR_AUTH_2: Password is required'); 
                window.location.href = '" . $_SERVER['HTTP_REFERER'] . "';
                </script>";
                exit();
            }

            $sql = "SELECT * FROM tbl_users WHERE USERNAME = '$validate_username' AND USER_PASSWORD = '$validate_password' AND ROLE = 'Administrator' AND EMPLOYEE_STATUS = 'ACTIVE'";

            $res_query = mysqli_query($databaseconn, $sql);

            if (mysqli_num_rows($res_query) === 1){
                $row = mysqli_fetch_assoc($res_query);

                if ($row['USERNAME'] === $validate_username && $row['USER_PASSWORD']){
                    
                    $_SESSION['REMOVE_ITEM'] = true;

                    echo "<script type='text/javascript'>alert('Administrator Credentials correct. You can now void items from cart.'); 
                    window.location.href = '" . $_SERVER['HTTP_REFERER'] . "';
                    </script>";
                    exit();
                }
            } else {
                echo "<script type='text/javascript'>alert('ERR_ORDER_VOID_1: Invalid administrator credentials detected! Permission denied!'); 
                window.location.href = '" . $_SERVER['HTTP_REFERER'] . "';
                </script>";
                exit();
            }
        }
    }
?>


<!DOCTYPE html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create Order - Buko Coolers Cafe</title>
        <link rel="stylesheet" type="text/css" href="styles/dashboard.css">
        <link rel="stylesheet" type="text/css" href="styles/dataentry_style.css">
        <link rel="stylesheet" type="text/css" href="styles/dashboard_style.css">
        <link rel="stylesheet" type="text/css" href="styles/authentication_validation.css">
        <link rel="stylesheet" type="text/css" href="styles/index.css">
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
            <h1>CREATE ORDER</h1>
            <div class="user_form">
                <form class="user_data_entry" method="POST" action="">
                    <fieldset class="form_info_order2">
                        <legend><h3>Product Availability List</h3></legend>
                        <div class="form_row">
                            <br>
                            <div style="overflow-y: auto; height: 290px;">
                                <table class="prod_avail_list">
                                    <thead>
                                        <tr>
                                            <th>Selected</th>
                                            <th>Product Category</th>
                                            <th>Product Name</th>
                                            <th>Product Price</th>
                                            <th>Product Image</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $show_available = "SELECT * FROM tbl_products_list WHERE PROD_STATUS = 'Available' AND STATUS = 'ACTIVE'";
                                        $exec_showavailable = mysqli_query($databaseconn, $show_available);

                                        while ($row = mysqli_fetch_assoc($exec_showavailable)){
                                            $ret_prodavail_id = $row['PK_PROD_LIST'];
                                            $ret_prodavail_cat = $row['PROD_CATEGORY'];
                                            $ret_prodavail_name = $row['PROD_NAME'];
                                            $ret_prodavail_price = $row['PROD_PRICE'];
                                            $ret_prodavail_image = $row['PROD_IMAGE'];
                                            
                                            echo "<tr>";
                                            echo "<td><input type='radio' class='itemsel' name='placeitem' value='{$row['PK_PROD_LIST']}' prod_data='{$row['PROD_NAME']}' price_data='{$row['PROD_PRICE']}'></option></td>";
                                            echo "<td>$ret_prodavail_cat</td>";
                                            echo "<td>$ret_prodavail_name</td>";
                                            echo "<td>₱$ret_prodavail_price</td>";
                                            echo "<td><img src='$ret_prodavail_image' width='100' height='100' alt='Image Error.'></td>";
                                            echo "</tr>";
                                        }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <br>
                        <div class="form_row">
                            <label for="ProdQty">Order Quantity</label>
                            <input type="number" name="order_qty" id="order_quantity" min="1" max="150" required value="<?php echo isset($_POST['order_qty']) ? $_POST['order_qty'] : ''; ?>">
                        </div>
                        <br>
                        <div class="form_buttons">
                            <button type="submit" id="itemadd_cart" name="save_ret">Add to Current Order</button>
                        </div>
                    </fieldset>
                </form>
            </div>

            <div class="order_cart">
                <fieldset class="form_info_order3">
                    <legend><h3>Items in Your Order</h3></legend>
                    <div class="form_row">
                        <div style="overflow-y: auto; width: 500px; height: 410px;">
                            <table class="order_item">
                                <thead>
                                    <tr>
                                        <th>Product Category</th>
                                        <th>Product</th>
                                        <th>Unit Price</th>
                                        <th>Qty</th>
                                        <th>Total Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="item_cart">
                                    <?php
                                        $QTY_TOTAL = 0;
                                        $SUBTOTAL = 0;
                                        
                                        if (!isset($_SESSION['cartitem'])){
                                            $_SESSION['cartitem'] = [];
                                        }

                                        $QTY_TOTAL = 0;
                                        $SUBTOTAL = 0;

                                        foreach ($_SESSION['cartitem'] as $cart_item){
                                            $TOTAL_ITEM = $cart_item['prod_price'] * $cart_item['prod_qty'];
                                            $SUBTOTAL += $TOTAL_ITEM;
                                            $QTY_TOTAL += $cart_item['prod_qty'];
                                            
                                            $remove_disabled = isset($_SESSION['REMOVE_ITEM']) && $_SESSION['REMOVE_ITEM'] === true ? '' : 'disabled';

                                            echo "<tr>
                                            <td>{$cart_item['prod_category']}</td>
                                            <td>{$cart_item['prod_name']}</td>
                                            <td>₱{$cart_item['prod_price']}</td>
                                            <td>{$cart_item['prod_qty']}</td>
                                            <td>₱$TOTAL_ITEM.00</td>
                                            <td><form method='POST' action='' style='display:inline;'>
                                                <input type='hidden' name='remove_sel_item' value='{$cart_item['prod_id']}'>
                                                <button type='submit' class='itemremove_cart' name='remove_item' $remove_disabled><i class='material-icons'>remove_circle</i></button>
                                            </form></td>
                                            </tr>"; 
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="pos_under">
                <fieldset class="form_info_order4">
                    <legend><h3>Payment Information</h3></legend>
                    <form method="POST" action="">
                        <div style="overflow-y: auto; height: 350px;">
                            <div class="form_row">
                                <label for="si_label">Invoice Number</label>
                                <input type="text" name="invoice_num" id="si_inputs" style="font-weight: bold;" value="" readonly>
                            </div>
                            <div class="form_row">
                                <label for="si_label">Customer Name</label>
                                <input type="text" name="cust_name" id="customerfield" disabled>
                            </div>
                            <div class="form_row">
                                <label for="payqty">Total Qty</label>
                                <input type="text" name="total_order_qty" id="total_qty" value="<?php echo $QTY_TOTAL; ?>" readonly>
                            </div>
                            <div class="form_row">
                                <label for="paysub">Subtotal</label>
                                <span id="total_sub_display">₱<?php echo ($SUBTOTAL > 1000) ? number_format($SUBTOTAL, 2) : number_format($SUBTOTAL, 2); ?></span>
                                <input type="hidden" name="subtotal" id="total_sub" value="<?php echo number_format($SUBTOTAL, 2, '.', ''); ?>">
                            </div>
                            <div class="form_row">
                                <label for="paysub">Discount Type</label>
                                <select name="disc_type" id="disc_type" onchange="appdiscount()">
                                    <option value="None">None</option>
                                    <option value="SC/PWD">SC/PWD (20%)</option>
                                </select>
                            </div>
                            <div class="form_row">
                                <label for="paysub">Total Amount</label>
                                <span id="return_total_amt">₱0.00</span>
                                <input type="hidden" name="totalamt" id="total_amt" value="0.00">
                            </div>
                            <div class="form_row">
                                <label for="paymethod">Payment Method</label>
                                <select name="payment_method" id="payment_method" required disabled>
                                    <option value="">Select Payment</option>
                                    <option value="Cash">Cash</option>
                                    <option value="GCash">GCash</option>
                                </select>
                            </div>
                            <div class="form_row">
                                <label for="paytender">Cash Tendered ₱</label>
                                <input type="number" name="tender_amount" id="paid_amount" required disabled oninput="cashchange()">
                            </div>
                            <div class="form_row">
                                <label for="gcashref">GCash Reference #</label>
                                <input type="text" name="gcash_refnum" id="ref_gcash" disabled>
                            </div>
                            <div class="form_row">
                                <label for="changetender">Change</label>
                                <span id="return_change_amount">₱0.00</span>
                                <input type="hidden" name="change" id="change_amount" value="0.00">
                            </div>
                        </div>
                        <br>
                        <div class="form_row">
                            <button type="submit" id="btnconfirm_order" name="order_confirmed">Confirm Order</button>
                            <button type="button" id="btnvoid_order" name="cancel_order">Void Order</button>
                            <button type="submit" id="btnpay_order" name="tender" disabled>Pay</button>
                        </div>
                    </form>
                </fieldset>
            </div>
        </div>
        <div class="header-right">
            <p>Logged as: <?php echo htmlspecialchars($return_username); ?> (<?php echo htmlspecialchars($return_role); ?>)</p>
        <div>
        <div class="auth_container" id="auth_popup" style="display:none;">
            <div class="auth_box">
                <form class="login_form" action="" method="POST">
                    <div class="form_row">
                        <h1>This priviledge requires Authentication.</h1>
                    </div>
                    <?php if ($return_role === 'Staff'){?>
                    <div class="form_row">
                        <label for="username">Administrator Username:</label>
                        <input type="text" name="usrname" placeholder="Enter Username">
                    </div> <?php } ?>
                    <div class="form_row">
                        <label for="username">Administrator Password:</label>
                        <input type="password" name="password" id="password_visible" placeholder="Enter Password">
                    </div> 
                    <div class="form_buttons">
                        <button class="login_btn" name="confirmlogin">CONFIRM</button>
                        <a class="cancel_btn" href="order_create.php">CANCEL</a>
                    </div>
                </form>
            </div>
        </div>
        <script type="text/javascript" src="scripts/auth_scripts.js"></script>
        <script type="text/javascript" src="scripts/function_place_order.js"></script>
        <script type="text/javascript" src="scripts/function_invoice.js"></script>
        <script type="text/javascript" src="templates/template_scripts/dropdown_script.js"></script>
        <script type="text/javascript" src="templates/template_scripts/sidenav_script.js"></script>
    </body>
</html>

