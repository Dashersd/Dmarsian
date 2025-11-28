<?php
// Load environment variables (gracefully handle missing file)
if (file_exists(__DIR__ . '/env-loader.php')) {
    require_once(__DIR__ . '/env-loader.php');
}

// Get database credentials from environment variables
$servername = getenv('DB_HOST') ?: 'localhost';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: '';
$database = getenv('DB_NAME') ?: 'capstone_db';
$port = (int)(getenv('DB_PORT') ?: 3306);

// Create connection
$conn = new mysqli($servername, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    // Don't use die() as it outputs HTML - let the calling script handle it
    // The $conn object will have connect_error set, which calling scripts can check
} else {
    // Set charset to UTF-8
    $conn->set_charset("utf8mb4");
}

// Legacy constants for backward compatibility (if needed by other files)
if (!defined('DB_SERVER')) {
    define('DB_SERVER', $servername);
    define('DB_USERNAME', $username);
    define('DB_PASSWORD', $password);
    define('DB_NAME', $database);
}

// SMTP2GO HTTP API credentials and email settings
if (!defined('SMTP2GO_API_KEY')) {
    define('SMTP2GO_API_KEY', getenv('SMTP2GO_API_KEY') ?: '');
    define('SMTP2GO_SENDER_EMAIL', getenv('SMTP2GO_SENDER_EMAIL') ?: '');
    define('SMTP2GO_SENDER_NAME', getenv('SMTP2GO_SENDER_NAME') ?: "D'Marsians Taekwondo Gym");
    define('ADMIN_BCC_EMAIL', getenv('ADMIN_BCC_EMAIL') ?: '');
}
?>
