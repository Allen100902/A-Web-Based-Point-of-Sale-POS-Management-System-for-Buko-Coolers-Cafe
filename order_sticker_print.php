<?php
    require 'libraries/fpdf186/fpdf.php';
    include ('database_connection.php');

    date_default_timezone_set("Asia/Manila");
    
    if (!isset($_GET['ordernumber']) || !isset($_GET['orderitemid'])){
        echo "ERR_VIEW_2: Error - required parameters are missing!";
        exit;
    }

    $ordernumberID = $_GET['ordernumber'];
    $orderitemsID = $_GET['orderitemid'];

    $pdf = new FPDF('L', 'mm', array(50,25));
    $pdf->AddPage();
    $pdf->SetAutoPageBreak(FALSE, 50);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(15);

    $order_id_list_sel = "SELECT tbl_order_items.PK_ORDER_ID AS 'PK_ITEM', tbl_order_items.PK_ORDER_ID AS 'FK_ORDER', DATE_FORMAT(DATE_ADD(tbl_orders.ORDER_TIMESTAMP, INTERVAL 8 HOUR), '%Y-%m-%d %r') AS 'ORDER_TIMESTAMP', 
                                tbl_order_items.ORDER_PRODUCT_CATEGORY, tbl_order_items.ORDER_PRODUCT_NAME, 
                                tbl_order_items.ORDER_PRODUCT_QTY, (tbl_order_items.ORDER_PRODUCT_QTY * tbl_order_items.ORDER_PRICE) AS ORDER_PRICE
                                FROM tbl_orders RIGHT JOIN tbl_order_items ON tbl_orders.PK_ORDER_ID = tbl_order_items.PK_ORDER_ID WHERE tbl_order_items.PK_ORDER_ITEM = $orderitemsID 
                                AND tbl_order_items.PK_ORDER_ID = $ordernumberID";

    $exec_showorderid = mysqli_query($databaseconn, $order_id_list_sel);

    while($row = mysqli_fetch_assoc($exec_showorderid)){
        $ret_order_id_pkitem = $row['PK_ITEM'];
        $ret_order_id_fkorder = $row['FK_ORDER'];
        $ret_order_datetime = $row['ORDER_TIMESTAMP'];
        $ret_order_cat = $row['ORDER_PRODUCT_CATEGORY'];
        $ret_order_name = $row['ORDER_PRODUCT_NAME'];
        $ret_order_qty = $row['ORDER_PRODUCT_QTY'];
        $ret_order_price = $row['ORDER_PRICE'];
    }

    $pdf->SetXY(2, 3);
    $pdf->MultiCell(0, 4, 'Name of Order: ' . $ret_order_name, 0, 0);
    $pdf->SetXY(2, 10);
    $pdf->Cell(0, 5, 'Date and Time Prepared:', 0, 0);
    $pdf->SetXY(2, 14);
    $pdf->Cell(0, 5, '' .  $ret_order_datetime . '', 0, 0);
    $pdf->Output('I', 'order_' . $ordernumberID . '_' . 'item_' . $orderitemsID . '.pdf');


?>