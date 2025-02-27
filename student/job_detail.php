<?php
session_start();
include '../config/config.php';

// ตรวจสอบสิทธิ์เฉพาะนักเรียน
if (!isset($_SESSION['role_account']) || $_SESSION['role_account'] !== 'student') {
    $_SESSION['Messager'] = 'You are not authorized!';
    header("location: {$base_url}../index.php");
    exit();
}

// รับค่า work_id
$work_id = $_GET['work_id'] ?? null;
if (!$work_id || !is_numeric($work_id)) {
    echo "Invalid job ID.";
    exit();
}

// ดึงข้อมูลรายละเอียดจาก company_details
$query = "SELECT * FROM company_details WHERE work_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $work_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "No details found for this job.";
    exit();
}
$job = $result->fetch_assoc();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Details</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../image/apple-touch-icon.png">
    <link rel="android-chrome" href="../image/android-chrome-192x192.png">
    <link rel="android-chrome" href="../image/android-chrome-512x512.png">
    <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="../image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../image/favicon-16x16.png">
    <link rel="manifest" href="../image/site.webmanifest">
    <link rel="stylesheet" href="../css/style_job_detail.css">
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
    <?php if (isset($_SESSION['Messager'])): ?>
        <div class="alert alert-info text-center">
            <?= htmlspecialchars($_SESSION['Messager']); ?>
            <?php unset($_SESSION['Messager']); ?>
        </div>
    <?php endif; ?>

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
    <div class="container mt-5">
        <div class="card">
            <div class="card-header text-center">
                <h2><?= htmlspecialchars($job['position_accepted'] ?? 'N/A'); ?></h2>
                <h4 class="mb-0"><?= htmlspecialchars($job['name_company'] ?? 'N/A'); ?></h4>
            </div>
            <div class="card-body">
                <p><strong>City:</strong> <?= htmlspecialchars($job['city_company'] ?? 'N/A'); ?></p>
                <p><strong>Country:</strong> <?= htmlspecialchars($job['country_company'] ?? 'N/A'); ?></p>
                <p><strong>Address:</strong> <?= htmlspecialchars($job['address_company'] ?? 'N/A'); ?></p>
                <p><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($job['email_company'] ?? ''); ?>"><?= htmlspecialchars($job['email_company'] ?? 'N/A'); ?></a></p>
                <p><strong>Phone:</strong> <a href="tel:<?= htmlspecialchars($job['number_company'] ?? ''); ?>"><?= htmlspecialchars($job['number_company'] ?? 'N/A'); ?></a></p>
                <p><strong>Website:</strong> <a href="<?= htmlspecialchars($job['website_company'] ?? '#'); ?>" target="_blank"><?= htmlspecialchars($job['website_company'] ?? 'N/A'); ?></a></p>
                <p><strong>Accommodation:</strong> <?= htmlspecialchars($job['accommodation'] ?? 'N/A'); ?></p>
                <p><strong>Shuttle Bus:</strong> <?= htmlspecialchars($job['shuttle_bus'] ?? 'N/A'); ?></p>
                <p><strong>Welfare:</strong> <?= htmlspecialchars($job['welfare'] ?? 'N/A'); ?></p>
                <div class="text-center mt-3">
                    <img src="../uploads/<?= htmlspecialchars($job['picture_FileName'] ?? 'default.jpg'); ?>" alt="Company Image" class="img-fluid">
                </div>
            </div>
            <div class="text-center mt-4">
                <?php if ($job['allow_apply']): ?>
                    <form action="../company/apply_job.php" method="post">
                        <input type="hidden" name="work_id" value="<?= htmlspecialchars($work_id); ?>">
                        <button type="submit" class="btnA btn-primary">Apply Now</button>
                    </form>
                <?php else: ?>
                    <p class="text-danger">Applications for this job are not allowed at this time.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>