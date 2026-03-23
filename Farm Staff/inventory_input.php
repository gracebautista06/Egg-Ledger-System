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
    $date_collected = $_POST['date_collected'];
    
    // Collect Egg Sizes
    $p = (int)$_POST['peewee'];
    $s = (int)$_POST['small'];
    $m = (int)$_POST['medium'];
    $l = (int)$_POST['large'];
    $xl = (int)$_POST['extra_large'];
    $j = (int)$_POST['jumbo'];
    
    // AUTOMATIC CALCULATION of Total
    $total_harvest = $p + $s + $m + $l + $xl + $j;

    // Abnormalities (Cracks/Dirty)
    $cracks = (int)$_POST['cracks'];
    $notes = $_POST['notes'];

    try {
        // SQL matching your updated table structure with total_harvest
        $sql = "INSERT INTO egg_inventory (coop_no, date_collected, peewee, small, medium, large, extra_large, jumbo, total_harvest, cracks, notes, recorded_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $coop_no, 
            $date_collected, 
            $p, $s, $m, $l, $xl, $j, 
            $total_harvest, 
            $cracks, 
            $notes, 
            $_SESSION['username']
        ]);
        
        $message = "<div class='success'>✅ Success! Recorded $total_harvest eggs for $coop_no.</div>";
    } catch (PDOException $e) {
        $message = "<div class='error'>❌ Database Error: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Harvest - Egg Ledger</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #fdfaf1; color: #5d4037; padding: 20px; margin: 0; }
        .form-container { 
            background: white; max-width: 500px; margin: 20px auto; padding: 30px; 
            border-radius: 30px; border: 4px solid #ffcc33; box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        h2 { color: #ff9800; text-align: center; margin-top: 0; }
        label { display: block; font-weight: bold; margin-bottom: 5px; font-size: 0.9em; color: #795548; }
        input, select, textarea { 
            width: 100%; padding: 12px; border: 2px solid #eee; border-radius: 12px; 
            box-sizing: border-box; font-size: 1em; outline: none; transition: 0.3s;
        }
        input:focus { border-color: #ffcc33; }
        .grid-inputs { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px; }
        .btn-submit { 
            width: 100%; padding: 15px; background: #8bc34a; color: white; border: none; 
            border-radius: 30px; font-weight: bold; cursor: pointer; margin-top: 20px; font-size: 1.1em;
        }
        .btn-submit:hover { background: #7cb342; transform: scale(1.02); }
        .success { background: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 10px; text-align: center; margin-bottom: 20px; }
        .error { background: #ffebee; color: #c62828; padding: 15px; border-radius: 10px; text-align: center; margin-bottom: 20px; }
        .back-link { display: block; text-align: center; margin-top: 20px; text-decoration: none; color: #9e9e9e; font-size: 0.9em; }
        .section-title { border-bottom: 2px solid #fff3e0; padding-bottom: 5px; margin: 20px 0 15px 0; color: #fb8c00; font-size: 1em; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>🥚 Daily Harvest</h2>
    <?php echo $message; ?>

    <form method="POST">
        <div class="grid-inputs">
            <div>
                <label>Coop Number</label>
                <select name="coop_no" required>
                    <option value="Coop 1">Coop 1</option>
                    <option value="Coop 2">Coop 2</option>
                    <option value="Coop 3">Coop 3</option>
                </select>
            </div>
            <div>
                <label>Date</label>
                <input type="date" name="date_collected" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
        </div>

        <div class="section-title">Egg Sizes (Trays/Pieces)</div>
        
        <div class="grid-inputs">
            <div><label>Peewee</label><input type="number" name="peewee" value="0" min="0"></div>
            <div><label>Small</label><input type="number" name="small" value="0" min="0"></div>
            <div><label>Medium</label><input type="number" name="medium" value="0" min="0"></div>
            <div><label>Large</label><input type="number" name="large" value="0" min="0"></div>
            <div><label>XL</label><input type="number" name="extra_large" value="0" min="0"></div>
            <div><label>Jumbo</label><input type="number" name="jumbo" value="0" min="0"></div>
        </div>

        <div class="section-title">Wastage & Notes</div>

        <div class="grid-inputs">
            <div style="grid-column: span 2;">
                <label>Cracked/Dirty Eggs</label>
                <input type="number" name="cracks" value="0" min="0">
            </div>
        </div>

        <label>Observations</label>
        <textarea name="notes" rows="2" placeholder="e.g. Birds are active, Coop 1 needs cleaning."></textarea>

        <button type="submit" class="btn-submit">Save Harvest</button>
    </form>

    <a href="staff_dashboard.php" class="back-link">← Return to Dashboard</a>
</div>

</body>
</html>