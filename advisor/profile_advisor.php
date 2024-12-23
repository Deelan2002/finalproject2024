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
    <link rel="stylesheet" href="../css/style_profile_advisor.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="../advisor/home_advisor.php">CIWE</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <?php include('../navbar/navbar_advisor.php') ?>
            </div>
        </div>
    </nav>


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
    <a href="<?php echo $base_url; ?>../index.php" class="btn btn-danger">Logout</a>

    <div class="add-profile-btn">
        <a href="../advisor/form_profile_advisor.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> <span>+</span>
        </a>
    </div>

</body>

</html>