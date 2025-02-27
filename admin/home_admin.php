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
    <link rel="apple-touch-icon" sizes="180x180" href="../image/apple-touch-icon.png">
    <link rel="android-chrome" href="../image/android-chrome-192x192.png">
    <link rel="android-chrome" href="../image/android-chrome-512x512.png">
    <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="../image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../image/favicon-16x16.png">
    <link rel="manifest" href="../image/site.webmanifest">
    <link rel="stylesheet" href="../css/style_home_admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: none;
            /* ปิดภาพพื้นหลังหลัก */
            display: flex;
            justify-content: flex-start;
            align-items: flex-start;
            overflow-x: hidden;
            position: relative;
        }

        /* สร้างเลเยอร์ภาพพื้นหลัง */
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('../image/pxu1.jpeg');
            /* เปลี่ยนเป็นที่อยู่ของภาพ */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            filter: blur(3px);
            /* ปรับค่าความเบลอ (px) */
            z-index: -1;
            /* ให้ภาพอยู่ด้านหลัง */
        }

        body::after {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.1);
            /* ปรับความมืด (0.3 = 30%) */
            z-index: -1;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h3>Admin Panel</h3>
        <a href="../admin/admin_create_account.php"></i> Create New Account</a>
        <a href="../admin/admin_manage_accounts.php"></i> Manage Accounts</a>
        <a href="../admin/admin_manage_applications.php"></i> Form Doc Approval Request</a>
        <a href="../admin/admin_show_student.php"></i>Student List</a>
        <a href="../admin/admin_show_register_international.php"></i>Student Skill</a>
        <a href="../admin/admin_show_advisor.php"></i>Advisor List</a>
        <a href="../company/company_details.php"></i>Uplode Form Company</a>
        <a href="<?php echo $base_url; ?>../index.php" onclick="return confirm('Are you sure you want to log out?');"> Logout</a>
    </div>

    <div class="content">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username_account']); ?> (Admin)</h2>
        <p>Use the sidebar to navigate.</p>

        <div class="card-container">
            <div class="card">
                <img src="../image/software-engineer.png" alt="Advisor">
                <h4>Advisor</h4>
                <button onclick="window.location.href='../admin/admin_show_advisor.php';">View</button>
            </div>

            <div class="card">
                <img src="../image/graduated.png" alt="Student">
                <h4>Student</h4>
                <button onclick="window.location.href='../admin/admin_show_student.php';">View</button>
            </div>

            <div class="card">
                <img src="../image/online-survey.png" alt="Request">
                <h4>Student Skill</h4>
                <button onclick="window.location.href='../admin/admin_show_register_international.php';">View</button>
            </div>

            <div class="card">
                <img src="../image/digital-nomad.png" alt="Company">
                <h4>Company Form</h4>
                <button onclick="window.location.href='../company/company_details.php';">View</button>
            </div>

            <div class="card">
                <img src="../image/b2b.png" alt="Jobs">
                <h4>Form Doc Approval Request</h4>
                <button onclick="window.location.href='../admin/admin_manage_applications.php';">View</button>
            </div>

        </div>
    </div>
</body>

</html>