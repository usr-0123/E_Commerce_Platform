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
                    <label>
                        <input class="search-input" type="search" placeholder="Search" />
                    </label>
                </div>
                <ul class="navigation-options">
                    <li><a href="?page=dashboard.php">Dashboard</a></li>
                    <li><a href="?page=orders.php">Orders</a></li>
                    <li><a href="?page=cart.php">Basket</a></li>
                    <?php
                        if ($_SESSION["user_type"] == "admin") {
                            echo '<li><a href="?page=product.php">Products</a></li>';
                        }
                    ?>
                </ul>
                <div class="header-section">
                    <div>
                        <a href="../layout/main.php"><img src="../assets/images/basket-shopping-solid.svg" alt="basket" height="24" width="24" ></a>
                    </div>
                    <div class="dropdown">
                        <a class="user-icon" href="">
                            <img src="../assets/images/user-solid.svg" alt="user" height="24" width="24" />
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="menu-click-items" href="#">Profile</a></li>
                            <li><a class="menu-click-items" href="#">Settings</a></li>
                            <li><a class="menu-click-items" onclick="logout()">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
            <section class="main-page-dynamic-content">
                <?php
                    $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard.php';

                    $pages = ['dashboard.php', 'orders.php', 'product.php', 'cart.php', 'edit_product.php', 'delete_product.php', 'edit_cart.php', 'delete_cart.php', 'orders.php', 'order_summary.php'];

                    if (in_array($page, $pages)) {
                        include '../pages/' . $page;
                    } else {
                        echo '<p class="page-not-found">Page not found</p>';
                    }
                ?>
            </section>
            <footer>
                    <div class="footer-container">
                        <div class="footer-section">
                            <h3>About Us</h3>
                            <p>Your one-stop shop for the best products at great prices.</p>
                        </div>
                        <div class="footer-section">
                            <h3>Quick Links</h3>
                            <ul>
                                <li><a href="../layout/main.php">Home</a></li>
                                <li><a href="#">Shop</a></li>
                                <li><a href="#">Contact</a></li>
                                <li><a href="#">About</a></li>
                            </ul>
                        </div>
                        <div class="footer-section">
                            <h3>Follow Us</h3>
                            <ul class="social-links">
                                <li>
                                    <a href="https://www.facebook.com" target="_blank">
                                        <img src="../assets/images/facebook-brands.svg" alt="Facebook">
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.x.com" target="_blank">
                                        <img src="../assets/images/x-twitter-brands.svg" alt="Twitter">
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.instagram.com" target="_blank">
                                        <img src="../assets/images/instagram-brands.svg" alt="Instagram">
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="footer-bottom">
                        <p>&copy; <?php echo date("Y"); ?> E-Commerce Platform. All rights reserved.</p>
                    </div>
                </footer>
            <script rel="" src="../assets/js/main.js" ></script>
        </body>
    </html>
