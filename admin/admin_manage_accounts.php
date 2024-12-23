<?php
session_start();
include '../config/config.php';

// ตรวจสอบการเข้าสู่ระบบและบทบาทผู้ใช้
if (empty($_SESSION['id_account']) || $_SESSION['role_account'] !== 'admin') {
    $_SESSION['Messager'] = 'You are not authorized!';
    header("Location: {$base_url}../index.php");
    exit();
}

// ค้นหา/ดึงข้อมูลผู้ใช้ทั้งหมด
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$sql = "SELECT id_account, username_account, email_account, role_account, created_at 
        FROM accounts 
        WHERE username_account LIKE '%$search%' OR role_account LIKE '%$search%'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Accounts</title>
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
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .table th {
            background-color: #ff7b00;
            color: white;
        }

        .btn-custom {
            background-color: #ff7b00;
            color: white;
        }

        .btn-custom:hover {
            background-color: #e66a00;
            color: white;
        }

        .search-box {
            margin-bottom: 20px;
        }

        .table {
            border-radius: 5px;
            overflow: hidden;
        }

        h2 {
            color: #ff7b00;
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Manage Accounts</h2>
        <?php if (isset($_SESSION['Messager'])): ?>
            <p style="color: #ff6600;"><?php echo $_SESSION['Messager']; unset($_SESSION['Messager']); ?></p>
        <?php endif; ?>
        <!-- Search Form -->
        <form method="GET" action="admin_manage_accounts.php" class="d-flex search-box">
            <input type="text" name="search" class="form-control me-2" placeholder="Search by username or role" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-custom">Search</button>
        </form>

        <!-- Accounts Table -->
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['id_account']; ?></td>
                        <td><?php echo htmlspecialchars($row['username_account']); ?></td>
                        <td><?php echo htmlspecialchars($row['email_account']); ?></td>
                        <td><?php echo htmlspecialchars($row['role_account']); ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td>
                            <a href="admin_edit_account.php?id=<?php echo $row['id_account']; ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="admin_delete_account.php?id=<?php echo $row['id_account']; ?>" onclick="return confirm('Are you sure?');" class="btn btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
         <!-- Submit Button -->
         <div class="d-grid gap-2">
                
                <a href="../admin/home_admin.php" class="btn btn-secondary">Back to Home Admin</a>
            </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
