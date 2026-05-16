<?php
    require 'libraries/fpdf186/fpdf.php';
    include ('database_connection.php');

    date_default_timezone_set("Asia/Manila");

    if (!isset($_GET['OrderNumID'])){
        echo "ERR_VIEW_2: Error - Missing Order Number ID!";
        exit;
    }

    $ordernumberID = $_GET['OrderNumID'];

    $pdf = new FPDF('P', 'mm', array(80,70));
    $pdf->AddPage();
    $pdf->SetAutoPageBreak(FALSE, 70);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(15);
    $pdf->Cell(20, 0, 'Buko Coolers Cafe', 0, 1, 'C');
    $pdf->Cell(0, 6, 'Rd 29, Cogeo Village Antipolo City', 0, 0, 'C');
    $pdf->Cell(-50, 13, 'FB: Bukocoolersofficial', 0, 0, 'C');

    $order_id_list_sel = "SELECT tbl_orders.PK_ORDER_ID, tbl_orders.CUST_NAME, DATE_FORMAT(DATE_ADD(tbl_orders.ORDER_TIMESTAMP, INTERVAL 8 HOUR), '%Y-%m-%d %r') AS 'ORDER_TIMESTAMP', tbl_order_items.ORDER_PRODUCT_CATEGORY, 
    tbl_order_items.ORDER_PRODUCT_NAME, tbl_order_items.ORDER_PRODUCT_QTY, tbl_order_items.ORDER_PRICE FROM tbl_orders RIGHT JOIN 
    tbl_order_items ON tbl_orders.PK_ORDER_ID = tbl_order_items.PK_ORDER_ID WHERE tbl_orders.PK_ORDER_ID = $ordernumberID";

    $exec_showorderid = mysqli_query($databaseconn, $order_id_list_sel);

    while($row = mysqli_fetch_assoc($exec_showorderid)){
        $ret_order_id = $row['PK_ORDER_ID'];
        $ret_order_cust = $row['CUST_NAME'];
        $ret_order_datetime = $row['ORDER_TIMESTAMP'];
        $ret_order_cat = $row['ORDER_PRODUCT_CATEGORY'];
        $ret_order_name = $row['ORDER_PRODUCT_NAME'];
        $ret_order_qty = $row['ORDER_PRODUCT_QTY'];
        $ret_order_price = $row['ORDER_PRICE'];
    }

    $pdf->Cell(0, 30, '===================================', 0, 1, 'C');
    $pdf->Cell(-7);
    $pdf->Cell(0, -24, 'Order #: ' . $ret_order_id . '', 0, 0);
    $pdf->Cell(-57);
    $pdf->Cell(0, -18, 'Customer: ' . $ret_order_cust . '', 0, 0);
    $pdf->Cell(-57);
    $pdf->Cell(0, -12, 'Order Date: ' . $ret_order_datetime . '', 0, 0);
    $pdf->Cell(-50);
    $pdf->Cell(0, -6, '===================================', 0, 1, 'C');
    $pdf->Cell(0, 0, '', 0, 1, 'C');
    $pdf->Cell(-7);
    $pdf->Cell(0, 12, 'Order Details:', 0, 0);
    
    $order_id_list = "SELECT tbl_orders.PK_ORDER_ID, DATE_FORMAT(DATE_ADD(tbl_orders.ORDER_TIMESTAMP, INTERVAL 8 HOUR), '%Y-%m-%d %r') AS 'ORDER_TIMESTAMP', tbl_order_items.ORDER_PRODUCT_CATEGORY, 
    tbl_order_items.ORDER_PRODUCT_NAME, tbl_order_items.ORDER_PRODUCT_QTY, (tbl_order_items.ORDER_PRODUCT_QTY * tbl_order_items.ORDER_PRICE) AS ORDER_PRICE
    FROM tbl_orders RIGHT JOIN tbl_order_items ON tbl_orders.PK_ORDER_ID = tbl_order_items.PK_ORDER_ID WHERE tbl_orders.PK_ORDER_ID = $ordernumberID";

    $exec_showorderlist = mysqli_query($databaseconn, $order_id_list);

    while($row = mysqli_fetch_assoc($exec_showorderlist)){
        $ret_order_id = $row['PK_ORDER_ID'];
        $ret_order_datetime = $row['ORDER_TIMESTAMP'];
        $ret_order_cat = $row['ORDER_PRODUCT_CATEGORY'];
        $ret_order_name = $row['ORDER_PRODUCT_NAME'];
        $ret_order_qty = $row['ORDER_PRODUCT_QTY'];
        $ret_order_price = $row['ORDER_PRICE'];

        $pdf->Cell(-57);
        $pdf->Cell(0, 16, '', 0, 1);
        $pdf->Cell(-7, -13);
        $pdf->Cell(0, -13, $ret_order_qty . 'x ' . $ret_order_name, 0, 1);
    }

    $pdf->Cell(-57);
    $pdf->Cell(0, 16, '', 0, 0);
    $pdf->Cell(-57);
    $pdf->Cell(0, 20, 'END OF ORDER', 0, 1);
    $pdf->Cell(-57);
    $pdf->Cell(0, 44, '', 0, 0, 'C');
    $pdf->Cell(-57);
    $pdf->Cell(0, 76, '-', 0, 0, 'C');

    $pdf->Output('I', 'order_' . $ordernumberID . '_' . '.pdf');

?>