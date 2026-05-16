<?php
    SESSION_START();

    date_default_timezone_set("Asia/Manila");

    SESSION_UNSET();
    SESSION_DESTROY();

    header("Location: index.php");
?>