<?php
session_start();
include '../config/config.php';

// ดึงรายชื่อผู้ใช้ที่สามารถแชทด้วย
$query = "SELECT id_account, username_account FROM accounts WHERE id_account != ?";  // ไม่รวมผู้ใช้งานที่ล็อกอิน
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $_SESSION['id_account']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// แสดงรายชื่อผู้ใช้
while ($user = mysqli_fetch_assoc($result)) {
    echo "<a href='chat_page.php?receiver_id=" . $user['id_account'] . "'>" . htmlspecialchars($user['username_account']) . "</a><br>";
}
?>
