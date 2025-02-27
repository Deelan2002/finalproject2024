<?php
session_start();
include '../ciwe/config/config.php';

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// ตรวจสอบค่าที่รับจากฟอร์ม
$email_account = isset($_POST['email_account']) ? trim($_POST['email_account']) : '';
$password_account = isset($_POST['password_account']) ? trim($_POST['password_account']) : '';

// ตรวจสอบว่า Email และ Password ไม่ว่าง
if (empty($email_account) || empty($password_account)) {
    $_SESSION['messager'] = 'Email and Password are required!';
    header("Location: ../ciwe/index.php");
    exit();
}

// ใช้ Prepared Statement เพื่อความปลอดภัย
$stmt = mysqli_prepare($conn, "SELECT * FROM accounts WHERE email_account = ?");
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $email_account);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // ตรวจสอบว่าพบผู้ใช้หรือไม่
    if ($result && mysqli_num_rows($result) === 1) {
        $accounts = mysqli_fetch_assoc($result);

        // ตรวจสอบรหัสผ่านที่แฮช
        if (password_verify($password_account, $accounts['password_account'])) {
            // ตั้งค่า Session หลังจากล็อกอินสำเร็จ
            $_SESSION['is_logged_in'] = true;
            $_SESSION['id_account'] = $accounts['id_account'];
            $_SESSION['username_account'] = $accounts['username_account']; // สอดคล้องกับฐานข้อมูล
            $_SESSION['role_account'] = $accounts['role_account'];

            // ตรวจสอบ role และเปลี่ยนเส้นทางไปหน้าที่เหมาะสม
            switch ($accounts['role_account']) {
                case 'admin':
                    header("Location: ../ciwe/admin/home_admin.php");
                    exit();
                case 'advisor':
                    header("Location: ../ciwe/advisor/home_advisor.php");
                    exit();
                case 'student':
                    header("Location: ../ciwe/student/home_student.php");
                    exit();
                case 'company':
                    header("Location: ../ciwe/company/home_company.php");
                    exit();
                default:
                    $_SESSION['messager'] = 'Invalid user role!';
                    header("Location: ../ciwe/index.php");
                    exit();
            }
        } else {
            $_SESSION['messager'] = 'Password is incorrect!';
            header("Location: ../ciwe/index.php");
            exit();
        }
    } else {
        $_SESSION['messager'] = 'Email not found!';
        header("Location: ../ciwe/index.php");
        exit();
    }
} else {
    $_SESSION['messager'] = 'Something went wrong. Please try again!';
    header("Location: ../ciwe/index.php");
    exit();
}

// ปิดการเชื่อมต่อ
mysqli_close($conn);
?>