<?php
session_start();
include '../config/config.php';

// ตรวจสอบสิทธิ์ผู้ใช้งาน
if (!isset($_SESSION['role_account']) || !in_array($_SESSION['role_account'], ['admin', 'advisor'])) {
    $_SESSION['Messager'] = 'You are not authorized!';
    header("location: {$base_url}../index.php");
    exit();
}

// รับค่า ID ของนักเรียน
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['Messager'] = 'Invalid Student ID!';
    header("location: admin_show_register_international.php");
    exit();
}
$student_id = $_GET['id'];

// ดึงข้อมูลนักเรียน
$sql = "SELECT * FROM student_profile WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

// ดึงข้อมูลทักษะภาษา
$sql_lang = "SELECT * FROM language_skills WHERE student_id = ?";
$stmt_lang = $conn->prepare($sql_lang);
$stmt_lang->bind_param("i", $student_id);
$stmt_lang->execute();
$result_lang = $stmt_lang->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Skill Student</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../image/apple-touch-icon.png">
    <link rel="android-chrome" href="../image/android-chrome-192x192.png">
    <link rel="android-chrome" href="../image/android-chrome-512x512.png">
    <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="../image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../image/favicon-16x16.png">
    <link rel="manifest" href="../image/site.webmanifest">
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* ตั้งค่าเริ่มต้น */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* การตั้งค่าของ body */
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


        /* Sidebar */
        .sidebar {
            width: 220px;
            height: 100vh;
            background: linear-gradient(to bottom, #6d0019, #a52a2a);
            padding: 20px;
            color: white;
            position: fixed;
            list-style: none;
            /* ลบจุดออก */
        }

        .sidebar ul {
            list-style: none;
            /* ลบจุดจาก ul */
            padding: 0;
            margin: 0;
        }

        .sidebar li {
            list-style: none;
            /* ลบจุดจาก li */
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


        /* Container */
        .container {
            padding: 30px;
            margin-left: 240px;
            /* เพิ่มระยะห่างจาก Sidebar */
            margin-top: 20px;
            /* เพิ่มระยะห่างด้านบน */
            width: calc(100% - 260px);
            max-width: 1100px;
            /* กำหนดขนาดสูงสุดเพื่อไม่ให้กว้างเกินไป */
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }

        /* หัวข้อของหน้า */
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 32px;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="../admin/home_admin.php">Home Admin</a></li>
            <li><a href="../admin/admin_create_account.php">Create New Account</a></li>
            <li><a href="../admin/admin_manage_accounts.php">Manage Account</a></li>
            <li><a href="../admin/admin_show_student.php">Student List</a></li>
            <li><a href="../admin/admin_show_advisor.php">Advisor List</a></li>
            <li><a href="../admin/admin_manage_applications.php">Form Doc Approval Request</a></li>
            <li><a href="../admin/admin_show_register_international.php">Student Skill</a></li>
            <li><a href="../company/company_details.php"></i>Uplode Form Company</a></li>
            <li><a href="<?php echo $base_url; ?>../index.php" onclick="return confirm('Are you sure you want to log out?');"> Logout</a></li>
        </ul>
    </div>

    <div class="container mt-4">
        <h2 class="text-center">Admin View Skill</h2>
        <table class="table table-bordered">
            <tr>
                <th>Name</th>
                <td><?= htmlspecialchars($student['student_name'] ?? '') ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?= htmlspecialchars($student['email'] ?? '') ?></td>
            </tr>
            <tr>
                <th>University</th>
                <td><?= htmlspecialchars($student['university'] ?? '') ?></td>
            </tr>
            <tr>
                <th>Faculty</th>
                <td><?= htmlspecialchars($student['faculty'] ?? '') ?></td>
            </tr>
            <tr>
                <th>Department</th>
                <td><?= htmlspecialchars($student['department'] ?? '') ?></td>
            </tr>
            <tr>
                <th>Skill</th>
                <td><?= htmlspecialchars($student['skill'] ?? '') ?></td>
            </tr>
            <tr>
                <th>Contact Number</th>
                <td><?= htmlspecialchars($student['contact_number'] ?? '') ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?= htmlspecialchars($student['address'] ?? '') ?></td>
            </tr>
            <tr>
                <th>Candidate Qualifications</th>
                <td><?= htmlspecialchars($student['candidate_qualifications'] ?? '') ?></td>
            </tr>
        </table>

        <h3>Language Skills</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Language</th>
                    <th>Listening</th>
                    <th>Speaking</th>
                    <th>Writing</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($lang = $result_lang->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($lang['language'] ?? '') ?></td>
                        <td><?= htmlspecialchars($lang['listening_level'] ?? '') ?></td>
                        <td><?= htmlspecialchars($lang['speaking_level'] ?? '') ?></td>
                        <td><?= htmlspecialchars($lang['writing_level'] ?? '') ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="advisor_show_register_international.php" class="btn btn-secondary">Back</a>
    </div>
</body>

</html>