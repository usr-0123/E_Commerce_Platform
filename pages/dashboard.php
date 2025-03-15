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

    // Fetch categories
    $categories_sql = "SELECT * FROM categories";
    $categories = $connect->query($categories_sql);

    // Get selected filters from GET request
    $category_id = isset($_GET['category']) ? $_GET['category'] : '';
    $price_range = isset($_GET['price']) ? $_GET['price'] : '';
    $rating = isset($_GET['rating']) ? $_GET['rating'] : '';

    // Build SQL query
    $sql = "SELECT * FROM products WHERE 1=1";
    $params = [];
    $types = "";

    // Apply category filter
    if (!empty($category_id)) {
        $sql .= " AND category_id = ?";
        $params[] = $category_id;
        $types .= "i";
    }

    // Apply price filter
    if (!empty($price_range)) {
        if ($price_range === "Below 50") {
            $sql .= " AND price < 50";
        } elseif ($price_range === "50 to 100") {
            $sql .= " AND price BETWEEN 50 AND 100";
        } elseif ($price_range === "100 to 150") {
            $sql .= " AND price BETWEEN 100 AND 150";
        } elseif ($price_range === "Above 150") {
            $sql .= " AND price > 150";
        }
    }

    // Apply rating filter
    if ($rating !== "") { // Rating can be 0, so check if it exists, not just empty
        $sql .= " AND rating = ?";
        $params[] = $rating;
        $types .= "i";
    }

    $search = isset($_GET['search']) ? $_GET['search'] : '';

    // Modify SQL query for search functionality
    if (!empty($search)) {
        $sql .= " AND (name LIKE ? OR description LIKE ?)";
        $searchTerm = "%$search%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= "ss"; // Two string parameters
    }

    // Prepare and execute query
    $stmt = $connect->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
?>

