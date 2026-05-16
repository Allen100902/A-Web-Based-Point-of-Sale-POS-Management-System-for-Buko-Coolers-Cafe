<?php
    include ('database_connection.php');

    date_default_timezone_set("Asia/Manila");

    if (isset($_GET['PastryID'])){
        $get_pastryid = $_GET['PastryID'];
    }
    
    $archive_pastryid = "UPDATE tbl_inventory SET STATUS_INVENTORY_ITEM = 'ARCHIVED', INVENTORY_ITEM_TIMESTAMP = NOW() WHERE PK_INVENTORY_ITEM = $get_pastryid";
    $exec_archive_pastryid = mysqli_query($databaseconn, $archive_pastryid);
    header("Location: inventory_pastry_list.php");
?>