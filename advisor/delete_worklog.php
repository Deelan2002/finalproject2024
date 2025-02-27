<?php
session_start();
include '../config/config.php';

// ตรวจสอบสิทธิ์ผู้ใช้ (เฉพาะ advisor เท่านั้น)
if (!isset($_SESSION['role_account']) || $_SESSION['role_account'] !== 'advisor') {
    $_SESSION['Messager'] = 'You are not authorized!';
    header("location: {$base_url}../index.php");
    exit();
}

// รับค่า id บันทึกประจำวัน
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id == 0) {
    $_SESSION['Messager'] = 'Invalid worklog ID!';
    header("location: daily_list.php");
    exit();
}

// ดึงข้อมูลบันทึกก่อนลบ
$query = mysqli_query($conn, "SELECT id_account FROM worklog WHERE id = '$id'");
$log = mysqli_fetch_assoc($query);

// ลบข้อมูลจากฐานข้อมูล
$delete_query = "DELETE FROM worklog WHERE id = '$id'";
if (mysqli_query($conn, $delete_query)) {
    $_SESSION['Messager'] = 'Deleted successfully!';
} else {
    $_SESSION['Messager'] = 'Delete failed!';
}

header("location: daily_view.php?id_student=" . $log['id_account']);
exit();
?>
