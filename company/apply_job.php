<?php
session_start();
include '../config/config.php';

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบแล้ว
if (!isset($_SESSION['id_account'])) {
    $_SESSION['Messager'] = 'You need to log in first.';
    header("Location: {$base_url}../login.php");
    exit();
}

$id_account = $_SESSION['id_account']; // สมมติว่าเก็บ id_account ใน session
$work_id = $_POST['work_id'] ?? null;

if (!$work_id || !is_numeric($work_id)) {
    echo "Invalid job ID.";
    exit();
}

// ตรวจสอบว่า id_account มีอยู่ใน profile_students หรือไม่
$check_account_query = "SELECT * FROM profile_students WHERE id_account = ?";
$stmt = $conn->prepare($check_account_query);
$stmt->bind_param("i", $id_account);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['Messager'] = 'Your account is not registered in the student profile.';
    header("Location: {$base_url}../student/job_detail.php?work_id={$work_id}");
    exit();
}

// ตรวจสอบว่าผู้ใช้เคยสมัครงานนี้แล้วหรือไม่
$check_query = "SELECT * FROM applications WHERE id_account = ? AND work_id = ?";
$stmt = $conn->prepare($check_query);
$stmt->bind_param("ii", $id_account, $work_id); // ใช้ id_account แทน
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['Messager'] = 'You have already applied for this job.';
    header("Location: {$base_url}../student/job_detail.php?work_id={$work_id}");
    exit();
}

// ถ้ายังไม่ได้สมัคร ก็ทำการสมัครงาน
$insert_query = "INSERT INTO applications (id_account, work_id, status) VALUES (?, ?, 'pending')";
$stmt = $conn->prepare($insert_query);
$stmt->bind_param("ii", $id_account, $work_id); // ใช้ id_account แทน
if ($stmt->execute()) {
    $_SESSION['Messager'] = 'Application submitted successfully!';
    header("Location: {$base_url}../student/job_detail.php?work_id={$work_id}");
    exit();
} else {
    $_SESSION['Messager'] = 'Failed to apply for the job. Please try again.';
    header("Location: {$base_url}../student/job_detail.php?work_id={$work_id}");
    exit();
}
?>
