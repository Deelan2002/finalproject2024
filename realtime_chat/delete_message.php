<?php
session_start();
include '../config/config.php';

$data = json_decode(file_get_contents('php://input'), true);
$id_message = $data['id_message'];

if ($id_message) {
    $query = "DELETE FROM chat_messages WHERE id_message = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_message);
    $result = mysqli_stmt_execute($stmt);
    
    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
?>
