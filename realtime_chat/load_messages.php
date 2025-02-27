<?php
include('../config/config.php');
if (!isset($_GET['chat_room_id'])) {
    die("Invalid request.");
}

$chat_room_id = $_GET['chat_room_id'];
$query = "SELECT sender_id, message, created_at 
          FROM messages 
          WHERE chat_room_id = ? 
          ORDER BY created_at";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $chat_room_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $sender = $row['sender_id'] == $_SESSION['id_account'] ? "You" : "Partner";
    echo "<p><strong>{$sender}:</strong> {$row['message']} <small>({$row['created_at']})</small></p>";
}
