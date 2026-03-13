<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: login.php"); // Send back if not an owner
    exit;
}
echo "<h1>Welcome, Business Owner</h1>";
echo "<p>Here you can see the full egg inventory and reports.</p>";
?>