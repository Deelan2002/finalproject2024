<?php
session_start();
include '../config/config.php';

// ตรวจสอบสิทธิ์การเข้าถึงเฉพาะแอดมิน
if (!isset($_SESSION['role_account']) || $_SESSION['role_account'] !== 'admin') {
    $_SESSION['Messager'] = 'You are not authorized!';
    header("location: {$base_url}../index.php");
    exit();
}

// ดึงข้อมูลการสมัครงาน พร้อมข้อมูลนักเรียน
$query = "
    SELECT a.application_id, a.work_id, a.status AS application_status, 
           ps.id_account, ps.name_student, ps.email_student, ps.number_student, ps.faculty_student
    FROM applications a
    LEFT JOIN profile_students ps ON a.id_account = ps.id_account
    WHERE a.work_id IS NOT NULL
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Applications</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../image/apple-touch-icon.png">
    <link rel="android-chrome" href="../image/android-chrome-192x192.png">
    <link rel="android-chrome" href="../image/android-chrome-512x512.png">
    <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="../image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../image/favicon-16x16.png">
    <link rel="manifest" href="../image/site.webmanifest">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
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

        /* Table */
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            overflow: hidden;
        }

        thead {
            background: #ff6f61;
            color: white;
        }

        thead th,
        tbody td {
            padding: 15px;
            text-align: center;
        }

        tbody tr:hover {
            background: #ffdad3;
        }

        /* ปรับแต่ง dropdown */
        select {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background: white;
            cursor: pointer;
        }

        /* ปรับแต่งปุ่ม Update */
        .actions-container {
            display: flex;
            align-items: center;
            gap: 10px;
            /* ระยะห่างระหว่างปุ่ม */
            justify-content: center;
            /* จัดให้อยู่กึ่งกลาง */
        }

        .status-form {
            display: flex;
            align-items: center;
            gap: 5px;
            /* ระยะห่างระหว่าง select และปุ่ม update */
        }

        .form-select {
            width: auto;
            /* ให้ขนาดพอดีกับเนื้อหา */
            display: inline-block;
        }

        .btn-view {
            background: #343a40;
            color: white;
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-view:hover {
            background: #23272b;
        }

        .btn-update {
            background: #28a745;
            color: white;
            padding: 6px 12px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        .btn-update:hover {
            background: #218838;
        }


        /* ปรับแต่ง alert */
        .alert {
            padding: 10px;
            background: #f1c40f;
            color: white;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 15px;
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

    <!-- Container -->
    <div class="container mt-4">
        <?php if (isset($_SESSION['Messager'])): ?>
            <div class="alert alert-info">
                <?= $_SESSION['Messager']; ?>
            </div>
            <?php unset($_SESSION['Messager']); ?>
        <?php endif; ?>

        <h1>Form Doc Approval Request</h1>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Student Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Faculty</th>
                    <th>Application Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php $count = 1; ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $count++; ?></td>
                            <td><?= htmlspecialchars($row['name_student']); ?></td>
                            <td><?= htmlspecialchars($row['email_student']); ?></td>
                            <td><?= htmlspecialchars($row['number_student']); ?></td>
                            <td><?= htmlspecialchars($row['faculty_student']); ?></td>
                            <td><?= ucfirst($row['application_status']); ?></td>
                            <td>
                                <div class="actions-container">
                                    <form method="post" action="update_application_status.php" class="status-form">
                                        <input type="hidden" name="application_id" value="<?= $row['application_id']; ?>">
                                        <select name="status" class="form-select">
                                            <option value="pending" <?= $row['application_status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="approved" <?= $row['application_status'] == 'approved' ? 'selected' : ''; ?>>Approved</option>
                                            <option value="rejected" <?= $row['application_status'] == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                        </select>
                                        <a href="admin_view_skill.php?id=<?= $row['id_account']; ?>" class="btn-view">View</a>
                                        <button type="submit" class="btn btn-update">Update</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No applications found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>