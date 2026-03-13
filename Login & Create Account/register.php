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

    // 1. Hash the password before saving
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    try {
        // 2. Insert into database using prepared statements
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$user, $hashed_password]);
        $message = "Registration successful! <a href='login.php'>Login here</a>";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Error code for duplicate entry
            $message = "Username already exists!";
        } else {
            $message = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h2>Create Account</h2>
    <?php if($message) echo "<p>$message</p>"; ?>
    
    <form method="POST">
        <input type="text" name="username" placeholder="Choose Username" required><br><br>
        <input type="password" name="password" placeholder="Choose Password" required><br><br>
        <button type="submit">Register</button>
    </form>
    <p><a href="login.php">Already have an account? Login</a></p>
</body>
</html>