<section style="padding: 10px">
    <h2>Products</h2>
    <div style="display:flex; gap: 10px;">
        <!-- Category Filter -->
        <label for="products-sort" hidden="hidden"></label>
        <select id="products-sort" class="sort-products" onchange="filterProducts()">
            <option value="">All categories</option>
                <?php
                    if ($categories->num_rows > 0) {
                        while ($category = $categories->fetch_assoc()) {
                            $selected = ($category_id == $category['id']) ? "selected" : "";
                            echo "<option value='{$category['id']}' $selected>{$category['name']}</option>";
                        }
                    }
                ?>
        </select>

        <!-- Price Filter -->
        <label for="products-price-sort" hidden="hidden"></label>
        <select id="products-price-sort" class="sort-products" onchange="filterProducts()">
            <option value="">All prices</option>
            <option value="Below 50" <?= ($price_range == "Below 50") ? "selected" : "" ?>>Below 50</option>
            <option value="50 to 100" <?= ($price_range == "50 to 100") ? "selected" : "" ?>>50 to 100</option>
            <option value="100 to 150" <?= ($price_range == "100 to 150") ? "selected" : "" ?>>100 to 150</option>
            <option value="Above 150" <?= ($price_range == "Above 150") ? "selected" : "" ?>>Above 150</option>
        </select>

        <!-- Rating Filter -->
        <label for="products-rating-sort" hidden="hidden"></label>
        <select id="products-rating-sort" class="sort-products" onchange="filterProducts()">
            <option value="">All ratings</option>
            <option value="1" <?= ($rating === "1") ? "selected" : "" ?>>1/10</option>
            <option value="2" <?= ($rating === "2") ? "selected" : "" ?>>2/10</option>
            <option value="3" <?= ($rating === "3") ? "selected" : "" ?>>3/10</option>
            <option value="4" <?= ($rating === "4") ? "selected" : "" ?>>4/10</option>
            <option value="5" <?= ($rating === "5") ? "selected" : "" ?>>5/10</option>
            <option value="6" <?= ($rating === "6") ? "selected" : "" ?>>6/10</option>
            <option value="7" <?= ($rating === "7") ? "selected" : "" ?>>7/10</option>
            <option value="8" <?= ($rating === "8") ? "selected" : "" ?>>8/10</option>
            <option value="9" <?= ($rating === "9") ? "selected" : "" ?>>9/10</option>
            <option value="10" <?= ($rating === "10") ? "selected" : "" ?>>10/10</option>
        </select>

        <label for="products-search" style="display: flex; align-items: center; border: 1px solid black">
            <input
                type="search" id="products-search"
                value="<?= htmlspecialchars($search) ?>"
                placeholder="Type in product name..."
                style="border: none; outline: none; background-color: transparent;"
            >
            <button onclick="filterProducts()" style="border: none; background-color: transparent">🔍</button>
        </label>

        <?php
            if ($_SESSION["user_type"] == "admin") {
                echo '<button onclick="addProduct()">Add Product</button>';
            }
        ?>
    </div>

    <div class="products-display" style="display: flex; justify-content: center; flex-wrap: wrap; gap: 10px; padding: 20px; margin: auto">
        <?php
        if ($result->num_rows > 0) {
            while ($item = $result->fetch_assoc()) {
                echo "
                    <div style='background: white; padding: 10px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); transition: transform 0.2s; width: 20%;'>
                        <img src='{$item['image_url']}' alt='{$item['name']}' style='width: 100%; max-height: 40%; border-radius: 10px'>
                        <div class='product-info' style='padding: 10px 0;height: 50%;bottom: 0'>
                            <h2>{$item['name']}</h2>
                            <p class='description'>{$item['description']}</p>
                            <p class='price' style='font-size: 1.2em; font-weight: bold; color: #e67e22'>Ksh. {$item['price']}</p>
                            <p class='stock'>In Stock: <span>{$item['in_stock']}</span></p>
                            <p class='rating'>⭐ {$item['rating']}/10</p>";

                            if (($_SESSION["user_type"] ?? '') == "admin") {
                                echo "<div style='display:flex; justify-content: space-evenly;'>
                                                <button onclick='navigate(\"{$item['id']}\")' class='cart-btn' style='background: #3498db; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; width: 45%; margin-top: 10px;'>Edit</button>
                                                <button onclick='deleteProduct(\"{$item['id']}\")' class='cart-btn' style='background: #ff3322; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; width: 45%; margin-top: 10px;'>Delete</button>
                                              </div>";
                            } else {
                                echo "<button onclick='addToCart(\"{$item['id']}\", \"$user_id\")' class='cart-btn' style='background: #3498db; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; width: 100%;'>Add to Cart</button>";
                            }
                echo "</div></div>";
            }
        } else {
            echo "<p>No products found</p>";
        }
        $stmt->close();
        $connect->close();
        ?>
    </div>
</section>

<script>
    function filterProducts() {
        let category = document.getElementById("products-sort").value;
        let price = document.getElementById("products-price-sort").value;
        let rating = document.getElementById("products-rating-sort").value;
        let search = document.getElementById("products-search").value.trim();

        let queryParams = [];
        if (category) queryParams.push("category=" + category);
        if (price) queryParams.push("price=" + price);
        if (rating) queryParams.push("rating=" + rating);
        if (search) queryParams.push("search=" + encodeURIComponent(search));

        window.location.href = "?" + queryParams.join("&");
    }

    function navigate(productId) {
        window.location.href = "?" + "page=edit_product.php&id=" + productId;
    }

    function deleteProduct(productId) {
        if (confirm("Are you sure you want to delete this product?")) {
            let form = document.createElement("form");
            form.method = "POST";
            form.action = "../pages/delete_product.php?id="+productId;

            let input = document.createElement("input");
            input.type = "hidden";
            input.name = "id";
            input.value = productId;

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    }

    function addToCart(productId, userId) {
        window.location.href = `../pages/add_to_cart.php?id=${productId}&user_id=${userId}`;
    }

    function addProduct() {
        window.location.href = "?page=add_product.php";
    }

</script>
