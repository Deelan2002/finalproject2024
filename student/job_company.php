<?php
session_start();
include '../config/config.php';

// ตรวจสอบสิทธิ์ผู้ใช้งาน (ให้เฉพาะ role student สามารถเข้าถึงได้)
if (!isset($_SESSION['role_account']) || $_SESSION['role_account'] !== 'student') {
    $_SESSION['Messager'] = 'You are not authorized!';
    header("location: {$base_url}../index.php");
    exit();
}

// ดึงข้อมูลจากฐานข้อมูล
$query = "SELECT * FROM company_details";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Jobs</title>
    <link rel="stylesheet" href="../css/style_job_company.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    

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

    <div class="container mt-4">
        <h1 class="text-center">Available Jobs</h1>
        <table class="table table-bordered">
            <thead>
                <tr class="tt">
                    <th>No</th>
                    <th>Job position </th>
                    <th>Company </th>
                    <th>Number accepted (people)</th>
                    <th>Number of applicants (people) </th>
                    <th>Number of applicants (people)</th>
                    <th>Job description</th>
                    <th>status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php $count = 1;
                    while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $count++; ?></td>
                            <td><?= htmlspecialchars($row['position_accepted']); ?></td>
                            <td><?= htmlspecialchars($row['name_company']); ?></td>
                            <td><?= intval($row['number_received']); ?></td>
                            <td>0</td> <!-- แสดงจำนวนผู้สมัครจากฟิลด์ที่เกี่ยวข้อง -->
                            <td>0</td> <!-- แสดงจำนวนผ่านคัดเลือกจากฟิลด์ที่เกี่ยวข้อง -->
                            <td><a href="../student/job_detail.php?work_id=<?= htmlspecialchars($row['work_id']); ?>">คลิกเพื่อดู</a></td>
                            <td class="<?= ($row['allow_apply'] ?? 0) ? 'allowed' : 'not-allowed'; ?>">
                                <?= ($row['allow_apply'] ?? 0) ? '<a href="#">สมัคร</a>' : 'ไม่อนุญาติ'; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No available jobs.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>