<?php
session_start();
include '../config/config.php';

// ตรวจสอบสิทธิ์ผู้ใช้ (เฉพาะ advisor เท่านั้น)
if (!isset($_SESSION['role_account']) || $_SESSION['role_account'] !== 'advisor') {
    $_SESSION['Messager'] = 'You are not authorized!';
    header("location: {$base_url}../index.php");
    exit();
}

// รับค่า id_student ที่ถูกส่งมา
$id_student = isset($_GET['id_student']) ? intval($_GET['id_student']) : 0;
if ($id_student == 0) {
    $_SESSION['Messager'] = 'Invalid student ID!';
    header("location: daily_list.php");
    exit();
}

// ดึงข้อมูลนักศึกษา
$query_student = mysqli_query($conn, "SELECT name_student, nickname_student FROM profile_students WHERE id_student = '$id_student'");
$student = mysqli_fetch_assoc($query_student);

if (!$student) { // ถ้าไม่พบข้อมูลนักศึกษา
    $_SESSION['Messager'] = 'ไม่พบนักศึกษานี้ในระบบ!';
    header("location: daily_list.php");
    exit();
}


// ดึงข้อมูลบันทึกประจำวันของนักศึกษา
$query_logs = mysqli_query($conn, "SELECT * FROM worklog WHERE id_account = (SELECT id_account FROM profile_students WHERE id_student = '$id_student') ORDER BY work_date DESC");
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>บันทึกประจำวันของ <?= htmlspecialchars($student['name_student']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="../image/apple-touch-icon.png">
    <link rel="android-chrome" href="../image/android-chrome-192x192.png">
    <link rel="android-chrome" href="../image/android-chrome-512x512.png">
    <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="../image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../image/favicon-16x16.png">
    <link rel="manifest" href="../image/site.webmanifest">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Lato', sans-serif;
        }

        body {
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

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            /* กำหนดความกว้างของ Sidebar */
            height: 100%;
            background: #7a0f0f;
            padding: 20px;
            color: white;
            overflow-y: auto;
        }

        .container {
            margin-left: 270px;
            /* ขยับ Container ออกจาก Sidebar */
            padding: 20px;
            max-width: 80%;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Sidebar */
        .sidebar {
            width: 220px;
            height: 100vh;
            background: linear-gradient(to bottom, #6d0019, #a52a2a);
            padding: 20px;
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            overflow: hidden;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar li {
            list-style: none;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px;
            margin: 8px 0;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.15);
            transition: background 0.3s, transform 0.2s;
            text-align: center;
        }

        .sidebar a:hover {
            color: #ff6347;
            background: rgba(255, 255, 255, 0.25);
            transform: scale(1.05);
        }
    </style>
</head>
<body>

        <!-- Sidebar -->
    <div class="sidebar">
        <a class="navbar-brand" href="../advisor/home_advisor.php">
            <img src="../image/logo-pxu.png" alt="SDIC Logo" width="40" height="40"> SDIC
        </a>
        <h4>advisor Panel</h4>
        <span class="navbar-text ms-3">
            Welcome, <?php echo $_SESSION['username_account']; ?>
        </span>
        <ul>
            <li> <a class="nav-link" href="<?php echo $base_url; ?>/advisor/home_advisor.php">Home Advisor</a></li>
            <li><a class="nav-link" href="<?php echo $base_url; ?>/advisor/profile_advisor.php">Profile</a></li>
            <li><a class="nav-link" href="<?php echo $base_url; ?>/advisor/advisor_view_student.php">Student List</a></li>
            <li><a class="nav-link" href="<?php echo $base_url; ?>/advisor/advisor_manage_applications.php">Form Doc Approval Request</a></li>
            <li><a class="nav-link" href="<?php echo $base_url; ?>/advisor/company_details.php">Uplode Form Company</a></li>
            <li><a class="nav-link" href="<?php echo $base_url; ?>/advisor/daily_list.php">Daily</a></li>
            <li>
                <a class="nav-link" href="<?php echo $base_url; ?>/realtime_chat/choose_receiver.php?receiver_id=<?php echo isset($_GET['receiver_id']) ? htmlspecialchars($_GET['receiver_id']) : 0; ?>">
                    Chat
                </a>
            </li>
            <li> <a class="nav-link" href="<?php echo $base_url; ?>/advisor/advisor_reset_password.php">Reset Password</a></li>
            <li><a href=" <?php echo $base_url; ?>../index.php" onclick="return confirm('Are you sure you want to log out?');"> Logout</a></li>
        </ul>
    </div>

    <div class="container mt-5">
        <h2>บันทึกประจำวันของ <?= htmlspecialchars($student['name_student']); ?> (<?= htmlspecialchars($student['nickname_student']); ?>)</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>วัน/เดือน/ปี</th>
                    <th>รายละเอียด</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($log = mysqli_fetch_assoc($query_logs)): ?>
                    <tr>
                        <td><?= htmlspecialchars($log['work_date']); ?></td>
                        <td><?= htmlspecialchars($log['details']); ?></td>
                        <td>
                            <a href="edit_worklog.php?id=<?= $log['id']; ?>" class="btn btn-warning">แก้ไข</a>
                            <a href="delete_worklog.php?id=<?= $log['id']; ?>" class="btn btn-danger" onclick="return confirm('ยืนยันการลบ?');">ลบ</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="daily_list.php" class="btn btn-secondary">🔙 กลับ</a>
    </div>
</body>
</html>
