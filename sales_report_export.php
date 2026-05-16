<?php
    include ('database_connection.php');

    date_default_timezone_set("Asia/Manila");

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=salesreport.csv');

    $csv_data_output = fopen('php://output', 'w');

    fputcsv($csv_data_output, ['Sales Invoice #', 'Customer Name', 'Sales Date', 'Sales Total Qty', 
    'Sales Discount Type', 'Sales Subtotal', 'Sales Discount', 'Sales Total', 'Sales Payment Method']);

    $filter_start = isset($_GET['filterdatestart']) ? $_GET['filterdatestart'] : null;
    $filter_end = isset($_GET['filterdateend']) ? $_GET['filterdateend'] : null;

    $show_orderlist = "SELECT tbl_orders.PK_ORDER_ID AS 'PK_ORDER_ID', tbl_orders.CUST_NAME AS 'CUST_NAME', DATE_FORMAT(DATE_ADD(tbl_orders.ORDER_TIMESTAMP, INTERVAL 8 HOUR), '%Y-%m-%d %r') AS 'ORDER_TIMESTAMP',
    SUM(tbl_order_items.ORDER_PRODUCT_QTY) AS 'TOTAL_QTY', tbl_orders.ORDER_SUBTOTAL AS 'SUBTOTAL', tbl_orders.ORDER_TOTAL_AMT AS 
    'TOTAL_SALES', tbl_orders.ORDER_DISCOUNT_TYPE AS 'DISCOUNT_TYPE', tbl_orders.ORDER_PAYMENT_METHOD AS 'PAYMENT_METHOD' FROM tbl_orders 
    RIGHT JOIN tbl_order_items ON tbl_orders.PK_ORDER_ID = tbl_order_items.PK_ORDER_ID";

    if (!empty($filter_start) && !empty($filter_end)){
        $filter_start = mysqli_real_escape_string($databaseconn, $filter_start);
        $filter_end = mysqli_real_escape_string($databaseconn, $filter_end);

        $show_orderlist .= " WHERE DATE(tbl_orders.ORDER_TIMESTAMP) BETWEEN '$filter_start' AND '$filter_end'";
    }

    $show_orderlist .= " GROUP BY tbl_orders.PK_ORDER_ID ORDER BY tbl_orders.PK_ORDER_ID DESC";

    $exec_showorderlist = mysqli_query($databaseconn, $show_orderlist);

    while($row = mysqli_fetch_assoc($exec_showorderlist)){
        $ret_order_id = $row['PK_ORDER_ID'];
        $ret_order_cust = $row['CUST_NAME'];
        $ret_order_datetime = $row['ORDER_TIMESTAMP'];
        $ret_order_qty = $row['TOTAL_QTY'];
        $ret_order_disc = $row['DISCOUNT_TYPE'];
        $ret_total_sub = $row['SUBTOTAL'];
        $ret_order_sales = $row['TOTAL_SALES'];
        $ret_order_payment = $row['PAYMENT_METHOD'];

        fputcsv($csv_data_output, [$ret_order_id, $ret_order_cust, $ret_order_datetime, $ret_order_qty, 
        $ret_order_disc, 'P' . number_format($ret_total_sub, 2), 'P' . number_format($ret_total_sub - $ret_order_sales, 2), 'P' . number_format($ret_order_sales, 2), $ret_order_payment]);
    }

    fclose($csv_data_output);
    exit();
?>