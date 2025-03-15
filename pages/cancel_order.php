<?php
    // Start session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../auth/login.php");
        exit();
    }

    // Include database connection
    include "../config/database_connect.php";

    // Get order ID from URL
    $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
    $user_id = $_SESSION['user_id'];

    if ($order_id <= 0) {
        echo "<script>alert('Invalid Order ID'); window.history.back();</script>";
        exit();
    }

    // Check if order belongs to the user and its status
    $order_check_sql = "SELECT status FROM orders WHERE id = ? AND user_id = ?";
    $stmt = $connect->prepare($order_check_sql);
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('Order not found or unauthorized'); window.history.back();</script>";
        exit();
    }

    $order = $result->fetch_assoc();
    $order_status = strtolower($order['status']);

    if ($order_status === 'shipped' || $order_status === 'delivered') {
        echo "<script>alert('You cannot cancel a shipped or delivered order'); window.history.back();</script>";
        exit();
    }

    // Delete order items
    $delete_items_sql = "DELETE FROM order_items WHERE order_id = ?";
    $stmt = $connect->prepare($delete_items_sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();

    // Delete the order
    $delete_order_sql = "DELETE FROM orders WHERE id = ?";
    $stmt = $connect->prepare($delete_order_sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Order successfully canceled'); window.location.href = '../layout/main.php?page=all_orders.php';</script>";
    } else {
        echo "<script>alert('Failed to cancel order'); window.history.back();</script>";
    }

    // Close the database connection
    $connect->close();
?>