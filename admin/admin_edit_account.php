<?php
session_start();
include '../config/config.php';

// ตรวจสอบการเข้าสู่ระบบและบทบาทผู้ใช้
if (empty($_SESSION['id_account']) || $_SESSION['role_account'] !== 'admin') {
    $_SESSION['Messager'] = 'You are not authorized!';
    header("Location: {$base_url}../index.php");
    exit();
}

$id = $_GET['id'];
$sql = "SELECT * FROM accounts WHERE id_account = $id";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    // อัพเดตข้อมูลผู้ใช้
    $update_sql = "UPDATE accounts SET 
                    username_account='$username', email_account='$email', role_account='$role'";
    if ($password) {
        $update_sql .= ", password_account='$password'";
    }
    $update_sql .= " WHERE id_account=$id";

    if (mysqli_query($conn, $update_sql)) {
        $_SESSION['Messager'] = "Account updated successfully!";
        header("Location: admin_manage_accounts.php");
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
    <title>Edit Account</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../image/apple-touch-icon.png">
    <link rel="android-chrome"  href="../image/android-chrome-192x192.png">
    <link rel="android-chrome"  href="../image/android-chrome-512x512.png">
    <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="../image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../image/favicon-16x16.png">
    <link rel="manifest" href="../image/site.webmanifest">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .container {
            margin-top: 50px;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #ff7b00;
            text-align: center;
            margin-bottom: 30px;
        }

        .btn-custom {
            background-color: #ff7b00;
            color: white;
        }

        .btn-custom:hover {
            background-color: #e66a00;
            color: white;
        }

        .form-label {
            font-weight: bold;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Edit Account</h2>

        <?php if (isset($_SESSION['Messager'])): ?>
            <div class="alert alert-info"><?php echo $_SESSION['Messager']; unset($_SESSION['Messager']); ?></div>
        <?php endif; ?>

        <form method="POST">
            <!-- Username -->
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" class="form-control" id="username" name="username" 
                       value="<?php echo htmlspecialchars($user['username_account']); ?>" required>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" 
                       value="<?php echo htmlspecialchars($user['email_account']); ?>" required>
            </div>

            <!-- Role -->
            <div class="mb-3">
                <label for="role" class="form-label">Role:</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="admin" <?php echo $user['role_account'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                    <option value="advisor" <?php echo $user['role_account'] === 'advisor' ? 'selected' : ''; ?>>Advisor</option>
                    <option value="student" <?php echo $user['role_account'] === 'student' ? 'selected' : ''; ?>>Student</option>
                </select>
            </div>

            <!-- New Password -->
            <div class="mb-3">
                <label for="password" class="form-label">New Password (leave blank if unchanged):</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>

            <!-- Submit Button -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-custom">Update Account</button>
                <a href="admin_manage_accounts.php" class="btn btn-secondary">Back to Manage Accounts</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
