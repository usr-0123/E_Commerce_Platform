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
?>

<style>
    .order-section-container {
        width: 100%;
        display: flex;
        justify-content: center;
    }
    .order-section-section {
        background: white;
        padding: 20px;
        width: 100%;
        max-width: 600px;
        border-radius: 10px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        text-align: center;
    }
    h2 {
        color: #3498db;
    }
    .info {
        margin-bottom: 15px;
        font-size: 16px;
        color: #555;
    }
    .highlight {
        font-weight: bold;
        color: #e67e22;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }
    th, td {
        padding: 10px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color: #3498db;
        color: white;
    }
    tr:hover {
        background-color: #f1f1f1;
    }
    .btn {
        display: inline-block;
        margin-top: 15px;
        padding: 10px 15px;
        text-decoration: none;
        color: white;
        border-radius: 5px;
        font-size: 14px;
    }
    .btn-green {
        background-color: #2ecc71;
    }
    .btn-blue {
        background-color: #3498db;
    }
    .btn:hover {
        opacity: 0.8;
    }
</style>

<div class="order-section-container">
    <section class="order-section-section">
        <h2>Order Summary</h2>
        <p class="info"><strong>Order ID:</strong> <span class="highlight"><?= $order['id'] ?></span></p>
        <p class="info"><strong>Customer:</strong> <?= $order['first_name'] . " " . $order['last_name'] ?></p>
        <p class="info"><strong>Total Price:</strong> <span class="highlight">Ksh. <?= number_format($order['total_price'], 2) ?></span></p>
        <p class="info"><strong>Status:</strong> <?= ucfirst($order['status']) ?></p>
        <p class="info"><strong>Placed on:</strong> <?= date("F j, Y, g:i a", strtotime($order['created_at'])) ?></p>

        <h3>Ordered Items</h3>
        <table>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price (Ksh)</th>
            </tr>
            <?php while ($item = $items_result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $item['name'] ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= number_format($item['price'], 2) ?></td>
                </tr>
            <?php } ?>
        </table>

        <a href="../layout/main.php?page=all_orders.php" class="btn btn-blue">View All Orders</a>
    </section>
</div>

<?php $connect->close(); ?>