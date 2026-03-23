<?php
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

$message = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password']; 
    $role = $_POST['role'];

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$user, $pass, $role]);
        
        // Flag for the JavaScript redirect
        $success = true;
        $message = "Account created successfully! Redirecting to login...";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $message = "Error: That username is already taken.";
        } else {
            $message = "Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - EggLedger</title>
    <style>
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

        .form-container { 
            background: white; 
            padding: 40px; 
            border-radius: 50px; 
            border: 4px solid #ffcc33; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            text-align: center;
            width: 100%;
            max-width: 350px;
        }

        h2 { color: #ff9800; margin-bottom: 25px; }

        .input-group { margin-bottom: 15px; text-align: left; }
        
        label { display: block; margin-bottom: 5px; font-weight: bold; font-size: 0.9em; }

        input, select {
            width: 100%;
            padding: 12px;
            border: 2px solid #eee;
            border-radius: 20px;
            box-sizing: border-box; /* Ensures padding doesn't affect width */
            font-size: 1em;
            outline: none;
            transition: border-color 0.3s;
        }

        input:focus, select:focus { border-color: #ffcc33; }

        .btn { 
            width: 100%;
            background: #ffcc33; 
            border: none; 
            padding: 12px; 
            border-radius: 25px; 
            cursor: pointer; 
            font-weight: bold; 
            color: #5d4037;
            font-size: 1em;
            margin-top: 10px;
            transition: background 0.3s, transform 0.2s;
        }

        .btn:hover { background: #ffb300; transform: scale(1.02); }

        .message {
            padding: 10px;
            border-radius: 15px;
            margin-bottom: 20px;
            font-size: 0.9em;
        }
        
        .error { background-color: #ffebee; color: #c62828; border: 1px solid #ef9a9a; }
        .success { background-color: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7; }

        .login-link { 
            display: inline-block; 
            margin-top: 20px; 
            color: #8bc34a; 
            text-decoration: none; 
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Register</h2>

        <?php if($message): ?>
            <div class="message <?php echo $success ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="input-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Enter username" required>
            </div>

            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Create password" required>
            </div>

            <div class="input-group">
                <label>Access Role</label>
                <select name="role" required>
                    <option value="staff">Farm Staff</option>
                    <option value="owner">Business Owner</option>
                </select>
            </div>

            <button type="submit" class="btn">Create Account</button>
        </form>

        <a href="login.php" class="login-link">Already have an account? Login</a>
    </div>

    <?php if($success): ?>
    <script>
        // Wait 2 seconds so the user can see the success message, then redirect
        setTimeout(function() {
            window.location.href = "login.php?msg=account_created";
        }, 2000);
    </script>
    <?php endif; ?>
</body>
</html>