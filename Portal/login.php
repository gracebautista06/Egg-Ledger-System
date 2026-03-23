<?php
session_start();
include "../includes/db.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$user]);
    $user_data = $stmt->fetch();

    // SIMPLE COMPARISON (No hashing)
    if ($user_data && $pass === $user_data['password']) {
        $_SESSION['user_id'] = $user_data['id'];
        $_SESSION['username'] = $user_data['username'];
        $_SESSION['role'] = $user_data['role'];

        if ($user_data['role'] == 'owner') {
            header("Location: ../Business Owner/owner_dashboard.php");
        } else {
            header("Location: ../Farm Staff/staff_dashboard.php");
        }
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Egg Ledger</title>
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
            width: 300px;
        }
        h2 { color: #ff9800; }
        input {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            border: 2px solid #ddd;
            border-radius: 20px;
        }
        .btn { 
            padding: 10px 25px; 
            background: #ffcc33; 
            border: none;
            color: #5d4037; 
            font-weight: bold;
            border-radius: 25px;
            cursor: pointer;
            width: 85%;
        }
        .btn:hover { background: #ffb300; }
        .error { color: #d32f2f; font-size: 0.9em; }

        .alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 8px;
    font-size: 0.9em;
    text-align: center;
    font-weight: bold;
}
.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}
.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        
<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success">
        <?php 
            if ($_GET['msg'] == 'logged_out') echo "Successfully logged out. See you soon!";
            if ($_GET['msg'] == 'registered') echo "Account created! Please log in.";
        ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger">
        <?php 
            if ($_GET['error'] == 'invalid') echo "Invalid username or password.";
            if ($_GET['error'] == 'expired') echo "Session expired. Please log in again.";
            if ($_GET['error'] == 'unauthorized') echo "Access denied: Owners only!";
        ?>
    </div>
<?php endif; ?>

        
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required><br><br>
            <input type="password" name="password" placeholder="Password" required><br><br>
            <button type="submit" class="btn">Login</button>
        </form>
        <a href="register.php" class="logout-link">Create Account</a>
    </div>
</body>
</html>