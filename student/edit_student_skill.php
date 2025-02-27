<?php
session_start();
include '../config/config.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบสิทธิ์ผู้ใช้งาน
if (!isset($_SESSION['role_account']) || $_SESSION['role_account'] !== 'student') {
    $_SESSION['Messager'] = 'You are not authorized!';
    header("location: {$base_url}../index.php");
    exit();
}

$id_account = $_SESSION['id_account']; // ดึง id_account

// ดึงข้อมูลจากฐานข้อมูลเพื่อแสดงในฟอร์ม
$sql = "SELECT * FROM student_profile WHERE student_id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $id_account);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    // ตรวจสอบว่ามีข้อมูลใน student_profile หรือไม่
    if (!$student) {
        $_SESSION['Messager'] = "You must fill out an application before you can edit it.";
        echo "<script>
                alert('You must fill out an application before you can edit it.');
                window.location.href = '../student/register_form_skill.php'; // ไปยังหน้าที่ให้กรอกใบสมัคร
              </script>";
        exit();
    }
}

// ดึงข้อมูลทักษะภาษา
$sql_lang = "SELECT * FROM language_skills WHERE student_id = ?";
$languages = [];
if ($stmt_lang = $conn->prepare($sql_lang)) {
    $stmt_lang->bind_param("i", $id_account);
    $stmt_lang->execute();
    $languages = $stmt_lang->get_result()->fetch_all(MYSQLI_ASSOC);
}

