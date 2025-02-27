<?php
session_start();
include '../ciwe/config/config.php';

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// ตรวจสอบค่าที่รับจากฟอร์มเมื่อผู้ใช้ส่งข้อมูล
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_account = isset($_POST['username_account']) ? trim($_POST['username_account']) : '';
    $email_account = isset($_POST['email_account']) ? trim($_POST['email_account']) : '';
    $password_account = isset($_POST['password_account']) ? trim($_POST['password_account']) : '';
    $role_account = isset($_POST['role_account']) ? trim($_POST['role_account']) : 'student'; // ค่าเริ่มต้นเป็น student

    // ตรวจสอบค่าที่กรอกในฟอร์ม
    if (empty($username_account) || empty($email_account) || empty($password_account)) {
        $_SESSION['messager'] = 'All fields are required!';
        header("Location: register.php");
        exit();
    }

    // ตรวจสอบว่าอีเมลซ้ำในฐานข้อมูลหรือไม่
    $stmt_check = mysqli_prepare($conn, "SELECT * FROM accounts WHERE email_account = ?");
    if ($stmt_check) {
        mysqli_stmt_bind_param($stmt_check, "s", $email_account);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);

        if ($result_check && mysqli_num_rows($result_check) > 0) {
            $_SESSION['messager'] = 'Email is already registered!';
            header("Location: register.php");
            exit();
        }
    }

    // แฮชรหัสผ่าน
    $hashed_password = password_hash($password_account, PASSWORD_BCRYPT);

    // เพิ่มผู้ใช้ใหม่ในฐานข้อมูล
    $stmt = mysqli_prepare($conn, "INSERT INTO accounts (username_account, email_account, password_account, role_account, created_at) VALUES (?, ?, ?, ?, NOW())");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssss", $username_account, $email_account, $hashed_password, $role_account);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['messager'] = 'Registration successful! Please log in.';
            header("Location: ../ciwe/index.php");
            exit();
        } else {
            $_SESSION['messager'] = 'Registration failed. Please try again!';
            header("Location: register.php");
            exit();
        }
    } else {
        $_SESSION['messager'] = 'Something went wrong. Please try again!';
        header("Location: register.php");
        exit();
    }
}

// ปิดการเชื่อมต่อ
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
    <?php
    if (isset($_SESSION['messager'])) {
        echo "<p style='color: red;'>" . $_SESSION['messager'] . "</p>";
        unset($_SESSION['messager']);
    }
    ?>
    <form action="register.php" method="post">
        <label for="username_account">Username:</label>
        <input type="text" id="username_account" name="username_account" required><br><br>
        
        <label for="email_account">Email:</label>
        <input type="email" id="email_account" name="email_account" required><br><br>
        
        <label for="password_account">Password:</label>
        <input type="password" id="password_account" name="password_account" required><br><br>
        
        <label for="role_account">Role:</label>
        <select id="role_account" name="role_account">
            <option value="student">Student</option>
            <option value="advisor">Advisor</option>
            <option value="admin">Admin</option>
            <option value="company">Company</option>
        </select><br><br>
        
        <button type="submit">Register</button>
    </form>
</body>
</html>
