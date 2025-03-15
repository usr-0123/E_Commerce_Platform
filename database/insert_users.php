<?php
    include '..\config\database_connect.php';

    $database_name = 'ecommerce';

    $conn->select_db($database_name);

    function insertUsers ($conn, $table_name, $table_query)
    {
        if ($conn -> query($table_query) === TRUE) {
            echo "New record created successfully into table $table_name";
        } else {
            echo "Error: " . $table_query . "<br>" . $conn->error;
        }
    }

    $password_hash = '$2y$10$bOo86z5OJxt6PXC/J6iwjOskvpSASoLhMGcdPshJLGNHALZ0nlxvi'; // 1234

    $insert_records = [
        "users" => "
            INSERT INTO users (first_name, last_name, email, password, role)
            VALUES
                ('John', 'Doe', 'john.doe@example.com', '$password_hash', 'admin'),
                ('Jane', 'Smith', 'jane.smith@example.com', '$password_hash', 'customer'),
                ('Michael', 'Johnson', 'michael.johnson@example.com', '$password_hash', 'customer'),
                ('Emily', 'Davis', 'emily.davis@example.com', '$password_hash', 'admin'),
                ('David', 'Brown', 'david.brown@example.com', '$password_hash', 'customer'),
                ('Sarah', 'Wilson', 'sarah.wilson@example.com', '$password_hash', 'customer'),
                ('James', 'Taylor', 'james.taylor@example.com', '$password_hash', 'admin'),
                ('Linda', 'Anderson', 'linda.anderson@example.com', '$password_hash', 'customer'),
                ('Robert', 'Thomas', 'robert.thomas@example.com', '$password_hash', 'customer'),
                ('Laura', 'Martinez', 'laura.martinez@example.com', '$password_hash', 'admin')
        "
    ];

    foreach ($insert_records as $table_name => $table_query) {
        insertUsers ($conn, $table_name, $table_query);
    }

    $conn -> close();
?>