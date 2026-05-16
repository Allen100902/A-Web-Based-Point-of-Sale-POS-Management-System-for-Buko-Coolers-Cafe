<?php

date_default_timezone_set("Asia/Manila");

$servername = "localhost";
$serverusername = "u325660191_buko_coolers";
$serverpassword = "Bukocoolers_2025";
$databasename = "u325660191_buko_coolers";

$databaseconn = mysqli_connect($servername, $serverusername, $serverpassword, $databasename);

if (!$databaseconn){
    die("ERR_DB_1: Database Connection Failure at " . date("d/m/y H:i:s") . ": " . mysqli_connect_error());
}

?>