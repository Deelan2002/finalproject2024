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
            <h1 class="text-center">Profile Advisor</h1>
            <form method="POST" action="../advisor/update_profile_advisor.php" enctype="multipart/form-data">
                <!-- รูปโปรไฟล์ -->
                <div class="mb-3 text-center">
                    <?php if (!empty($username_account['profile_advisor'])): ?>
                        <img src="../uploads/<?php echo htmlspecialchars($username_account['profile_advisor']); ?>" alt="profile advisor" class="rounded-circle" width="150" height="150">
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
                    <label for="name_advisor">Name</label>
                    <input type="text" id="name_advisor" name="name_advisor" class="form-control" value="<?php echo htmlspecialchars($username_account['name_advisor']); ?>">
                </div>
                <div class="mb-3">
                    <label for="position_advisor">Position</label>
                    <input type="text" id="position_advisor" name="position_advisor" class="form-control" value="<?php echo htmlspecialchars($username_account['position_advisor']); ?>">
                </div>
                <div class="mb-3">
                    <label for="email_advisor">E-mail</label>
                    <input type="text" id="email_advisor" name="email_advisor" class="form-control" value="<?php echo htmlspecialchars($username_account['email_advisor']); ?>">
                </div>
                <div class="mb-3">
                    <label for="number_advisor">Number</label>
                    <input type="number" id="number_advisor" name="number_advisor" class="form-control" value="<?php echo htmlspecialchars($username_account['number_advisor']); ?>">
                </div> 
                <div class="mb-3">
                    <label for="university_advisor">Universityfaculty advisor</label>
                    <input type="text" id="university_advisor" name="university_advisor" class="form-control" value="<?php echo htmlspecialchars($username_account['university_advisor']); ?>">
                </div>
                <div class="mb-3">
                    <label for="faculty_advisor">Faculty</label>
                    <input type="text" id="faculty_advisor" name="faculty_advisor" class="form-control" value="<?php echo htmlspecialchars($username_account['faculty_advisor']); ?>">
                </div>
                <div class="mb-3">
                    <label for="department_advisor">Department</label>
                    <input type="text" id="department_advisor" name="department_advisor" class="form-control" value="<?php echo htmlspecialchars($username_account['department_advisor']); ?>">
                </div>
                <div class="mb-3">
                    <label for="country_advisor">Country</label>
                    <input type="text" id="faculty_student" name="country_advisor" class="form-control" value="<?php echo htmlspecialchars($username_account['country_advisor']); ?>">
                </div>
                
                <button type="submit" class="btn btn-success">Save</button>
                <a href="../advisor/profile_advisor.php" class="btn btn-danger">Cancel</a>
            </form>

        </div>
    </div>
    <a href="<?php echo $base_url; ?>../index.php" class="btn btn-danger">Logout</a>

</body>

</html>