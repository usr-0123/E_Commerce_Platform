<?php
    $host = "localhost";
    $user = "root";
    $password = "";

    $connect = new mysqli($host, $user, $password);

    $db_name = 'ecommerce';

    $sql = "CREATE DATABASE IF NOT EXISTS $db_name";

    if ($connect->query($sql) === TRUE) {
        echo "Database $db_name created successfully";
    } else {
        echo "AN error occurred while creating database: " . $connect->error;
    }
?>