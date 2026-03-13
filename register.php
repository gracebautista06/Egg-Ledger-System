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
    $pass = $_POST['password'];
    $role = $_POST['role']; // Gets 'owner' or 'staff' from the dropdown

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$user, $pass, $role]);
        $message = "Account created successfully for " . htmlspecialchars($role) . "! <a href='login.php'>Login here</a>";
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
<html>
<head>
    <title>Create Account - EggLedger</title>
</head>
<body>
    <h2>Register New User</h2>
    <?php if($message) echo "<p>$message</p>"; ?>
    
    <form method="POST">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <label>Account Type:</label><br>
        <select name="role" required>
            <option value="staff">Farm Staff</option>
            <option value="owner">Business Owner</option>
        </select><br><br>

        <button type="submit">Create Account</button>
    </form>
    <p><a href="login.php">Back to Login</a></p>
</body>
</html>