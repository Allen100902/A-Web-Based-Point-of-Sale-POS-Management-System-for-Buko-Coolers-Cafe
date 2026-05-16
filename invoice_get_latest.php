<?php
    include ('database_connection.php');

    date_default_timezone_set("Asia/Manila");

    $INVOICE_GET_QUERY = "SELECT PK_ORDER_ID FROM tbl_orders ORDER BY PK_ORDER_ID DESC LIMIT 1";
    $INVOICE_GET_QUERY_RESULT = mysqli_query($databaseconn, $INVOICE_GET_QUERY);
    $row = mysqli_fetch_assoc($INVOICE_GET_QUERY_RESULT);

    $INV_NUM = isset($row['PK_ORDER_ID']) ? $row['PK_ORDER_ID'] + 1 : 1;

    echo json_encode(['invoice_num' => $INV_NUM]);
?>