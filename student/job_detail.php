<?php
session_start();
include '../config/config.php';

// ตรวจสอบสิทธิ์เฉพาะนักเรียน
if (!isset($_SESSION['role_account']) || $_SESSION['role_account'] !== 'student') {
    $_SESSION['Messager'] = 'You are not authorized!';
    header("location: {$base_url}../index.php");
    exit();
}

// รับค่า work_id
$work_id = $_GET['work_id'] ?? null;
if (!$work_id || !is_numeric($work_id)) {
    echo "Invalid job ID.";
    exit();
}

// ดึงข้อมูลรายละเอียดจาก company_details
$query = "SELECT * FROM company_details WHERE work_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $work_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "No details found for this job.";
    exit();
}
$job = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Details</title>
    <link rel="stylesheet" href="../css/style_job_detail.css">
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

    <div class="container mt-5">
        <div class="card">
            <div class="card-header text-center">
                <h2><?= htmlspecialchars($job['position_accepted'] ?? 'N/A'); ?></h2>
                <h4 class="mb-0"><?= htmlspecialchars($job['name_company'] ?? 'N/A'); ?></h4>
            </div>
            <div class="card-body">
                <p><strong>City:</strong> <?= htmlspecialchars($job['city_company'] ?? 'N/A'); ?></p>
                <p><strong>Country:</strong> <?= htmlspecialchars($job['country_company'] ?? 'N/A'); ?></p>
                <p><strong>Address:</strong> <?= htmlspecialchars($job['address_company'] ?? 'N/A'); ?></p>
                <p><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($job['email_company'] ?? ''); ?>"><?= htmlspecialchars($job['email_company'] ?? 'N/A'); ?></a></p>
                <p><strong>Phone:</strong> <a href="tel:<?= htmlspecialchars($job['number_company'] ?? ''); ?>"><?= htmlspecialchars($job['number_company'] ?? 'N/A'); ?></a></p>
                <p><strong>Website:</strong> <a href="<?= htmlspecialchars($job['website_company'] ?? '#'); ?>" target="_blank"><?= htmlspecialchars($job['website_company'] ?? 'N/A'); ?></a></p>
                <p><strong>Accommodation:</strong> <?= htmlspecialchars($job['accommodation'] ?? 'N/A'); ?></p>
                <p><strong>Shuttle Bus:</strong> <?= htmlspecialchars($job['shuttle_bus'] ?? 'N/A'); ?></p>
                <p><strong>Welfare:</strong> <?= htmlspecialchars($job['welfare'] ?? 'N/A'); ?></p>
                <div class="text-center mt-3">
                    <img src="../uploads/<?= htmlspecialchars($job['picture_FileName'] ?? 'default.jpg'); ?>" alt="Company Image" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</body>

</html>