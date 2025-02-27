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
    <link rel="apple-touch-icon" sizes="180x180" href="../image/apple-touch-icon.png">
    <link rel="android-chrome" href="../image/android-chrome-192x192.png">
    <link rel="android-chrome" href="../image/android-chrome-512x512.png">
    <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="../image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../image/favicon-16x16.png">
    <link rel="manifest" href="../image/site.webmanifest">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: none;
            /* ปิดภาพพื้นหลังหลัก */
            display: flex;
            justify-content: flex-start;
            align-items: flex-start;
            overflow-x: hidden;
            position: relative;
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

        .container {
            padding: 30px;
            margin-left: 235px;
            /* ขยับไปทางขวามากขึ้น (จาก 240px เป็น 280px) */
            margin-top: 20px;
            width: calc(100% - 260px);
            max-width: 1300px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
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

        .table th {
            background-color:#a52a2a;
            color: white;
        }

        .btn-custom {
            background-color: #a52a2a;
            color: white;
        }

        .btn-custom:hover {
            background-color:  #6d0019;
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
            color:rgb(0, 0, 0);
            text-align: center;
            margin-bottom: 30px;
        }


        /* Toggle Button */
        .toggle-btn {
            position: fixed;
            top: 15px;
            left: 15px;
            background: #a52a2a;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1001;
            transition: background 0.3s ease;
        }

        .toggle-btn:hover {
            background: #e67e22;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h3>Admin Panel</h3>
        <ul>
            <li><a href="../admin/home_admin.php">Home Admin</a></li>
            <li><a href="../admin/admin_create_account.php">Create New Account</a></li>
            <li><a href="../admin/admin_manage_accounts.php">Manage Account</a></li>
            <li><a href="../admin/admin_show_student.php">Student List</a></li>
            <li><a href="../admin/admin_show_advisor.php">Advisor List</a></li>
            <li><a href="../admin/admin_manage_applications.php">Form Doc Approval Request</a></li>
            <li><a href="../admin/admin_show_register_international.php">Student Skill</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="container">
        <h2>Manage Accounts</h2>
        <?php if (isset($_SESSION['Messager'])): ?>
            <p style="color: #a52a2a;"><?php echo $_SESSION['Messager'];
                                        unset($_SESSION['Messager']); ?></p>
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


</body>

</html>