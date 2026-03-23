<?php
session_start();

// 1. Unset all session variables
$_SESSION = array();

// 2. If it's desired to kill the session, also delete the session cookie.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Finally, destroy the session.
session_destroy();

// 4. Redirect to login with a "logged_out" message
header("Location: login.php?msg=logged_out");
exit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logout - Egg Ledger</title>
    <style>
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background-color: #fdfaf1; 
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
            width: 350px;
        }
        h2 { color: #5d4037; }
        .btn-group { margin-top: 30px; }
        
        .btn {
            display: inline-block;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            margin: 0 10px;
            transition: 0.2s;
        }
        
        .btn-yes {
            background: #d32f2f;
            color: white;
        }
        
        .btn-no {
            background: #ffcc33;
            color: #5d4037;
        }

        .btn:hover { transform: scale(1.05); }
    </style>
</head>
<body>

    <div class="container">
    <h2>Logging out?</h2>
    <p>Are you sure you want to leave the Egg Ledger?</p>
    <div class="btn-group">
        <a href="logout.php" class="btn">Yes, Logout</a>

        <a href="javascript:history.back()" class="btn btn-no">No, Stay</a>
    </div>
</div>

</body>
</html>