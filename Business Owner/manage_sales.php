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
    try {
        $stmt = $pdo->prepare("DELETE FROM egg_sales WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: manage_sales.php?msg=deleted");
        exit;
    } catch (PDOException $e) {
        $error = "Error deleting: " . $e->getMessage();
    }
}

// 3. FETCH ALL SALES DATA (Latest first)
$stmt = $pdo->query("SELECT * FROM egg_sales ORDER BY date_sold DESC");
$sales = $stmt->fetchAll();

// 4. QUICK TOTAL FOR THIS PAGE
$total_revenue = 0;
foreach($sales as $s) { $total_revenue += $s['total_amount']; }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Sales - Owner</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; margin: 0; display: flex; }
        .sidebar { width: 250px; background: #2c3e50; color: white; height: 100vh; padding: 20px; position: fixed; }
        .sidebar a { display: block; color: #bdc3c7; padding: 15px; text-decoration: none; border-radius: 10px; margin-bottom: 5px; }
        .sidebar a:hover { background: #34495e; color: white; }

        .main-content { margin-left: 290px; padding: 40px; width: calc(100% - 290px); }
        .revenue-banner { background: #27ae60; color: white; padding: 20px; border-radius: 15px; margin-bottom: 30px; }
        .table-container { background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        
        table { width: 100%; border-collapse: collapse; }
        th { background: #f8f9fa; padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6; color: #607d8b; font-size: 0.85em; }
        td { padding: 12px; border-bottom: 1px solid #eee; font-size: 0.95em; }
        
        .btn-delete { color: #e74c3c; text-decoration: none; font-weight: bold; font-size: 0.85em; }
        .msg { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>🥚 EggLedger Pro</h2>
    <a href="owner_dashboard.php">📊 Dashboard</a>
    <a href="manage_inventory.php">📦 Egg Inventory</a>
    <a href="manage_sales.php" style="background: #f1c40f; color: #2c3e50;">💰 Sales Reports</a>
    <a href="manage_flock.php">🐔 Flock Health</a>
    <a href="manage_users.php">👥 Staff Accounts</a>
    <a href="../portal/logout_confirm.php">Logout</a>
</div>

<div class="main-content">
    <div class="revenue-banner">
        <h3 style="margin:0; font-size: 0.9em; text-transform: uppercase; opacity: 0.8;">Total Accumulated Sales</h3>
        <h1 style="margin:5px 0 0 0;">₱<?php echo number_format($total_revenue, 2); ?></h1>
    </div>

    <?php if(isset($_GET['msg'])) echo "<div class='msg'>Sale record removed successfully.</div>"; ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Type</th>
                    <th>Qty (Trays)</th>
                    <th>Total</th>
                    <th>Staff</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sales as $row): ?>
                <tr>
                    <td><?php echo date('M d, Y', strtotime($row['date_sold'])); ?></td>
                    <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                    <td><?php echo $row['customer_type']; ?></td>
                    <td><?php echo $row['quantity_trays']; ?> (<?php echo $row['egg_size']; ?>)</td>
                    <td><strong>₱<?php echo number_format($row['total_amount'], 2); ?></strong></td>
                    <td><?php echo htmlspecialchars($row['sold_by']); ?></td>
                    <td>
                        <a href="manage_sales.php?delete_id=<?php echo $row['id']; ?>" 
                           class="btn-delete" 
                           onclick="return confirm('Delete this sale record?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>