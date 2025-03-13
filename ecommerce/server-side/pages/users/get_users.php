<?php
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");

    include "..\..\config\config.php";

    $database_name = 'ecommerce';

    $response = [
        "success" => false,
        "message" => "No message content.",
        "data" => []
    ];

    if ($conn->connect_error) {
        $response = [
            "success" => false,
            "message" => $conn->connect_error,
            "data" => []
        ];
        echo json_encode($response);
        exit();
    }

    $conn->select_db($database_name);

    // Fetch data
    $sql = "SELECT id, first_name, last_name, email, password, role, created_at FROM users";

    $result = $conn->query($sql);

    if (!$result) {
        echo json_encode($response);
        exit();
    }

    $users = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        $response["data"] = $users;
        $response["success"] = true;
        $response["message"] = "Get users success";
    } else {
        $response["success"] = true;
        $response["message"] = "Users retrieved successfully.";
        $response["data"] = [];
    }

    $conn->close();

    echo json_encode($response);
?>