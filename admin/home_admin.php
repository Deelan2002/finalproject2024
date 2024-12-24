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
    <link rel="stylesheet" href="../css/style_home_admin.css">

</head>

<body>

    <header>
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username_account']); ?>(Admin)</h1>
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

    <div class="card">
        <div class="card-content">
            <img src="../image/software-engineer.png" alt="Icon" class="icon-image">
            <span>Advisor</span>
            <button class="action-button">Click</button>
        </div>
    </div>

    <div class="card">
        <div class="card-content">
            <img src="../image/online-survey.png" alt="Icon" class="icon-image">
            <span>Request for approval of important documents</span>
            <button class="action-button">Click</button>
        </div>
    </div>

    <div class="card">
        <div class="card-content">
            <img src="../image/graduated.png" alt="Icon" class="icon-image">
            <span>Student</span>
            <button class="action-button" onclick="window.location.href='../admin/admin_show_student.php';">Click</button>
        </div>
    </div>

</body>

</html>