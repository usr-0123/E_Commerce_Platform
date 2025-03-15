<?php
    include "../config/database_connect.php";

    if (!isset($_GET['id']) || !isset($_GET['change'])) {
        echo "<script>alert('Invalid request.'); window.history.back();</script>";
        exit();
    }

    $cart_id = intval($_GET['id']);
    $change = intval($_GET['change']);

    // Get current quantity
    $query = "SELECT quantity FROM cart WHERE id = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
    $stmt->bind_result($current_quantity);
    $stmt->fetch();
    $stmt->close();

    if ($current_quantity + $change <= 0) {
        // Remove item if quantity goes to zero
        $deleteQuery = "DELETE FROM cart WHERE id = ?";
        $stmt = $connect->prepare($deleteQuery);
        $stmt->bind_param("i", $cart_id);
        $stmt->execute();
        $stmt->close();
        echo "<script>alert('Item removed from cart.'); window.location.href = '../layout/main.php?page=cart.php';</script>";
    } else {
        // Update the quantity
        $updateQuery = "UPDATE cart SET quantity = quantity + ? WHERE id = ?";
        $stmt = $connect->prepare($updateQuery);
        $stmt->bind_param("ii", $change, $cart_id);
        $stmt->execute();
        $stmt->close();
        echo "<script>window.location.href = '../layout/main.php?page=cart.php';</script>";
    }

    $connect->close();
?>