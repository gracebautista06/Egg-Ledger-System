<?php
session_start();
include "../includes/db.php"; 

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../portal/login.php");
    exit;
}

// --- LOGIC: AGGREGATE DATA FOR THE MONTH ---
try {
    // 1. Total Eggs by Coop (Efficiency)
    $coop_stats = $pdo->query("SELECT coop_no, SUM(total_harvest) as total FROM egg_inventory GROUP BY coop_no")->fetchAll();

    // 2. Total Sales vs. Total Cracks (Loss Analysis)
    $total_sales = $pdo->query("SELECT SUM(total_amount) FROM egg_sales")->fetchColumn() ?: 0;
    $total_cracks = $pdo->query("SELECT SUM(cracks) FROM egg_inventory")->fetchColumn() ?: 0;
    
    // 3. Size Popularity (What sells most?)
    $size_stats = $pdo->query("SELECT egg_size, SUM(quantity_trays) as trays FROM egg_sales GROUP BY egg_size ORDER BY trays DESC")->fetchAll();

} catch (PDOException $e) {
    die("Report Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Monthly Performance Report</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; background: #fff; padding: 40px; color: #333; }
        .report-paper { max-width: 800px; margin: auto; border: 1px solid #ddd; padding: 50px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px; }
        .stat-row { display: flex; justify-content: space-between; margin-bottom: 10px; padding: 5px 0; border-bottom: 1px dashed #eee; }
        .section-title { background: #f4f4f4; padding: 10px; font-weight: bold; margin-top: 30px; text-transform: uppercase; }
        @media print { .no-print { display: none; } body { padding: 0; } .report-paper { border: none; box-shadow: none; } }
    </style>
</head>
<body>

<div class="no-print" style="text-align:center; margin-bottom: 20px;">
    <button onclick="window.print()" style="padding: 10px 20px; cursor:pointer;">🖨️ Print/Save as PDF</button>
    <a href="owner_dashboard.php" style="margin-left: 10px;">Back to Dashboard</a>
</div>

<div class="report-paper">
    <div class="header">
        <h1>EGGLEDGER SYSTEM</h1>
        <p>Official Monthly Production & Sales Report</p>
        <p>Generated on: <?php echo date('F d, Y'); ?></p>
    </div>

    <div class="section-title">1. Production by Coop</div>
    <?php foreach ($coop_stats as $cs): ?>
        <div class="stat-row">
            <span><?php echo $cs['coop_no']; ?></span>
            <span><?php echo number_format($cs['total']); ?> Eggs</span>
        </div>
    <?php endforeach; ?>

    <div class="section-title">2. Financial & Loss Summary</div>
    <div class="stat-row">
        <span>Total Revenue</span>
        <strong>₱<?php echo number_format($total_sales, 2); ?></strong>
    </div>
    <div class="stat-row" style="color: #c62828;">
        <span>Total Cracked/Dirty Eggs (Loss)</span>
        <span><?php echo $total_cracks; ?> pcs</span>
    </div>

    <div class="section-title">3. Market Demand (Most Sold Sizes)</div>
    <table>
        <tr style="text-align:left;">
            <th width="200">Egg Size</th>
            <th>Trays Sold</th>
        </tr>
        <?php foreach ($size_stats as $ss): ?>
        <tr>
            <td><?php echo $ss['egg_size']; ?></td>
            <td><?php echo $ss['trays']; ?> Trays</td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div style="margin-top: 50px; border-top: 1px solid #333; padding-top: 10px; font-size: 0.8em; text-align: center;">
        *** End of Automated Report - EggLedger System v1.0 ***
    </div>
</div>

</body>
</html>