<?php
session_start();
include '../config/config.php';

// ตรวจสอบสิทธิ์ผู้ใช้งาน
if (!isset($_SESSION['role_account']) || !in_array($_SESSION['role_account'], ['admin', 'advisor'])) {
    $_SESSION['Messager'] = 'You are not authorized!';
    header("location: {$base_url}../index.php");
    exit();
}

// ดึงข้อมูลผู้ใช้งานจากฐานข้อมูล
$id_account = $_SESSION['id_account'];
$query = mysqli_query($conn, "SELECT * FROM accounts WHERE id_account='{$id_account}'");
$user_account = mysqli_fetch_assoc($query);

// ตรวจสอบการส่งข้อมูล
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name_company = mysqli_real_escape_string($conn, $_POST['name_company']);
    $position_accepted = mysqli_real_escape_string($conn, $_POST['position_accepted']);
    $number_received = intval($_POST['number_received']);
    $email_company = mysqli_real_escape_string($conn, $_POST['email_company']);
    $number_company = mysqli_real_escape_string($conn, $_POST['number_company']);
    $city_company = mysqli_real_escape_string($conn, $_POST['city_company']);
    $country_company = mysqli_real_escape_string($conn, $_POST['country_company']);
    $address_company = mysqli_real_escape_string($conn, $_POST['address_company']);
    $website_company = mysqli_real_escape_string($conn, $_POST['website_company']);
    $accommodation = mysqli_real_escape_string($conn, $_POST['accommodation']);
    $shuttle_bus = mysqli_real_escape_string($conn, $_POST['shuttle_bus']);
    $welfare = mysqli_real_escape_string($conn, $_POST['welfare']);

    // Handle file upload
    if (isset($_FILES['picture_file']) && $_FILES['picture_file']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        $picture_file_name = basename($_FILES['picture_file']['name']);
        $picture_file_path = $upload_dir . $picture_file_name;
        $picture_file_type = mime_content_type($_FILES['picture_file']['tmp_name']);

        // ตรวจสอบประเภทไฟล์
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($picture_file_type, $allowed_types)) {
            echo "<script>alert('File type not allowed!'); window.history.back();</script>";
            exit();
        }

        // ย้ายไฟล์ไปยังโฟลเดอร์อัปโหลด
        if (!move_uploaded_file($_FILES['picture_file']['tmp_name'], $picture_file_path)) {
            echo "<script>alert('Failed to upload file!'); window.history.back();</script>";
            exit();
        }
    } else {
        $picture_file_name = null;
        $picture_file_type = null;
    }

    // เพิ่มข้อมูลในฐานข้อมูล
    $stmt = $conn->prepare("INSERT INTO company_details (name_company, position_accepted, number_received, email_company, number_company, city_company, country_company, address_company, website_company, accommodation, shuttle_bus, welfare, picture_FileName, picture_FileType, id_account) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisissssssssss", $name_company, $position_accepted, $number_received, $email_company, $number_company, $city_company, $country_company, $address_company, $website_company, $accommodation, $shuttle_bus, $welfare, $picture_file_name, $picture_file_type, $id_account);

    if ($stmt->execute()) {
        // ตรวจสอบ role เพื่อเปลี่ยนหน้า
        if ($_SESSION['role_account'] === 'admin') {
            echo "<script>alert('Record added successfully'); window.location.href='../admin/home_admin.php';</script>";
        } elseif ($_SESSION['role_account'] === 'advisor') {
            echo "<script>alert('Record added successfully'); window.location.href='../advisor/home_advisor.php';</script>";
        }
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Form</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../image/apple-touch-icon.png">
    <link rel="android-chrome" href="../image/android-chrome-192x192.png">
    <link rel="android-chrome" href="../image/android-chrome-512x512.png">
    <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="../image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../image/favicon-16x16.png">
    <link rel="manifest" href="../image/site.webmanifest">
    <link rel="stylesheet" href="../css/style_company_details.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
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

        /* Container */
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
        
</style>

<body>

     <!-- Sidebar -->
     <div class="sidebar">
        <h2>Admin Panel</h2>
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
        <h1>Company Form</h1>
        <form method="POST" enctype="multipart/form-data">
            <label for="name_company">Company Name</label>
            <input type="text" name="name_company" id="name_company" required>

            <label for="position_accepted">Position Accepted</label>
            <input type="text" name="position_accepted" id="position_accepted" required>

            <label for="number_received">Number Received</label>
            <input type="number" name="number_received" id="number_received">

            <label for="email_company">Email</label>
            <input type="email" name="email_company" id="email_company" required>

            <label for="number_company">Phone Number</label>
            <input type="text" name="number_company" id="number_company">

            <label for="city_company">City</label>
            <input type="text" name="city_company" id="city_company">

            <label for="country_company">Country</label>
            <input type="text" name="country_company" id="country_company">

            <label for="address_company">Address</label>
            <textarea name="address_company" id="address_company"></textarea>

            <label for="website_company">Website</label>
            <input type="url" name="website_company" id="website_company">

            <label for="accommodation">Accommodation</label>
            <textarea name="accommodation" id="accommodation"></textarea>

            <label for="shuttle_bus">Shuttle Bus</label>
            <textarea name="shuttle_bus" id="shuttle_bus"></textarea>

            <label for="welfare">Welfare</label>
            <textarea name="welfare" id="welfare"></textarea>

            <label for="picture_file">Upload Picture</label>
            <input type="file" name="picture_file" id="picture_file" accept="image/*">

            <button type="submit">Submit</button>
            <div>
                <?php if ($_SESSION['role_account'] === 'admin') : ?>
                    <a href="../admin/home_admin.php" class="btn btn-secondary">Back to Admin</a>
                <?php elseif ($_SESSION['role_account'] === 'advisor') : ?>
                    <a href="../advisor/home_advisor.php" class="btn btn-secondary">Back to Advisor</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</body>

</html>