<?php
session_start();
include '../config/config.php';

// ลบค่าของ session ที่เกี่ยวข้อง
unset($_SESSION['WP' . 'check']);
unset($_SESSION['WP' . 'id_account']);
unset($_SESSION['WP' . 'username_account']);
unset($_SESSION['WP' . 'email_account']);
unset($_SESSION['WP' . 'role_account']);

// ทำลาย session ทั้งหมด
session_destroy();

// ส่งผู้ใช้กลับไปที่หน้า login หรือ index
header("location: {$base_url}../index.php");
exit();

?>
