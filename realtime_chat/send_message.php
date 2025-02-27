<?php
session_start();
include '../config/config.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['is_logged_in'])) {
    echo "You must be logged in to send a message.";
    exit();
}

// รับข้อมูลจากฟอร์ม
$sender_id = $_SESSION['id_account'];  // ผู้ส่ง
$receiver_id = $_POST['receiver_id'];  // ผู้รับ
$message_content = mysqli_real_escape_string($conn, $_POST['message_content']);  // ข้อความ

// ตรวจสอบว่า receiver_id มีอยู่ในระบบหรือไม่
$query = "SELECT * FROM accounts WHERE id_account = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $receiver_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    echo "Receiver does not exist in the system.";
    exit();
}

// บันทึกข้อความลงใน chat_messages
$query = "INSERT INTO chat_messages (sender_id, receiver_id, message_content, timestamp) VALUES (?, ?, ?, NOW())";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "iis", $sender_id, $receiver_id, $message_content);
if (mysqli_stmt_execute($stmt)) {
    header("Location: chat_page.php?receiver_id=" . $receiver_id);  // หลังส่งข้อความจะไปยังหน้าแชท
} else {
    echo "Failed to send message.";
}