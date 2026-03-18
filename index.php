<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Egg Ledger System</title>
    <style>
        /* Keeping your original "Egg" theme styling */
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
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
            max-width: 400px;
        }

        .egg-icon {
            width: 40px; height: 50px; background-color: #fff; border: 2px solid #ddd;
            border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
            display: inline-block; margin-bottom: 10px;
        }

        h1 { color: #ff9800; margin-bottom: 10px; }
        .nav-links { margin-top: 30px; }

        .btn { 
            display: block; padding: 12px 25px; margin: 10px auto;
            text-decoration: none; background: #ffcc33; color: #5d4037; 
            font-weight: bold; border-radius: 25px; transition: 0.3s; width: 80%;
        }

        .btn:hover { background: #ffb300; transform: scale(1.05); }
        .btn-register { background: #8bc34a; color: white; }
        .btn-register:hover { background: #7cb342; }
        
        .logout-link { display: block; margin-top: 20px; color: #d32f2f; text-decoration: none; font-size: 0.9em; }
    </style>
</head>
<body>

    <div class="container">
        <div class="egg-icon"></div>
        
        <h1>Egg Ledger</h1>
        <p>Your Farm's Digital Record</p>

        <?php if (isset($_SESSION['user_id'])): ?>
            <p>Welcome back, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!</p>
            
            <div class="nav-links">
                <?php 
                    // Redirect based on the role assigned during login
                    $target_page = ($_SESSION['role'] === 'owner') ? 'owner_dashboard.php' : 'staff_dashboard.php'; 
                ?>
                <a href="<?php echo $target_page; ?>" class="btn">Open Inventory</a>
                
                <a href="logout_confirm.php" class="logout-link">Logout</a>
            </div>

        <?php else: ?>
            <p>Ready to record today's harvest?</p>
            <div class="nav-links">
                <a href="login.php" class="btn">Login</a>
                <a href="register.php" class="btn btn-register">Create Account</a>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>