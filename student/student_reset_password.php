<?php
session_start();
include '../config/config.php';

$id_account = $_SESSION['id_account'] ?? null;
if (!$id_account) {
    $_SESSION['messager'] = 'Unauthorized access!';
    $_SESSION['alert_type'] = 'error'; 
    header("Location: ../student/student_reset_password.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    if ($new_password !== $confirm_password) {
        $_SESSION['messager'] = 'Passwords do not match!';
        $_SESSION['alert_type'] = 'error';
        header("Location: ../student/student_reset_password.php");
        exit();
    }

    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
    $query = "UPDATE accounts SET password_account = '$hashed_password' WHERE id_account = '$id_account'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $_SESSION['messager'] = 'Password reset successfully!';
        $_SESSION['alert_type'] = 'success';
    } else {
        $_SESSION['messager'] = 'Error resetting password!';
        $_SESSION['alert_type'] = 'error';
    }
    header("Location: ../student/student_reset_password.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../image/apple-touch-icon.png">
    <link rel="android-chrome"  href="../image/android-chrome-192x192.png">
    <link rel="android-chrome"  href="../image/android-chrome-512x512.png">
    <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="../image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../image/favicon-16x16.png">
    <link rel="manifest" href="../image/site.webmanifest">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Reset Password</h2>
        <form method="POST" action="student_reset_password.php" class="w-50 mx-auto" onsubmit="return confirmReset();">
            <div class="mb-3">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Reset Password</button>
        </form>
    </div>

    <!-- Toast Notification -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="toastNotification" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Notification</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <?php echo $_SESSION['messager'] ?? ''; ?>
            </div>
        </div>
    </div>

    <script>
        function confirmReset() {
            return confirm("คุณแน่ใจหรือไม่ว่าต้องการเปลี่ยนรหัสผ่าน?");
        }

        window.onload = function() {
            <?php if (isset($_SESSION['messager'])): ?>
                let toast = new bootstrap.Toast(document.getElementById('toastNotification'));
                toast.show();
                <?php if ($_SESSION['alert_type'] === 'success'): ?>
                    setTimeout(() => window.location.href = "../index.php", 3000);
                <?php endif; ?>
                <?php unset($_SESSION['messager'], $_SESSION['alert_type']); ?>
            <?php endif; ?>
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
