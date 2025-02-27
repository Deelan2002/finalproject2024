<?php
session_start();
include '../config/config.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['is_logged_in'])) {
    echo "You must be logged in to view this page.";
    exit();
}

// รับ receiver_id จาก URL
$receiver_id = isset($_GET['receiver_id']) ? $_GET['receiver_id'] : 0;
$sender_id = $_SESSION['id_account'];

// ดึงชื่อผู้รับ
$receiver_query = "SELECT username_account FROM accounts WHERE id_account = ?";
$stmt_receiver = mysqli_prepare($conn, $receiver_query);
mysqli_stmt_bind_param($stmt_receiver, "i", $receiver_id);
mysqli_stmt_execute($stmt_receiver);
$result_receiver = mysqli_stmt_get_result($stmt_receiver);

// ตรวจสอบว่ามีผู้รับหรือไม่
if ($row_receiver = mysqli_fetch_assoc($result_receiver)) {
    $receiver_username = htmlspecialchars($row_receiver['username_account']);
} else {
    $receiver_username = "Unknown User";
}

// ดึงข้อความที่เกี่ยวข้องกับ sender_id และ receiver_id
$query = "
    SELECT cm.*, 
           sa.username_account AS sender_username, 
           ra.username_account AS receiver_username
    FROM chat_messages cm
    LEFT JOIN accounts sa ON cm.sender_id = sa.id_account
    LEFT JOIN accounts ra ON cm.receiver_id = ra.id_account
    WHERE (cm.sender_id = ? AND cm.receiver_id = ?) 
       OR (cm.sender_id = ? AND cm.receiver_id = ?)
    ORDER BY cm.timestamp ASC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with User <?php echo htmlspecialchars($receiver_id); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../image/apple-touch-icon.png">
    <link rel="android-chrome" href="../image/android-chrome-192x192.png">
    <link rel="android-chrome" href="../image/android-chrome-512x512.png">
    <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="../image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../image/favicon-16x16.png">
    <link rel="manifest" href="../image/site.webmanifest">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        /* สร้างเลเยอร์ภาพพื้นหลัง */
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('../image/pxu1.jpeg');
            /* เปลี่ยนเป็นที่อยู่ของภาพ */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            filter: blur(3px);
            /* ปรับค่าความเบลอ (px) */
            z-index: -1;
            /* ให้ภาพอยู่ด้านหลัง */
        }

        body::after {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.1);
            /* ปรับความมืด (0.3 = 30%) */
            z-index: -1;
        }

        h2 {
            text-align: center;
            margin: 20px 0;
            color: white;
            font-size: 24px;
        }

        #chat-container {
            width: 90%;
            max-width: 600px;
            height: 400px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            padding: 20px;
        }

        #chat-container .message {
            display: flex;
            margin-bottom: 15px;
            align-items: flex-start;
            position: relative;
        }

        .sender,
        .receiver {
            max-width: 70%;
            padding: 10px;
            border-radius: 15px;
            font-size: 14px;
            word-wrap: break-word;
        }

        .sender {
            background-color: #d8f3dc;
            color: #333;
            align-self: flex-end;
            margin-left: auto;
        }

        .receiver {
            background-color: #f1f1f1;
            color: #333;
            margin-right: auto;
        }

        .user-icon {
            font-size: 24px;
            margin-right: 10px;
        }

        .sender .user-icon {
            margin-left: 10px;
            margin-right: 0;
        }

        .delete-button {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: #ff4d4d;
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            font-size: 12px;
        }

        .delete-button:hover {
            background-color: #ff1a1a;
        }

        form {
            width: 90%;
            max-width: 600px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            padding: 10px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        textarea {
            flex: 1;
            height: 40px;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            font-size: 14px;
            resize: none;
            margin-right: 10px;
        }

        textarea:focus {
            outline: none;
            border-color: #007bff;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        button:hover {
            background-color: #0056b3;
        }

        button:active {
            background-color: #004494;
        }

        /* Sidebar */
        .sidebar {
            width: 220px;
            height: 100vh;
            background: linear-gradient(to bottom, #6d0019, #a52a2a);
            padding: 20px;
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            overflow: hidden;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar li {
            list-style: none;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px;
            margin: 8px 0;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.15);
            transition: background 0.3s, transform 0.2s;
            text-align: center;
        }

        .sidebar a:hover {
            color: #ff6347;
            background: rgba(255, 255, 255, 0.25);
            transform: scale(1.05);
        }
    </style>
</head>

<body>

    

    <h2>Chat with <?php echo $receiver_username; ?></h2>


    <div id="chat-container">
        <?php
        // แสดงข้อความทั้งหมด
        while ($message = mysqli_fetch_assoc($result)) {
            $sender = $message['sender_id'] == $sender_id ? 'You' : htmlspecialchars($message['sender_username']);
            $class = $message['sender_id'] == $sender_id ? 'sender' : 'receiver';
            $iconClass = $message['sender_id'] == $sender_id ? 'fas fa-user-circle' : 'fas fa-user';
            $id_message = $message['id_message'];

            echo "<div class='message $class' id='message_$id_message' oncontextmenu='showDeleteOption(event, $id_message)'>
                <i class='$iconClass user-icon'></i>
                <div class='message-content'>
                    <strong>$sender:</strong> " . htmlspecialchars($message['message_content']) . "
                </div>
              </div>";
        }
        ?>
    </div>

    <!-- ฟอร์มส่งข้อความ -->
    <form method="POST" action="send_message.php">
        <input type="hidden" name="receiver_id" value="<?php echo $receiver_id; ?>">
        <textarea name="message_content" required placeholder="Type your message..."></textarea>
        <button type="submit">
            <i class="fas fa-paper-plane"></i> Send
        </button>
    </form>

    <!-- ตัวเลือกการลบข้อความ -->
    <div id="delete-options" style="display:none;">
        <button id="delete-message" style="background-color:rgb(174, 6, 6); color: white;">Delete Message</button>
    </div>

    <script>
        let selectedMessageId = null;

        function showDeleteOption(event, messageId) {
            event.preventDefault(); // ป้องกันเมนูคอนเท็กซ์เมนูเดิม
            selectedMessageId = messageId;

            const deleteOptions = document.getElementById('delete-options');
            deleteOptions.style.display = 'block';
            deleteOptions.style.position = 'absolute';
            deleteOptions.style.top = event.clientY + 'px';
            deleteOptions.style.left = event.clientX + 'px';

            // เพิ่มตัวจับเหตุการณ์ให้ซ่อนเมื่อคลิกพื้นที่ว่าง
            document.addEventListener('click', hideDeleteOption);
        }

        function hideDeleteOption(event) {
            const deleteOptions = document.getElementById('delete-options');
            if (!deleteOptions.contains(event.target)) {
                deleteOptions.style.display = 'none';
                selectedMessageId = null;
                document.removeEventListener('click', hideDeleteOption);
            }
        }

        // การลบข้อความพร้อมการยืนยัน
        document.getElementById('delete-message').onclick = function() {
            if (selectedMessageId) {
                const confirmDelete = confirm('Are you sure you want to delete this message?');
                if (confirmDelete) {
                    fetch('delete_message.php', {
                            method: 'POST',
                            body: JSON.stringify({
                                id_message: selectedMessageId
                            }),
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById('message_' + selectedMessageId).remove();
                                alert('Message deleted');
                            } else {
                                alert('Error deleting message');
                            }
                        });
                }
            }
            document.getElementById('delete-options').style.display = 'none'; // ซ่อนตัวเลือกหลังการยืนยัน
        };
    </script>

</body>

</html>