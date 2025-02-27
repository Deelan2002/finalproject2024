<?php
include('../config/config.php');

// ตรวจสอบว่ามี `job_id` และ `status` ใน URL
if (!isset($_GET['job_id']) || !isset($_GET['status'])) {
    header("Location: manage_jobs.php");
    exit;
}

$job_id = $_GET['job_id'];
$status = ($_GET['status'] === 'open') ? 'closed' : 'open';

// อัปเดตสถานะตำแหน่งงาน
$query = "UPDATE manage_jobs SET job_status = ?, updated_at = NOW() WHERE job_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $status, $job_id);

if ($stmt->execute()) {
    header("Location: manage_jobs.php?status_changed=true");
    exit;
} else {
    echo "Failed to change status.";
}
?>
