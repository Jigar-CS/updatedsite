<?php
// Configuration for deployment
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Ensure proper MIME types
if (!function_exists('apache_get_modules') || !in_array('mod_rewrite', apache_get_modules())) {
    // Fallback for servers without mod_rewrite
    header('Content-Type: text/html; charset=UTF-8');
}
?>
