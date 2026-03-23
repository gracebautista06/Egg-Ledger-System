<?php
session_start();
include "../includes/db.php"; 

// 1. SECURITY GATE: Only allow logged-in Staff
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
    header("Location: ../portal/login.php?error=unauthorized");
    exit;
}

$username = $_SESSION['username'];

try {
    // 2. RETRIEVE RECENT HARVESTS
    $stmtH = $pdo->prepare("SELECT * FROM egg_inventory WHERE recorded_by = ? ORDER BY date_collected DESC LIMIT 5");
    $stmtH->execute([$username]);
    $harvests = $stmtH->fetchAll();

    // 3. RETRIEVE RECENT SALES
    $stmtS = $pdo->prepare("SELECT * FROM egg_sales WHERE sold_by = ? ORDER BY date_sold DESC LIMIT 5");
    $stmtS->execute([$username]);
    $sales = $stmtS->fetchAll();

    // 4. RETRIEVE RECENT FLOCK HEALTH REPORTS
    $stmtF = $pdo->prepare("SELECT * FROM flock_status WHERE reported_by = ? ORDER BY report_date DESC LIMIT 5");
    $stmtF->execute([$username]);
    $flock = $stmtF->fetchAll();

} catch (PDOException $e) {
    die("Error fetching records: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Activity History - Egg Ledger</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #fdfaf1; color: #5d4037; padding: 20px; margin: 0; }
        .container { max-width: 900px; margin: 20px auto; background: white; padding: 30px; border-radius: 30px; border: 3px solid #607d8b; box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        h2 { color: #455a64; text-align: center; margin-bottom: 30px; }
        h3 { font-size: 1.1em; color: #795548; margin-top: 40px; border-left: 5px solid #607d8b; padding-left: 10px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; background: #fff; }
        th { background: #f8f9fa; color: #607d8b; text-align: left; padding: 12px; border-bottom: 2px solid #eee; font-size: 0.85em; text-transform: uppercase; }
        td { padding: 12px; border-bottom: 1px solid #f1f1f1; font-size: 0.95em; }
        tr:hover { background-color: #fcfcfc; }
        
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 0.75em; font-weight: bold; color: white; }
        .bg-harvest { background: #8bc34a; }
        .bg-sale { background: #fb8c00; }
        .bg-flock { background: #009688; }
        
        .back-btn { display: inline-block; margin-bottom: 20px; text-decoration: none; color: #607d8b; font-weight: bold; font-size: 0.9em; }
        .back-btn:hover { text-decoration: underline; }
        .no-data { color: #9e9e9e; font-style: italic; padding: 20px; text-align: center; }
    </style>
</head>
<body>

<div class="container">
    <a href="staff_dashboard.php" class="back-btn">← Back to Dashboard</a>
    <h2>My Recent Activity</h2>

    <h3><span class="badge bg-harvest">Harvest</span> Daily Collections</h3>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Coop</th>
                <th>Total Eggs</th>
                <th>Cracks</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($harvests) > 0): ?>
                <?php foreach ($harvests as $h): ?>
                <tr>
                    <td><?php echo date('M d, Y', strtotime($h['date_collected'])); ?></td>
                    <td><?php echo htmlspecialchars($h['coop_no']); ?></td>
                    <td><strong><?php echo $h['total_harvest']; ?></strong></td>
                    <td><?php echo $h['cracks']; ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4" class="no-data">No harvest records found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h3><span class="badge bg-sale">Sales</span> Egg Transactions</h3>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Customer</th>
                <th>Qty (Trays)</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($sales) > 0): ?>
                <?php foreach ($sales as $s): ?>
                <tr>
                    <td><?php echo date('M d, Y', strtotime($s['date_sold'])); ?></td>
                    <td><?php echo htmlspecialchars($s['customer_name']); ?></td>
                    <td><?php echo $s['quantity_trays']; ?> (<?php echo $s['egg_size']; ?>)</td>
                    <td>₱<?php echo number_format($s['total_amount'], 2); ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4" class="no-data">No sales records found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h3><span class="badge bg-flock">Flock</span> Health Reports</h3>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Coop</th>
                <th>Mortality</th>
                <th>Observations</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($flock) > 0): ?>
                <?php foreach ($flock as $f): ?>
                <tr>
                    <td><?php echo date('M d, Y', strtotime($f['report_date'])); ?></td>
                    <td><?php echo htmlspecialchars($f['coop_no']); ?></td>
                    <td><?php echo $f['mortality_count']; ?></td>
                    <td><em><?php echo htmlspecialchars($f['health_observations']); ?></em></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4" class="no-data">No health reports found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>