<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../auth/login.php");
        exit();
    }

    include '..\config\database_connect.php';

    $id = isset($_GET['id']) ? $_GET['id'] : '';
    $status = isset($_GET['status']) ? $_GET['status'] : '';

    if ($id == "") {
        echo '<script>alert("Order id Not Found!"); window.history.back();</script>';
        exit();
    }

    $sql = "SELECT * FROM orders WHERE id = '$id'";
    $result = $connect->query($sql);
    if (!$result) {
        echo '<script>alert("Order Not Found!"); window.history.back();</script>';
        exit();
    }

    $update_sql = "UPDATE orders SET status = '$status' WHERE id = '$id'";
    $update_result = $connect->query($update_sql);
    echo "<p>$update_result</p>";
    if ($update_result) {
        echo '<script>window.history.back();</script>';
    } else {
        echo '<script>alert("Order Status Not Updated!"); window.history.back();</script>';
    }

    echo '<script>window.location = "?page=all_orders.php";</script>';

    $connect->close();
?>