// อัปเดตข้อมูลเมื่อส่งฟอร์ม
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับข้อมูลที่แก้ไขจากฟอร์ม
    $year_level = $_POST['year_level'];
    $student_name = $_POST['student_name'];
    $email = $_POST['email'];
    $skill = $_POST['skill'];
    $university = $_POST['university'];
    $faculty = $_POST['faculty'];
    $department = $_POST['department'];
    $country = $_POST['country'];
    $address = $_POST['address'];
    $experience = $_POST['experience'];
    $contact_number = $_POST['contact_number'];
    $candidate_qualifications = $_POST['candidate_qualifications'];

    // อัปเดตข้อมูลใน student_profile
    $sql_update = "UPDATE student_profile SET 
                    year_level = ?, student_name = ?, email = ?, skill = ?, university = ?, 
                    faculty = ?, department = ?, country = ?, address = ?, experience = ?, 
                    contact_number = ?, candidate_qualifications = ? 
                    WHERE student_id = ?";
    if ($stmt_update = $conn->prepare($sql_update)) {
        $stmt_update->bind_param(
            "isssssssssssi",
            $year_level,
            $student_name,
            $email,
            $skill,
            $university,
            $faculty,
            $department,
            $country,
            $address,
            $experience,
            $contact_number,
            $candidate_qualifications,
            $id_account
        );
        $stmt_update->execute();
    }

    // อัปเดตข้อมูลทักษะภาษา
    if (!empty($_POST['language'])) {
        // ลบข้อมูลภาษาเดิมก่อน
        $conn->query("DELETE FROM language_skills WHERE student_id = $id_account");

        $languages = $_POST['language'];
        $listening_levels = $_POST['listening_level'];
        $speaking_levels = $_POST['speaking_level'];
        $writing_levels = $_POST['writing_level'];

        foreach ($languages as $index => $language) {
            $listening = $listening_levels[$index];
            $speaking = $speaking_levels[$index];
            $writing = $writing_levels[$index];

            $sql_lang_update = "INSERT INTO language_skills (student_id, language, listening_level, speaking_level, writing_level)
                                VALUES (?, ?, ?, ?, ?)";
            if ($stmt_lang_update = $conn->prepare($sql_lang_update)) {
                $stmt_lang_update->bind_param("issss", $id_account, $language, $listening, $speaking, $writing);
                $stmt_lang_update->execute();
            }
        }
    }

    $_SESSION['Messager'] = "Data has been updated successfully!";
    header("Location: ../student/success.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Student Profile</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../image/apple-touch-icon.png">
    <link rel="android-chrome" href="../image/android-chrome-192x192.png">
    <link rel="android-chrome" href="../image/android-chrome-512x512.png">
    <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="../image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../image/favicon-16x16.png">
    <link rel="manifest" href="../image/site.webmanifest">
    <link rel="stylesheet" href="../css/style_register_form_skill.css">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
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


        .form-container {
            width: 80%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        label {
            font-weight: 600;
        }

        input[type="text"],
        input[type="number"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        textarea {
            resize: none;
            height: 80px;
        }

        .language-skills {
            grid-column: span 2;
        }

        .language-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            align-items: center;
        }

        .buttons {
            grid-column: span 2;
            text-align: center;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
        }

        .cancel {
            background-color: #d9534f;
        }

        .confirm {
            background-color: #5cb85c;
        }

        /* จัดตำแหน่ง header ของ labels */
        .language-header {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            /* จัดเป็น 4 คอลัมน์ */
            gap: 10px;
            /* ระยะห่างระหว่างคอลัมน์ */
            text-align: center;
            margin-bottom: 10px;
        }

        /* การตกแต่ง label */
        .skill {
            font-weight: bold;
            text-align: center;
        }

        /* จัดเรียง input/select */
        .language-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            /* 4 คอลัมน์เท่า ๆ กัน */
            gap: 10px;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <a class="navbar-brand" href="../student/home_student.php">
            <img src="../image/logo-pxu.png" alt="SDIC Logo" width="40" height="40"> SDIC
        </a>
        <h4>Student Panel</h4>
        <span class="navbar-text ms-3">
            Welcome, <?php echo $_SESSION['username_account']; ?>
        </span>
        <ul>
            <li> <a class="nav-link" href="<?php echo $base_url; ?>/student/home_student.php">Home</a></li>
            <li><a class="nav-link" href="<?php echo $base_url; ?>/student/profile_student.php">Profile</a></li>
            <li><a class="nav-link" href="<?php echo $base_url; ?>/student/register_form_skill.php">International Cooperative Education</a></li>
            <li><a class="nav-link" href="<?php echo $base_url; ?>/student/student_view_advisor.php">Advisor</a></li>
            <li><a class="nav-link" href="<?php echo $base_url; ?>/student/job_company.php">Company</a></li>
            <li><a class="nav-link" href="<?php echo $base_url; ?>/student/daily.php">Daily</a></li>
            <li>
                <a class="nav-link" href="<?php echo $base_url; ?>/realtime_chat/choose_receiver.php?receiver_id=<?php echo isset($_GET['receiver_id']) ? htmlspecialchars($_GET['receiver_id']) : 0; ?>">
                    Chat
                </a>
            </li>
            <li> <a class="nav-link" href="<?php echo $base_url; ?>/student/student_reset_password.php">Reset Password</a></li>
            <li><a href=" <?php echo $base_url; ?>../index.php" onclick="return confirm('Are you sure you want to log out?');"> Logout</a></li>
        </ul>
    </div>
    <div class="form-container">
        <h2>Edit Your Profile</h2>
        <form method="POST" action="edit_student_skill.php">

            <!-- Student Information -->
            <label for="year_level">Year Level</label>
            <input type="number" name="year_level" value="<?php echo isset($student['year_level']) ? $student['year_level'] : ''; ?>" required>

            <label for="student_name">Student Name</label>
            <input type="text" name="student_name" value="<?php echo isset($student['student_name']) ? $student['student_name'] : ''; ?>" required>

            <label for="candidate_qualifications">Candidate Qualifications</label>
            <textarea name="candidate_qualifications"><?php echo isset($student['candidate_qualifications']) ? $student['candidate_qualifications'] : ''; ?></textarea>

            <!-- Language Skills -->
            <div class="language-skills">
                <h3>Language Skills</h3>
                <div id="language-container">
                    <div class="language-grid">
                        <label>Language</label>
                        <label>Listening</label>
                        <label>Speaking</label>
                        <label>Writing</label>

                        <?php foreach ($languages as $lang): ?>
                            <input type="text" name="language[]" value="<?php echo isset($lang['language']) ? $lang['language'] : ''; ?>" required>
                            <select name="listening_level[]">
                                <option value="Basic" <?php echo ($lang['listening_level'] == 'Basic') ? 'selected' : ''; ?>>Basic</option>
                                <option value="Intermediate" <?php echo ($lang['listening_level'] == 'Intermediate') ? 'selected' : ''; ?>>Intermediate</option>
                                <option value="Advanced" <?php echo ($lang['listening_level'] == 'Advanced') ? 'selected' : ''; ?>>Advanced</option>
                            </select>
                            <select name="speaking_level[]">
                                <option value="Basic" <?php echo ($lang['speaking_level'] == 'Basic') ? 'selected' : ''; ?>>Basic</option>
                                <option value="Intermediate" <?php echo ($lang['speaking_level'] == 'Intermediate') ? 'selected' : ''; ?>>Intermediate</option>
                                <option value="Advanced" <?php echo ($lang['speaking_level'] == 'Advanced') ? 'selected' : ''; ?>>Advanced</option>
                            </select>
                            <select name="writing_level[]">
                                <option value="Basic" <?php echo ($lang['writing_level'] == 'Basic') ? 'selected' : ''; ?>>Basic</option>
                                <option value="Intermediate" <?php echo ($lang['writing_level'] == 'Intermediate') ? 'selected' : ''; ?>>Intermediate</option>
                                <option value="Advanced" <?php echo ($lang['writing_level'] == 'Advanced') ? 'selected' : ''; ?>>Advanced</option>
                            </select>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- ปุ่ม Add Skill -->
                <button type="button" id="add-skill-btn" class="btn btn-primary mt-2">Add Skill</button>
            </div>




            <!-- Skills -->
            <label for="skill">Skill</label>
            <textarea name="skill"><?php echo isset($student['skill']) ? $student['skill'] : ''; ?></textarea>

            <!-- Contact Details -->
            <label for="email">Email</label>
            <input type="email" name="email" value="<?php echo isset($student['email']) ? $student['email'] : ''; ?>" required>

            <label for="university">University</label>
            <input type="text" name="university" value="<?php echo isset($student['university']) ? $student['university'] : ''; ?>" required>

            <label for="faculty">Faculty</label>
            <input type="text" name="faculty" value="<?php echo isset($student['faculty']) ? $student['faculty'] : ''; ?>" required>

            <label for="department">Department</label>
            <input type="text" name="department" value="<?php echo isset($student['department']) ? $student['department'] : ''; ?>" required>

            <label for="country">Country</label>
            <input type="text" name="country" value="<?php echo isset($student['country']) ? $student['country'] : ''; ?>" required>

            <label for="address">Address</label>
            <textarea name="address"><?php echo isset($student['address']) ? $student['address'] : ''; ?></textarea>

            <label for="experience">Experience</label>
            <textarea name="experience"><?php echo isset($student['experience']) ? $student['experience'] : ''; ?></textarea>

            <label for="contact_number">Contact Number</label>
            <input type="text" name="contact_number" value="<?php echo isset($student['contact_number']) ? $student['contact_number'] : ''; ?>">

            <!-- Buttons -->
            <!-- ปุ่ม Confirm และ Cancel -->
            <div class="buttons">
                <button type="submit" class="confirm">Confirm</button>
                <a href="../student/home_student.php" class="cancel">Cancel</a>
            </div>

        </form>
    </div>

    <script>
        document.getElementById('add-skill-btn').addEventListener('click', function() {
            let container = document.getElementById('language-container');

            let newSkill = document.createElement('div');
            newSkill.classList.add('language-grid');

            newSkill.innerHTML = `
        <input type="text" name="language[]" placeholder="Enter language" required>
        <select name="listening_level[]">
            <option value="Basic">Basic</option>
            <option value="Intermediate">Intermediate</option>
            <option value="Advanced">Advanced</option>
        </select>
        <select name="speaking_level[]">
            <option value="Basic">Basic</option>
            <option value="Intermediate">Intermediate</option>
            <option value="Advanced">Advanced</option>
        </select>
        <select name="writing_level[]">
            <option value="Basic">Basic</option>
            <option value="Intermediate">Intermediate</option>
            <option value="Advanced">Advanced</option>
        </select>
    `;

            container.appendChild(newSkill);
        });
    </script>

</body>

</html>