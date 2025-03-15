<?php
require_once __DIR__ . '/vendor/autoload.php'; // Load Dotenv

use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Retrieve DB credentials from .env
$serverName = $_ENV['DB_HOST'] ?? 'localhost'; 
$database = $_ENV['DB_NAME'] ?? '';
$username = $_ENV['DB_USER'] ?? '';
$password = $_ENV['DB_PASSWORD'] ?? '';

// Debugging: Check if variables are loaded
var_dump($database, $username, $password);

// Connection options
$connectionOptions = [
    "Database" => $database,
    "Uid" => $username,
    "PWD" => $password
];

// Attempt to connect
$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die("❌ Database connection failed: " . print_r(sqlsrv_errors(), true));
} else {
    echo "✅ Connected successfully!";
}
?>