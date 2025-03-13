<?php
    header("Content-type: application/json");
    header("Access-Control-Allow-Origin: *");

    include "..\..\config\config.php";

    $database_name = 'ecommerce';

    $response = [
        "success" => false,
        "message" => "No products found.",
        "error" => null,
        "data" => []
    ];

    if ($conn->connect_error) {
        $response["success"] = false;
        $response["message"] = "Connection to the database failed.";
        $response["error"] = $conn->connect_error;
        $response["data"] = [];

        echo json_encode($response);
        exit();
    }

    $conn->select_db($database_name);

    $sql = "SELECT * FROM products";

    $result = $conn->query($sql);

    if (!$result) {
        echo json_encode($response);
        exit();
    }

    $products = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        $response["success"] = true;
        $response["message"] = "Products fetched successfully.";
        $response["data"] = $products;
    } else {
        $response["success"] = true;
        $response["message"] = "Products fetched successfully.";
        $response["data"] = [];
    }

    $conn->close();

    echo json_encode($response);
?>