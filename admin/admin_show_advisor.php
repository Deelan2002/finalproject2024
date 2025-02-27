<?php
session_start();
include '../config/config.php';

// ตรวจสอบการเข้าสู่ระบบและบทบาทผู้ใช้
if (empty($_SESSION['id_account']) || $_SESSION['role_account'] !== 'admin') {
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

// ดึงข้อมูลที่มีบทบาทเป็น advisor จากฐานข้อมูล
$query_advisors = mysqli_query($conn, "
    SELECT a.*, pa.* 
    FROM accounts a
    LEFT JOIN profile_advisor pa ON a.id_account = pa.id_account
    WHERE a.role_account = 'advisor' 
    AND (a.username_account LIKE '%$search_query%' OR pa.name_advisor LIKE '%$search_query%')
");

if (!$query_advisors) {
    die("Error: " . mysqli_error($conn));  // หากมีข้อผิดพลาดในการดึงข้อมูล
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Show Advisors</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../image/apple-touch-icon.png">
    <link rel="android-chrome" href="../image/android-chrome-192x192.png">
    <link rel="android-chrome" href="../image/android-chrome-512x512.png">
    <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="../image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../image/favicon-16x16.png">
    <link rel="manifest" href="../image/site.webmanifest">
    <link rel="stylesheet" href="../css/style_admin_show_advisor.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
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

    <div class="container">
        <h1>Advisor List</h1>

        <!-- Search Form -->
        <div class="search-container">
            <input type="text" placeholder="Search by name or ID">
            <button type="submit">
                <i class="fa fa-search"></i>
            </button>
        </div>



        <?php
        if (mysqli_num_rows($query_advisors) > 0) {
            while ($advisor = mysqli_fetch_assoc($query_advisors)) {
        ?>
                <div class="card">
                    <img src="../uploads/<?php echo !empty($advisor['profile_advisor']) ? htmlspecialchars($advisor['profile_advisor']) : 'default.png'; ?>" alt="Advisor Image">
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
                    </div>
                    <a href="../admin/admin_show_details_advisor.php?id=<?php echo $advisor['id_account']; ?>" class="action-button">Details</a>
                </div>
        <?php
            }
        } else {
            echo "<p class='no-advisors'>No advisors found.</p>";
        }
        ?>
    </div>



</body>

</html>