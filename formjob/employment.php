<?php
session_start();
include '../config/config.php';

// ตรวจสอบสิทธิ์ผู้ใช้งาน
if (!isset($_SESSION['role_account']) || $_SESSION['role_account'] !== 'student') {
    $_SESSION['Messager'] = 'You are not authorized!';
    header("location: {$base_url}../index.php");
    exit();
}

// ดึง id_account จาก session
$id_account = $_SESSION['id_account'];

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
$query = mysqli_query($conn, "
    SELECT a.*, ps.* 
    FROM accounts a 
    LEFT JOIN profile_students ps ON a.id_account = ps.id_account 
    WHERE a.id_account = '{$id_account}'");

if (!$query) {
    die("Error: " . mysqli_error($conn));
}

$user_data = mysqli_fetch_assoc($query);
if (!$user_data) {
    die("No data found for this account.");
}

// ตรวจสอบการส่งฟอร์ม
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // กรองข้อมูลเพื่อป้องกัน SQL Injection
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $position_1 = mysqli_real_escape_string($conn, $_POST['position_1']);
    $position_2 = mysqli_real_escape_string($conn, $_POST['position_2']);
    $salary = mysqli_real_escape_string($conn, $_POST['salary']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $tel = mysqli_real_escape_string($conn, $_POST['tel']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $nationality = mysqli_real_escape_string($conn, $_POST['nationality']);
    $religion = mysqli_real_escape_string($conn, $_POST['religion']);
    $id_card = mysqli_real_escape_string($conn, $_POST['id_card']);
    $height = mysqli_real_escape_string($conn, $_POST['height']);
    $weight = mysqli_real_escape_string($conn, $_POST['weight']);
    $military_status = mysqli_real_escape_string($conn, $_POST['military_status']);
    $marital_status = mysqli_real_escape_string($conn, $_POST['marital_status']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $language_skills = mysqli_real_escape_string($conn, $_POST['language_skills']);

    // เตรียมคำสั่ง SQL
    $sql = "INSERT INTO job_applications (
        id_account, name, position_1, position_2, salary, address, tel, mobile, email, dob, age, 
        nationality, religion, id_card, height, weight, military_status, marital_status, gender, language_skills
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
    )";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ississssssisssiiisss",
        $id_account,
        $name,
        $position_1,
        $position_2,
        $salary,
        $address,
        $tel,
        $mobile,
        $email,
        $dob,
        $age,
        $nationality,
        $religion,
        $id_card,
        $height,
        $weight,
        $military_status,
        $marital_status,
        $gender,
        $language_skills
    );

    if ($stmt->execute()) {
        $_SESSION['Messager'] = 'Your job application has been submitted successfully!';
    } else {
        $_SESSION['Messager'] = 'Error submitting your application: ' . $stmt->error;
    }

    $stmt->close();
    header("Location: confirmation_page.php"); // แนะนำให้เปลี่ยนไปหน้าสำเร็จ
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application for Employment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }

        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
        }

        h2 {
            color: #333;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="number"] {
            width: calc(100% - 20px);
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="radio"] {
            margin-right: 5px;
        }

        button {
            background-color:rgb(4, 206, 21);
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color:rgb(0, 179, 6);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            text-align: left;
            padding: 10px;
            border: 1px solid #ccc;
        }

        th {
            background-color: #f4f4f4;
        }

        .address-container,
        .contact-container,
        .personal-info-container,
        .id-info-container,
        .physical-info-container {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }

        input[type="text"],
        input[type="number"],
        input[type="email"],
        input[type="date"] {
            flex: 1;
        }

        .gender,
        .marital-status,
        .military-status {
            margin-bottom: 15px;
        }

        .gender p,
        .marital-status p,
        .military-status p {
            font-weight: bold;
            margin-bottom: 8px;
        }

        .group-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 20px;
            text-align: center;

        }

        .group-container div {
            flex: 1;
        }

        .group-container p {
            font-weight: bold;
            margin-bottom: 8px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="radio"] {
            margin-right: 5px;
        }
        .add,.sub {
            margin: 10px;
        }
        
    </style>
</head>

