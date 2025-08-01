<?php
// Database configuration settings
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // Default XAMPP MySQL username
define('DB_PASS', ''); // Default XAMPP MySQL password (empty)
define('DB_NAME', 'framed_soul'); // Database name

// Set the timezone
date_default_timezone_set('America/New_York'); // Adjust to your timezone

// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start a session
session_start();
?>