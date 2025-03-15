<?php
    include "../config/database_connect.php";

    $cart_id = isset($_GET["id"]) ? $_GET["id"] : "";

    if ($cart_id != "") {
        $delete_sql = "DELETE FROM cart WHERE id = ?";
        $stmt = $connect->prepare($delete_sql);
        $stmt->bind_param("i", $cart_id);

        if ($stmt->execute()) {
            echo "<script>alert('Cart deleted successfully!'); window.location.href ='../layout/main.php';</script>";
            exit();
        } else {
            echo "<script>alert('Something went wrong!'); window.location.href ='../layout/main.php';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Invalid request. No product ID provided.'); window.location.href ='../layout/main.php';</script>";
    }

    $connect->close();
?>