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
    <title>Admin - Show Students</title>
    <link rel="stylesheet" href="../css/style_admin_show_student.css">
    <!-- เพิ่ม FontAwesome สำหรับไอคอนค้นหา -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

</head>

<body>

    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>Admin Dashboard</h2>
            <ul>
                <li><a href="../admin/home_admin.php">Home Admin</a></li>
                <li><a href="../admin/admin_create_account.php">Create New Account</a></li>
                <li><a href="../admin/admin_manage_accounts.php">Manage Account</a></li>
                <li><a href="../admin/admin_show_student.php">Student List</a></li>
                <li><a href="#">Advisor List</a></li>
                <li><a href="#">Form Doc Approval Request</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </div>

        <div class="container">
            <h1>Student List</h1>

            <div class="search-container">
                <form method="GET" action="admin_show_student.php">
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
                        <a href="../admin/admin_show_details_student.php?id=<?php echo $student['id_account']; ?>" class="action-button">Details</a>
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