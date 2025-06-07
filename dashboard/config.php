<?php
// Database credentials
define('DB_HOST', 'localhost');      // Change if your DB host is different
define('DB_USER', 'root');   // Replace with your DB username
define('DB_PASS', '');   // Replace with your DB password
define('DB_NAME', 'r4xn');   // Replace with your DB name

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>