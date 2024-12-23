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
$id_account = $_SESSION['id_account'];  // หรือ $_SESSION['WP' . 'id_account']

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
    <title>Profile Student</title>
    <link rel="stylesheet" href="../css/style_profile_student.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
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
        <div class="form-header">
            <h2>Profile Student</h2>
        </div>

        <form>

            <!-- รูปโปรไฟล์ -->
            <div class="mb-3 text-center">
            <?php if (!empty($username_account['profile_image_student'])): ?>
                        <img src="../uploads/<?php echo htmlspecialchars($username_account['profile_image_student']); ?>" alt="profile student" class="rounded" width="150" height="150">
                    <?php else: ?>
                        <img src="../uploads/default.jpg" alt="Default Profile" class="rounded-circle" width="150" height="150">
                    <?php endif; ?>
            </div>

            <div class="grid-container">
                <table class="table">

                    <div class="form-group">
                        <label class="datashow">Student ID</label>
                        <p><?php echo htmlspecialchars($username_account['student_id']); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="datashow">Student Name</label>
                        <p><?php echo htmlspecialchars($username_account['name_student']); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="datashow">Nickname</label>
                        <p><?php echo htmlspecialchars($username_account['nickname_student']); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="datashow">Age</label>
                        <p><?php echo htmlspecialchars($username_account['age_student']); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="datashow">Birthday</label>
                        <p><?php echo htmlspecialchars($username_account['birthday_student']); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="datashow">Gender</label>
                        <p><?php echo htmlspecialchars($username_account['gender_student']); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="datashow">Email</label>
                        <p><?php echo htmlspecialchars($username_account['email_student']); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="datashow">Phone number</label>
                        <p><?php echo htmlspecialchars($username_account['number_student']); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="datashow">University</label>
                        <p><?php echo htmlspecialchars($username_account['university_student']); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="datashow">Faculty</label>
                        <p><?php echo htmlspecialchars($username_account['faculty_student']); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="datashow">Department</label>
                        <p><?php echo htmlspecialchars($username_account['department_student']); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="datashow">Country</label>
                        <p><?php echo htmlspecialchars($username_account['country_student']); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="datashow">Address</label>
                        <p><?php echo htmlspecialchars($username_account['address_student']); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="datashow">Passport</label>
                        <p>
                            <?php if (!empty($username_account['passport_image_student'])): ?>
                                <?php $path = "../uploads/" . htmlspecialchars($username_account['passport_image_student']); ?>
                                <img src="<?php echo $path; ?>" alt="Passport Image" width="150" class="mb-2" onerror="this.onerror=null; this.src='../uploads/default.png';">
                            <?php else: ?>
                        <p>No Passport Image Available</p>
                    <?php endif; ?>
                    </p>
                    </div>
                    <div class="form-group">
                        <label class="datashow">Visa</label>
                        <p>
                            <?php if (!empty($username_account['visa_image_student'])): ?>
                                <?php $path = "../uploads/" . htmlspecialchars($username_account['visa_image_student']); ?>
                                <img src="<?php echo $path; ?>" alt="Visa Image" width="150" class="mb-2" onerror="this.onerror=null; this.src='../uploads/default.png';">
                            <?php else: ?>
                        <p>No Visa Image Available</p>
                    <?php endif; ?>
                    </p>
                    </div>
                    <div class="form-group">
                        <label class="datashow">E-Visa</label>
                        <p>
                            <?php if (!empty($username_account['evisa_image_student'])): ?>
                                <?php $path = "../uploads/" . htmlspecialchars($username_account['evisa_image_student']); ?>
                                <img src="<?php echo $path; ?>" alt="E-Visa Image" width="150" class="mb-2" onerror="this.onerror=null; this.src='../uploads/default.png';">
                            <?php else: ?>
                        <p>No E-Visa Image Available</p>
                    <?php endif; ?>
                    </p>
                    </div>

                </table>

            </div>


        </form>

    </div>
    <a href="<?php echo $base_url; ?>../index.php" class="btn btn-danger">Logout</a>

    <div class="add-profile-btn">
        <a href="../student/form_profile_student.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> <span>+</span>
        </a>
    </div>

</body>

</html>