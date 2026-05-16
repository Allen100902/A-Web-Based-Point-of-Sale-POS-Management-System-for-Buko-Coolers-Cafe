<?php
    require 'libraries/fpdf186/fpdf.php';
    include ('database_connection.php');

    SESSION_START();

    date_default_timezone_set("Asia/Manila");

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

    if (!isset($_GET['SalesNumID'])){
        echo "ERR_VIEW_3: Error - Missing Receipt Number ID!";
        exit;
    }

    $SalesnumberID = $_GET['SalesNumID'];

    $pdf = new FPDF('P', 'mm', array(130,70));
    $pdf->AddPage();
    $pdf->SetAutoPageBreak(FALSE, 70);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(15);
    $pdf->Cell(20, 0, 'Buko Coolers Cafe', 0, 1, 'C');
    $pdf->Cell(0, 6, 'Rd 29, Cogeo Village Antipolo City', 0, 0, 'C');
    $pdf->Cell(-50, 13, 'FB: Bukocoolersofficial', 0, 0, 'C');

    $order_id_list_sel = "SELECT tbl_orders.PK_ORDER_ID AS 'PK_ORDER_ID', tbl_orders.CUST_NAME AS 'CUST_NAME', DATE_FORMAT(DATE_ADD(tbl_orders.ORDER_TIMESTAMP, INTERVAL 8 HOUR), '%Y-%m-%d %r') AS 'ORDER_TIMESTAMP',
        SUM(tbl_order_items.ORDER_PRODUCT_QTY) AS 'TOTAL_QTY', SUM(tbl_order_items.ORDER_PRICE * tbl_order_items.ORDER_PRODUCT_QTY) AS 'TOTAL_SALES', 
        tbl_orders.ORDER_DISCOUNT_TYPE AS 'DISCOUNT_TYPE', tbl_orders.ORDER_TOTAL_AMT AS 'TOTAL_DISCOUNT', tbl_orders.ORDER_PAYMENT_METHOD AS 'PAYMENT_METHOD', 
        tbl_orders.ORDER_PAID_AMT AS 'PAID_AMT', tbl_orders.ORDER_CHANGE_AMT AS 'CHANGE_AMT', tbl_orders.ORDER_GCASH_REF AS 'GCASH_REF' FROM tbl_orders 
        RIGHT JOIN tbl_order_items ON tbl_orders.PK_ORDER_ID = tbl_order_items.PK_ORDER_ID WHERE tbl_orders.PK_ORDER_ID = $SalesnumberID GROUP BY tbl_orders.PK_ORDER_ID;";

    $exec_showorderid = mysqli_query($databaseconn, $order_id_list_sel);

    while($row = mysqli_fetch_assoc($exec_showorderid)){
        $ret_order_id_upr = $row['PK_ORDER_ID'];
        $ret_order_cust_upr = $row['CUST_NAME'];
        $ret_order_timestamp_upr = $row['ORDER_TIMESTAMP'];
        $ret_order_qty_upr = $row['TOTAL_QTY'];
        $ret_order_price_upr = $row['TOTAL_SALES'];
        $ret_order_disc_upr = $row['DISCOUNT_TYPE'];
        $ret_order_disc_total_upr = $row['TOTAL_DISCOUNT'];
        $ret_order_payment_upr = $row['PAYMENT_METHOD'];
        $ret_order_paid = $row['PAID_AMT'];
        $ret_order_change = $row['CHANGE_AMT'];
        $ret_order_gcashref_upr = $row['GCASH_REF'];

        $gcash_numref = "None";
    }

    $pdf->Cell(0, 30, '===================================', 0, 1, 'C');
    $pdf->Cell(-7);
    $pdf->Cell(0, -24, 'SI#: ' . $ret_order_id_upr . '', 0, 0);
    $pdf->Cell(-57);
    $pdf->Cell(0, -18, 'Customer: ' . $ret_order_cust_upr . '', 0, 0);
    $pdf->Cell(-57);
    $pdf->Cell(0, -12, 'Invoice Date: ' . $ret_order_timestamp_upr . '', 0, 0);
    $pdf->Cell(-57);
    $pdf->Cell(0, -6, 'Staff: ' . $return_username . '', 0, 0);
    $pdf->Cell(-50);
    $pdf->Cell(0, 0, '===================================', 0, 0, 'C');
    $pdf->Cell(0, 0, '', 0, 1, 'C');
    $pdf->Cell(-7);
    $pdf->Cell(0, 6, 'Sales Invoice Details', 0, 0);
    
    $order_id_list = "SELECT tbl_orders.PK_ORDER_ID, DATE_FORMAT(tbl_orders.ORDER_TIMESTAMP, '%Y-%m-%d %r') AS 'ORDER_TIMESTAMP', tbl_order_items.ORDER_PRODUCT_CATEGORY, 
    tbl_order_items.ORDER_PRODUCT_NAME, tbl_order_items.ORDER_PRODUCT_QTY, (tbl_order_items.ORDER_PRODUCT_QTY * tbl_order_items.ORDER_PRICE) AS ORDER_PRICE
    FROM tbl_orders RIGHT JOIN tbl_order_items ON tbl_orders.PK_ORDER_ID = tbl_order_items.PK_ORDER_ID WHERE tbl_orders.PK_ORDER_ID = $SalesnumberID";

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
        $pdf->Cell(-7);
        $pdf->Cell(0, -13, $ret_order_qty . 'x ' . $ret_order_name, 0, 1);
        $pdf->Cell(58, 13, 'P' . $ret_order_price, 0, 0, 'R');
    }

    //with 12 percent VAT exempt as per panel - commented out code due to beneficiary NON-VAT in OR
    // $ret_order_price_upr_12VAT = number_format($ret_order_price_upr / 1.12, 2);
    // $ret_order_price_upr_20DISC = number_format($ret_order_price_upr_12VAT * 0.20, 2);

    // $pdf->Cell(-57);
    // $pdf->Cell(0, 16, '', 0, 0);
    // $pdf->Cell(-57);
    // $pdf->Cell(0, 20, '====', 0, 0);
    // $pdf->Cell(-6);
    // $pdf->Cell(0, 20, '==========', 0, 0);
    // $pdf->Cell(-57);
    // $pdf->Cell(0, 24, $ret_order_qty_upr . ' Item(s)', 0, 0, 'L');
    // $pdf->Cell(8, 24, 'Subtotal P'. $ret_order_price_upr, 0, 0, 'R');
    // $pdf->Cell(-57);
    // $pdf->Cell(0, 28, '', 0, 0);
    // $pdf->Cell(-52);
    // $pdf->Cell(0, 36, 'Sales ('. $ret_order_disc_upr .')', 0, 0, 'L');
    // $pdf->Cell(8, 36, 'P'. $ret_order_price_upr, 0, 0, 'R');
    // $pdf->Cell(-60);
    // $pdf->Cell(0, 42, 'Less VAT (12%)', 0, 0, 'L');
    // $pdf->Cell(8, 42, 'P'. $ret_order_price_upr - $ret_order_price_upr_12VAT, 0, 0, 'R');
    // $pdf->Cell(-60);
    // $pdf->Cell(0, 48, 'Amount Net of VAT', 0, 0, 'L');
    // $pdf->Cell(8, 48, 'P'. $ret_order_price_upr_12VAT, 0, 0, 'R');
    // $pdf->Cell(-60);
    // $pdf->Cell(0, 54, 'Less Discount (20%)', 0, 0, 'L');
    // $pdf->Cell(8, 54, 'P'. $ret_order_price_upr_20DISC, 0, 0, 'R');
    // $pdf->Cell(-60);
    // $pdf->Cell(0, 60, 'Less Discount', 0, 0, 'L');
    // $pdf->Cell(8, 60, 'P'. ($ret_order_price_upr - $ret_order_price_upr_12VAT) + $ret_order_price_upr_20DISC, 0, 0, 'R');
    // $pdf->Cell(-60);
    // $pdf->Cell(0, 66, 'Total Due', 0, 0, 'L');
    // $pdf->Cell(8, 66, 'P' . $ret_order_disc_total_upr, 0, 0, 'R');
    // $pdf->Cell(-57);
    // $pdf->Cell(0, 70, '', 0, 0);
    // $pdf->Cell(-52);
    // $pdf->Cell(0, 78, 'Payment', 0, 0, 'L');
    // $pdf->Cell(8, 78, $ret_order_payment_upr, 0, 0, 'R');
    // $pdf->Cell(-60);
    // $pdf->Cell(0, 84, 'Reference #', 0, 0, 'L');
    
    // if ($ret_order_gcashref_upr !== "" && $ret_order_payment_upr === "GCash"){
    //     $pdf->Cell(8, 84, $ret_order_gcashref_upr, 0, 0, 'R');
    // } else {
    //     $pdf->Cell(8, 84, 'None', 0, 0, 'R');
    // }
    
    // $pdf->Cell(-60);
    // $pdf->Cell(0, 90, 'Paid', 0, 0, 'L');
    // $pdf->Cell(8, 90, 'P'. $ret_order_paid, 0, 0, 'R');
    // $pdf->Cell(-60);
    // $pdf->Cell(0, 96, 'Change', 0, 0, 'L');
    // $pdf->Cell(8, 96, 'P'. $ret_order_change, 0, 0, 'R');
    // $pdf->Cell(-57);
    // $pdf->Cell(0, 102, '===================================', 0, 0, 'C');
    // $pdf->Cell(-50);
    // $pdf->Cell(0, 108, 'Thank You Come Again', 0, 0, 'C');
    // $pdf->Cell(-50);
    // $pdf->Cell(0, 120, 'Buko Coolers', 0, 0, 'C');
    // $pdf->Cell(-50);
    // $pdf->Cell(0, 126, 'Recreating the joy of', 0, 0, 'C');
    // $pdf->Cell(-50);
    // $pdf->Cell(0, 132, 'Island moments here in the city!', 0, 0, 'C');

    //NON-VAT OR only
    $pdf->Cell(-57);
    $pdf->Cell(0, 16, '', 0, 0);
    $pdf->Cell(-57);
    $pdf->Cell(0, 20, '====', 0, 0);
    $pdf->Cell(-6);
    $pdf->Cell(0, 20, '==========', 0, 0);
    $pdf->Cell(-57);
    $pdf->Cell(0, 24, $ret_order_qty_upr . ' Item(s)', 0, 0, 'L');
    $pdf->Cell(8, 24, 'Subtotal P'. $ret_order_price_upr, 0, 0, 'R');
    $pdf->Cell(-57);
    $pdf->Cell(0, 28, '', 0, 0);
    $pdf->Cell(-52);
    $pdf->Cell(0, 36, 'Sales Total', 0, 0, 'L');
    $pdf->Cell(8, 36, 'P'. $ret_order_price_upr, 0, 0, 'R');
    $pdf->Cell(-60);
    $pdf->Cell(0, 42, 'Less Discount', 0, 0, 'L');
    $pdf->Cell(8, 42, 'P'. number_format($ret_order_price_upr - $ret_order_disc_total_upr, 2), 0, 0, 'R');
    $pdf->Cell(-60);
    $pdf->Cell(0, 48, 'Total Due', 0, 0, 'L');
    $pdf->Cell(8, 48, 'P' . $ret_order_disc_total_upr, 0, 0, 'R');
    $pdf->Cell(-57);
    $pdf->Cell(0, 52, '', 0, 0);
    $pdf->Cell(-52);
    $pdf->Cell(0, 60, 'Payment', 0, 0, 'L');
    $pdf->Cell(8, 60, $ret_order_payment_upr, 0, 0, 'R');
    $pdf->Cell(-60);
    $pdf->Cell(0, 66, 'Reference #', 0, 0, 'L');
    
    if ($ret_order_gcashref_upr !== "" && $ret_order_payment_upr === "GCash"){
        $pdf->Cell(8, 66, $ret_order_gcashref_upr, 0, 0, 'R');
    } else {
        $pdf->Cell(8, 66, 'None', 0, 0, 'R');
    }
    
    $pdf->Cell(-60);
    $pdf->Cell(0, 72, 'Paid', 0, 0, 'L');
    $pdf->Cell(8, 72, 'P'. $ret_order_paid, 0, 0, 'R');
    $pdf->Cell(-60);
    $pdf->Cell(0, 78, 'Change', 0, 0, 'L');
    $pdf->Cell(8, 78, 'P'. $ret_order_change, 0, 0, 'R');
    $pdf->Cell(-57);
    $pdf->Cell(0, 84, '===================================', 0, 0, 'C');
    $pdf->Cell(-50);
    $pdf->Cell(0, 90, 'Thank You Come Again', 0, 0, 'C');
    $pdf->Cell(-50);
    $pdf->Cell(0, 102, 'Buko Coolers', 0, 0, 'C');
    $pdf->Cell(-50);
    $pdf->Cell(0, 108, 'Recreating island bliss', 0, 0, 'C');
    $pdf->Cell(-50);
    $pdf->Cell(0, 114, '', 0, 0, 'C');
    $pdf->Cell(-50);
    $pdf->Cell(0, 150, '-', 0, 0, 'C');

    $pdf->Output('I', 'order_' . $SalesnumberID . '_' . '.pdf');

?>