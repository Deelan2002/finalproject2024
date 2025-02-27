<?php
include('../config/config.php');

// ตรวจสอบว่ามี `job_id` ใน URL
if (!isset($_GET['job_id'])) {
    header("Location: manage_jobs.php");
    exit;
}

$job_id = $_GET['job_id'];

// ดึงข้อมูลตำแหน่งงาน
$query = "SELECT * FROM manage_jobs WHERE job_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: manage_jobs.php");
    exit;
}

$job = $result->fetch_assoc();

// ตรวจสอบการส่งฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_title = $_POST['job_title'];
    $job_description = $_POST['job_description'];

    $update_query = "UPDATE manage_jobs SET job_title = ?, job_description = ?, updated_at = NOW() WHERE job_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ssi", $job_title, $job_description,  $job_id);

    if ($update_stmt->execute()) {
        header("Location: manage_jobs.php?updated=true");
        exit;
    } else {
        $error = "Failed to update job. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job</title>
    
    <style>
        /* พื้นหลังและฟอนต์ */
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f4f7fc;
    color: #333;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* หัวข้อ */
h1 {
    color: #4CAF50;
    font-size: 2.5rem;
    font-weight: bold;
    text-align: center;
    margin-bottom: 20px;
}

/* ฟอร์ม */
form {
    background-color: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 500px;
}

/* ป้าย (label) */
label {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 10px;
    display: block;
    color: #444;
}

/* กล่องข้อความ (input และ textarea) */
input[type="text"], textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 1rem;
    background-color: #f9f9f9;
    transition: all 0.3s ease;
}

input[type="text"]:focus, textarea:focus {
    border-color: #4CAF50;
    background-color: #f1f1f1;
}

/* ปุ่ม Submit */
button {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 12px 20px;
    font-size: 1rem;
    border-radius: 6px;
    cursor: pointer;
    width: 100%;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #45a049;
}

/* ข้อความข้อผิดพลาด */
.error {
    color: #f44336;
    font-size: 1rem;
    margin-bottom: 15px;
    text-align: center;
}

    </style>
</head>
<body>

    <?php if (isset($error)) : ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <form action="edit_job.php?job_id=<?php echo $job_id; ?>" method="POST">
        
    <h1>Edit Job</h1>
        <label for="job_title">Job Title:</label>
        <input type="text" id="job_title" name="job_title" value="<?php echo htmlspecialchars($job['job_title']); ?>" required>

        <label for="job_description">Job Description:</label>
        <textarea id="job_description" name="job_description" required><?php echo htmlspecialchars($job['job_description']); ?></textarea>

        
        <button type="submit">Update Job</button>
    </form>
</body>
</html>
