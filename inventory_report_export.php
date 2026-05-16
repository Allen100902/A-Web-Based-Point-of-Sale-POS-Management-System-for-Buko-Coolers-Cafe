<?php
    include ('database_connection.php');

    date_default_timezone_set("Asia/Manila");

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=inventoryreport.csv');

    $csv_data_output = fopen('php://output', 'w');

    fputcsv($csv_data_output, ['Inventory Code', 'Inventory Name', 'Inventory Amount Available', 'Inventory Units', 
    'Inventory Cost of Goods', 'Minimum Stock Level', 'Stock Level Status', 'Last Updated']);

    $filter_start = isset($_GET['filterdate']) ? $_GET['filterdate'] : null;

    $inventory_search = "SELECT PK_INVENTORY_ITEM, INVENTORY_ITEM_CODE, INVENTORY_ITEM_NAME, SUM(INVENTORY_ITEM_AMOUNT) AS 'INVENTORY_ITEM_AMOUNT', INVENTORY_ITEM_UNITS, INVENTORY_ITEM_STOCK_MIN, SUM(INVENTORY_ITEM_COG) AS 'INVENTORY_ITEM_COG', DATE_FORMAT(DATE_ADD(INVENTORY_ITEM_TIMESTAMP, INTERVAL 8 HOUR), '%Y-%m-%d') AS 'INVENTORY_ITEM_TIMESTAMP' FROM tbl_inventory";

    if (!empty($filter_start)){
        $filter_start = mysqli_real_escape_string($databaseconn, $filter_start);

        $inventory_search .= " WHERE INVENTORY_ITEM_TIMESTAMP >= '$filter_start' AND STATUS_INVENTORY_ITEM = 'ACTIVE' GROUP BY INVENTORY_ITEM_CODE, INVENTORY_ITEM_NAME, INVENTORY_ITEM_UNITS, INVENTORY_ITEM_STOCK_MIN";
    }

    $inventory_search .= " WHERE STATUS_INVENTORY_ITEM = 'ACTIVE' GROUP BY INVENTORY_ITEM_CODE, INVENTORY_ITEM_NAME, INVENTORY_ITEM_UNITS, INVENTORY_ITEM_STOCK_MIN";

    $exec_showinventory = mysqli_query($databaseconn, $inventory_search);

    while($row = mysqli_fetch_assoc($exec_showinventory)){
        $ret_inventory_id = $row['PK_INVENTORY_ITEM'];
        $ret_inventory_code = $row['INVENTORY_ITEM_CODE'];
        $ret_inventory_name = $row['INVENTORY_ITEM_NAME'];
        $ret_inventory_amt = $row['INVENTORY_ITEM_AMOUNT'];
        $ret_inventory_units = $row['INVENTORY_ITEM_UNITS'];
        $ret_inventory_min = $row['INVENTORY_ITEM_STOCK_MIN'];
        $ret_inventory_cog = $row['INVENTORY_ITEM_COG'];
        $ret_inventory_timestamp = $row['INVENTORY_ITEM_TIMESTAMP'];

        $warning_value = 0.2;
        $warning_stock_parameter = $ret_inventory_min + ($ret_inventory_min * $warning_value);

        if (fmod($warning_stock_parameter, 1) !== 0.0){
            $warning_stock = intval($warning_stock_parameter) + 1;
        } else {
            $warning_stock = intval($warning_stock_parameter);
        }

        if ($ret_inventory_amt <= $ret_inventory_min){
            $stock_level_state = "CRITICAL";
        } else if ($ret_inventory_amt <= $warning_stock){
            $stock_level_state = "REORDER";
        } else {
            $stock_level_state = "NORMAL";
        }

        fputcsv($csv_data_output, [$ret_inventory_code, $ret_inventory_name, $ret_inventory_amt, $ret_inventory_units, 
        'P' . number_format($ret_inventory_cog, 2), $ret_inventory_min, $stock_level_state, $ret_inventory_timestamp]);
    }

    fclose($csv_data_output);
    exit();
?>