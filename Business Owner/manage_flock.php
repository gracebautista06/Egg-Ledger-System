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
        $stmt = $pdo->prepare("DELETE FROM flock_status WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: manage_flock.php?msg=deleted");
        exit;
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// 3. FETCH ALL FLOCK DATA (Latest reports first)
$stmt = $pdo->query("SELECT * FROM flock_status ORDER BY report_date DESC");
$reports = $stmt->fetchAll();

// 4. CALCULATE WEEKLY MORTALITY TOTAL
$stmt_week = $pdo->query("SELECT SUM(mortality_count) FROM flock_status WHERE report_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
$weekly_deaths = $stmt_week->fetchColumn() ?: 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Flock Health Management - Owner</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; margin: 0; display: flex; }
        .sidebar { width: 250px; background: #2c3e50; color: white; height: 100vh; padding: 20px; position: fixed; }
        .sidebar a { display: block; color: #bdc3c7; padding: 15px; text-decoration: none; border-radius: 10px; margin-bottom: 5px; }
        .sidebar a:hover { background: #34495e; color: white; }

        .main-content { margin-left: 290px; padding: 40px; width: calc(100% - 290px); }
        .alert-banner { 
            background: <?php echo ($weekly_deaths > 10) ? '#e74c3c' : '#009688'; ?>; 
            color: white; padding: 20px; border-radius: 15px; margin-bottom: 30px; 
        }
        .table-container { background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        
        table { width: 100%; border-collapse: collapse; }
        th { background: #f8f9fa; padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6; color: #607d8b; font-size: 0.85em; }
        td { padding: 12px; border-bottom: 1px solid #eee; font-size: 0.95em; }
        
        .obs-text { font-style: italic; color: #7f8c8d; max-width: 300px; font-size: 0.9em; }
        .btn-delete { color: #e74c3c; text-decoration: none; font-weight: bold; font-size: 0.85em; }
        .msg { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>🥚 EggLedger Pro</h2>
    <a href="owner_dashboard.php">📊 Dashboard</a>
    <a href="manage_inventory.php">📦 Egg Inventory</a>
    <a href="manage_sales.php">💰 Sales Reports</a>
    <a href="manage_flock.php" style="background: #f1c40f; color: #2c3e50;">🐔 Flock Health</a>
    <a href="manage_users.php">👥 Staff Accounts</a>
    <a href="../portal/logout_confirm.php">Logout</a>
</div>

<div class="main-content">
    <div class="alert-banner">
        <h3 style="margin:0; font-size: 0.9em; text-transform: uppercase; opacity: 0.9;">Weekly Mortality Total</h3>
        <h1 style="margin:5px 0 0 0;"><?php echo $weekly_deaths; ?> <small style="font-size: 0.5em;">Birds</small></h1>
        <p style="margin:5px 0 0 0; font-size: 0.85em; opacity: 0.9;">
            <?php echo ($weekly_deaths > 10) ? "⚠️ Warning: Higher than usual mortality detected." : "✅ Flock health is within normal range."; ?>
        </p>
    </div>

    <?php if(isset($_GET['msg'])) echo "<div class='msg'>Report removed successfully.</div>"; ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Coop</th>
                    <th>Mortality</th>
                    <th>Culling</th>
                    <th>Observations</th>
                    <th>Reported By</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reports as $row): ?>
                <tr>
                    <td><?php echo date('M d, Y', strtotime($row['report_date'])); ?></td>
                    <td><?php echo htmlspecialchars($row['coop_no']); ?></td>
                    <td style="color: #e74c3c; font-weight: bold;"><?php echo $row['mortality_count']; ?></td>
                    <td><?php echo $row['culling_count']; ?></td>
                    <td class="obs-text">"<?php echo htmlspecialchars($row['health_observations']); ?>"</td>
                    <td><?php echo htmlspecialchars($row['reported_by']); ?></td>
                    <td>
                        <a href="manage_flock.php?delete_id=<?php echo $row['id']; ?>" 
                           class="btn-delete" 
                           onclick="return confirm('Remove this health report?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>