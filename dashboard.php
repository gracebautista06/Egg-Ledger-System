<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<?php
include "db.php";
$sql = "SELECT * FROM users";
$result= $conn->query($sql);
?>
<h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
<p>This is your private dashboard.</p>
<a href="logout.php">Logout</a>