<?php
session_start();
include "../includes/db.php"; 

// 1. SECURITY GATE: Only allow Owners
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../portal/login.php");
    exit;
}

// 2. HANDLE DELETE REQUEST
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM egg_inventory WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage_inventory.php?msg=deleted");
    exit;
}

// 3. FETCH ALL HARVEST DATA
$stmt = $pdo->query("SELECT * FROM egg_inventory ORDER BY date_collected DESC");
$inventory = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Inventory - Owner</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; margin: 0; display: flex; }
        .sidebar { width: 250px; background: #2c3e50; color: white; height: 100vh; padding: 20px; position: fixed; }
        .sidebar a { display: block; color: #bdc3c7; padding: 15px; text-decoration: none; border-radius: 10px; }
        .sidebar a:hover { background: #34495e; color: white; }
        
        .main-content { margin-left: 290px; padding: 40px; width: calc(100% - 290px); }
        .table-container { background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #f8f9fa; padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6; color: #607d8b; }
        td { padding: 12px; border-bottom: 1px solid #eee; vertical-align: middle; }
        
        .btn-edit { color: #3498db; text-decoration: none; font-weight: bold; margin-right: 10px; }
        .btn-delete { color: #e74c3c; text-decoration: none; font-weight: bold; }
        .btn-delete:hover { text-decoration: underline; }
        
        .msg { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>🥚 EggLedger Pro</h2>
    <a href="owner_dashboard.php">📊 Dashboard</a>
    <a href="manage_inventory.php" style="background: #f1c40f; color: #2c3e50;">📦 Egg Inventory</a>
    <a href="manage_sales.php">💰 Sales Reports</a>
    <a href="manage_flock.php">🐔 Flock Health</a>
    <a href="manage_users.php">👥 Staff Accounts</a>
    <a href="../portal/logout_confirm.php">Logout</a>
</div>

<div class="main-content">
    <h1>📦 Manage Egg Harvests</h1>
    
    <?php if(isset($_GET['msg'])) echo "<div class='msg'>Record successfully updated/deleted!</div>"; ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Coop</th>
                    <th>Total Eggs</th>
                    <th>Recorded By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($inventory as $row): ?>
                <tr>
                    <td><?php echo date('M d, Y', strtotime($row['date_collected'])); ?></td>
                    <td><?php echo $row['coop_no']; ?></td>
                    <td><strong><?php echo $row['total_harvest']; ?></strong></td>
                    <td><?php echo htmlspecialchars($row['recorded_by']); ?></td>
                    <td>
                        <a href="edit_inventory.php?id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                        <a href="manage_inventory.php?delete_id=<?php echo $row['id']; ?>" 
                           class="btn-delete" 
                           onclick="return confirm('Are you sure you want to delete this record? This cannot be undone.')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>