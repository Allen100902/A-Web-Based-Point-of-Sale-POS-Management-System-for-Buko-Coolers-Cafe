<?php 
    require 'libraries/fpdf186/fpdf.php';
    include ('database_connection.php');

    date_default_timezone_set("Asia/Manila");

    $pdf = new FPDF('L', 'mm', 'Letter');
    $pdf->AddPage();
    $pdf->Image('templates/designs/logo.png', 10, 0, 30);
    $pdf->SetFont('Arial', 'B', 16);

    $pdftitle = "Buko Coolers Sales Report";

    $pdf->Cell(0, 10, $pdftitle, 0, 1, 'C');
    $pdf->Ln(10);

    $filter_start = isset($_GET['filterdatestart']) ? $_GET['filterdatestart'] : null;
    $filter_end = isset($_GET['filterdateend']) ? $_GET['filterdateend'] : null;

    $show_orderlist = "SELECT tbl_orders.PK_ORDER_ID AS 'PK_ORDER_ID', tbl_orders.CUST_NAME AS 'CUST_NAME', DATE_FORMAT(DATE_ADD(tbl_orders.ORDER_TIMESTAMP, INTERVAL 8 HOUR), '%Y-%m-%d %r') AS 'ORDER_TIMESTAMP',
    SUM(tbl_order_items.ORDER_PRODUCT_QTY) AS 'TOTAL_QTY', tbl_orders.ORDER_SUBTOTAL AS 'SUBTOTAL', tbl_orders.ORDER_TOTAL_AMT AS 
    'TOTAL_SALES', tbl_orders.ORDER_DISCOUNT_TYPE AS 'DISCOUNT_TYPE', tbl_orders.ORDER_PAYMENT_METHOD AS 'PAYMENT_METHOD' FROM tbl_orders 
    RIGHT JOIN tbl_order_items ON tbl_orders.PK_ORDER_ID = tbl_order_items.PK_ORDER_ID";

    $show_sum_head = "SELECT SUM(tbl_order_items.ORDER_PRODUCT_QTY) AS 'TOTAL_QTY', (SELECT SUM(tbl_orders.ORDER_TOTAL_AMT) FROM tbl_orders";

    if (!empty($filter_start) && !empty($filter_end)){
        $filter_start = mysqli_real_escape_string($databaseconn, $filter_start);
        $filter_end = mysqli_real_escape_string($databaseconn, $filter_end);

        $show_orderlist .= " WHERE DATE(tbl_orders.ORDER_TIMESTAMP) BETWEEN '$filter_start' AND '$filter_end'";
        $show_sum_head .= " WHERE DATE(tbl_orders.ORDER_TIMESTAMP) BETWEEN '$filter_start' AND '$filter_end') AS 'TOTAL_SALES' 
        FROM tbl_orders JOIN tbl_order_items ON tbl_orders.PK_ORDER_ID = tbl_order_items.PK_ORDER_ID WHERE DATE(tbl_orders.ORDER_TIMESTAMP) BETWEEN '$filter_start' AND '$filter_end'";
    }

    $show_orderlist .= " GROUP BY tbl_orders.PK_ORDER_ID ORDER BY tbl_orders.PK_ORDER_ID DESC";
    $show_sum_head .= " ORDER BY tbl_orders.PK_ORDER_ID DESC";

    $exec_showorderlist = mysqli_query($databaseconn, $show_orderlist);
    $exec_sum_head = mysqli_query($databaseconn, $show_sum_head);

    $pdf->SetFont('Arial', 'B', 8);

    $header_yaxis = $pdf->getY();
    $header_xaxis = $pdf->getX();

    $column_width_head = [20, 35, 30, 25, 30, 30, 30, 30, 30];
    $header_title = ["Sales\nInvoice #", "Customer Name\n ", "Sales Date\n ", "Total Qty\n ", "Discount\nType", "Subtotal\n ", "Discount\n ", "Total\n ", "Payment\nMethod"];

    for ($init_header_count = 0; $init_header_count < count($header_title); $init_header_count++){
        $pdf->SetXY($header_xaxis, $header_yaxis);
        $pdf->MultiCell($column_width_head[$init_header_count], 5, $header_title[$init_header_count], 1, 'C');
        $header_xaxis += $column_width_head[$init_header_count];
    }
    $pdf->SetFont('Arial', '', 7);

    if ($exec_showorderlist && mysqli_num_rows($exec_showorderlist) > 0) {
        while($row = mysqli_fetch_assoc($exec_showorderlist)){
            $ret_order_id = $row['PK_ORDER_ID'];
            $ret_order_cust = $row['CUST_NAME'];
            $ret_order_datetime = $row['ORDER_TIMESTAMP'];
            $ret_order_qty = $row['TOTAL_QTY'];
            $ret_order_disc = $row['DISCOUNT_TYPE'];
            $ret_total_sub = $row['SUBTOTAL'];
            $ret_order_sales = $row['TOTAL_SALES'];
            $ret_order_payment = $row['PAYMENT_METHOD'];

            $ret_discount = $ret_total_sub - $ret_order_sales;

            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(20, 5, $ret_order_id, 1);
            
            $pdf->SetFont('Arial', '', 7);
            if ($ret_order_cust === "") {
                $pdf->Cell(35, 5, "None", 1);
            } else {
                $pdf->Cell(35, 5, $ret_order_cust, 1);
            }
            $pdf->Cell(30, 5, "$ret_order_datetime", 1);
            $pdf->Cell(25, 5, $ret_order_qty, 1);
            $pdf->Cell(30, 5, $ret_order_disc, 1);
            $pdf->Cell(30, 5, "P" . number_format($ret_total_sub, 2), 1);
            $pdf->Cell(30, 5, "P" . number_format($ret_discount, 2), 1);
            $pdf->Cell(30, 5, "P" . number_format($ret_order_sales, 2), 1);
            $pdf->Cell(30, 5, $ret_order_payment, 1);
            $pdf->Ln();
        }

        $ret_total_qty = 0;
        $ret_total_sales = 0;

        if ($exec_sum_head && mysqli_num_rows($exec_sum_head) > 0){
            $head_row = mysqli_fetch_assoc($exec_sum_head);
            $ret_total_qty = $head_row['TOTAL_QTY'];
            $ret_total_sales = $head_row['TOTAL_SALES'];
        }

        $pdf->Ln();
        $pdf->Cell(30, 5, "Start Date: " . $filter_start, 0);
        $pdf->Ln();
        $pdf->Cell(30, 5, "End Date: " . $filter_end, 0);
        $pdf->Ln();
        $pdf->Cell(30, 5, "Total Sales: P" . $ret_total_sales, 0);

    } else {
        $pdf->Cell(0, 10, "No Sales Reports as of this Period.", 1, 1, 'C');
    }

    $date_pdf = date("Y-m-d g:i:s A");
    $pdf->Ln();
    $pdf->Cell(30, 5, "Report Date Generated: " . $date_pdf, 0);

    $pdf->Output('I', 'BukoCoolersSalesReport' . '.pdf');
    exit();
?>