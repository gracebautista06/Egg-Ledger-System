<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Egg Ledger System</title>
    <style>
        body { font-family: sans-serif; text-align: center; margin-top: 50px; }
        .nav-links { margin-top: 20px; }
        .btn { padding: 10px 20px; text-decoration: none; background: #007bff; color: white; border-radius: 5px; }
    </style>
</head>
<body>

    <h1>Welcome to our System</h1>

    <?php if (isset($_SESSION['user_id'])): ?>
        <p>Hello, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>! You are currently logged in.</p>
        <div class="nav-links">
            <a href="dashboard.php" class="btn">Go to Dashboard</a>
            <a href="logout.php" style="color: red;">Logout</a>
        </div>
    <?php else: ?>
        <p>Please log in or create an account to access the dashboard.</p>
        <div class="nav-links">
            <a href="login.php" class="btn">Login</a>
            <a href="register.php" class="btn" style="background: #28a745;">Register</a>
        </div>
    <?php endif; ?>

</body>
</html>