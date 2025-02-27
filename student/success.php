<?php
session_start();

// ตรวจสอบสิทธิ์ผู้ใช้งาน
if (!isset($_SESSION['role_account']) || $_SESSION['role_account'] !== 'student') {
    $_SESSION['Messager'] = 'You are not authorized!';
    header("location: {$base_url}../index.php");
    exit();
}

// ตรวจสอบว่ามีข้อความแจ้งเตือนจากการบันทึกข้อมูลหรือไม่
if (isset($_SESSION['Messager'])) {
    // แสดงข้อความแจ้งเตือน
    $message = $_SESSION['Messager'];
    unset($_SESSION['Messager']);
} else {
    // ถ้าไม่มีข้อความแจ้งเตือน ก็แสดงข้อความ default
    $message = "Welcome to the Student Registration!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Success</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../image/apple-touch-icon.png">
    <link rel="android-chrome"  href="../image/android-chrome-192x192.png">
    <link rel="android-chrome"  href="../image/android-chrome-512x512.png">
    <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="../image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../image/favicon-16x16.png">
    <link rel="manifest" href="../image/site.webmanifest">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            width: 80%;
            max-width: 600px;
        }

        h2 {
            color: #28a745;
            font-size: 24px;
            margin-bottom: 20px;
        }

        p {
            font-size: 18px;
            color: #333;
            margin-bottom: 30px;
        }

        a {
            text-decoration: none;
            background-color:rgb(255, 132, 0);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 18px;
            transition: background-color 0.3s;
        }

        a:hover {
            background-color:rgb(179, 87, 0);
        }

        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><?php echo $message; ?></h2>
        <p>Your registration was successful! You can now go to your profile.</p>
        <a href="../student/home_student.php">Go to Home</a>
        <div class="footer">
            <p>&copy; 2024 Student Registration System</p>
        </div>
    </div>
</body>
</html>
