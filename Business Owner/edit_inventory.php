<?php
session_start();
include "../includes/db.php"; 

// 1. SECURITY GATE
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../portal/login.php");
    exit;
}

// 2. GET THE RECORD TO EDIT
if (!isset($_GET['id'])) {
    header("Location: manage_inventory.php");
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM egg_inventory WHERE id = ?");
$stmt->execute([$id]);
$record = $stmt->fetch();

if (!$record) {
    die("Record not found!");
}

// 3. HANDLE THE UPDATE (When the "Save Changes" button is clicked)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $coop = $_POST['coop_no'];
    $p = (int)$_POST['peewee'];
    $s = (int)$_POST['small'];
    $m = (int)$_POST['medium'];
    $l = (int)$_POST['large'];
    $xl = (int)$_POST['extra_large'];
    $j = (int)$_POST['jumbo'];
    $cracks = (int)$_POST['cracks'];
    
    // Recalculate Total
    $total = $p + $s + $m + $l + $xl + $j;

    try {
        $sql = "UPDATE egg_inventory 
                SET coop_no = ?, peewee = ?, small = ?, medium = ?, large = ?, extra_large = ?, jumbo = ?, total_harvest = ?, cracks = ? 
                WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$coop, $p, $s, $m, $l, $xl, $j, $total, $cracks, $id]);
        
        header("Location: manage_inventory.php?msg=updated");
        exit;
    } catch (PDOException $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Harvest - Owner</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; padding: 40px; }
        .edit-container { 
            background: white; max-width: 600px; margin: auto; padding: 30px; 
            border-radius: 20px; border-top: 5px solid #3498db; box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        }
        h2 { color: #2c3e50; margin-top: 0; }
        label { display: block; margin: 15px 0 5px; font-weight: bold; color: #7f8c8d; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .btn-save { 
            width: 100%; padding: 15px; background: #3498db; color: white; border: none; 
            border-radius: 10px; font-weight: bold; cursor: pointer; margin-top: 25px; 
        }
        .btn-cancel { display: block; text-align: center; margin-top: 15px; color: #95a5a6; text-decoration: none; }
    </style>
</head>
<body>

<div class="edit-container">
    <h2>✏️ Edit Harvest Record</h2>
    <p>Adjusting record for <strong><?php echo $record['date_collected']; ?></strong></p>

    <form method="POST">
        <label>Coop Number</label>
        <select name="coop_no">
            <option value="Coop 1" <?php if($record['coop_no'] == 'Coop 1') echo 'selected'; ?>>Coop 1</option>
            <option value="Coop 2" <?php if($record['coop_no'] == 'Coop 2') echo 'selected'; ?>>Coop 2</option>
            <option value="Coop 3" <?php if($record['coop_no'] == 'Coop 3') echo 'selected'; ?>>Coop 3</option>
        </select>

        <div class="grid">
            <div><label>Peewee</label><input type="number" name="peewee" value="<?php echo $record['peewee']; ?>"></div>
            <div><label>Small</label><input type="number" name="small" value="<?php echo $record['small']; ?>"></div>
            <div><label>Medium</label><input type="number" name="medium" value="<?php echo $record['medium']; ?>"></div>
            <div><label>Large</label><input type="number" name="large" value="<?php echo $record['large']; ?>"></div>
            <div><label>XL</label><input type="number" name="extra_large" value="<?php echo $record['extra_large']; ?>"></div>
            <div><label>Jumbo</label><input type="number" name="jumbo" value="<?php echo $record['jumbo']; ?>"></div>
        </div>

        <label>Cracks</label>
        <input type="number" name="cracks" value="<?php echo $record['cracks']; ?>">

        <button type="submit" class="btn-save">Update Record</button>
        <a href="manage_inventory.php" class="btn-cancel">Cancel and Go Back</a>
    </form>
</div>

</body>
</html>