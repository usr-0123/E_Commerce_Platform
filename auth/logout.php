<?php
    session_start();
    session_unset();  // Unset session variables
    session_destroy(); // Destroy session

    // Redirect to login page
    header("Location: ..\auth\login.php");
    exit();
?>