<?php
// ตัวอย่าง get_new_messages.php

session_start();
include '../config/config.php';

$receiver_id = isset($_GET['receiver_id']) ? $_GET['receiver_id'] : 0;
$sender_id = $_SESSION['id_account'];

// แก้ไข SQL query เพื่อดึงข้อมูล username ของผู้ส่งและผู้รับจากตาราง accounts
$query = "
    SELECT cm.*, 
           sa.username_account AS sender_username, 
           ra.username_account AS receiver_username
    FROM chat_messages cm
    LEFT JOIN accounts sa ON cm.sender_id = sa.id_account
    LEFT JOIN accounts ra ON cm.receiver_id = ra.id_account
    WHERE (cm.sender_id = ? AND cm.receiver_id = ?) 
       OR (cm.sender_id = ? AND cm.receiver_id = ?)
    ORDER BY cm.timestamp ASC
";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// สร้าง HTML พร้อม CSS
while ($message = mysqli_fetch_assoc($result)) {
    // ใช้ sender_username และ receiver_username แทนการแสดงแค่ id
    $sender = $message['sender_id'] == $sender_id ? 'You' : htmlspecialchars($message['sender_username']);
    $receiver = $message['receiver_id'] == $receiver_id ? htmlspecialchars($message['receiver_username']) : 'Unknown';
    $class = $message['sender_id'] == $sender_id ? 'sender' : 'receiver';
    $iconClass = $message['sender_id'] == $sender_id ? 'fas fa-user-circle' : 'fas fa-user';

    echo "<div class='message $class'>
            <i class='$iconClass user-icon'></i>
            <div class='message-content'>
                <strong>$sender:</strong> " . htmlspecialchars($message['message_content']) . "
            </div>
          </div>";
}

?>
