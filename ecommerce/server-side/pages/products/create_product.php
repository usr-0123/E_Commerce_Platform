<?php
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");

    include '..\..\config\config.php';

    $database_name = 'ecommerce';

    $response = [
        "success" => false,
        "message" => "No message content.",
        "data" => []
    ];

    if ($conn->connect_error) {
        $response = [
            $response["success"] = false,
            $response["message"] = "Connection to the database failed: " . $conn->connect_error,
            $response["data"] => []
        ];
        echo json_encode($response);
        exit();
    }

    $conn->select_db($database_name);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $in_stock = $_POST['in_stock'];
        $image_url = $_POST['image_url'];
        $category_id = $_POST['category_id'];
        $rating = $_POST['rating'];

        $sql = "INSERT INTO products (name, description, price, in_stock, image_url, category_id, rating)
                VALUES ('$name', '$description', '$price', '$in_stock', '$image_url', '$category_id', '$rating')";

        if ($conn->query($sql)) {
            $response["success"] = true;
            $response["message"] = "Product added successfully";
        } else {
            $response["success"] = false;
            $response["message"] = "An error occurred while creating product.";
            $response["error"] = "$sql . $conn->error";
        }
    }
?>