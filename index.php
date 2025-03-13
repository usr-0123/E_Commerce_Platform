<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        header("Location: auth/login.php");
        exit();
    }

    require_once "config\database_connect.php";
?>

<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>E-Commerce Home</title>
            <link rel="stylesheet" href="assets/css/style.css">
        </head>
        <body class="index-body-section">

            <section class="main-content-section">
                <?php
                    if ($_SESSION["user_type"] == "admin") {
                        header("Location: layout/main.php?page=dashboard.php");
                    } else {
                        header("Location: layout/main.php?page=dashboard.php");
                    }
                ?>
            </section>
        </body>
    </html>
