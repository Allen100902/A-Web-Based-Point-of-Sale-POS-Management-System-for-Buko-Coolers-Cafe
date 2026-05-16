<?php
    include ('database_connection.php');

    date_default_timezone_set("Asia/Manila");

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=bestsellerproductsanalytics_report.csv');

    $csv_data_output = fopen('php://output', 'w');

    fputcsv($csv_data_output, ['Product Category', 'Product Name', 'Unit Price', 'Total Sold', 'Total Sales']);

    $best_seller_query = "SELECT ORDER_PRODUCT_CATEGORY, ORDER_PRODUCT_NAME, SUM(ORDER_PRODUCT_QTY) AS 'PROD_QTY', ORDER_PRICE, 
    SUM(ORDER_PRICE * ORDER_PRODUCT_QTY) AS 'TOTAL_SALES' FROM `tbl_order_items` GROUP BY 
    ORDER_PRODUCT_NAME ORDER BY TOTAL_SALES DESC LIMIT 10";
    
    $exec_best_seller = mysqli_query($databaseconn, $best_seller_query);

    while($row = mysqli_fetch_assoc($exec_best_seller)){
        $ret_best_cat = $row['ORDER_PRODUCT_CATEGORY'];
        $ret_best_prod = $row['ORDER_PRODUCT_NAME'];
        $ret_best_qty = $row['PROD_QTY'];
        $ret_best_unit = $row['ORDER_PRICE'];
        $ret_best_sales = $row['TOTAL_SALES'];

        fputcsv($csv_data_output, [$ret_best_cat, $ret_best_prod, $ret_best_qty, $ret_best_unit, "P" . $ret_best_sales]);
    }

    fclose($csv_data_output);
    exit();
?>