<?php
session_start();
include '../config/config.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['is_logged_in'])) {
    echo "You must be logged in to view this page.";
    exit();
}

// ดึงรายชื่อผู้ใช้ทั้งหมด (ยกเว้นตัวเอง)
$query = "SELECT id_account, username_account FROM accounts WHERE id_account != ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $_SESSION['id_account']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Receiver</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../image/apple-touch-icon.png">
    <link rel="android-chrome" href="../image/android-chrome-192x192.png">
    <link rel="android-chrome" href="../image/android-chrome-512x512.png">
    <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="../image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../image/favicon-16x16.png">
    <link rel="manifest" href="../image/site.webmanifest">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->
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
            color: white;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        form {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 400px;
        }

        label {
            font-size: 16px;
            color: #555;
            display: block;
            margin-bottom: 10px;
        }

        select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
            outline: none;
            transition: border 0.3s ease;
        }

        select:focus {
            border: 1px solidrgb(255, 0, 0);
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: rgb(255, 0, 0);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: rgb(179, 0, 0);
            transform: scale(1.05);
        }

        button:active {
            transform: scale(1);
        }

        .icon {
            font-size: 50px;
            color: rgb(255, 0, 0);
            margin-bottom: 20px;
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            h2 {
                font-size: 20px;
            }

            form {
                padding: 15px;
            }

            button {
                font-size: 14px;
            }
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

  

    <i class="fas fa-user-friends icon"></i>
    <h2>Choose a Receiver</h2>
    <form method="GET" action="chat_page.php">
        <label for="receiver_id">Choose Receiver:</label>
        <select name="receiver_id" id="receiver_id" required>
            <?php
            // แสดงรายชื่อผู้ใช้ที่สามารถส่งข้อความไปได้
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='{$row['id_account']}'>{$row['username_account']}</option>";
            }
            ?>
        </select>
        <button type="submit">
            <i class="fas fa-paper-plane"></i> Go to Chat
        </button>
    </form>

</body>

</html>