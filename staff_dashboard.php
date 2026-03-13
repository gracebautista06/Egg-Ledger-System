<?php
session_start();

// Security check: Ensure only staff can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Egg Ledger</title>
    <style>
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background-color: #fdfaf1; 
            color: #5d4037; 
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
            max-width: 500px;
        }
        .egg-icon {
            width: 50px;
            height: 60px;
            background-color: #fff;
            border: 2px solid #ddd;
            border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
            display: inline-block;
            margin-bottom: 15px;
        }
        h1 { color: #ff9800; margin-bottom: 10px; }
        p { line-height: 1.6; color: #795548; }
        
        .nav-actions { margin-top: 30px; }
        
        .btn { 
            display: inline-block;
            padding: 15px 30px; 
            text-decoration: none; 
            background: #8bc34a; /* Farm Green */
            color: white; 
            font-weight: bold;
            border-radius: 30px;
            transition: 0.3s;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .btn:hover {
            background: #7cb342;
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        .logout-btn {
            display: block;
            margin-top: 25px;
            color: #d32f2f;
            text-decoration: none;
            font-size: 0.9em;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="egg-icon"></div>
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p>You are logged in as <strong>Farm Staff</strong>.</p>
        <p>Ready to record the harvest?</p>

        <div class="nav-actions">
            <a href="inventory_input.php" class="btn">Continue to Inventory</a>
        </div>

        <a href="logout_confirm.php" class="logout-btn">Logout from System</a>
    </div>

</body>
</html>