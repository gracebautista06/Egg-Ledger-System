<?php
session_start();

// 1. SECURITY CHECK: Only owners should see this list
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: login.php");
    exit;
}

// 2. DATABASE CONNECTION
$host = 'localhost';
$dbname = 'inventory_schema';
$username_db = 'root';
$password_db = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username_db, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 3. FETCH ALL USERS
    $stmt = $pdo->query("SELECT id, username, role FROM users ORDER BY role DESC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Egg Ledger</title>
    <style>
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background-color: #fdfaf1; 
            color: #5d4037; 
            margin: 0;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .header-container {
            width: 100%;
            max-width: 800px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .back-link {
            text-decoration: none;
            color: #ff9800;
            font-weight: bold;
        }

        .table-container {
            background: white;
            padding: 30px;
            border-radius: 40px;
            border: 4px solid #ffcc33;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 800px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            text-align: left;
            padding: 15px;
            border-bottom: 2px solid #ffcc33;
            color: #ff9800;
            text-transform: uppercase;
            font-size: 0.85em;
            letter-spacing: 1px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        tr:hover { background-color: #fffde7; }

        .role-pill {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.8em;
            font-weight: bold;
            text-transform: capitalize;
        }

        .role-owner { background: #fff3e0; color: #ef6c00; }
        .role-staff { background: #e8f5e9; color: #2e7d32; }

        .btn-delete {
            color: #d32f2f;
            text-decoration: none;
            font-size: 0.85em;
            font-weight: bold;
            border: 1px solid #d32f2f;
            padding: 5px 10px;
            border-radius: 10px;
            transition: 0.2s;
        }

        .btn-delete:hover {
            background: #d32f2f;
            color: white;
        }
    </style>
</head>
<body>

    <div class="header-container">
        <h1>User Management</h1>
        <a href="Business Owner\owner_dashboard.php" class="back-link">← Back to Dashboard</a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Access Level</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td>#<?php echo htmlspecialchars($user['id']); ?></td>
                    <td><strong><?php echo htmlspecialchars($user['username']); ?></strong></td>
                    <td>
                        <span class="role-pill role-<?php echo $user['role']; ?>">
                            <?php echo htmlspecialchars($user['role']); ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                            <a href="Business Owner\delete_user.php?id=<?php echo $user['id']; ?>" 
                               class="btn-delete" 
                               onclick="return confirm('Are you sure you want to remove this user?')">
                               Remove
                            </a>
                        <?php else: ?>
                            <span style="color: #ccc; font-size: 0.8em;">(You)</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>