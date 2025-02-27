<?php
session_start();
include '../config/config.php';

// ตรวจสอบสิทธิ์ผู้ใช้งาน
if (!isset($_SESSION['role_account']) || !in_array($_SESSION['role_account'], ['admin', 'advisor'])) {
    $_SESSION['Messager'] = 'You are not authorized!';
    header("location: {$base_url}../index.php");
    exit();
}

// ตรวจสอบว่ามีการส่งข้อมูลมาจากฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับข้อมูลจากฟอร์ม
    $application_id = $_POST['application_id'];
    $status = $_POST['status'];

    // ตรวจสอบสถานะที่ได้รับมาว่าถูกต้องหรือไม่
    $valid_statuses = ['pending', 'approved', 'rejected'];
    if (!in_array($status, $valid_statuses)) {
        $_SESSION['Messager'] = 'Invalid status!';
        header("location: advisor_manage_applications.php");
        exit();
    }

    // อัปเดตสถานะในตาราง applications
    $query = "UPDATE applications SET status = ? WHERE application_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'si', $status, $application_id);
    
    // ตรวจสอบผลการอัปเดต
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['Messager'] = 'Application status updated successfully!';
    } else {
        $_SESSION['Messager'] = 'Error updating application status!';
    }
    
    // เปลี่ยนเส้นทางกลับไปยังหน้าการจัดการสมัครงาน
    header("location: advisor_manage_applications.php");
    exit();
} else {
    // หากไม่ใช่การส่งข้อมูลผ่าน POST ให้กลับไปยังหน้าเดิม
    $_SESSION['Messager'] = 'Invalid request method!';
    header("location: advisor_manage_applications.php");
    exit();
}
?>
