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
    $customer = $_POST['customer_name'];
    $type = $_POST['customer_type'];
    $size = $_POST['egg_size'];
    $qty = (int)$_POST['quantity_trays'];
    $price = (float)$_POST['price_per_tray'];
    
    // AUTOMATIC CALCULATION: Quantity * Price
    $total_amount = $qty * $price;

    try {
        $sql = "INSERT INTO egg_sales (customer_name, customer_type, egg_size, quantity_trays, price_per_tray, total_amount, date_sold, sold_by) 
                VALUES (?, ?, ?, ?, ?, ?, CURDATE(), ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $customer, 
            $type, 
            $size, 
            $qty, 
            $price, 
            $total_amount, 
            $_SESSION['username']
        ]);
        
        $message = "<div class='success'>💰 Sale Recorded! Total: ₱" . number_format($total_amount, 2) . "</div>";
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
    <title>Record Sale - Egg Ledger</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #fdfaf1; color: #5d4037; padding: 20px; }
        .form-container { 
            background: white; max-width: 450px; margin: auto; padding: 30px; 
            border-radius: 30px; border: 4px solid #fb8c00; box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        h2 { color: #e65100; text-align: center; margin-top: 0; }
        label { display: block; font-weight: bold; margin-bottom: 5px; font-size: 0.9em; }
        input, select { 
            width: 100%; padding: 12px; border: 2px solid #eee; border-radius: 12px; 
            box-sizing: border-box; margin-bottom: 15px; font-size: 1em;
        }
        .btn-sale { 
            width: 100%; padding: 15px; background: #fb8c00; color: white; border: none; 
            border-radius: 30px; font-weight: bold; cursor: pointer; font-size: 1.1em;
        }
        .btn-sale:hover { background: #ef6c00; }
        .success { background: #fff3e0; color: #e65100; padding: 15px; border-radius: 10px; text-align: center; margin-bottom: 20px; font-weight: bold; }
        .error { background: #ffebee; color: #c62828; padding: 15px; border-radius: 10px; text-align: center; margin-bottom: 20px; }
        .back-link { display: block; text-align: center; margin-top: 20px; text-decoration: none; color: #9e9e9e; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>💰 Record Egg Sale</h2>
    <?php echo $message; ?>

    <form method="POST">
        <label>Customer Name</label>
        <input type="text" name="customer_name" placeholder="Enter Customer name" required>

        <label>Customer Type</label>
        <select name="customer_type">
            <option value="Walk-in">Walk-in</option>
            <option value="Retail">Retailer / Store</option>
            <option value="Wholesale">Wholesale Buyer</option>
        </select>

        <label>Egg Size Sold</label>
        <select name="egg_size">
            <option value="Peewee">Peewee</option>
            <option value="Small">Small</option>
            <option value="Medium">Medium</option>
            <option value="Large">Large</option>
            <option value="Extra Large">Extra Large</option>
            <option value="Jumbo">Jumbo</option>
        </select>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div>
                <label>Qty (Trays)</label>
                <input type="number" name="quantity_trays" value="1" min="1" required>
            </div>
            <div>
                <label>Price per Tray</label>
                <input type="number" name="price_per_tray" step="0.01" placeholder="0.00" required>
            </div>
        </div>

        <button type="submit" class="btn-sale">Confirm Sale</button>
    </form>

    <a href="staff_dashboard.php" class="back-link">← Back to Dashboard</a>
</div>

</body>
</html>