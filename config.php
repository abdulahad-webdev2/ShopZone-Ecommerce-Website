<?php
// Start session only once
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "ecomerrce";

$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Set character encoding
mysqli_set_charset($conn, "utf8mb4");
?>