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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password']; // No hashing function used here
    $role = $_POST['role'];

    try {
        // Insert the raw password directly
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$user, $pass, $role]);
        $message = "Account created! <a href='login.php'>Login here</a>";
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
    <title>Create Account - EggLedger</title>
    <style>
        body { font-family: sans-serif; background-color: #fdfaf1; text-align: center; padding-top: 50px; }
        .form-container { background: white; padding: 30px; border-radius: 40px; border: 3px solid #ffcc33; display: inline-block; }
        .btn { background: #ffcc33; border: none; padding: 10px 20px; border-radius: 20px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Register New User</h2>
        <?php if($message) echo "<p>$message</p>"; ?>
        
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required><br><br>
            <input type="password" name="password" placeholder="Password" required><br><br>
            <select name="role" required>
                <option value="staff">Farm Staff</option>
                <option value="owner">Business Owner</option>
            </select><br><br>
            <button type="submit" class="btn">Create Account</button>
        </form>
        <p><a href="login.php">Back to Login</a></p>
    </div>
</body>
</html>