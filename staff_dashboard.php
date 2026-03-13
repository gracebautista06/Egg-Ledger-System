<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: login.php"); // Send back if not staff
    exit;
}
echo "<h1>Welcome, Farm Staff</h1>";
echo "<p>Here you can record new egg stocks (Pewee to Jumbo).</p>";
?>