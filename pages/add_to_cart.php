<?php
    session_start();
    include "../config/database_connect.php";

    if (!isset($_SESSION["user_id"])) {
        echo "<script>alert('You need to log in to add items to the cart.'); window.history.back();</script>";
        exit();
    }

    $user_id = $_SESSION["user_id"];
    $product_id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
    $quantity = isset($_GET["quantity"]) ? intval($_GET["quantity"]) : 1;


if ($product_id <= 0 || $quantity <= 0) {
        echo "<script>alert('Invalid product selection. $product_id, $quantity'); window.history.back();</script>";
        exit();
    }

    // Check if the product exists
    $product_check = $connect->prepare("SELECT in_stock FROM products WHERE id = ?");
    $product_check->bind_param("i", $product_id);
    $product_check->execute();
    $product_result = $product_check->get_result();
    $product = $product_result->fetch_assoc();

    if (!$product || $product["in_stock"] < $quantity) {
        echo "<script>alert('Product is out of stock or unavailable.'); window.history.back();</script>";
        exit();
    }

    // Check if the item is already in the cart
    $cart_check = $connect->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $cart_check->bind_param("ii", $user_id, $product_id);
    $cart_check->execute();
    $cart_result = $cart_check->get_result();

    if ($cart_item = $cart_result->fetch_assoc()) {
        // Update the quantity
        $new_quantity = $cart_item["quantity"] + $quantity;
        $update_cart = $connect->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $update_cart->bind_param("ii", $new_quantity, $cart_item["id"]);
        $update_cart->execute();
    } else {
        // Insert a new item into the cart
        $add_cart = $connect->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $add_cart->bind_param("iii", $user_id, $product_id, $quantity);
        $add_cart->execute();
    }

    echo "<script>alert('Product added to cart successfully!'); window.history.back();</script>";

    $connect->close();
?>
