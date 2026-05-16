<?php
    include ('database_connection.php');

    date_default_timezone_set("Asia/Manila");
    
    if (isset($_GET['ProdIngID'])){
        $get_prod_ing_id = $_GET['ProdIngID'];
    }
    
    $del_prod_ing = "DELETE FROM tbl_product_ingredients WHERE PK_PROD_ING = $get_prod_ing_id";
    $exec_del_prod_ing = mysqli_query($databaseconn, $del_prod_ing);
    header("Location: product_ingredient_list.php");
?>