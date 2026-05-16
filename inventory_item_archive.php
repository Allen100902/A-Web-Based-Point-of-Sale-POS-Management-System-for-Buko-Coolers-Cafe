<?php
    include ('database_connection.php');

    date_default_timezone_set("Asia/Manila");

    if (isset($_GET['InventoryItemID'])){
        $get_invitemid = $_GET['InventoryItemID'];
    }
    
    $invmaterialitem_udate = "UPDATE tbl_inventory_materials_list SET INVENTORY_ITEM_STATUS = 'ARCHIVED', INVENTORY_ITEM_TIMESTAMP = NOW() WHERE PK_INVENTORY = '{$get_invitemid}'";
    $invmaterialitem_udate_exec = mysqli_query($databaseconn, $invmaterialitem_udate);
    header("Location: inventory_item_list.php");
?>