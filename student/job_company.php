<?php
session_start();
include '../config/config.php';

// ตรวจสอบสิทธิ์ผู้ใช้งาน (ให้เฉพาะ role student เข้าถึงได้)
if (!isset($_SESSION['role_account']) || $_SESSION['role_account'] !== 'student') {
    $_SESSION['Messager'] = 'You are not authorized!';
    header("location: {$base_url}../index.php");
    exit();
}

// ดึงข้อมูลจากฐานข้อมูล
$query = "
    SELECT cd.*, 
           (SELECT COUNT(*) FROM applications WHERE applications.work_id = cd.work_id) AS number_of_applicants, 
           (SELECT COUNT(*) FROM applications WHERE applications.work_id = cd.work_id AND applications.status = 'approved') AS passed_applicants,
           COALESCE(a.status, 'Not Applied') AS application_status 
    FROM company_details cd
    LEFT JOIN applications a 
    ON cd.work_id = a.work_id AND a.id_account = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['id_account']);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Jobs</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../image/apple-touch-icon.png">
    <link rel="android-chrome"  href="../image/android-chrome-192x192.png">
    <link rel="android-chrome"  href="../image/android-chrome-512x512.png">
    <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="../image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../image/favicon-16x16.png">
    <link rel="manifest" href="../image/site.webmanifest">
    <link rel="stylesheet" href="../css/style_job_company.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
    </style>
</head>

<body>
   <!-- Sidebar -->
   <div class="sidebar">
        <a class="navbar-brand" href="../student/home_student.php">
            <img src="../image/logo-pxu.png" alt="SDIC Logo" width="40" height="40"> SDIC
        </a>
        <h4>Student Panel</h4>
        <span class="navbar-text ms-3">
            Welcome, <?php echo $_SESSION['username_account']; ?>
        </span>
        <ul>
            <li> <a class="nav-link" href="<?php echo $base_url; ?>/student/home_student.php">Home</a></li>
            <li><a class="nav-link" href="<?php echo $base_url; ?>/student/profile_student.php">Profile</a></li>
            <li><a class="nav-link" href="<?php echo $base_url; ?>/student/register_form_skill.php">International Cooperative Education</a></li>
            <li><a class="nav-link" href="<?php echo $base_url; ?>/student/student_view_advisor.php">Advisor</a></li>
            <li><a class="nav-link" href="<?php echo $base_url; ?>/student/job_company.php">Company</a></li>
            <li><a class="nav-link" href="<?php echo $base_url; ?>/student/daily.php">Daily</a></li>
            <li>
                <a class="nav-link" href="<?php echo $base_url; ?>/realtime_chat/choose_receiver.php?receiver_id=<?php echo isset($_GET['receiver_id']) ? htmlspecialchars($_GET['receiver_id']) : 0; ?>">
                    Chat
                </a>
            </li>
            <li> <a class="nav-link" href="<?php echo $base_url; ?>/student/student_reset_password.php">Reset Password</a></li>
            <li><a href=" <?php echo $base_url; ?>../index.php" onclick="return confirm('Are you sure you want to log out?');"> Logout</a></li>
        </ul>
    </div>

    <div class="container mt-4">
        <h1 class="text-center">Available Jobs</h1>
        <table class="table table-bordered">
            <thead>
                <tr class="tt">
                    <th>No</th>
                    <th>Job Position</th>
                    <th>Company</th>
                    <th>Number Accepted (People)</th>
                    <th>Number of Applicants (People)</th>
                    <th>Passed Applicants (People)</th>
                    <th>Job Description</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php $count = 1; ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $count++; ?></td>
                            <td><?= htmlspecialchars($row['position_accepted']); ?></td>
                            <td><?= htmlspecialchars($row['name_company']); ?></td>
                            <td><?= intval($row['number_received']); ?></td>
                            <td><?= intval($row['number_of_applicants']); ?></td>
                            <td><?= intval($row['passed_applicants']); ?></td>
                            <td>
                                <a href="../student/job_detail.php?work_id=<?= htmlspecialchars($row['work_id']); ?>">View Details</a>
                            </td>
                            <td>
                                <?= htmlspecialchars($row['application_status']); ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No available jobs.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>