<?php
// Load environment variables from .env file
require_once __DIR__ . '/vendor/autoload.php'; // Required if using vlucas/phpdotenv

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Retrieve database credentials from .env
$serverName = getenv('LAPTOP-4RR0GNSE\\SQLEXPRESS'); 
$connectionOptions = [
    "Database" => getenv('ecommerceDB'),
    "Uid" => getenv('ZYVIE'),
    "PWD" => getenv('ZYVIE091298'),
    "CharacterSet" => getenv('UTF-8')
];

// Connect to SQL Server
$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die("❌ Database connection failed: " . print_r(sqlsrv_errors(), true));
}
?>