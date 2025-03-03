<?php
$serverName = "LAPTOP-4RR0GNSE\\SQLEXPRESS"; // Double backslash for SQL Server instance
$connectionOptions = [
    "Database" => "ecommerceDB",
    "Uid" => "ZYVIE",
    "PWD" => "zyvie091298",
    "CharacterSet" => "UTF-8" // Ensure correct character encoding
];

// Connect to SQL Server
$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die("❌ Database connection failed: " . print_r(sqlsrv_errors(), true));
}
?>