<?php
require_once 'config.php';

function connectDB() {
    // Use constants if defined, otherwise use variables from config.php
    $server = defined('DB_SERVER') ? DB_SERVER : (isset($GLOBALS['servername']) ? $GLOBALS['servername'] : 'localhost');
    $username = defined('DB_USERNAME') ? DB_USERNAME : (isset($GLOBALS['username']) ? $GLOBALS['username'] : 'root');
    $password = defined('DB_PASSWORD') ? DB_PASSWORD : (isset($GLOBALS['password']) ? $GLOBALS['password'] : '');
    $database = defined('DB_NAME') ? DB_NAME : (isset($GLOBALS['database']) ? $GLOBALS['database'] : 'capstone_db');
    
    $conn = mysqli_connect($server, $username, $password, $database);
    
    if (!$conn) {
        error_log("Database connection failed: " . mysqli_connect_error());
        // Don't die() - let calling scripts handle the error
        return false;
    }
    
    mysqli_set_charset($conn, "utf8mb4");
    return $conn;
}

// Automatically establish connection when this file is included
// Use the connection from config.php if available, otherwise create new one
if (!isset($conn) || (isset($conn) && isset($conn->connect_error) && $conn->connect_error)) {
    $conn = connectDB();
}
?>