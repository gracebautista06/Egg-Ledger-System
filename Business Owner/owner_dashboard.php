<?php
session_start();
include "../includes/db.php"; 

// 1. SECURITY GATE: Only allow logged-in Owners
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../portal/login.php?error=unauthorized");
    exit;
}

// 2. FETCH QUICK STATS (Summary for the Boss)
try {
    // Total Eggs Harvested Today
    $stmt1 = $pdo->query("SELECT SUM(total_harvest) FROM egg_inventory WHERE date_collected = CURDATE()");
    $today_harvest = $stmt1->fetchColumn() ?: 0;

    // Total Sales Amount (Current Month)
    $stmt2 = $pdo->query("SELECT SUM(total_amount) FROM egg_sales WHERE MONTH(date_sold) = MONTH(CURDATE())");
    $monthly_sales = $stmt2->fetchColumn() ?: 0;

    // Recent Mortality Count
    $stmt3 = $pdo->query("SELECT SUM(mortality_count) FROM flock_status WHERE report_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
    $weekly_mortality = $stmt3->fetchColumn() ?: 0;

} catch (PDOException $e) {
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Owner Command Center - Egg Ledger</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; margin: 0; display: flex; }
        
        /* Sidebar Navigation */
        .sidebar { width: 250px; background: #2c3e50; color: white; height: 100vh; padding: 20px; position: fixed; }
        .sidebar h2 { color: #f1c40f; font-size: 1.2em; text-align: center; margin-bottom: 30px; }
        .sidebar a { display: block; color: #bdc3c7; padding: 15px; text-decoration: none; border-radius: 10px; margin-bottom: 5px; }
        .sidebar a:hover { background: #34495e; color: white; }
        .sidebar a.active { background: #f1c40f; color: #2c3e50; font-weight: bold; }

        /* Main Content Area */
        .main-content { margin-left: 290px; padding: 40px; width: 100%; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        
        /* Stats Cards */
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px; }
        .card { background: white; padding: 25px; border-radius: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); border-top: 5px solid #f1c40f; }
        .card h3 { margin: 0; color: #7f8c8d; font-size: 0.9em; text-transform: uppercase; }
        .card .value { font-size: 2em; font-weight: bold; color: #2c3e50; margin-top: 10px; }

        .logout-btn { color: #e74c3c; font-weight: bold; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>🥚 EggLedger Pro</h2>
    <a href="owner_dashboard.php" class="active">📊 Dashboard</a>
    <a href="manage_inventory.php">📦 Egg Inventory</a>
    <a href="manage_sales.php">💰 Sales Reports</a>
    <a href="manage_flock.php">🐔 Flock Health</a>
    <a href="manage_users.php">👥 Staff Accounts</a>

    <hr style="border: 1px solid #34495e; margin: 15px 0;">
    <a href="generate_report.php" style="background: #27ae60; color: white; font-weight: bold;">📋 Generate Monthly Report</a>
    
    <a href="../portal/logout_confirm.php" class="logout-btn">Logout</a>
</div>

<div class="main-content">
    <div class="header">
        <h1>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p><?php echo date('l, F j, Y'); ?></p>
    </div>

    <div class="stats-grid">
        <div class="card">
            <h3>Harvest (Today)</h3>
            <div class="value"><?php echo number_format($today_harvest); ?> <small>Eggs</small></div>
        </div>
        <div class="card" style="border-top-color: #27ae60;">
            <h3>Revenue (This Month)</h3>
            <div class="value">₱<?php echo number_format($monthly_sales, 2); ?></div>
        </div>
        <div class="card" style="border-top-color: #e67e22;">
            <h3>Flock Mortality (7 Days)</h3>
            <div class="value"><?php echo $weekly_mortality; ?> <small>Birds</small></div>
        </div>
    </div>

    <div class="card" style="border-top: none;">
        <h3>System Overview</h3>
        <p>Use the sidebar to manage your farm data. As the Owner, you have full authority to <strong>edit or delete</strong> any records entered by the staff.</p>
    </div>
</div>

</body>
</html>