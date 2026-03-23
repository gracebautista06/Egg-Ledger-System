<?php
session_start();

// 1. SECURITY GATE: If not logged in OR if the user is NOT a staff member, kick them out
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    // Redirect back to login in the portal folder
    header("Location: ../portal/login.php?error=access_denied");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Egg Ledger</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Custom dashboard layout */
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
    
    <div class="nav-actions">
        <a href="inventory_input.php" class="btn">🥚 Record Harvest</a>
        <a href="record_sales.php" class="btn" style="background: #fb8c00;">💰 Record Sale</a>
        <a href="flock_status.php" class="btn" style="background: #009688;">🐔 Flock Health</a>

        <hr style="border: 1px solid #eee; margin: 25px 0;">

        <a href="view_my_records.php" class="btn-view">📄 View My History & Reports</a>
    </div>

    <a href="../portal/logout_confirm.php" class="logout-btn">Logout from System</a>
    </div>

</body>
</html>