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
    <link rel="stylesheet" href="../css/style_company_details.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<style>
   
</style>


<body>
    


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
        </form>
    </div>
</body>

</html>