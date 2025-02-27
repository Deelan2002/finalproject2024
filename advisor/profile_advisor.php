<?php
session_start();
include '../config/config.php';

// ตรวจสอบสิทธิ์ผู้ใช้งาน
if (!isset($_SESSION['role_account']) || $_SESSION['role_account'] !== 'advisor') {
    $_SESSION['Messager'] = 'You are not authorized!';
    header("location: {$base_url}../index.php");
    exit();
}

// ดึง id_account จาก session
$id_account = $_SESSION['id_account'];  // หรือ $_SESSION['WP' . 'id_account']

// ดึงข้อมูลจากตาราง accounts และ profile_advisor โดยเชื่อมโยงกับ id_account
$query = mysqli_query($conn, "
    SELECT a.*, pa.* 
    FROM accounts a 
    LEFT JOIN profile_advisor pa ON a.id_account = pa.id_account 
    WHERE a.id_account = '{$id_account}'");

// ตรวจสอบผลลัพธ์
if (!$query) {
    die("Error: " . mysqli_error($conn));  // หากมีข้อผิดพลาดในการดึงข้อมูล
}

$username_account = mysqli_fetch_assoc($query);
if (!$username_account) {
    die("No data found for this account."); // ถ้าไม่มีข้อมูล
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Student</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../image/apple-touch-icon.png">
    <link rel="android-chrome" href="../image/android-chrome-192x192.png">
    <link rel="android-chrome" href="../image/android-chrome-512x512.png">
    <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="../image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../image/favicon-16x16.png">
    <link rel="manifest" href="../image/site.webmanifest">
    <link rel="stylesheet" href="../css/style_profile_advisor.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;600&display=swap" rel="stylesheet">
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



    <div class="form-container">
        <div class="form-header">
            <h2>Profile Advisor</h2>
        </div>

        <form>

            <!-- รูปโปรไฟล์ -->
            <div class="mb-3 text-center">
                <?php if (!empty($username_account['profile_advisor'])): ?>
                    <img src="../uploads/<?php echo htmlspecialchars($username_account['profile_advisor']); ?>" alt="profile advisor" class="rounded" width="150" height="150">
                <?php else: ?>
                    <img src="../uploads/default.jpg" alt="Default Profile" class="rounded-circle" width="150" height="150">
                <?php endif; ?>
            </div>

            <div class="grid-container">
                <table class="table">

                    <div class="form-group">
                        <label class="datashow">Name</label>
                        <p><?php echo htmlspecialchars($username_account['name_advisor']); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="datashow">Position</label>
                        <p><?php echo htmlspecialchars($username_account['position_advisor']); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="datashow">E-mail</label>
                        <p><?php echo htmlspecialchars($username_account['email_advisor']); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="datashow">Number</label>
                        <p><?php echo htmlspecialchars($username_account['number_advisor']); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="datashow">University</label>
                        <p><?php echo htmlspecialchars($username_account['university_advisor']); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="datashow">Faculty</label>
                        <p><?php echo htmlspecialchars($username_account['faculty_advisor']); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="datashow">Department</label>
                        <p><?php echo htmlspecialchars($username_account['department_advisor']); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="datashow">Country</label>
                        <p><?php echo htmlspecialchars($username_account['country_advisor']); ?></p>
                    </div>

                </table>

            </div>


        </form>

    </div>


    <div class="add-profile-btn">
        <a href="../advisor/form_profile_advisor.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> <span>+</span>
        </a>
    </div>

</body>

</html>