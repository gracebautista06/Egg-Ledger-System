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
            width: 90%;
        }
        .badge {
            background-color: #ff9800;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8em;
            text-transform: uppercase;
            font-weight: bold;
        }
        h1 { color: #ff9800; margin-top: 15px; }
        p { color: #795548; margin-bottom: 30px; }
        
        .menu-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .btn { 
            display: block;
            padding: 15px; 
            text-decoration: none; 
            background: #ffcc33; 
            color: #5d4037; 
            font-weight: bold;
            border-radius: 30px;
            transition: 0.3s;
            border: 2px solid transparent;
        }
        
        .btn:hover {
            background: #fff;
            border: 2px solid #ffcc33;
            transform: scale(1.02);
        }

        .btn-users {
            background: #8bc34a;
            color: white;
        }

        .btn-users:hover {
            background: #fff;
            border: 2px solid #8bc34a;
            color: #8bc34a;
        }

        .logout-link {
            display: inline-block;
            margin-top: 25px;
            color: #d32f2f;
            text-decoration: none;
            font-size: 0.9em;
        }
    </style>
</head>
<body>

    <div class="container">
        <span class="badge">Business Owner</span>
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
        <p>Manage your farm operations and staff from here.</p>

        <div class="menu-grid">
            <a href="inventory_view.php" class="btn">Continue to Inventory</a>
            <a href="user_list.php" class="btn btn-users">View User List</a>
        </div>

        <a href="logout_confirm.php" class="logout-link">Logout</a>
    </div>

</body>
</html>