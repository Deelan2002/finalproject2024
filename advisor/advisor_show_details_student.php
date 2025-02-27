<?php
session_start();
include '../config/config.php';

// ตรวจสอบการเข้าสู่ระบบและบทบาทผู้ใช้
if (empty($_SESSION['id_account']) || $_SESSION['role_account'] !== 'advisor') {
    $_SESSION['Messager'] = 'You are not authorized!';
    header("Location: {$base_url}../index.php");
    exit();
}





// ตรวจสอบว่ามีการส่ง id ผ่าน URL หรือไม่
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Invalid Request</title>
        <style>
            body {
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                background-color: #f4f4f4;
                font-family: Arial, sans-serif;
            }
            .error-container {
                text-align: center;
                background: #fff;
                padding: 30px 50px;
                border: 1px solid #ccc;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
            }
            .error-container h1 {
                color: #e74c3c;
                font-size: 36px;
                margin-bottom: 10px;
            }
            .error-container p {
                font-size: 18px;
                color: #555;
                margin-bottom: 20px;
            }
            .error-container a {
                text-decoration: none;
                color: #3498db;
                font-size: 16px;
                padding: 10px 20px;
                border: 1px solid #3498db;
                border-radius: 4px;
                transition: background-color 0.3s, color 0.3s;
            }
            .error-container a:hover {
                background-color: #3498db;
                color: #fff;
            }
        </style>
    </head>
    <body>
        <div class="error-container">
            <h1>No student information</h1>
            <p>The student has not yet filled in the information.</p>
            <a href="../admin/admin_show_student.php">Back to Student List</a>
        </div>
    </body>
    </html>
    ';
    exit();
}

$id_account = intval($_GET['id']); // ป้องกัน SQL Injection ด้วยการแปลงค่าเป็น integer

// ดึงข้อมูลนักเรียนที่ตรงกับ id_account
$query_student = mysqli_query($conn, "
    SELECT a.*, ps.* 
    FROM accounts a
    LEFT JOIN profile_students ps ON a.id_account = ps.id_account
    WHERE a.id_account = $id_account
");

if (!$query_student) {
    die("Error: " . mysqli_error($conn));
}


// ตรวจสอบว่าพบข้อมูลหรือไม่
$student = mysqli_fetch_assoc($query_student);
if (!$student) {
    die("Student not found.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../image/apple-touch-icon.png">
    <link rel="android-chrome" href="../image/android-chrome-192x192.png">
    <link rel="android-chrome" href="../image/android-chrome-512x512.png">
    <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="../image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../image/favicon-16x16.png">
    <link rel="manifest" href="../image/site.webmanifest">
    <link rel="stylesheet" href="../css/admin_show_details_student.css">
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
            margin-left: 280px;
            /* ขยับไปทางขวามากขึ้น (จาก 240px เป็น 280px) */
            margin-top: 20px;
            width: calc(100% - 260px);
            max-width: 1300px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }
    </style>
</head>

<body>

    <div class="wrapper">
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


        <div class="container">
            <h1>Student Details</h1>
            <div class="card">
                <div class="card-content">
                    <!-- หากมีรูปภาพของนักเรียนให้แสดงรูปภาพจากฐานข้อมูล -->
                    <img src="../uploads/<?php echo !empty($student['profile_image_student']) ? htmlspecialchars($student['profile_image_student']) : 'default.png'; ?>" alt="Student Image" class="icon-image">
                    <div class="student-info">
                        <div>
                            <label>Student ID</label>
                            <span><?php echo !empty($student['id_student']) ? htmlspecialchars($student['id_student']) : 'N/A'; ?></span>
                        </div>
                        <div>
                            <label>Name</label>
                            <span><?php echo !empty($student['name_student']) ? htmlspecialchars($student['name_student']) : htmlspecialchars($student['username_account']); ?></span>
                        </div>
                        <div>
                            <label>Nickname</label>
                            <span><?php echo !empty($student['nickname_student']) ? htmlspecialchars($student['nickname_student']) : 'N/A'; ?></span>
                        </div>
                        <div>
                            <label>Age</label>
                            <span><?php echo !empty($student['age_student']) ? htmlspecialchars($student['age_student']) : 'N/A'; ?></span>
                        </div>
                        <div>
                            <label>Birthday</label>
                            <span><?php echo !empty($student['birthday_student']) ? htmlspecialchars($student['birthday_student']) : 'N/A'; ?></span>
                        </div>
                        <div>
                            <label>Gender</label>
                            <span><?php echo !empty($student['gender_student']) ? htmlspecialchars($student['gender_student']) : 'N/A'; ?></span>
                        </div>
                        <div>
                            <label>Email</label>
                            <span><?php echo htmlspecialchars($student['email_account']); ?></span>
                        </div>
                        <div>
                            <label>Phone</label>
                            <span><?php echo !empty($student['number_student']) ? htmlspecialchars($student['number_student']) : 'N/A'; ?></span>
                        </div>
                        <div>
                            <label>University</label>
                            <span><?php echo !empty($student['university_student']) ? htmlspecialchars($student['university_student']) : 'N/A'; ?></span>
                        </div>
                        <div>
                            <label>Faculty</label>
                            <span><?php echo !empty($student['faculty_student']) ? htmlspecialchars($student['faculty_student']) : 'N/A'; ?></span>
                        </div>
                        <div>
                            <label>Department</label>
                            <span><?php echo !empty($student['department_student']) ? htmlspecialchars($student['department_student']) : 'N/A'; ?></span>
                        </div>
                        <div>
                            <label>Country</label>
                            <span><?php echo !empty($student['country_student']) ? htmlspecialchars($student['country_student']) : 'N/A'; ?></span>
                        </div>
                        <div>
                            <label>Address</label>
                            <span><?php echo !empty($student['address_student']) ? htmlspecialchars($student['address_student']) : 'N/A'; ?></span>
                        </div>
                        <div>
                            <label>Passport Image</label>
                            <span><img src="../uploads/<?php echo !empty($student['passport_image_student']) ? htmlspecialchars($student['passport_image_student']) : 'default.png'; ?>" alt="Passport Image" class="student-image"></span>
                        </div>
                        <div>
                            <label>Visa Image</label>
                            <span><img src="../uploads/<?php echo !empty($student['visa_image_student']) ? htmlspecialchars($student['visa_image_student']) : 'default.png'; ?>" alt="Visa Image" class="student-image"></span>
                        </div>
                        <div>
                            <label>eVisa Image</label>
                            <span><img src="../uploads/<?php echo !empty($student['evisa_image_student']) ? htmlspecialchars($student['evisa_image_student']) : 'default.png'; ?>" alt="eVisa Image" class="student-image"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>

</html>