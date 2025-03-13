<?php
    include "../config/database_connect.php";

    // Handle form submission
    $message = ""; // Message placeholder

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $product_id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $stock = $_POST['in_stock'];
        $rating = $_POST['rating'];

        $sql = "UPDATE products SET name = ?, description = ?, price = ?, in_stock = ?, rating = ? WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("ssdiid", $name, $description, $price, $stock, $rating, $product_id);

        if ($stmt->execute()) {
            $message = "<p style='color: green; text-align: center;'>Product updated successfully!</p>";
        } else {
            $message = "<p style='color: red; text-align: center;'>Error updating product.</p>";
        }

        $stmt->close();
    }

    // Load product details for editing
    $product_id = isset($_GET['id']) ? $_GET['id'] : '';

    if (!$product_id) {
        echo "<p style='color: red; text-align: center;'>Invalid product ID.</p>";
        exit();
    }

    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        echo "<p style='color: red; text-align: center;'>Product not found.</p>";
        exit();
    }

    $stmt->close();
    $connect->close();
?>

<section style="max-width: 500px; margin: auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
    <h2 style="text-align: center; color: #333;">Edit Product</h2>

    <?= $message ?>

    <form method="POST" style="display: flex; flex-direction: column; gap: 10px;">
        <input type="hidden" name="id" value="<?= $product['id'] ?>">

        <label style="font-weight: bold;">Product Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required
               style="padding: 8px; border: 1px solid #ccc; border-radius: 5px; width: 100%;">

        <label style="font-weight: bold;">Description:</label>
        <textarea name="description" required style="padding: 8px; border: 1px solid #ccc; border-radius: 5px; width: 100%; height: 80px;">
            <?= htmlspecialchars($product['description']) ?>
        </textarea>

        <label style="font-weight: bold;">Price:</label>
        <input type="number" name="price" value="<?= number_format($product['price'], 2, '.', '') ?>" step="0.01" required
               style="padding: 8px; border: 1px solid #ccc; border-radius: 5px; width: 100%;">

        <label style="font-weight: bold;">Stock:</label>
        <input type="number" name="in_stock" value="<?= $product['in_stock'] ?>" required
               style="padding: 8px; border: 1px solid #ccc; border-radius: 5px; width: 100%;">

        <label style="font-weight: bold;">Rating:</label>
        <input type="number" name="rating" value="<?= $product['rating'] ?>" step="0.1" min="0" max="10" required
               style="padding: 8px; border: 1px solid #ccc; border-radius: 5px; width: 100%;">

        <button type="submit" style="background: #3498db; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; font-size: 1em;">
            Update Product
        </button>
    </form>

    <div style="text-align: center; margin-top: 10px;">
        <a href="../layout/main.php?page=dashboard.php" style="text-decoration: none; color: black; padding: 10px; border-radius: 5px; cursor: pointer; font-size: 1rem;width: 100%">
            Back to Dashboard
        </a>
    </div>
</section>
