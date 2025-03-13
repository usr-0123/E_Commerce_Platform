<?php
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Origin: *');

    include '..\..\config\config.php';

    $database_name = 'ecommerce';

    $response = [
        "success" => false,
        "message" => "An error occurred.",
        "errors" => null,
        "data" => []
    ];

    if ($conn->connect_error) {
        $response["success"] = false;
        $response["message"] = "Connection to the database failed.";
        $response["errors"] = $conn->connect_error;

        echo json_encode($response);
        exit();
    }

    $conn->select_db($database_name);

    $id = $_GET["id"];

    $get_product = "SELECT * FROM products WHERE id = '$id'";
    $delete_product = "DELETE FROM products WHERE id = '$id'";

    $product_result = $conn->query($get_product);

    if (!$product_result) {
        echo json_encode($response);
        exit();
    }

    if ($product_result->num_rows <= 0) {
        $response["success"] = false;
        $response["message"] = "Product not found.";

        echo json_encode($response);
        exit();
    }

    $res = $conn->query($delete_product);

    if (!$res) {
        $response["success"] = false;
        $response["message"] = "Delete product failed.";
    } else {
        $response["success"] = true;
        $response["message"] = "Delete product successfully.";
    }

    echo json_encode($response);
    exit();

    $conn->close();
?>