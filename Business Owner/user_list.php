<?php
session_start();
// Security: Only owners can see the user list
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: login.php");
    exit;
}

$pdo = new PDO("mysql:host=localhost;dbname=egg_inventory", "root", "");
$stmt = $pdo->query("SELECT id, username, role FROM users");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management - Egg Ledger</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #fdfaf1; padding: 50px; text-align: center; }
        .container { background: white; padding: 40px; border-radius: 30px; border: 4px solid #8bc34a; display: inline-block; min-width: 400px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; }
        th { color: #5d4037; }
        .back-btn { display: inline-block; margin-top: 20px; text-decoration: none; color: #ff9800; font-weight: bold; }
    </style>
</head>
<body>

    <div class="container">
        <h2>Registered System Users</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
            </tr>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo ucfirst($user['role']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        
        <a href="owner_dashboard.php" class="back-btn">← Back to Dashboard</a>
    </div>

</body>
</html>