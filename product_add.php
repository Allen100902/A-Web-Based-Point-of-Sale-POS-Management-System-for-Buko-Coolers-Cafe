<?php
    include ('database_connection.php');
    SESSION_START();

    date_default_timezone_set("Asia/Manila");

    $directory_target = "images/products/";
    $maximumFileSize = 5 * 1024 * 1024;

    if (isset($_POST['save_ret'])){
        if (!empty($_FILES["prodimage"]["name"])){
            $PRODUCT_CATEGORY = $_POST['prodcat'];
            $PRODUCT_NAME = $_POST['prodname'];
            $PRODUCT_PRICE = $_POST['prodprice'];
            $PRODUCT_STATUS = $_POST['prodstatus'];

            $prod_imgname = basename($_FILES["prodimage"]["name"]);
            $prod_imgname_filetype = strtolower(pathinfo($prod_imgname, PATHINFO_EXTENSION));

            $file_allowtype = array('png');

            if (in_array($prod_imgname_filetype, $file_allowtype)){
                if ($_FILES["prodimage"]["size"] <= $maximumFileSize){
                    $check_png = getimagesize($_FILES["prodimage"]["tmp_name"]);
                    
                    if ($check_png !== FALSE && $check_png[2] == IMAGETYPE_PNG){
                        $prodimage_addfilename = time() . "_" . $prod_imgname;
                        $destination_dir = $directory_target . $prodimage_addfilename;

                        if (move_uploaded_file($_FILES["prodimage"]["tmp_name"], $destination_dir)){
                            $PROD_ADD = "INSERT INTO tbl_products_list (PROD_CATEGORY, PROD_NAME, PROD_PRICE, PROD_STATUS, PROD_IMAGE) VALUES ('{$PRODUCT_CATEGORY}','{$PRODUCT_NAME}','{$PRODUCT_PRICE}','{$PRODUCT_STATUS}','{$destination_dir}')";
                            $EXEC_PROD_ADD = mysqli_query($databaseconn, $PROD_ADD);

                            if (!$PROD_ADD) {
                                echo "ERR_DB_2: Operation failed: " . mysqli_error($databaseconn);
                            } else {
                                echo "<script type='text/javascript'>window.location.href = 'product_list.php';</script>";
                                exit();
                            }
                        } else {
                            echo "<script type='text/javascript'>alert('ERR_PROD_IT_1: Error adding product item: Error detected when uploading image'); 
                            window.location.href = '" . $_SERVER['HTTP_REFERER'] . "';
                            </script>";
                            exit();
                        } 
                    } else {
                        echo "<script type='text/javascript'>alert('ERR_PROD_IT_2: Error adding product item: The uploaded .png image file is invalid'); 
                        window.location.href = '" . $_SERVER['HTTP_REFERER'] . "';
                        </script>";
                        exit();
                    }
                    
                } else {
                    echo "<script type='text/javascript'>alert('ERR_PROD_IT_3: Error adding product item: The .png image file size is excessive of 5MB'); 
                    window.location.href = '" . $_SERVER['HTTP_REFERER'] . "';
                    </script>";
                    exit();   
                }
            }  else {
                echo "<script type='text/javascript'>alert('ERR_PROD_IT_4: Error adding product item: The image type is not a .png extension, please upload image using .png extension'); 
                window.location.href = '" . $_SERVER['HTTP_REFERER'] . "';
                </script>";
                exit(); 
            }
        } else {
            echo "<script type='text/javascript'>alert('ERR_PROD_IT_5: Error adding product item: No .png image file selected, please select .png image file to upload before adding a product item'); 
            window.location.href = '" . $_SERVER['HTTP_REFERER'] . "';
            </script>";
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
        <title>Add Product Detail - Buko Coolers Cafe</title>
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
            <h1>ADD PRODUCT DETAILS</h1>
            <div class="user_form">
                <form class="user_data_entry" method="POST" action="" enctype="multipart/form-data">
                    <fieldset class="form_product">
                        <legend><h3>Product Information</h3></legend>    
                        <div style="overflow-y: auto; height: 350px;">
                            <div class="form_row">
                                <label for="ProdCat">Product Category</label>
                                <select id="product_cat" name="prodcat" required>
                                    <option value="">Select Product</option>
                                    <?php
                                        $show_cat = "SELECT * FROM tbl_prod_categories WHERE PROD_CAT_STATUS = 'ACTIVE'";
                                        $exec_showcat = mysqli_query($databaseconn, $show_cat);

                                        $prod_catresult = [];

                                        while ($row = mysqli_fetch_assoc($exec_showcat)){
                                            $ret_prodcat_id = $row['PK_PROD_CAT'];
                                            $ret_prodcat_name = $row['PROD_CAT_NAME'];
                                            $ret_prodcat_status = $row['PROD_CAT_STATUS'];
                                            
                                            $prod_catresult[$ret_prodcat_id] = $ret_prodcat_name
                                    ?>
                                    <option value="<?php echo $ret_prodcat_name ?>"><?php echo $row['PROD_CAT_NAME']; ?></option>
                                    <?php
                                        }
                                        echo "<script>var prodDescData = " . json_encode($prod_catresult) . ";</script>"
                                    ?>
                                </select>
                            </div>
                            <div class="form_row">
                                <label for="ProdCatName">Product Name</label>
                                <input type="text" name="prodname" id="prod_name" placeholder="Enter new product name here" required>
                            </div>
                            <div class="form_row">
                                <label for="ProdPrice">Product Price</label>
                                <input type="number" name="prodprice" id="prod_price" min="1" max="500" required>
                            </div>
                            <div class="form_row">
                                <label for="ProdStatus">Availability</label>
                                <select name="prodstatus" id="prod_status" required>
                                    <option value="">Select</option>
                                    <option value="Available">Available</option>
                                    <option value="Not Available">Not Available</option>
                                </select>
                            </div>
                            <div class="form_row">
                                <label for="ProdImg">Product Image</label>
                                <input type="file" name="prodimage" id="prod_image" accept="image/png">
                            </div>
                        </div>
                    </fieldset>
                    <div class="form_buttons">
                        <button type="reset" class="reset_btn">Clear Fields</button>
                        <button type="submit" class="take_action" name="save_ret">Add Product Item</button>
                    </div>
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