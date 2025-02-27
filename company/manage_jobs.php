<?php
// เริ่มต้นการเชื่อมต่อฐานข้อมูล
include('../config/config.php');

// การลบตำแหน่งงาน
if (isset($_GET['delete'])) {
    $job_id = $_GET['delete'];
    $delete_query = "DELETE FROM manage_jobs WHERE job_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    header("Location: manage_jobs.php?deleted=true");
    exit;
}

// ดึงข้อมูลตำแหน่งงาน
$query_jobs = "SELECT * FROM manage_jobs ORDER BY created_at DESC";
$result_jobs = mysqli_query($conn, $query_jobs);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Jobs</title>
    <!-- ใส่ลิงก์ CSS -->
    <link rel="stylesheet" href="styles.css">
    <style>
        /* กำหนดพื้นฐาน */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

/* Navbar */
nav {
    background-color: #333;
    padding: 10px 0;
}

nav ul {
    list-style: none;
    text-align: center;
}

nav ul li {
    display: inline;
    margin-right: 20px;
}

nav ul li a {
    color: white;
    text-decoration: none;
    font-size: 18px;
}

nav ul li a:hover, nav ul li a.active {
    text-decoration: underline;
}

/* Section Manage Jobs */
.manage-jobs {
    padding: 20px;
    max-width: 1000px;
    margin: 30px auto;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.manage-jobs h1 {
    text-align: center;
    color: #333;
}

.manage-jobs .btn {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 5px;
    display: inline-block;
    margin: 10px 0;
}

.manage-jobs .btn:hover {
    background-color: #45a049;
}

.manage-jobs .btn-danger {
    background-color: #e74c3c;
}

.manage-jobs .btn-danger:hover {
    background-color: #c0392b;
}

.manage-jobs table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.manage-jobs table th, .manage-jobs table td {
    padding: 12px;
    border: 1px solid #ddd;
    text-align: left;
}

.manage-jobs table th {
    background-color: #f4f4f4;
    color: #333;
}

.manage-jobs table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

.manage-jobs table tbody tr:hover {
    background-color: #f1f1f1;
}

/* Footer */
footer {
    background-color: #333;
    color: white;
    text-align: center;
    padding: 10px;
    position: fixed;
    width: 100%;
    bottom: 0;
}

footer p {
    margin: 0;
    font-size: 14px;
}

    </style>
</head>

<body>
    <!-- Navbar -->
    <nav>
        <ul>
            <li><a href="home_company.php">Home</a></li>
            <li><a href="manage_jobs.php" class="active">Manage Jobs</a></li>
            <li><a href="company_profile.php">Company Profile</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Section Manage Jobs -->
    <section class="manage-jobs">
        <h1>Manage Jobs</h1>

        <!-- ปุ่มเพิ่มตำแหน่งงานใหม่ -->
        <a href="add_job.php" class="btn">Add New Job</a>

       <!-- แสดงตารางตำแหน่งงาน -->
<table>
    <thead>
        <tr>
            <th>Job Title</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($job = mysqli_fetch_assoc($result_jobs)) : ?>
            <tr>
                <td><?php echo htmlspecialchars($job['job_title']); ?></td>
                <td><?php echo htmlspecialchars($job['job_status']); ?></td>
                <td>
                    <!-- ปุ่ม Edit -->
                    <a href="edit_job.php?job_id=<?php echo $job['job_id']; ?>" class="btn">Edit</a>
                    <!-- ปุ่ม Delete -->
                    <a href="manage_jobs.php?delete=<?php echo $job['job_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this job?');">Delete</a>
                    <!-- ปุ่ม Change Status -->
                    <a href="change_status.php?job_id=<?php echo $job['job_id']; ?>&status=<?php echo $job['job_status']; ?>" class="btn">
                        <?php echo ($job['job_status'] === 'open') ? 'Close' : 'Open'; ?>
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
</section>

<!-- Footer -->
<footer>
    <p>© <?php echo date('Y'); ?> Company Portal. All Rights Reserved.</p>
</footer>
</body>
</html>
