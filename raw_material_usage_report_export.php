<?php
    include ('database_connection.php');

    date_default_timezone_set("Asia/Manila");

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=rawmaterialusage_report.csv');

    $csv_data_output = fopen('php://output', 'w');

    fputcsv($csv_data_output, ['Product Category', 'Product Name', 'Raw Material Code', 'Raw Material Description', 'Raw Material Amount', 'Raw Material Units',
    'Total Qty Sold', 'Total Ingredient Usage Amount', 'Total Ingredient Usage Units']);

    $inventory_search = "SELECT tbl_products_list.PROD_CATEGORY, tbl_products_list.PROD_NAME, tbl_product_ingredients.ING_CODE, tbl_product_ingredients.INGREDIENT_NAME, tbl_product_ingredients.INGREDIENT_AMOUNT, 
    tbl_product_ingredients.INGREDIENT_UNIT, 
    SUM(tbl_order_items.ORDER_PRODUCT_QTY) AS 'TOTAL_QTY', 
    SUM(tbl_order_items.ORDER_PRODUCT_QTY * tbl_product_ingredients.INGREDIENT_AMOUNT) AS 'TOTAL_USAGE' 
    FROM tbl_order_items 
    INNER JOIN tbl_products_list ON tbl_products_list.PROD_NAME = tbl_order_items.ORDER_PRODUCT_NAME
    INNER JOIN tbl_product_ingredients ON tbl_product_ingredients.PK_PROD_LIST = tbl_products_list.PK_PROD_LIST
    GROUP BY tbl_products_list.PROD_CATEGORY, tbl_products_list.PROD_NAME, tbl_product_ingredients.ING_CODE, tbl_product_ingredients.INGREDIENT_NAME, tbl_product_ingredients.INGREDIENT_AMOUNT, tbl_product_ingredients.INGREDIENT_UNIT 
    ORDER BY tbl_products_list.PROD_NAME DESC";
    
    $exec_showinventory = mysqli_query($databaseconn, $inventory_search);

    while($row = mysqli_fetch_assoc($exec_showinventory)){
        $ret_inventory_raw_prodcat = $row['PROD_CATEGORY'];
        $ret_inventory_raw_prodname = $row['PROD_NAME'];
        $ret_inventory_raw_ingcode = $row['ING_CODE'];
        $ret_inventory_raw_ingname = $row['INGREDIENT_NAME'];
        $ret_inventory_raw_amt = $row['INGREDIENT_AMOUNT'];
        $ret_inventory_raw_unit = $row['INGREDIENT_UNIT'];
        $ret_inventory_raw_qty = $row['TOTAL_QTY'];
        $ret_inventory_raw_usage = $row['TOTAL_USAGE'];

        fputcsv($csv_data_output, [$ret_inventory_raw_prodcat, $ret_inventory_raw_prodname, $ret_inventory_raw_ingcode, $ret_inventory_raw_ingname, 
        $ret_inventory_raw_amt, $ret_inventory_raw_unit, $ret_inventory_raw_qty, $ret_inventory_raw_usage, $ret_inventory_raw_unit]);
    }

    fclose($csv_data_output);
    exit();
?>