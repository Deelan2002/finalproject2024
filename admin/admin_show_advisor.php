<?php
session_start();
include '../config/config.php';

// ตรวจสอบการเข้าสู่ระบบและบทบาทผู้ใช้
if (empty($_SESSION['id_account']) || $_SESSION['role_account'] !== 'admin') {
    $_SESSION['Messager'] = 'You are not authorized!';
    header("Location: {$base_url}../index.php");
    exit();
}

// ดึง id_account จาก session
$id_account = $_SESSION['id_account'];

// ตรวจสอบว่ามีการค้นหาหรือไม่
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
}

// ดึงข้อมูลที่มีบทบาทเป็น advisor จากฐานข้อมูล
$query_advisors = mysqli_query($conn, "
    SELECT a.*, pa.* 
    FROM accounts a
    LEFT JOIN profile_advisor pa ON a.id_account = pa.id_account
    WHERE a.role_account = 'advisor' 
    AND (a.username_account LIKE '%$search_query%' OR pa.name_advisor LIKE '%$search_query%')
");

if (!$query_advisors) {
    die("Error: " . mysqli_error($conn));  // หากมีข้อผิดพลาดในการดึงข้อมูล
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Show Advisors</title>
    <link rel="stylesheet" href="../css/style_admin_show_advisor.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body>

    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>Admin Dashboard</h2>
            <ul>
                <li><a href="../admin/home_admin.php">Home Admin</a></li>
                <li><a href="../admin/admin_create_account.php">Create New Account</a></li>
                <li><a href="../admin/admin_manage_accounts.php">Manage Account</a></li>
                <li><a href="../admin/admin_show_student.php">Student List</a></li>
                <li><a href="../admin/admin_show_advisor.php">Advisor List</a></li>
                <li><a href="#">Form Doc Approval Request</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </div>

        <div class="container">
            <h1>Advisor List</h1>

            <!-- Search Form -->
            <div class="search-container">
                <form method="GET" action="admin_show_advisor.php">
                    <input type="text" name="search" placeholder="Search by name or ID" value="<?php echo htmlspecialchars($search_query); ?>" />
                    <button type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <?php
            if (mysqli_num_rows($query_advisors) > 0) {
                while ($advisor = mysqli_fetch_assoc($query_advisors)) {
            ?>
                    <div class="card">
                        <img src="../uploads/<?php echo !empty($advisor['profile_advisor']) ? htmlspecialchars($advisor['profile_advisor']) : 'default.png'; ?>" alt="Advisor Image">
                        <div class="advisor-info">
                            <div>
                                <label>Advisor ID</label>
                                <span><?php echo htmlspecialchars($advisor['id_account']); ?></span>
                            </div>
                            <div>
                                <label>Name</label>
                                <span><?php echo !empty($advisor['name_advisor']) ? htmlspecialchars($advisor['name_advisor']) : htmlspecialchars($advisor['username_account']); ?></span>
                            </div>
                            <div>
                                <label>Email</label>
                                <span><?php echo htmlspecialchars($advisor['email_account']); ?></span>
                            </div>
                            <div>
                                <label>Phone</label>
                                <span><?php echo !empty($advisor['number_advisor']) ? htmlspecialchars($advisor['number_advisor']) : 'N/A'; ?></span>
                            </div>
                            <div>
                                <label>University</label>
                                <span><?php echo !empty($advisor['university_advisor']) ? htmlspecialchars($advisor['university_advisor']) : 'N/A'; ?></span>
                            </div>
                            <div>
                                <label>Faculty</label>
                                <span><?php echo !empty($advisor['faculty_advisor']) ? htmlspecialchars($advisor['faculty_advisor']) : 'N/A'; ?></span>
                            </div>
                        </div>
                        <a href="../admin/admin_show_details_advisor.php?id=<?php echo $advisor['id_account']; ?>" class="action-button">Details</a>
                    </div>
            <?php
                }
            } else {
                echo "<p class='no-advisors'>No advisors found.</p>";
            }
            ?>
        </div>
    </div>
</body>

</html>
