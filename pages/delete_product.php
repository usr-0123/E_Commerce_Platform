<?php
    include "../config/database_connect.php";

    $product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($product_id > 0) {
        $delete_sql = "DELETE FROM products WHERE id = ?";
        $stmt = $connect->prepare($delete_sql);
        $stmt->bind_param("i", $product_id);

        if ($stmt->execute()) {
            echo "<script>alert('Product deleted successfully!'); window.location.href='../layout/main.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error deleting product.'); window.history.back();</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Invalid request. No product ID provided.'); window.history.back();</script>";
    }

    $connect->close();
?>
