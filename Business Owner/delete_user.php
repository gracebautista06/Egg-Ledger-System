<?php
session_start();

// Security: Check if owner
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id_to_delete = $_GET['id'];
    
    // Prevent owner from deleting themselves
    if ($id_to_delete == $_SESSION['user_id']) {
        header("Location: user_list.php?error=self_delete");
        exit;
    }

    $host = 'localhost';
    $dbname = 'inventory_schema';
    $username_db = 'root';
    $password_db = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username_db, $password_db);
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id_to_delete]);
        
        header("Location: user_list.php?success=deleted");
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    header("Location: Business Owner\user_list.php");
    exit;
}