<body>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Form Example</title>
        <link rel="stylesheet" href="styles.css">
    </head>

    <body>
        <form>
            <h2>Application for Employment</h2>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" placeholder="Enter your name">

            <div class="position-container">
                <label for="position1">Position Applied for:</label>
                <input type="text" id="position1" name="position1">
                <label for="position2">Position Applied for 2:</label>
                <input type="text" id="position2" name="position2">
                <label for="salary">Salary:</label>
                <input type="text" id="salary" name="salary" placeholder="Baht / month">
            </div>



            <h2>Personal Information</h2>
            <label for="present-address">Present Address:</label>
            <input type="text" id="present-address" name="present-address">

            <div class="address-container">
                <label for="moo">Moo:</label>
                <input type="text" id="moo" name="moo">

                <label for="road">Road:</label>
                <input type="text" id="road" name="road">
            </div>

            <div class="address-container">
                <label for="district">District:</label>
                <input type="text" id="district" name="district">

                <label for="amphur">Amphur:</label>
                <input type="text" id="amphur" name="amphur">
            </div>

            <div class="address-container">
                <label for="province">Province:</label>
                <input type="text" id="province" name="province">

                <label for="postcode">Post Code:</label>
                <input type="text" id="postcode" name="postcode">
            </div>

            <div class="contact-container">
                <label for="tel">Tel:</label>
                <input type="text" id="tel" name="tel">

                <label for="mobile">Mobile:</label>
                <input type="text" id="mobile" name="mobile">
            </div>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email">

            <div class="personal-info-container">
                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob">

                <label for="age">Age:</label>
                <input type="number" id="age" name="age">

                <label for="race">Race:</label>
                <input type="text" id="race" name="race">

                <label for="nationality">Nationality:</label>
                <input type="text" id="nationality" name="nationality">
            </div>

            <div class="id-info-container">
                <label for="id-card">Identity Card No:</label>
                <input type="text" id="id-card" name="id-card">

                <label for="id-expiry">Expiration Date:</label>
                <input type="date" id="id-expiry" name="id-expiry">
            </div>

            <div class="physical-info-container">
                <label for="height">Height:</label>
                <input type="number" id="height" name="height" placeholder="cm">

                <label for="weight">Weight:</label>
                <input type="number" id="weight" name="weight" placeholder="kgs">
            </div>

            <div class="group-container">
                <div class="military-status">
                    <p>Military Status:</p>
                    <label><input type="radio" name="military" value="exempted"> Exempted</label>
                    <label><input type="radio" name="military" value="served"> Served</label>
                    <label><input type="radio" name="military" value="not-yet-served"> Not Yet Served</label>
                </div>

                <div class="marital-status">
                    <p>Marital Status:</p>
                    <label><input type="radio" name="marital" value="single"> Single</label>
                    <label><input type="radio" name="marital" value="married"> Married</label>
                    <label><input type="radio" name="marital" value="widowed"> Widowed</label>
                    <label><input type="radio" name="marital" value="separated"> Separated</label>
                </div>

                <div class="gender">
                    <p>Gender:</p>
                    <label><input type="radio" name="sex" value="male"> Male</label>
                    <label><input type="radio" name="sex" value="female"> Female</label>
                </div>
            </div>


            <h2>Language Skills</h2>
            <table id="language-skills-table">
                <thead>
                    <tr>
                        <th>Language</th>
                        <th>Listening</th>
                        <th>Speaking</th>
                        <th>Writing</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" name="language1"></td>
                        <td>
                            <label><input type="radio" name="listening1" value="passable"> Passable</label>
                            <label><input type="radio" name="listening1" value="moderate"> Moderate</label>
                            <label><input type="radio" name="listening1" value="excellent"> Excellent</label>
                        </td>
                        <td>
                            <label><input type="radio" name="speaking1" value="passable"> Passable</label>
                            <label><input type="radio" name="speaking1" value="moderate"> Moderate</label>
                            <label><input type="radio" name="speaking1" value="excellent"> Excellent</label>
                        </td>
                        <td>
                            <label><input type="radio" name="writing1" value="passable"> Passable</label>
                            <label><input type="radio" name="writing1" value="moderate"> Moderate</label>
                            <label><input type="radio" name="writing1" value="excellent"> Excellent</label>
                        </td>
                    </tr>
                </tbody>
            </table>
            <button type="button" id="add-language-btn" class="add">Add Language</button>

            <script>
                let languageCount = 1;

                document.getElementById('add-language-btn').addEventListener('click', function() {
                    languageCount++;
                    const tableBody = document.querySelector('#language-skills-table tbody');
                    const newRow = document.createElement('tr');

                    newRow.innerHTML = `
            <td><input type="text" name="language${languageCount}"></td>
            <td>
                <label><input type="radio" name="listening${languageCount}" value="passable"> Passable</label>
                <label><input type="radio" name="listening${languageCount}" value="moderate"> Moderate</label>
                <label><input type="radio" name="listening${languageCount}" value="excellent"> Excellent</label>
            </td>
            <td>
                <label><input type="radio" name="speaking${languageCount}" value="passable"> Passable</label>
                <label><input type="radio" name="speaking${languageCount}" value="moderate"> Moderate</label>
                <label><input type="radio" name="speaking${languageCount}" value="excellent"> Excellent</label>
            </td>
            <td>
                <label><input type="radio" name="writing${languageCount}" value="passable"> Passable</label>
                <label><input type="radio" name="writing${languageCount}" value="moderate"> Moderate</label>
                <label><input type="radio" name="writing${languageCount}" value="excellent"> Excellent</label>
            </td>
        `;
                    tableBody.appendChild(newRow);
                });
            </script>


           <br> <button type="submit" class="sub">Submit</button>
        </form>
    </body>

    </html>