<?php
session_start();
include '../config/config.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบสิทธิ์ผู้ใช้งาน
if (!isset($_SESSION['role_account']) || !in_array($_SESSION['role_account'], ['admin', 'advisor'])) {
    $_SESSION['Messager'] = 'You are not authorized!';
    header("location: {$base_url}../index.php");
    exit();
}

// ตรวจสอบว่ามีการส่ง student_id หรือไม่
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    // ลบข้อมูลทักษะภาษาของนักเรียน
    $lang_delete_sql = "DELETE FROM language_skills WHERE student_id = ?";
    $lang_delete_stmt = $conn->prepare($lang_delete_sql);
    $lang_delete_stmt->bind_param("i", $student_id);
    $lang_delete_stmt->execute();

    // ลบข้อมูลนักเรียน
    $delete_sql = "DELETE FROM student_profile WHERE student_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $student_id);
    if ($delete_stmt->execute()) {
        header("Location: advisor_view_skill.php"); // Redirect ไปหน้าแสดงข้อมูลหลังจากลบ
        exit();
    } else {
        echo "Error deleting student.";
    }
} else {
    echo "Invalid request.";
}
?>
