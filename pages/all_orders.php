<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    include "../config/database_connect.php";

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../auth/login.php");
        exit();
    }

    $user_id = $_SESSION["user_id"];

    $orders_sql = "SELECT o.id, o.total_price, o.status, o.created_at, u.first_name, u.last_name 
                   FROM orders o 
                   JOIN users u ON o.user_id = u.id
                   WHERE o.user_id = '$user_id'
                   ORDER BY o.created_at DESC";

    $orders = $connect->query($orders_sql);
?>

<style>
    .all-orders-container {
        display: flex;
        justify-content: center;
        width: 100%;
    }
    .container {
        background: white;
        padding: 20px;
        width: 90%;
        max-width: 75%;
        border-radius: 10px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        text-align: center;
    }
    h2 {
        color: #3498db;
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
    .status {
        padding: 5px 10px;
        border-radius: 5px;
        color: white;
        font-weight: bold;
        text-transform: capitalize;
    }
    .status.pending { background-color: #e67e22; }
    .status.completed { background-color: #2ecc71; }
    .status.cancelled { background-color: #e74c3c; }
    .btn {
        display: inline-block;
        padding: 8px 12px;
        text-decoration: none;
        border-radius: 5px;
        font-size: 14px;
        color: white;
        border: none;
        cursor: pointer;
    }
    .btn-blue { background-color: #3498db; }
    .btn-red { background-color: #e74c3c; }
    .btn:hover { opacity: 0.8; }
</style>

<section class="all-orders-container">
    <div class="container">
        <h2>All Orders</h2>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Total Price (Ksh)</th>
                <th>Status</th>
                <th>Placed On</th>
                <th>Actions</th>
            </tr>
            <?php while ($order = $orders->fetch_assoc()) { ?>
                <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= $order['first_name'] . " " . $order['last_name'] ?></td>
                    <td><strong>Ksh. <?= number_format($order['total_price'], 2) ?></strong></td>
                    <td>
                        <span class="status <?= strtolower($order['status']) ?>">
                            <?= ucfirst($order['status']) ?>
                        </span>
                    </td>
                    <td><?= date("F j, Y, g:i a", strtotime($order['created_at'])) ?></td>
                    <td>
                        <a href="../layout/main.php?page=order_summary.php&order_id=<?= $order['id'] ?>" class="btn btn-blue">View</a>
                        <?php if (strtolower($order['status']) == "pending") { ?>
                            <button onclick="cancelOrder(<?php echo $order['id']; ?>)"
                                    style="background: #e74c3c; color: white; padding: 8px 12px; border: none; border-radius: 5px; cursor: pointer;">
                                Cancel Order
                            </button>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</section>

<script>
    function cancelOrder(orderId) {
        if (confirm("Are you sure you want to cancel this order?")) {
            window.location.href = `../pages/cancel_order.php?order_id=${orderId}`;
        }
    }
</script>

<?php $connect->close(); ?>
