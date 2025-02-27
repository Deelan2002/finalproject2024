<?php
session_start();

include '../config/config.php';

// ตรวจสอบสิทธิ์ผู้ใช้งาน
if (!isset($_SESSION['role_account']) || $_SESSION['role_account'] !== 'student') {
    $_SESSION['Messager'] = 'You are not authorized!';
    header("location: {$base_url}../index.php");
    exit();
}
// ดึง id_account จาก session
$id_account = $_SESSION['id_account'];

// ดึงข้อมูลจากตาราง accounts และ profile_students โดยเชื่อมโยงกับ id_account
$query = mysqli_query($conn, "
    SELECT a.*, ps.* 
    FROM accounts a 
    LEFT JOIN profile_students ps ON a.id_account = ps.id_account 
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
    <title>Form Profile Student</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../image/apple-touch-icon.png">
    <link rel="android-chrome"  href="../image/android-chrome-192x192.png">
    <link rel="android-chrome"  href="../image/android-chrome-512x512.png">
    <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="../image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../image/favicon-16x16.png">
    <link rel="manifest" href="../image/site.webmanifest">
    <link rel="stylesheet" href="../css/style_form_profile_student.css">
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


    <div class="form-container">
        <div class="container">
            <h1 class="text-center">Profile Student</h1>
            <form method="POST" action="../student/update_profile_student.php" enctype="multipart/form-data">
                <!-- รูปโปรไฟล์ -->
                <div class="mb-3 text-center">
                    <?php if (!empty($username_account['profile_image_student'])): ?>
                        <img src="../uploads/<?php echo htmlspecialchars($username_account['profile_image_student']); ?>" alt="profile student" class="rounded-circle" width="150" height="150">
                    <?php else: ?>
                        <img src="../uploads/default.jpg" alt="Default Profile" class="rounded-circle" width="150" height="150">
                    <?php endif; ?>
                    <div class="mb-3 text-center">
                        <label for="profile_image" class="form-label">Upload New Profile Picture</label>
                        <input type="file" id="profile_image" name="profile_image" class="form-control">
                    </div>
                </div>

                <!-- ช่องกรอกข้อมูล -->
                <div class="mb-3">
                    <label for="student_id">Student ID</label>
                    <input type="text" id="student_id" name="student_id" class="form-control" value="<?php echo htmlspecialchars($username_account['student_id']); ?>">
                </div>
                <div class="mb-3">
                    <label for="name_student">Student Name</label>
                    <input type="text" id="name_student" name="name_student" class="form-control" value="<?php echo htmlspecialchars($username_account['name_student']); ?>">
                </div>
                <div class="mb-3">
                    <label for="nickname_student">Nickname</label>
                    <input type="text" id="nickname_student" name="nickname_student" class="form-control" value="<?php echo htmlspecialchars($username_account['nickname_student']); ?>">
                </div>
                <div class="mb-3">
                    <label for="age_student">Age</label>
                    <input type="number" id="age_student" name="age_student" class="form-control" value="<?php echo htmlspecialchars($username_account['age_student']); ?>">
                </div>
                <div class="mb-3">
                    <label for="birthday_student">Birthday</label>
                    <input type="date" id="birthday_student" name="birthday_student" class="form-control" value="<?php echo htmlspecialchars($username_account['birthday_student']); ?>">
                </div>
                <div class="mb-3">
                    <label for="gender_student">Gender</label>
                    <select id="gender_student" name="gender_student" class="form-control">
                        <option value="Male" <?php echo ($username_account['gender_student'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($username_account['gender_student'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="email_student">Email</label>
                    <input type="email" id="email_student" name="email_student" class="form-control" value="<?php echo htmlspecialchars($username_account['email_student']); ?>">
                </div>
                <div class="mb-3">
                    <label for="number_student">Phone Number</label>
                    <input type="text" id="number_student" name="number_student" class="form-control" value="<?php echo htmlspecialchars($username_account['number_student']); ?>">
                </div>
                <div class="mb-3">
                    <label for="university_student">University</label>
                    <input type="text" id="university_student" name="university_student" class="form-control" value="<?php echo htmlspecialchars($username_account['university_student']); ?>">
                </div>
                <div class="mb-3">
                    <label for="faculty_student">Faculty</label>
                    <input type="text" id="faculty_student" name="faculty_student" class="form-control" value="<?php echo htmlspecialchars($username_account['faculty_student']); ?>">
                </div>
                <div class="mb-3">
                    <label for="department_student">Department</label>
                    <input type="text" id="department_student" name="department_student" class="form-control" value="<?php echo htmlspecialchars($username_account['department_student']); ?>">
                </div>
                <div class="mb-3">
                    <label for="country_student">Country</label>
                    <input type="text" id="country_student" name="country_student" class="form-control" value="<?php echo htmlspecialchars($username_account['country_student']); ?>">
                </div>
                <div class="mb-3">
                    <label for="address_student">Address</label>
                    <textarea id="address_student" name="address_student" class="form-control"><?php echo htmlspecialchars($username_account['address_student']); ?></textarea>
                </div>

                <!-- ช่องกรอกข้อมูลสำหรับ Passport -->
                <div class="mb-3">
                    <label for="passport_image_student">Passport</label>
                    <div>
                        <?php if (!empty($username_account['passport_image_student'])): ?>
                            <img src="../uploads/<?php echo htmlspecialchars($username_account['passport_image_student']); ?>" alt="Passport" width="150" height="150" class="mb-2">
                        <?php endif; ?>
                        <input type="file" id="passport_image" name="passport_image" class="form-control">
                    </div>
                </div>

                <!-- ช่องกรอกข้อมูลสำหรับ Visa -->
                <div class="mb-3">
                    <label for="visa_image_student">Visa</label>
                    <div>
                        <?php if (!empty($username_account['visa_image_student'])): ?>
                            <img src="../uploads/<?php echo htmlspecialchars($username_account['visa_image_student']); ?>" alt="Visa" width="150" height="150" class="mb-2">
                        <?php endif; ?>
                        <input type="file" id="visa_image" name="visa_image" class="form-control">
                    </div>
                </div>

                <!-- ช่องกรอกข้อมูลสำหรับ E-Visa -->
                <div class="mb-3">
                    <label for="evisa_image_student">E-Visa</label>
                    <div>
                        <?php if (!empty($username_account['evisa_image_student'])): ?>
                            <img src="../uploads/<?php echo htmlspecialchars($username_account['evisa_image_student']); ?>" alt="E-Visa" width="150" height="150" class="mb-2">
                        <?php endif; ?>
                        <input type="file" id="evisa_image" name="evisa_image" class="form-control">
                    </div>
                </div>



                <button type="submit" class="btn btn-success">Save</button>
                <a href="../student/profile_student.php" class="btn btn-danger">Cancel</a>
            </form>

        </div>
    </div>
    

</body>

</html>