<?php
    header('Content-type: application/json');
    header('Access-Control-Allow-Origin: *');

    include '..\..\config\config.php';

    $database_name = 'ecommerce';

    $response = [
        "success" => false,
        "message" => "An error occurred.",
        "data" => []
    ];

    if ($conn->connect_error) {
        $response["success"] = false;
        $response["message"] = "Connection to the database failed: " . $conn->connect_error;

        echo json_encode($response);
        exit();
    }

    $conn->select_db($database_name);

    $id = $_GET['id'];

    $get_sql = "SELECT email FROM users WHERE id = $id";
    $delete_sql = "DELETE FROM users WHERE id=$id";

    $user = $conn -> query($get_sql);

    if (!$user) {
        echo json_encode($response);
        exit();
    }

    if ($user -> num_rows <= 0) {
        $response["success"] = false;
        $response["message"] = "User does not exist.";

        echo json_encode($response);
        exit();
    }

    $res = $conn -> query($delete_sql);

    if (!$res) {
        $response["success"] = false;
        $response["message"] = "User not deleted.";
    } else {
        $response["success"] = true;
        $response["message"] = "User deleted successfully!";
    }

    echo json_encode($response);
    exit();

    $conn->close();
?>