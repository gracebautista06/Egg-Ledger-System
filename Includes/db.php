<?php
// includes/db.php

$host = 'localhost';
$dbname = 'inventory_schema'; // Your database name
$username_db = 'root';
$password_db = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username_db, $password_db);
    // Set error mode to exception to help you debug
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If connection fails, stop the script and show why
    die("Database connection failed: " . $e->getMessage());
}
?>