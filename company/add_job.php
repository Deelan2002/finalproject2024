<?php
// เริ่มต้นการเชื่อมต่อฐานข้อมูล
include('../config/config.php');

// ตรวจสอบการส่งฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_title = $_POST['job_title'];
    $job_description = $_POST['job_description'];
    $id_company = $_POST['id_company']; // รับค่าจากฟอร์ม

    $query = "INSERT INTO manage_jobs (job_title, job_description,  id_company) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $job_title, $job_description,  $id_company);

    if ($stmt->execute()) {
        header("Location: manage_jobs.php?success=true");
        exit;
    } else {
        $error = "Failed to add job. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Job</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Add New Job</h1>

    <?php if (isset($error)) : ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <form action="add_job.php" method="POST">
        <label for="job_title">Job Title:</label>
        <input type="text" id="job_title" name="job_title" required>

        <label for="job_description">Job Description:</label>
        <textarea id="job_description" name="job_description" required></textarea>

        <button type="submit">Add Job</button>
    </form>
</body>
</html>
