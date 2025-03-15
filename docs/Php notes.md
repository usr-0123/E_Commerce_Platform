# **Session start**
`session_start()`; is a PHP function that initializes or resumes a session. It is required before working with `$_SESSION` variables.

## How It Works
1. Checks if a session exists:
    - If a session already exists, it resumes the existing session.
    - If a session does not exist, it starts a new one.
2. Generates a session ID:
   - When a session starts, PHP assigns a unique session ID to the user.
   - This ID is stored in a cookie called `PHPSESSID` on the user's browser.
3. Stores session variables:
   - You can store user data (e.g., `$_SESSION["user_id"]`) for later use across multiple pages.

## **Why Use `session_start()`?**
- **User Authentication** → Keeps users logged in.
- **Cart System** → Stores cart items until checkout.
- **Order Tracking** → Saves user-specific order details.

## **Important:**
- `session_start();` must be at the top of the PHP file (before any HTML output).
- Without it, `$_SESSION` variables won't be available.

# Sessions
In vanilla PHP, login and session management involve verifying user credentials and maintaining their authentication status using PHP sessions. Here's a breakdown of how it works:

---

### **1. Understanding PHP Sessions**
- PHP sessions allow you to store user information (like login status) across multiple pages.
- A session is started with `session_start()` and data is stored in the `$_SESSION` superglobal.

---

### **2. Steps for Login and Session Management**
#### **A. Create a Login Form (`login.php`)**
This form allows users to enter their credentials.

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <form action="auth.php" method="POST">
        <label>Email:</label>
        <input type="email" name="email" required>
        <br>
        <label>Password:</label>
        <input type="password" name="password" required>
        <br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
```

---

#### **B. Handle Login Authentication (`auth.php`)**
This script verifies the user credentials and starts a session.

```php
<?php
session_start();
include 'db.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user from the database
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Store user details in session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['name'] = $user['name'];

        header("Location: dashboard.php");
        exit();
    } else {
        echo "Invalid email or password.";
    }
}
?>
```

- **`password_verify()`**: This function is used to check the hashed password stored in the database.

---

#### **C. Protect Pages with Session (`dashboard.php`)**
To prevent unauthorized access, check if the user is logged in.

```php
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION['name']; ?>!</h1>
    <a href="logout.php">Logout</a>
</body>
</html>
```

---

#### **D. Logout and Destroy Session (`logout.php`)**
To log out a user, destroy the session.

```php
<?php
session_start();
session_unset();  // Unset session variables
session_destroy(); // Destroy session
header("Location: login.php");
exit();
?>
```

---

### **3. Additional Notes**
- **Password Hashing**: Always store passwords securely using `password_hash($password, PASSWORD_DEFAULT)`.
- **Session Security**:
   - Use `session_regenerate_id(true);` after login to prevent session fixation attacks.
   - Store sessions securely by setting session cookie parameters (`session_set_cookie_params()`).
- **Database Connection (`db.php`)**:

```php
<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "my_database";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
```

---

### **Conclusion**
- **Login Flow:** User submits form → Validate credentials → Start session → Redirect to a protected page.
- **Session Management:** Store user data in `$_SESSION` → Check session on restricted pages → Destroy session on logout.

This is a basic implementation. For a real-world application, consider adding CSRF protection, session expiration, and secure cookies. 🚀