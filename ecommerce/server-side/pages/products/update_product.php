<?php
    header('Content-type: application/json');
    header('Access-Control-Allow-Origin: *');

    include "..\..\config\config.php";

    $database_name = 'ecommerce';

    $response = [
        "success" => false,
        "message" => "An error occurred while processing your request.",
        "error" => null,
        "data" => []
    ];

    if ($conn->connect_error) {
        $response["success"] = false;
        $response["message"] = "Connection to the database failed.";
        $response["error"] = $conn->connect_error;

        echo json_encode($response);
        exit();
    }

    $conn->select_db($database_name);

    $id = $_GET["id"];

    $get_product = "SELECT * FROM products WHERE id = '$id'";

    $product = $conn->query($get_product);

    if (!$product) {
        echo json_encode($response);
        exit();
    }

    if ($product->num_rows <= 0) {
        $response["success"] = false;
        $response["message"] = "Product does not exist in the database.";

        echo json_encode($response);
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $in_stock = $_POST['in_stock'];
        $image_url = $_POST['image_url'];
        $category_id = $_POST['category_id'];
        $rating = $_POST['rating'];
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');

        $update_sql = "UPDATE products
        SET
            name = '$name',
            description = '$description',
            price = '$price',
            in_stock = '$in_stock',
            image_url = '$image_url',
            category_id = '$category_id',
            rating = '$rating',
            created_at = '$created_at',
            updated_at = '$updated_at'
        WHERE id = '$id'";
    }

    $res = $conn->query($update_sql);

    if (!$res) {
        $response["success"] = false;
        $response["message"] = "An error occurred while processing your request.";
    } else {
        $response["success"] = true;
        $response["message"] = "Product updated successfully.";
    }

    echo json_encode($response);
    exit();

    $conn->close();
?>