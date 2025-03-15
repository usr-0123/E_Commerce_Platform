<?php
    session_start();
    include "../config/database_connect.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $first_name = trim($_POST["first_name"]);
        $last_name = trim($_POST["last_name"]);
        $email = trim($_POST["email"]);
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];
        $role = "user";

        // Check if passwords match
        if ($password !== $confirm_password) {
            $error = "Passwords do not match!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Use prepared statement to prevent SQL injection
            $sql = "INSERT INTO users (first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, ?)";
            $stmt = $connect->prepare($sql);
            $stmt->bind_param("sssss", $first_name, $last_name, $email, $hashed_password, $role);

            if ($stmt->execute()) {
                header("Location: login.php?success=1"); // Redirect to login page
                exit();
            } else {
                $error = "An error occurred: " . $connect->error;
            }
            $stmt->close();
        }
    }
?>

<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Register Account Page</title>
            <link rel="stylesheet" href="../assets/css/register.css">
        </head>
        <body>

            <div class="container">
                <h2>Register</h2>

                <form method="POST">
                    <label for="first_name">First Name:</label>
                    <input type="text" name="first_name" required>

                    <label for="last_name">Last Name:</label>
                    <input type="text" name="last_name" required>

                    <label for="email">Email:</label>
                    <input type="email" name="email" required>

                    <label for="password">Password:</label>
                    <input type="password" name="password" required>

                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" name="confirm_password" required>

                    <button type="submit">Register</button>
                </form>

                <?php if (isset($error)): ?>
                    <p class="error"><?php echo $error; ?></p>
                <?php endif; ?>

                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </body>
    </html>