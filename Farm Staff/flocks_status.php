<?php
session_start();
include "../includes/db.php"; 

// 1. SECURITY GATE: Only allow logged-in Staff
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
    header("Location: ../portal/login.php?error=unauthorized");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $coop_no = $_POST['coop_no'];
    $mortality = (int)$_POST['mortality_count'];
    $culling = (int)$_POST['culling_count'];
    $observations = $_POST['health_observations'];
    $report_date = $_POST['report_date'];

    try {
        $sql = "INSERT INTO flock_status (coop_no, mortality_count, culling_count, health_observations, report_date, reported_by) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $coop_no, 
            $mortality, 
            $culling, 
            $observations, 
            $report_date, 
            $_SESSION['username']
        ]);
        
        $message = "<div class='success'>📋 Health Report Saved for $coop_no</div>";
    } catch (PDOException $e) {
        $message = "<div class='error'>❌ Error: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flock Status - Egg Ledger</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #fdfaf1; color: #5d4037; padding: 20px; }
        .form-container { 
            background: white; max-width: 450px; margin: auto; padding: 30px; 
            border-radius: 30px; border: 4px solid #4db6ac; box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        h2 { color: #00796b; text-align: center; margin-top: 0; }
        label { display: block; font-weight: bold; margin-bottom: 5px; font-size: 0.9em; }
        input, select, textarea { 
            width: 100%; padding: 12px; border: 2px solid #eee; border-radius: 12px; 
            box-sizing: border-box; margin-bottom: 15px; font-size: 1em;
        }
        .btn-report { 
            width: 100%; padding: 15px; background: #009688; color: white; border: none; 
            border-radius: 30px; font-weight: bold; cursor: pointer; font-size: 1.1em;
        }
        .btn-report:hover { background: #00796b; }
        .success { background: #e0f2f1; color: #00796b; padding: 15px; border-radius: 10px; text-align: center; margin-bottom: 20px; font-weight: bold; }
        .error { background: #ffebee; color: #c62828; padding: 15px; border-radius: 10px; text-align: center; margin-bottom: 20px; }
        .back-link { display: block; text-align: center; margin-top: 20px; text-decoration: none; color: #9e9e9e; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>🐔 Flock Health Report</h2>
    <?php echo $message; ?>

    <form method="POST">
        <label>Select Coop</label>
        <select name="coop_no" required>
            <option value="Coop 1">Coop 1</option>
            <option value="Coop 2">Coop 2</option>
            <option value="Coop 3">Coop 3</option>
        </select>

        <label>Date of Observation</label>
        <input type="date" name="report_date" value="<?php echo date('Y-m-d'); ?>" required>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div>
                <label>Mortality (Dead)</label>
                <input type="number" name="mortality_count" value="0" min="0">
            </div>
            <div>
                <label>Culling (Removed)</label>
                <input type="number" name="culling_count" value="0" min="0">
            </div>
        </div>

        <label>Health Observations / Signs</label>
        <textarea name="health_observations" rows="3" placeholder="e.g. Birds are sneezing, low water intake..."></textarea>

        <button type="submit" class="btn-report">Submit Health Report</button>
    </form>

    <a href="staff_dashboard.php" class="back-link">← Back to Dashboard</a>
</div>

</body>
</html>