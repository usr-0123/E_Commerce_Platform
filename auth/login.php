<?php
    session_start();

    include "..\config\database_connect.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST["email"];
        $password = $_POST["password"];

        // Check if the user exists
        $sql = "SELECT id, last_name, email, password, role FROM users WHERE email = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_type"] = $user["role"];

                header("Location: ..\index.php");

                exit();
            } else {
                $error = "Wrong authentication details.";
            }
        } else {
            $error = "User with these details does not exist.";
        }
    }

?>

<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Login Page</title>
            <link rel="stylesheet" href="../assets/css/login.css">
        </head>
        <body class="login-body-section">
            <div class="container">
                <h2>Login</h2>

                <form method="POST">
                    <label for="email">Email:</label>
                    <input type="email" name="email" required>

                    <label for="password">Password:</label>
                    <input type="password" name="password" required>

                    <button type="submit">Login</button>
                </form>

                <?php if (isset($error)): ?>
                    <p class="error"><?php echo $error; ?></p>
                <?php endif; ?>

                <p>You don't have an account already? <a href="register.php">Register here</a></p>

            </div>
        </body>
    </html>