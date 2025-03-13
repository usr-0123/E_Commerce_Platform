<?php
    header('Content-type: application/json');
    header('Access-Control-Allow-Origin: *');

    include '..\..\config\config.php';

    $database_name = 'ecommerce';

    $id = $_GET['id'];

    $response = [
        "success" => false,
        "message" => "An error occurred while retrieving data.",
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

    $sql = "SELECT * FROM products WHERE id = '$id'";

    $result = $conn->query($sql);

    $products = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        $response["success"] = true;
        $response["message"] = "Product fetched successfully.";
        $response["data"] = $products;
    } else {
        $response["success"] = false;
        $response["message"] = "Product not found.";
        $response["data"] = [];
    }

    $conn->close();

    echo json_encode($response);
?>