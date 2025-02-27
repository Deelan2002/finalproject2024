<?php
session_start();
include '../config/config.php';

$id_account = $_SESSION['id_account'];
$id = $_POST['id'] ?? ''; // ตรวจสอบ id_worklog
$date = $_POST['date'];
$details = $_POST['details'];

if ($id) {
    // ถ้ามี id หมายถึงการแก้ไขข้อมูล
    $query = "UPDATE worklog SET work_date = '$date', details = '$details' WHERE id = '$id' AND id_account = '$id_account'";
} else {
    // ถ้าไม่มี id หมายถึงการเพิ่มข้อมูลใหม่
    $query = "INSERT INTO worklog (id_account, work_date, details) VALUES ('$id_account', '$date', '$details')";
}

if (mysqli_query($conn, $query)) {
    echo $id ?: mysqli_insert_id($conn); // ส่งค่า id_worklog กลับไป
} else {
    echo "error";
}
?>
