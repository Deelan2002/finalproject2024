<?php
session_start();
include '../config/config.php';

// ตรวจสอบการเข้าสู่ระบบและบทบาทผู้ใช้
if (empty($_SESSION['id_account']) || $_SESSION['role_account'] !== 'admin') {
    $_SESSION['Messager'] = 'You are not authorized!';
    header("Location: {$base_url}../index.php");
    exit();
}

// ดึงข้อมูลแอดมินจากฐานข้อมูล
$id_account = $_SESSION['id_account'];
$query = mysqli_query($conn, "SELECT * FROM accounts WHERE id_account='{$id_account}'");
$admin_account = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        header {
            background: linear-gradient(to right, #ff9966, #ff5e62);
            color: white;
            padding: 10px 20px;
            text-align: center;
        }

        nav {
            background: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        nav a {
            color: white;
            margin: 0 10px;
            text-decoration: none;
            padding: 5px 10px;
            background-color: #ff6600;
            border-radius: 5px;
        }

        nav a:hover {
            background-color: #e65c00;
        }

        .container {
            margin: 20px auto;
            max-width: 800px;
            text-align: center;
        }

        h1 {
            color: #333;
        }
    </style>
</head>

<body>

    <header>
        <h1>Welcome,  <?php echo htmlspecialchars($_SESSION['username_account']); ?>(Admin)</h1>
    </header>

    <nav>
        <!-- ลิงก์ไปยังหน้าต่าง ๆ -->
        <a href="../admin/admin_create_account.php">Create New Account</a>
        <a href="../admin/admin_manage_accounts.php">Manage Accounts</a>
        <a href="<?php echo $base_url; ?>../index.php" onclick="return confirm('Are you sure you want to log out?');">Logout</a>
    </nav>

    <div class="container">
        <h2>Admin Dashboard</h2>
        <p>Use the navigation menu above to manage accounts.</p>
    </div>

</body>

</html>
