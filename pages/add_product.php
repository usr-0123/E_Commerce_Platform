<?php
    // Start session and connect to database
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    include "../config/database_connect.php";

    // Fetch categories from the database
    $categories = [];
    $query = "SELECT id, name FROM categories";
    $result = $connect->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
    }

    // Initialize variables
    $name = $description = $price = $in_stock = $category_id = $rating = "";
    $image_url = "";
    $success_message = $error_message = "";

    // Check if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $in_stock = $_POST['in_stock'];
        $category_id = $_POST['category_id'] ?: NULL;
        $rating = $_POST['rating'];

        // ✅ Handle Image Upload
        if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
            $target_dir = "../assets/images/";
            $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
            $allowed_types = ["jpg", "jpeg", "png", "gif"];

            if (!in_array($imageFileType, $allowed_types)) {
                $error_message = "Only JPG, JPEG, PNG & GIF files are allowed.";
            } else {
                $new_file_name = time() . "_" . uniqid() . "." . $imageFileType;
                $image_url = $target_dir . $new_file_name;

                if (!move_uploaded_file($_FILES["image"]["tmp_name"], $image_url)) {
                    $error_message = "Image upload failed.";
                }
            }
        }

        // ✅ Insert into Database if no errors
        if (empty($error_message)) {
            $insert_product_query = "INSERT INTO products (name, description, price, in_stock, image_url, category_id, rating) VALUES ('$name', '$description', '$price', '$in_stock', '$image_url', '$category_id', '$rating')";
            $result = $connect->query($insert_product_query);

            if ($result) {
                $success_message = "Product added successfully!";
            } else {
                $error_message = "Error adding product." . "<br>" . $connect->error;
            }
        }

        $connect->close();
    }
?>

<style>
    #add_products_button { background: #28a745; color: white; padding: 10px; border: none; margin-top: 15px; cursor: pointer; }
    #add_products_button:hover { background: #218838; }
    .message { text-align: center; padding: 10px; margin: 10px 0; font-weight: bold; }
    .success { background: #d4edda; color: #155724; }
    .error { background: #f8d7da; color: #721c24; }
</style>

<section style="display:flex;flex-direction: column;align-items: center; justify-content: center;">

    <?php if (!empty($success_message)) echo "<div class='message success'>$success_message</div>"; ?>
    <?php if (!empty($error_message)) echo "<div class='message error'>$error_message</div>"; ?>

    <form style="display:flex; flex-direction: column; justify-content: center; align-items: center; width: 500px; margin: 20px; padding: 20px; border: 1px solid #ccc; border-radius: 5px; background: #f9f9f9;"
          id="upload_product_form" action="" method="POST"
          enctype="multipart/form-data"
    >
        <h2>Add New Product</h2>
        <label style="align-self: start; font-weight: bold; display: block; margin-top: 10px;" for="name">Product Name:</label>
        <input style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;" type="text" id="name" name="name" required>

        <label style="align-self: start; font-weight: bold; display: block; margin-top: 10px;" for="description">Description:</label>
        <textarea style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;" id="description" name="description" required></textarea>

        <label style="align-self: start; font-weight: bold; display: block; margin-top: 10px;" for="price">Price (Ksh):</label>
        <input style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;" type="number" id="price" name="price" step="0.01" required>

        <label style="align-self: start; font-weight: bold; display: block; margin-top: 10px;" for="in_stock">Stock Quantity:</label>
        <input style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;" type="number" id="in_stock" name="in_stock" required>

        <label style="align-self: start; font-weight: bold; display: block; margin-top: 10px;" for="category_id">Category:</label>
        <select style="align-self: start; width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;" id="category_id" name="category_id">
            <option value="">Select Category</option>
            <?php
                foreach ($categories as $category) {
                    echo '<option value="' . htmlspecialchars($category['id']) . '">' . htmlspecialchars($category['name']) . '</option>';
                }
            ?>
        </select>

        <label style="align-self: start; font-weight: bold; display: block; margin-top: 10px;" for="rating">Rating (1-10):</label>
        <input style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;" type="number" id="rating" name="rating" min="1" max="10" required>

        <label style="align-self: start; font-weight: bold; display: block; margin-top: 10px;" for="image">Product Image:</label>
        <input style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;" type="file" id="image" name="image" accept="image/*" required>

        <button id="add_products_button" style="border-radius: 5px;background: #28a745; color: white; padding: 10px; border: none; margin-top: 15px; cursor: pointer;" type="submit">Submit Product</button>
    </form>
</section>