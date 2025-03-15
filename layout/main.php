<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../auth/login.php");
        exit();
    }
?>

<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>E-Commerce Platform</title>
            <link rel="stylesheet" href="../assets/css/main.css">
        </head>
        <body class="main-body-content-section">
            <nav class="navigation-container" >
                <div class="logo-section">
                    <h1 class="logo">MyShop</h1>
                    <?php echo "<p style='font-size: larger; font-weight: 900; color: #1d3045';>" .$_SESSION["user_type"]."</p>"?>
                </div>
                <ul class="navigation-options">
                    <li><a href="?page=dashboard.php">Dashboard</a></li>
                    <?php
                        if ($_SESSION["user_type"] !== "admin") {
                            echo '<li><a href="?page=cart.php">Basket</a></li>';
                        }
                    ?>
                    <li><a href="?page=all_orders.php">Orders</a></li>
                </ul>
                <div class="header-section">
                    <div class="dropdown">
                        <a class="user-icon" href="">
                            <img src="../assets/images/user-solid.svg" alt="user" height="24" width="24" />
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="menu-click-items" onclick="logout()">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
            <section style="height: 100px" class="main-page-dynamic-content">
                <?php
                    $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard.php';

                    $pages = ['dashboard.php', 'orders.php', 'update_orders_status.php', 'cart.php', 'add_product.php', 'upload_image.php', 'edit_product.php', 'delete_product.php', 'edit_cart.php', 'delete_cart.php', 'orders.php', 'all_orders.php', 'checkout.php'];

                    if (in_array($page, $pages)) {
                        include '../pages/' . $page;
                    } else {
                        echo '<p class="page-not-found">Page not found</p>';
                    }
                ?>
            </section>
            <script>
                function logout() {
                    window.location.href = "../auth/login.php";
                }
            </script>
        </body>
    </html>
