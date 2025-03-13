<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../auth/login.php");
        exit();
    }

    include "../config/database_connect.php";

    $user_id = $_SESSION['user_id'];

    // Calculate total price
    $total_sql = "SELECT SUM(p.price * c.quantity) AS total_price FROM cart c 
                  JOIN products p ON c.product_id = p.id 
                  WHERE c.user_id = ?";
    $stmt = $connect->prepare($total_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $total_price = $row['total_price'] ?? 0;

    if ($total_price > 0) {
        // Create new order
        $order_sql = "INSERT INTO orders (user_id, total_price) VALUES (?, ?)";
        $stmt = $connect->prepare($order_sql);
        $stmt->bind_param("id", $user_id, $total_price);
        $stmt->execute();
        $order_id = $stmt->insert_id;

        // Move cart items to order_items
        $cart_items_sql = "SELECT product_id, quantity, price FROM cart 
                           JOIN products ON cart.product_id = products.id 
                           WHERE cart.user_id = ?";
        $stmt = $connect->prepare($cart_items_sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $items = $stmt->get_result();

        while ($item = $items->fetch_assoc()) {
            $insert_order_item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                                      VALUES (?, ?, ?, ?)";
            $stmt = $connect->prepare($insert_order_item_sql);
            $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
            $stmt->execute();

            // Deduct stock from products
            $update_stock_sql = "UPDATE products SET in_stock = in_stock - ? WHERE id = ?";
            $stmt = $connect->prepare($update_stock_sql);
            $stmt->bind_param("ii", $item['quantity'], $item['product_id']);
            $stmt->execute();
        }

        // Clear cart
        $clear_cart_sql = "DELETE FROM cart WHERE user_id = ?";
        $stmt = $connect->prepare($clear_cart_sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        echo "<script>alert('Order placed successfully!'); window.location.href='../layout/main.php?page=order_summary.php&order_id=$order_id';</script>";
    } else {
        echo "<script>alert('Your cart is empty!'); window.history.back();</script>";
    }

    $connect->close();
?>
