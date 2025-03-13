<?php
    header("Content-type: application/json");
    header("Access-Control-Allow-Origin: *");

    include '..\..\config\config.php';

    $database_name = 'ecommerce';

    $response = [
        "success" => false,
        "message" => "An error occurred while processing your request.",
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

    $user = $conn->query($get_sql);

    if (!$user) {
        echo json_encode($response);
        exit();
    }

    if ($user->num_rows <= 0) {
        $response["success"] = false;
        $response["message"] = "User not found in the database.";

        echo json_encode($response);
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];

        $update_sql = "UPDATE users
        SET 
            first_name = '$first_name',
            last_name = '$last_name',
            email = '$email'
        WHERE id = $id";
    }

    $res = $conn->query($update_sql);

    if (!$res) {
        $response["success"] = false;
        $response["message"] = "An error occurred while updating your data.";
    } else {
        $response["success"] = true;
        $response["message"] = "Your data has been updated.";
    }

    echo json_encode($response);
    exit();

    $conn->close();
?>