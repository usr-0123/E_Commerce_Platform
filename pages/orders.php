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

    $cart_id = isset($_GET["success"]) ? $_GET["success"] : "";

    // Calculate total price
    $total_sql = "SELECT SUM(p.price * c.quantity) AS total_price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = '$user_id'";
    $result = $connect->query($total_sql);
    $row = $result->fetch_assoc();
    $total_price = $row['total_price'] ?? 0;

    if ($total_price > 0) {

        // Create new order
        $order_sql = "INSERT INTO orders (user_id, total_price) VALUES ($user_id, $total_price)";
        $create_order = $connect->query($order_sql);
        $order_id = $connect->insert_id;

        // Move cart items to order_items
        $cart_items_sql = "SELECT product_id, quantity, price FROM cart JOIN products ON cart.product_id = products.id WHERE cart.user_id = '$user_id'";
        $items = $connect->query($cart_items_sql);

        while ($item = $items->fetch_assoc()) {
            $insert_order_item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($order_id, " . $item['product_id'] . "," . $item['quantity'] . "," . $item['price'] . ")";
            $connect->query($insert_order_item_sql);

            // Deduct stock from products
            $update_stock_sql = "UPDATE products SET in_stock = " . $item['quantity'] . " WHERE id = " . $item['product_id'] . " ";
            $connect->query($update_stock_sql);
        }

        // Clear cart
        $clear_cart_sql = "DELETE FROM cart WHERE user_id = '$user_id'";
        $connect->prepare($clear_cart_sql);

        echo "
            <script>
                window.location.href='../layout/main.php?page=all_orders.php;
            </script>
        ";
    } else {
        echo "
            <script>
                alert('Your cart is empty!'); window.history.back();
            </script>
        ";
    }

    $connect->close();
?>
