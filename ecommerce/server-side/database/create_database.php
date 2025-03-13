<?php
    include '..\config\config.php';

    $db_name = 'ecommerce';

    $sql = "CREATE DATABASE IF NOT EXISTS $db_name";

    if ($conn->query($sql) === TRUE) {
        echo "Database $db_name created successfully";
    } else {
        echo "AN error occurred while creating database: " . $conn->error;
    }
?>