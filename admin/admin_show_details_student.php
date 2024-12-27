<?php
session_start();
include '../config/config.php';

// ตรวจสอบการเข้าสู่ระบบและบทบาทผู้ใช้
if (empty($_SESSION['id_account']) || $_SESSION['role_account'] !== 'admin') {
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
    <link rel="stylesheet" href="../css/admin_show_details_student.css">
</head>

<body>

    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>Admin Dashboard</h2>
            <ul>
                <li><a href="../admin/home_admin.php">Home Admin</a></li>
                <li><a href="../admin/admin_create_account.php">Creeate New Account</a></li>
                <li><a href="../admin/admin_manage_accounts.php">Manage Account</a></li>
                <li><a href="../admin/admin_show_student.php">Student List</a></li>
                <li><a href="../admin/admin_show_advisor.php">Advisor List</a></li>
                <li><a href="#">Form Doc Approval Request</a></li>
                <li><a href="../logout.php">Logout</a></li>
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