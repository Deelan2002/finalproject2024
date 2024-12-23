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
    <link rel="stylesheet" href="../css/style_form_profile_student.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark ">
        <div class="container-fluid">
            <a class="navbar-brand" href="../student/home_student.php">CIWE</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <?php include('../navbar/navbar_student.php') ?>
            </div>
        </div>
    </nav>

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
    <a href="<?php echo $base_url; ?>../index.php" class="btn btn-danger">Logout</a>

</body>

</html>