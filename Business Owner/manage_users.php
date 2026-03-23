<?php
session_start();
include "../includes/db.php"; 

// 1. SECURITY GATE: Only allow Owners
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../portal/login.php");
    exit;
}

// 2. FETCH ALL USERS (Except the current logged-in owner for safety)
$current_user = $_SESSION['username'];
$stmt = $pdo->prepare("SELECT id, username, role, created_at FROM users WHERE username != ? ORDER BY role DESC");
$stmt->execute([$current_user]);
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Staff - Egg Ledger</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; margin: 0; display: flex; }
        .sidebar { width: 250px; background: #2c3e50; color: white; height: 100vh; padding: 20px; position: fixed; }
        .sidebar a { display: block; color: #bdc3c7; padding: 15px; text-decoration: none; border-radius: 10px; margin-bottom: 5px; }
        .sidebar a:hover { background: #34495e; color: white; }

        .main-content { margin-left: 290px; padding: 40px; width: calc(100% - 290px); }
        .user-container { background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        
        table { width: 100%; border-collapse: collapse; }
        th { background: #f8f9fa; padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6; color: #607d8b; font-size: 0.85em; text-transform: uppercase; }
        td { padding: 12px; border-bottom: 1px solid #eee; font-size: 0.95em; }
        
        .role-badge { padding: 4px 10px; border-radius: 20px; font-size: 0.75em; font-weight: bold; text-transform: uppercase; }
        .role-staff { background: #e3f2fd; color: #1976d2; }
        .role-owner { background: #fff3e0; color: #e65100; }
        
        .info-box { background: #e1f5fe; color: #01579b; padding: 15px; border-radius: 10px; margin-bottom: 25px; font-size: 0.9em; border-left: 5px solid #0288d1; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>🥚 EggLedger Pro</h2>
    <a href="owner_dashboard.php">📊 Dashboard</a>
    <a href="manage_inventory.php">📦 Egg Inventory</a>
    <a href="manage_sales.php">💰 Sales Reports</a>
    <a href="manage_flock.php">🐔 Flock Health</a>
    <a href="manage_users.php" style="background: #f1c40f; color: #2c3e50;">👥 Staff Accounts</a>
    <a href="../portal/logout_confirm.php">Logout</a>
</div>

<div class="main-content">
    <h1>👥 System User Management</h1>
    
    <div class="info-box">
        <strong>Note:</strong> This list shows all accounts registered in the system. As the Owner, you can monitor when staff accounts were created.
    </div>

    <div class="user-container">
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>System Role</th>
                    <th>Account Created</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($u['username']); ?></strong></td>
                    <td>
                        <span class="role-badge <?php echo ($u['role'] == 'owner') ? 'role-owner' : 'role-staff'; ?>">
                            <?php echo $u['role']; ?>
                        </span>
                    </td>
                    <td><?php echo date('M d, Y', strtotime($u['created_at'])); ?></td>
                    <td style="color: #2e7d32; font-weight: bold;">● Active</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>