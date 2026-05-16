<?php
    include ('database_connection.php');

    date_default_timezone_set("Asia/Manila");

    if (isset($_GET['RawMatID'])){
        $get_rmatid = $_GET['RawMatID'];
    }
    
    $archive_rmatid = "UPDATE tbl_inventory SET STATUS_INVENTORY_ITEM = 'ARCHIVED', INVENTORY_ITEM_TIMESTAMP = NOW() WHERE PK_INVENTORY_ITEM = $get_rmatid";
    $exec_archive_rmatid = mysqli_query($databaseconn, $archive_rmatid);
    header("Location: inventory_raw_list.php");
?>