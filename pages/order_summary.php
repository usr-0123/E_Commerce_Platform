<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    include "../config/database_connect.php";

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../auth/login.php");
        exit();
    }

    $order_id = isset($_GET['order_id']) ? $_GET['order_id'] : 1;

    $order_sql = "SELECT o.id, o.total_price, o.status, o.created_at, u.first_name, u.last_name
                  FROM orders o 
                  JOIN users u ON o.user_id = u.id
                  WHERE o.id = ?";
    $stmt = $connect->prepare($order_sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $order_result = $stmt->get_result();
    $order = $order_result->fetch_assoc();

    $items_sql = "SELECT p.name, oi.quantity, oi.price 
                  FROM order_items oi
                  JOIN products p ON oi.product_id = p.id
                  WHERE oi.order_id = ?";
    $stmt = $connect->prepare($items_sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $items_result = $stmt->get_result();

    echo "<h2>Order Summary</h2>";
    echo "<p>Order ID: " . $order['id'] . "</p>";
    echo "<p>Name: " . $order['first_name'] . " " . $order['last_name'] . "</p>";
    echo "<p>Total Price: Ksh. " . $order['total_price'] . "</p>";
    echo "<p>Status: " . ucfirst($order['status']) . "</p>";
    echo "<p>Placed on: " . $order['created_at'] . "</p>";

    echo "<h3>Items</h3>";
    echo "<table border='1'><tr><th>Product</th><th>Quantity</th><th>Price</th></tr>";
    while ($item = $items_result->fetch_assoc()) {
        echo "<tr><td>{$item['name']}</td><td>{$item['quantity']}</td><td>Ksh. {$item['price']}</td></tr>";
    }
    echo "</table>";

    echo "<br><a href='../layout/main.php?page=orders.php'>View All Orders</a>";

    $connect->close();
?>
