<?php
session_start();
include '../config/config.php';

// ตรวจสอบการเข้าสู่ระบบและบทบาทผู้ใช้
if (empty($_SESSION['id_account']) || $_SESSION['role_account'] !== 'advisor') {
    $_SESSION['Messager'] = 'You are not authorized!';
    header("Location: {$base_url}../index.php");
    exit();
}

// ดึง id_account จาก session
$id_account = $_SESSION['id_account'];

// ตรวจสอบว่ามีการค้นหาหรือไม่
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
}

// ดึงข้อมูลนักเรียนทั้งหมดที่มีบทบาทเป็น 'student' จากฐานข้อมูล
$query_students = mysqli_query($conn, "
    SELECT a.*, ps.* 
    FROM accounts a
    LEFT JOIN profile_students ps ON a.id_account = ps.id_account
    WHERE a.role_account = 'student' 
    AND (a.username_account LIKE '%$search_query%' OR ps.name_student LIKE '%$search_query%')
");

if (!$query_students) {
    die("Error: " . mysqli_error($conn));  // หากมีข้อผิดพลาดในการดึงข้อมูล
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advisor Show Students</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../image/apple-touch-icon.png">
    <link rel="android-chrome" href="../image/android-chrome-192x192.png">
    <link rel="android-chrome" href="../image/android-chrome-512x512.png">
    <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="../image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../image/favicon-16x16.png">
    <link rel="manifest" href="../image/site.webmanifest">
    <!-- เพิ่ม FontAwesome สำหรับไอคอนค้นหา -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
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

        .container {
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
            margin-bottom: 30px;
        }

        .search-container {
            margin-bottom: 20px;
        }

        .search-container input {
            width: 80%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .search-container button {
            padding: 10px;
            border-radius: 5px;
            border: none;
            background-color: #6d0019;
            color: white;
            cursor: pointer;
        }

        .search-container button:hover {
            background-color: #a52a2a;
        }

        .card {
            display: flex;
            flex-direction: column;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border-radius: 8px;
            padding: 20px;
            text-align: left;
            transition: transform 0.3s ease-in-out;
        }

        .card:hover {
            transform: translateY(-10px);
        }

        .card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
        }

        .advisor-info div {
            margin-bottom: 10px;
        }

        .advisor-info label {
            font-weight: bold;
        }

        .advisor-info span {
            color: #555;
        }

        .action-button {
            display: inline-block;
            background-color:rgb(178, 4, 4);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            margin-top: 15px;
        }

        .action-button:hover {
            background-color:rgb(202, 12, 12);
            color: white;
        }

        .no-advisors {
            color: #999;
            font-size: 18px;
            margin-top: 20px;
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
             <li><a href="<?php echo $base_url; ?>../index.php" onclick="return confirm('Are you sure you want to log out?');"> Logout</a></li>
        </ul>
    </div>

    <div class="container">
        <h1>Student List</h1>

        <div class="search-container">
            <form method="GET" action="admin_show_student.php" style="width: 100%; display: flex;">
                <input type="text" name="search" placeholder="Search by name or ID" value="<?php echo htmlspecialchars($search_query); ?>" />
                <button type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>


        <?php
        if (mysqli_num_rows($query_students) > 0) {
            while ($student = mysqli_fetch_assoc($query_students)) {
        ?>
                <div class="card">
                    <img src="../uploads/<?php echo !empty($student['profile_image_student']) ? htmlspecialchars($student['profile_image_student']) : 'default.png'; ?>" alt="Student Image">
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
                            <label>Country</label>
                            <span><?php echo !empty($student['country_student']) ? htmlspecialchars($student['country_student']) : 'N/A'; ?></span>
                        </div>
                    </div>
                    <a href="../advisor/advisor_show_details_student.php?id=<?php echo $student['id_account']; ?>" class="action-button">Details</a>
                </div>
        <?php
            } // end while
        } else {
            echo "<p class='no-students'>No students found.</p>";
        }
        ?>
    </div>


</body>

</html>