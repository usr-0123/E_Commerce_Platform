<?php
    include "..\..\config\config.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST["name"];
        $description = $_POST["description"];
        $price = $_POST["price"];
        $stock = $_POST["stock"];

        $image = $_FILES["image"]["name"];
        $imagePath = "../../uploads/" . basename($image);

        move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath);

        $sql = "INSERT INTO products (name, description, price, stock, image) VALUES ('$name', '$description', '$price', '$stock', '$imagePath')";

        if ($conn->query($sql) === TRUE) {
            echo "New product created successfully";
        } else {
            echo "An error occurred while creating a new product: " . $conn->error;
        }
    }
?>