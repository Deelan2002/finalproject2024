<?php
session_start();
include '../config/config.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบการเข้าสู่ระบบและบทบาทผู้ใช้
if (empty($_SESSION['id_account']) || $_SESSION['role_account'] !== 'admin') {
    $_SESSION['Messager'] = 'You are not authorized!';
    header("Location: {$base_url}../index.php");
    exit();
}

// ตรวจสอบการส่งข้อมูลจากฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_account = mysqli_real_escape_string($conn, $_POST['username_account']);
    $password_account = mysqli_real_escape_string($conn, $_POST['password_account']);
    $email_account = mysqli_real_escape_string($conn, $_POST['email_account']);
    $role_account = mysqli_real_escape_string($conn, $_POST['role_account']);

    // ตรวจสอบว่า role เป็นค่าที่ถูกต้อง
    if (!in_array($role_account, ['admin', 'advisor', 'student'])) {
        echo "Invalid role selected!";
        exit();
    }

    // แฮชรหัสผ่านก่อนเก็บลงฐานข้อมูล
    $hashed_password = password_hash($password_account, PASSWORD_DEFAULT);

    // SQL สำหรับการ INSERT ข้อมูลผู้ใช้ใหม่
    $sql = "INSERT INTO accounts (username_account, password_account, email_account, role_account) 
            VALUES ('$username_account', '$hashed_password', '$email_account', '$role_account')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['Messager'] = "Account created successfully!";
        header("Location: ../admin/admin_create_account.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #ff9966, #ff5e62);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        .container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
            padding: 30px;
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h2 {
            color: #ff6600;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #555;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #ff6600;
            outline: none;
            box-shadow: 0 0 6px rgba(255, 102, 0, 0.3);
        }

        .form-group i {
            position: absolute;
            margin-left: -30px;
            margin-top: 14px;
            color: #888;
        }

        button {
            width: 100%;
            background-color: #ff6600;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #e65c00;
        }

        .footer {
            margin-top: 20px;
            font-size: 14px;
        }

        .footer a {
            color: #ff6600;
            text-decoration: none;
            font-weight: 600;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Create New Account</h2>

        <?php if (isset($_SESSION['Messager'])): ?>
            <p style="color: #ff6600;"><?php echo $_SESSION['Messager']; unset($_SESSION['Messager']); ?></p>
        <?php endif; ?>

        <form method="POST" action="admin_create_account.php">
            <div class="form-group">
                <label for="username_account">Username</label>
                <input type="text" name="username_account" id="username_account" required>
            </div>

            <div class="form-group">
                <label for="password_account">Password</label>
                <input type="password" name="password_account" id="password_account" required>
            </div>

            <div class="form-group">
                <label for="email_account">Email</label>
                <input type="email" name="email_account" id="email_account" required>
            </div>

            <div class="form-group">
                <label for="role_account">Role</label>
                <select name="role_account" id="role_account" required>
                    <option value="advisor">Advisor</option>
                    <option value="student">Student</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <button type="submit">Create Account</button>
        </form>

        <div class="footer">
            <p>Return to <a href="../admin/home_admin.php">Admin Dashboard</a></p>
        </div>
    </div>
</body>

</html>
