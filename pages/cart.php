<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../auth/login.php");
        exit();
    }

    include "../config/database_connect.php";

    // Get user id
    $user_id = $_SESSION["user_id"];

    // Fetch my cart
    $cart_sql = "SELECT
                    c.id AS cart_id,
                    c.user_id,
                    u.first_name,
                    u.last_name,
                    u.email,
                    c.product_id,
                    p.name AS product_name,
                    p.description AS product_description,
                    p.price,
                    c.quantity,
                    (p.price * c.quantity) AS total_price,
                    p.in_stock,
                    p.image_url,
                    p.rating,
                    cat.id AS category_id,
                    cat.name AS category_name,
                    c.added_at,
                    c.updated_at 
                FROM cart c 
                JOIN users u ON c.user_id = u.id 
                JOIN products p ON c.product_id = p.id 
                LEFT JOIN categories cat ON p.category_id = cat.id
                where c.user_id = '{$user_id}'
                ORDER BY c.added_at DESC
                ";

    $basket = $connect->query($cart_sql);

    $cart_total_sql = "SELECT SUM(p.price * c.quantity) AS total_cart_price FROM cart c JOIN products p ON c.product_id = p.id where c.user_id = '{$user_id}'";

    $result = $connect->query($cart_total_sql);
    $res = $result->fetch_assoc();
    $total_cart_price = $res['total_cart_price'];
?>

<section>
    <h2>Cart Items</h2>
    <div style="display:flex; flex-direction: column; align-items: center; gap: 10px; width: 90%; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
            <thead>
            <tr style="background-color: #3498db; color: white;">
                <th style="padding: 12px; text-align: center;">Image</th>
                <th style="padding: 12px; text-align: center;">Product</th>
                <th style="padding: 12px; text-align: center;">Category</th>
                <th style="padding: 12px; text-align: center;">Date Added</th>
                <th style="padding: 12px; text-align: center;">Price (Ksh)</th>
                <th style="padding: 12px; text-align: center;">Quantity</th>
                <th style="padding: 12px; text-align: center;">Total</th>
                <th style="padding: 12px; text-align: center;">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ($basket->num_rows > 0) {
                while ($row = $basket->fetch_assoc()) {
                    echo "<tr style='border-bottom: 1px solid #ddd; text-align: center;'>
                            <td style='padding: 12px;'><img src='{$row['image_url']}' alt='Product' style='width: 50px; height: 50px; border-radius: 5px; object-fit: cover;'></td>
                            <td style='padding: 12px;'>{$row['product_name']}</td>
                            <td style='padding: 12px;'>{$row['category_name']}</td>
                            <td style='padding: 12px;'>{$row['added_at']}</td>
                            <td style='padding: 12px; font-weight: bold; color: #e67e22;'>Ksh. {$row['price']}</td>
                            <td style='padding: 12px; display: flex; align-items: center; justify-content: center; gap: 5px;'>
                                <button onclick='updateCart({$row['cart_id']}, -1)' style='background: #e74c3c; color: white; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;'>−</button>
                                <span style='min-width: 30px; text-align: center;'>{$row['quantity']}</span>
                                <button onclick='updateCart({$row['cart_id']}, 1)' style='background: #2ecc71; color: white; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;'>+</button>
                            </td>
                            <td style='padding: 12px; font-weight: bold; color: #27ae60;'>Ksh. {$row['total_price']}</td>
                            <td style='padding: 12px; display: flex; justify-content: center; gap: 10px;'>
                                <button onclick='deleteCartItem({$row['cart_id']})' style='background: #e74c3c; color: white; padding: 8px 12px; border: none; border-radius: 5px; cursor: pointer;'>Delete</button>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='7' style='padding: 12px; text-align: center;'>Your cart is empty.</td></tr>";
            }
            $connect->close();
            ?>
            </tbody>
        </table>
        <?php
        if ($basket->num_rows > 0) {
            echo "<button
                    onclick='checkout()'
                    style='width: 10%; background: #2ecc71; color: white; padding: 8px 12px; border: none; border-radius: 5px; cursor: pointer;'
                >CheckOut Ksh." .number_format($total_cart_price,2)."</button>";
            }
        ?>
    </div>
</section>

<script>

    function updateCart(cartId, change) {
        window.location.href = `../layout/main.php?page=edit_cart.php&id=${cartId}&change=${change}`;
    }

    function checkout() {
        window.location.href = `../layout/main.php?page=checkout.php`;
    }

    function deleteCartItem(cartId) {
        if (confirm("Are you sure you want to remove this item?")) {
            window.location.href = `../layout/main.php?page=delete_cart.php&id=${cartId}`;
        }
    }
</script>