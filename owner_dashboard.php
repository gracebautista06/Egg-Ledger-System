<?php
session_start();

// Security check: Ensure only owners can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard - Egg Ledger</title>
    <style>
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background-color: #fdfaf1; 
            color: #5d4037; 
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .dashboard-card {
            background: white;
            padding: 40px;
            border-radius: 50px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            text-align: center;
            border: 4px solid #ffcc33;
            max-width: 500px;
            width: 90%;
        }

        .role-badge {
            background-color: #ff9800;
            color: white;
            padding: 6px 18px;
            border-radius: 20px;
            font-size: 0.75em;
            text-transform: uppercase;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 10px;
        }

        h1 { color: #ff9800; margin: 10px 0; font-size: 1.8em; }
        .welcome-msg { color: #795548; margin-bottom: 30px; font-style: italic; }
        
        .menu-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .btn { 
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 18px; 
            text-decoration: none; 
            background: #ffcc33; 
            color: #5d4037; 
            font-weight: bold;
            border-radius: 30px;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .btn:hover {
            background: #fff;
            border: 2px solid #ffcc33;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(255, 204, 51, 0.4);
        }

        .btn-secondary {
            background: #8bc34a; /* Farm Green */
            color: white;
        }

        .btn-secondary:hover {
            background: #fff;
            border: 2px solid #8bc34a;
            color: #8bc34a;
            box-shadow: 0 5px 15px rgba(139, 195, 74, 0.4);
        }

        .footer-links {
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        .logout-link {
            color: #d32f2f;
            text-decoration: none;
            font-size: 0.9em;
            font-weight: 600;
        }

        .logout-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <div class="dashboard-card">
        <div class="role-badge">Administrator Access</div>
        <h1>Master Panel</h1>
        <p class="welcome-msg">Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>

        <div class="menu-grid">
            <a href="inventory_view.php" class="btn">
                📊 View Farm Inventory
            </a>
            
            <a href="user_list.php" class="btn btn-secondary">
                👥 Manage User Accounts
            </a>
            
            <a href="reports.php" class="btn" style="background: #f1f1f1; color: #999; cursor: not-allowed;">
                📈 Sales Reports (Coming Soon)
            </a>
        </div>

        <div class="footer-links">
            <a href="logout_confirm.php" class="logout-link">Secure Logout</a>
        </div>
    </div>

</body>
</html>