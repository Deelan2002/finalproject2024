<?php
session_start();
include '../config/config.php';

// ตรวจสอบการเข้าสู่ระบบและบทบาทผู้ใช้
if (empty($_SESSION['id_account']) || $_SESSION['role_account'] !== 'student') {
    $_SESSION['Messager'] = 'You are not authorized!';
    header("Location: {$base_url}../index.php");
    exit();
}

// ดึง id_account จาก session
$id_account = $_SESSION['id_account'];

// รับค่า id_account จาก URL
if (isset($_GET['id'])) {
    $id_account_detail = $_GET['id'];
} else {
    $_SESSION['Messager'] = 'Advisor not found.';
    header("Location: student_show_advisor.php"); // หน้าแสดงรายการ advisor สำหรับ student
    exit();
}

// ดึงข้อมูลจากฐานข้อมูลสำหรับ Advisor
$query_advisor_details = mysqli_query($conn, "
    SELECT a.*, pa.* 
    FROM accounts a
    LEFT JOIN profile_advisor pa ON a.id_account = pa.id_account
    WHERE a.id_account = '$id_account_detail'
");

if (!$query_advisor_details) {
    die("Error: " . mysqli_error($conn));  // หากมีข้อผิดพลาดในการดึงข้อมูล
}

$advisor = mysqli_fetch_assoc($query_advisor_details);
if (!$advisor) {
    $_SESSION['Messager'] = 'Advisor not found.';
    header("Location: student_show_advisor.php"); // หน้าแสดงรายการ advisor สำหรับ student
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student - Advisor Details</title>
    <link rel="stylesheet" href="../css/style_student_view_advisor.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="../image/apple-touch-icon.png">
    <link rel="android-chrome" href="../image/android-chrome-192x192.png">
    <link rel="android-chrome" href="../image/android-chrome-512x512.png">
    <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="../image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../image/favicon-16x16.png">
    <link rel="manifest" href="../image/site.webmanifest">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: none;
            display: flex;
            justify-content: flex-start;
            align-items: flex-start;
            overflow-x: hidden;
            position: relative;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('../image/pxu1.jpeg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            filter: blur(3px);
            z-index: -1;
        }

        body::after {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.1);
            z-index: -1;
        }
        .container {
            margin-top: 30px;
            width: 90%;
            max-width: 900px;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
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

        .container h1 {
            color: #333;
            font-size: 36px;
            margin-bottom: 20px;
            text-transform: uppercase;
            font-weight: 600;
        }

        .card {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-bottom: 20px;
        }

        .advisor-info div {
            margin-bottom: 15px;
            text-align: left;
        }

        .advisor-info label {
            font-weight: 600;
            color: #333;
            display: inline-block;
            width: 150px;
        }

        .advisor-info span {
            color: #555;
            font-size: 16px;
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

    <div class="container">
        <h1>Advisor Details</h1>

        <div class="card">
            <img src="../uploads/<?php echo !empty($advisor['profile_advisor']) ? htmlspecialchars($advisor['profile_advisor']) : 'default.png'; ?>" alt="Advisor Image" class="icon-image">
            <div class="advisor-info">
                <div>
                    <label>Advisor ID</label>
                    <span><?php echo htmlspecialchars($advisor['id_account']); ?></span>
                </div>
                <div>
                    <label>Name</label>
                    <span><?php echo !empty($advisor['name_advisor']) ? htmlspecialchars($advisor['name_advisor']) : htmlspecialchars($advisor['username_account']); ?></span>
                </div>
                <div>
                    <label>Email</label>
                    <span><?php echo htmlspecialchars($advisor['email_account']); ?></span>
                </div>
                <div>
                    <label>Phone</label>
                    <span><?php echo !empty($advisor['number_advisor']) ? htmlspecialchars($advisor['number_advisor']) : 'N/A'; ?></span>
                </div>
                <div>
                    <label>University</label>
                    <span><?php echo !empty($advisor['university_advisor']) ? htmlspecialchars($advisor['university_advisor']) : 'N/A'; ?></span>
                </div>
                <div>
                    <label>Faculty</label>
                    <span><?php echo !empty($advisor['faculty_advisor']) ? htmlspecialchars($advisor['faculty_advisor']) : 'N/A'; ?></span>
                </div>
                <div>
                    <label>Department</label>
                    <span><?php echo !empty($advisor['department_advisor']) ? htmlspecialchars($advisor['department_advisor']) : 'N/A'; ?></span>
                </div>
            </div>
        </div>

    </div>

</body>

</html>