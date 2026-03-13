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
        /* General Page Styling */
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: #fdfaf1; /* Creamy white background */
            color: #5d4037; /* Earthy brown text */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* The Main Container */
        .container {
            background: white;
            padding: 40px;
            border-radius: 50px; /* Rounded like an egg */
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            text-align: center;
            border: 4px solid #ffcc33; /* Yolk yellow border */
            max-width: 400px;
            position: relative;
        }

        /* Small Egg Icon Decoration */
        .egg-icon {
            width: 40px;
            height: 50px;
            background-color: #fff;
            border: 2px solid #ddd;
            border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
            display: inline-block;
            margin-bottom: 10px;
        }

        h1 { color: #ff9800; margin-bottom: 10px; }

        .nav-links { margin-top: 30px; }

        /* Button Styling */
        .btn { 
            display: block;
            padding: 12px 25px; 
            margin: 10px auto;
            text-decoration: none; 
            background: #ffcc33; /* Yolk Yellow */
            color: #5d4037; 
            font-weight: bold;
            border-radius: 25px;
            transition: transform 0.2s, background 0.2s;
            width: 80%;
        }

        .btn:hover {
            background: #ffb300;
            transform: scale(1.05);
        }

        .btn-register {
            background: #8bc34a; /* Farm Green */
            color: white;
        }

        .btn-register:hover {
            background: #7cb342;
        }

        .logout-link {
            display: block;
            margin-top: 20px;
            color: #d32f2f;
            text-decoration: none;
            font-size: 0.9em;
        }
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
                <a href="dashboard.php" class="btn">Open Inventory</a>
                <a href="logout.php" class="logout-link">Logout</a>
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