<?php
session_start();
include '../ciwe/config/config.php';

if (isset($_SESSION['messager'])) {
    echo "<div class=message-box style='color: red; text-align: center;'>" . $_SESSION['messager'] . "</div>";
    unset($_SESSION['messager']); // แสดงแล้วเคลียร์ข้อความ
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login SDIC</title>
    <!-- เชื่อมไฟล์ CSS -->
    <link rel="icon" type="image/x-icon" href="image/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="image/favicon-16x16.png">
    <link rel="manifest" href="image/site.webmanifest">
    <link rel="apple-touch-icon" sizes="180x180" href="image/apple-touch-icon.png">
    <link rel="android-chrome"  href="image/android-chrome-192x192.png">
    <link rel="android-chrome"  href="image/android-chrome-512x512.png">
    <link rel="stylesheet" href="../ciwe/css/style-index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;600&display=swap" rel="stylesheet">


</head>

<body>

    <h1 class="welcome-title">Student database system for international CWIE</h1>
    <!-- ส่วนซ้าย (แถบสีส้ม) -->
    <div class="container">
        <div class="left-panel">
            <nav>
                <ul>
                    <li>Welcome!</li>
                    <li>Please sign in to access the services.</li>
                </ul>
            </nav>
        </div>

        <!-- ส่วนขวา (ฟอร์ม Login) -->
        <div class="right-panel">
            <div class="login-box">
                <div class="logo">
                    <!-- โลโก้ -->
                    <img src="../ciwe/image/logo-pxu.png" alt="Logo" class="logo-img">
                </div>
                <form action="../ciwe/login_db.php" method="POST">
                    <div class="input-field">
                        <div class="input-container">
                            <i class="icon fas fa-envelope"></i> <!-- ไอคอนสำหรับ Email -->
                            <input type="email" name="email_account" placeholder="Email" required>
                        </div>
                    </div>

                    <div class="input-field">
                        <div class="input-container">
                            <i class="icon fas fa-lock"></i> <!-- ไอคอนสำหรับ Password -->
                            <input type="password" name="password_account" placeholder="Password" required>
                        </div>
                    </div>
                    <button type="submit" class="btn">Sign in</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>