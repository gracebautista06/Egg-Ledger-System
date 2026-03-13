<?php
session_start();

// If not logged in, just go to index
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logout - Egg Ledger</title>
    <style>
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background-color: #fdfaf1; 
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 50px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            text-align: center;
            border: 4px solid #ffcc33;
            width: 350px;
        }
        h2 { color: #5d4037; }
        .btn-group { margin-top: 30px; }
        
        .btn {
            display: inline-block;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            margin: 0 10px;
            transition: 0.2s;
        }
        
        .btn-yes {
            background: #d32f2f;
            color: white;
        }
        
        .btn-no {
            background: #ffcc33;
            color: #5d4037;
        }

        .btn:hover { transform: scale(1.05); }
    </style>
</head>
<body>

    <div class="container">
        <h2>Are you sure?</h2>
        <p>Do you really want to log out of the Egg Ledger system?</p>
        
        <div class="btn-group">
            <a href="logout_action.php" class="btn btn-yes">Yes, Logout</a>
            
            <a href="<?php echo ($_SESSION['role'] == 'owner') ? 'owner_dashboard.php' : 'staff_dashboard.php'; ?>" class="btn btn-no">No, Stay</a>
        </div>
    </div>

</body>
</html>