<?php
session_start();
include '../config/config.php';

// ตรวจสอบสิทธิ์ผู้ใช้งาน
if (!isset($_SESSION['role_account']) || !in_array($_SESSION['role_account'], ['admin', 'advisor', 'student'])) {
    $_SESSION['Messager'] = 'You are not authorized!';
    header("location: {$base_url}../index.php");
    exit();
}

// เชื่อมต่อฐานข้อมูล company_details
mysqli_select_db($conn, 'ciwe');

// ดึงข้อมูลจากตาราง company_details
$query = "SELECT * FROM company_details";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Details</title>
    <style>
         * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        img {
            max-width: 100px;
            height: auto;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Company Details</h1>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Company Name</th>
                    <th>Position Accepted</th>
                    <th>Number Received</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>City</th>
                    <th>Country</th>
                    <th>Website</th>
                    <th>Picture</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['work_id']) ?></td>
                    <td><?= htmlspecialchars($row['name_company']) ?></td>
                    <td><?= htmlspecialchars($row['position_accepted']) ?></td>
                    <td><?= htmlspecialchars($row['number_received']) ?></td>
                    <td><?= htmlspecialchars($row['email_company']) ?></td>
                    <td><?= htmlspecialchars($row['number_company']) ?></td>
                    <td><?= htmlspecialchars($row['city_company']) ?></td>
                    <td><?= htmlspecialchars($row['country_company']) ?></td>
                    <td><a href="<?= htmlspecialchars($row['website_company']) ?>" target="_blank">Visit</a></td>
                    <td>
                        <?php if (!empty($row['picture_FileData'])): ?>
                            <img src="data:<?= htmlspecialchars($row['picture_FileType']) ?>;base64,<?= base64_encode($row['picture_FileData']) ?>" alt="Company Picture">
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
