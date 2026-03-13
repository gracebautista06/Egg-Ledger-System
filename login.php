<?php
session_start();

// Database connection
$host = 'localhost';
$dbname = 'inventory_schema';
$username_db = 'root';
$password_db = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username_db, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$user]);
    $user_data = $stmt->fetch();

    // FIXED: Changed password_verify() to a direct string comparison (===)
    if ($user_data && $pass === $user_data['password']) {
        $_SESSION['user_id'] = $user_data['id'];
        $_SESSION['username'] = $user_data['username'];
        $_SESSION['role'] = $user_data['role'];

        if ($user_data['role'] == 'owner') {
            header("Location: owner_dashboard.php");
        } else {
            header("Location: staff_dashboard.php");
        }
        exit;
    } else {
        $error = "Incorrect username or password.";
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
    </style>
</head>
<body>

    <div class="container">
        <h2>Egg Ledger Login</h2>
        
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
        
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br><br>
            <button type="submit" class="btn">Login</button>
        </form>
        
        <p style="font-size: 0.8em; margin-top: 20px;">
            <a href="register.php" style="color: #8bc34a;">Create an Account</a>
        </p>
    </div>

</body>
</html>