<?php
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");

    include "..\..\config\config.php";

    $database_name = 'ecommerce';

    $id = $_GET['id'];

    $response = [
        "success" => false,
        "message" => "",
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

    // Fetch data
    $sql = "SELECT * FROM users where id=$id";

    $result = $conn->query($sql);

    if (!$result) {
        echo json_encode($response);
        exit;
    }

    $data = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $response["success"] = true;
        $response["message"] = "User fetched successfully";
        $response["data"] = $data;
    } else {
        $response["success"] = false;
        $response["message"] = "No user records found.";
        $response["data"] = $data;
    }

    $conn->close();

    echo json_encode($response);
?>