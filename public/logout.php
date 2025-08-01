<?php
session_start();

// Destroy the session to log the user out
if (isset($_SESSION['user_id'])) {
    unset($_SESSION['user_id']);
}

// Redirect to the login page after logout
header("Location: login.php");
exit();
?>