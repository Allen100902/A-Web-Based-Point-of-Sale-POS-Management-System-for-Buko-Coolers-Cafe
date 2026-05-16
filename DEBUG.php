<?php
    include ('database_connection.php');
    //SESSION_START();

    date_default_timezone_set("Asia/Manila");

    $best_seller_query = "SELECT ORDER_PRODUCT_CATEGORY, ORDER_PRODUCT_NAME, SUM(ORDER_PRODUCT_QTY) AS 'PROD_QTY', ORDER_PRICE, 
    SUM(ORDER_PRICE * ORDER_PRODUCT_QTY) AS 'TOTAL_SALES' FROM `tbl_order_items` GROUP BY 
    ORDER_PRODUCT_NAME ORDER BY TOTAL_SALES DESC";
    $exec_best_seller = mysqli_query($databaseconn, $best_seller_query);

    $best_seller_total_query =  "SELECT SUM(ORDER_PRICE * ORDER_PRODUCT_QTY) AS 'ALL_TOTAL_SALES' FROM tbl_order_items";
    $exec_best_seller_total = mysqli_query($databaseconn, $best_seller_total_query);
    
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
        <title>Debug Page - Buko Coolers Cafe</title>
        <link rel="stylesheet" type="text/css" href="styles/dashboard.css">
        <link rel="stylesheet" type="text/css" href="styles/viewdata_style.css">
        <link rel="stylesheet" type="text/css" href="styles/dashboard_style.css">
        <link rel="icon" href="templates/designs/logo.png" type="image/x-icon">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    </head>

    <body>
        <div class="form_container">
            <h1>Expiry/Out of Stock Reminder</h1>
            <div class="table_container_nouppr">
                <div style="overflow-y: auto; height: 470px;">
                    <table class="dataview">
                        <thead>
                            <tr>
                                <th>Product Category</th>
                                <th>Product Name</th>
                                <th>Unit Price</th>
                                <th>Total Sold</th>
                                <th>Total Sales</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody id="table_body">
                            <tr>
                                <?php
                                    while($row = mysqli_fetch_assoc($exec_best_seller_total)){
                                        $ret_total_sales = $row['ALL_TOTAL_SALES'];
                                    }

                                    while($row = mysqli_fetch_assoc($exec_best_seller)){
                                        $ret_best_cat = $row['ORDER_PRODUCT_CATEGORY'];
                                        $ret_best_prod = $row['ORDER_PRODUCT_NAME'];
                                        $ret_best_qty = $row['PROD_QTY'];
                                        $ret_best_unit = $row['ORDER_PRICE'];
                                        $ret_best_sales = $row['TOTAL_SALES'];

                                        $ret_total_sales_proportion = number_format((($ret_best_sales / $ret_total_sales) * 100), 2);

                                        echo "<tr>";
                                        echo "<td>$ret_best_cat</td>";
                                        echo "<td>$ret_best_prod</td>";
                                        echo "<td>₱$ret_best_unit</td>";
                                        echo "<td>$ret_best_qty</td>";
                                        echo "<td>₱$ret_best_sales</td>";
                                        echo "<td><b>$ret_total_sales_proportion%</b></td>";
                                        echo "</tr>";
                                    }
                                ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>