<?php
session_start();
include '../config/config.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบสิทธิ์ผู้ใช้งาน
if (!isset($_SESSION['role_account']) || !in_array($_SESSION['role_account'], ['admin', 'advisor'])) {
    $_SESSION['Messager'] = 'You are not authorized!';
    header("location: {$base_url}../index.php");
    exit();
}

// ตรวจสอบว่ามีการส่ง student_id หรือไม่
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    // ค้นหาข้อมูลนักเรียนจากฐานข้อมูล
    $sql = "SELECT * FROM student_profile WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
    } else {
        echo "Student not found.";
        exit();
    }

    // ดึงข้อมูลทักษะภาษาจากฐานข้อมูล
    $lang_sql = "SELECT * FROM language_skills WHERE student_id = ?";
    $lang_stmt = $conn->prepare($lang_sql);
    $lang_stmt->bind_param("i", $student_id);
    $lang_stmt->execute();
    $result_lang = $lang_stmt->get_result();
}

// อัปเดตข้อมูลเมื่อมีการส่งฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_name = $_POST['student_name'];
    $email = $_POST['email'];
    $university = $_POST['university'];
    $faculty = $_POST['faculty'];
    $department = $_POST['department'];
    $skill = $_POST['skill'];
    $contact_number = $_POST['contact_number'];

    // อัปเดตข้อมูลในฐานข้อมูล
    $update_sql = "UPDATE student_profile SET student_name = ?, email = ?, university = ?, faculty = ?, department = ?, skill = ?, contact_number = ? WHERE student_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssssssi", $student_name, $email, $university, $faculty, $department, $skill, $contact_number, $student_id);

    if ($update_stmt->execute()) {
        // อัปเดตข้อมูลทักษะภาษา
        foreach ($_POST['language'] as $key => $language) {
            $listening = $_POST['listening'][$key];
            $speaking = $_POST['speaking'][$key];
            $writing = $_POST['writing'][$key];

            // อัปเดตข้อมูลทักษะภาษา
            $lang_update_sql = "UPDATE language_skills SET listening_level = ?, speaking_level = ?, writing_level = ? WHERE student_id = ? AND language = ?";
            $lang_update_stmt = $conn->prepare($lang_update_sql);
            $lang_update_stmt->bind_param("sssis", $listening, $speaking, $writing, $student_id, $language);
            $lang_update_stmt->execute();
        }

        header("Location: admin_view_skill.php"); // Redirect ไปหน้าแสดงข้อมูลหลังจากแก้ไข
        exit();
    } else {
        echo "Error updating student.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../image/apple-touch-icon.png">
    <link rel="android-chrome" href="../image/android-chrome-192x192.png">
    <link rel="android-chrome" href="../image/android-chrome-512x512.png">
    <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="../image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../image/favicon-16x16.png">
    <link rel="manifest" href="../image/site.webmanifest">

    <style>
        /* ตั้งค่าเริ่มต้น */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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

        /* Container */
        .container {
            padding: 30px;
            margin-left: 235px;
            /* ขยับไปทางขวามากขึ้น (จาก 240px เป็น 280px) */
            margin-top: 20px;
            width: calc(100% - 260px);
            max-width: 1300px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }

        /* Color palette */
        :root {
            --primary-color: #9b1b30;
            /* เลือดหมู */
            --secondary-color: #d14a54;
            /* สีแดงอ่อนกว่าผสมเลือดหมู */
            --light-color: #f8f1f1;
            /* สีเบจอ่อนสำหรับพื้นหลัง */
            --border-color: #e3a1b8;
            /* สีชมพูอ่อนสำหรับขอบ */
            --button-hover-color: #d15e6e;
            /* สีสำหรับปุ่มเมื่อ hover */
            --table-header-color: #9b1b30;
            /* สีหัวตาราง */
        }


        h2 {
            color: var(--primary-color);
            text-align: center;
            font-size: 2rem;
        }

        form {
            background-color: var(--light-color);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        input[type="text"],
        input[type="email"],
        .form-control {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 15px;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
            transition: border 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        button {
            background-color: var(--primary-color);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            width: 100%;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: var(--button-hover-color);
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table th,
        table td {
            padding: 14px;
            text-align: left;
            border: 1px solid var(--border-color);
            font-size: 16px;
        }

        table th {
            background-color: var(--table-header-color);
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f8f1f1;
        }

        table tr:hover {
            background-color: #f1c6d9;
        }

        table input[type="text"] {
            background-color: #fff;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            padding: 10px;
            width: 100%;
        }

        a {
            color: var(--secondary-color);
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>


<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h3>Admin Panel</h3>
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
        <h2>Edit Student</h2>

        <form method="POST">
            <!-- ข้อมูลนักเรียน -->
            <div class="form-group">
                <label for="student_name">Name</label>
                <input type="text" name="student_name" class="form-control" value="<?= htmlspecialchars($student['student_name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($student['email']) ?>" required>
            </div>
            <div class="form-group">
                <label for="university">University</label>
                <input type="text" name="university" class="form-control" value="<?= htmlspecialchars($student['university']) ?>" required>
            </div>
            <div class="form-group">
                <label for="faculty">Faculty</label>
                <input type="text" name="faculty" class="form-control" value="<?= htmlspecialchars($student['faculty']) ?>" required>
            </div>
            <div class="form-group">
                <label for="department">Department</label>
                <input type="text" name="department" class="form-control" value="<?= htmlspecialchars($student['department']) ?>" required>
            </div>
            <div class="form-group">
                <label for="skill">Skill</label>
                <input type="text" name="skill" class="form-control" value="<?= htmlspecialchars($student['skill']) ?>" required>
            </div>
            <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="text" name="contact_number" class="form-control" value="<?= htmlspecialchars($student['contact_number']) ?>" required>
            </div>

            <!-- Language Skills -->
            <h2>Language Skills</h2>
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
                    <?php $index = 0;
                    while ($lang = $result_lang->fetch_assoc()): ?>
                        <tr>
                            <td><input type="text" name="language[]" class="form-control" value="<?= htmlspecialchars($lang['language']) ?>" required></td>

                            <!-- Listening Level -->
                            <td>
                                <select name="listening[]" class="form-control" required>
                                    <option value="passable" <?php echo $lang['listening_level'] == 'passable' ? 'selected' : ''; ?>>Passable</option>
                                    <option value="moderate" <?php echo $lang['listening_level'] == 'moderate' ? 'selected' : ''; ?>>Moderate</option>
                                    <option value="excellent" <?php echo $lang['listening_level'] == 'excellent' ? 'selected' : ''; ?>>Excellent</option>
                                </select>
                            </td>

                            <!-- Speaking Level -->
                            <td>
                                <select name="speaking[]" class="form-control" required>
                                    <option value="passable" <?php echo $lang['speaking_level'] == 'passable' ? 'selected' : ''; ?>>Passable</option>
                                    <option value="moderate" <?php echo $lang['speaking_level'] == 'moderate' ? 'selected' : ''; ?>>Moderate</option>
                                    <option value="excellent" <?php echo $lang['speaking_level'] == 'excellent' ? 'selected' : ''; ?>>Excellent</option>
                                </select>
                            </td>

                            <!-- Writing Level -->
                            <td>
                                <select name="writing[]" class="form-control" required>
                                    <option value="passable" <?php echo $lang['writing_level'] == 'passable' ? 'selected' : ''; ?>>Passable</option>
                                    <option value="moderate" <?php echo $lang['writing_level'] == 'moderate' ? 'selected' : ''; ?>>Moderate</option>
                                    <option value="excellent" <?php echo $lang['writing_level'] == 'excellent' ? 'selected' : ''; ?>>Excellent</option>
                                </select>
                            </td>
                        </tr>
                    <?php $index++;
                    endwhile; ?>
                </tbody>
            </table>


            <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
        </form>
    </div>
</body>

</html>