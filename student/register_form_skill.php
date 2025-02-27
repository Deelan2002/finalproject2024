<?php
session_start();
include '../config/config.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบสิทธิ์ผู้ใช้งาน
if (!isset($_SESSION['role_account']) || $_SESSION['role_account'] !== 'student') {
    $_SESSION['Messager'] = 'You are not authorized!';
    header("location: {$base_url}../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ตรวจสอบ id_account
    if (!isset($_SESSION['id_account'])) {
        $_SESSION['Messager'] = 'User account error!';
        header("Location: ../index.php");
        exit();
    }
    $id_account = $_SESSION['id_account'];

    // ตรวจสอบว่าเคยกรอกข้อมูลไปแล้วหรือไม่
    $check_sql = "SELECT * FROM student_profile WHERE id_account = ?";
    if ($stmt_check = $conn->prepare($check_sql)) {
        $stmt_check->bind_param("i", $id_account);
        $stmt_check->execute();
        $result = $stmt_check->get_result();
        if ($result->num_rows > 0) {
            $_SESSION['Messager'] = "You have already registered. Please edit your information instead.";
            header("Location: ../student/edit_student_skill.php"); // ไปยังหน้าที่ให้แก้ไขข้อมูล
            exit();
        }
    }

    // กรองข้อมูลจากฟอร์ม
    $year_level = trim($_POST['year_level']);
    $student_name = htmlspecialchars(trim($_POST['student_name']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $skill = htmlspecialchars(trim($_POST['skill']));
    $university = htmlspecialchars(trim($_POST['university']));
    $faculty = htmlspecialchars(trim($_POST['faculty']));
    $department = htmlspecialchars(trim($_POST['department']));
    $country = htmlspecialchars(trim($_POST['country']));
    $address = htmlspecialchars(trim($_POST['address']));
    $experience = htmlspecialchars(trim($_POST['experience']));
    $contact_number = htmlspecialchars(trim($_POST['contact_number']));
    $candidate_qualifications = htmlspecialchars(trim($_POST['candidate_qualifications']));

    // ตรวจสอบค่าว่างในช่องที่จำเป็น
    if (empty($year_level) || empty($student_name) || empty($email)) {
        $_SESSION['Messager'] = "Please fill in all required fields.";
        header("Location: ../student/register_form_skill.php");
        exit();
    }

    // SQL การบันทึกข้อมูล
    $sql = "INSERT INTO student_profile (
                student_id, id_account, year_level, student_name, email, skill, 
                university, faculty, department, country, address, experience, 
                contact_number, candidate_qualifications
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // bind_param
        $stmt->bind_param(
            "iiisssssssssss",
            $id_account,
            $id_account,
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
            $candidate_qualifications
        );

        if ($stmt->execute()) {
            // บันทึกข้อมูลทักษะภาษา
            if (!empty($_POST['language'])) {
                $languages = $_POST['language'];
                $listening_levels = $_POST['listening_level'];
                $speaking_levels = $_POST['speaking_level'];
                $writing_levels = $_POST['writing_level'];

                foreach ($languages as $index => $language) {
                    $language = htmlspecialchars(trim($language));
                    $listening = htmlspecialchars($listening_levels[$index]);
                    $speaking = htmlspecialchars($speaking_levels[$index]);
                    $writing = htmlspecialchars($writing_levels[$index]);

                    if (!empty($language)) { // ตรวจสอบว่าช่อง Language ไม่ว่าง
                        $sql_lang = "INSERT INTO language_skills (student_id, language, listening_level, speaking_level, writing_level)
                                     VALUES (?, ?, ?, ?, ?)";
                        if ($stmt_lang = $conn->prepare($sql_lang)) {
                            $stmt_lang->bind_param("issss", $id_account, $language, $listening, $speaking, $writing);
                            $stmt_lang->execute();
                        }
                    }
                }
            }

            // ข้อความสำเร็จ
            $_SESSION['Messager'] = "Data has been saved successfully!";
            header("Location: ../student/success.php");
            exit();
        } else {
            $_SESSION['Messager'] = "Error: " . $stmt->error;
        }
    } else {
        $_SESSION['Messager'] = "Database error: " . $conn->error;
    }
}
?>



<!-- HTML Form -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">


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

        /* สไตล์ปุ่มลอย */
        .floating-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: rgb(255, 181, 34);
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease-in-out;
        }

        .floating-btn:hover {
            background-color: rgb(230, 28, 25);
            transform: scale(1.1);
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <a class="logo-container" href="../student/home_student.php">
            <img src="../image/logo-pxu.png" alt="SDIC Logo" width="40" height="40">
            <span>SDIC</span>
        </a>
        <h4>Student Panel</h4>
        <span class="navbar-text ms-3">
            Welcome, <?php echo $_SESSION['username_account']; ?>
        </span>
        <ul>
            <li><a class="nav-link" href="<?php echo $base_url; ?>/student/home_student.php">Home</a></li>
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
            <li><a class="nav-link" href="<?php echo $base_url; ?>/student/student_reset_password.php">Reset Password</a></li>
        <li><a href=" <?php echo $base_url; ?>../index.php" onclick="return confirm('Are you sure you want to log out?');">Logout</a></li>
        </ul>
    </div>


    <div class="form-container">
        <h2>Register for an International Cooperative Education Program</h2>
        <form action="../student/register_form_skill.php" method="POST">

            <!-- Student Information -->
            <label for="year_level">Year Level</label>
            <input type="number" name="year_level" id="year_level" required>

            <label for="student_name">Student Name</label>
            <input type="text" name="student_name" id="student_name" required>

            <label for="candidate_qualifications">Candidate Qualifications</label>
            <textarea name="candidate_qualifications" id="candidate_qualifications"></textarea>

            <!-- Language Skills -->
            <div class="language-skills">
                <h3>Language Skills</h3>
                <div class="language-header">
                    <label for="language" class="skill">Language</label>
                    <label for="listening" class="skill">Listening</label>
                    <label for="speaking" class="skill">Speaking</label>
                    <label for="writing" class="skill">Writing</label>
                </div>
                <div id="language-container">
                    <div class="language-grid">
                        <input type="text" name="language[]" placeholder="Language" required>
                        <select name="listening_level[]">
                            <option value="passable">Passable</option>
                            <option value="moderate">Moderate</option>
                            <option value="excellent">Excellent</option>
                        </select>
                        <select name="speaking_level[]">
                            <option value="passable">Passable</option>
                            <option value="moderate">Moderate</option>
                            <option value="excellent">Excellent</option>
                        </select>
                        <select name="writing_level[]">
                            <option value="passable">Passable</option>
                            <option value="moderate">Moderate</option>
                            <option value="excellent">Excellent</option>
                        </select>
                    </div>
                </div>
                <!-- ปุ่มเพิ่มช่อง -->
                <button type="button" onclick="addLanguageSkill()" class="btn btn-success mt-2">Add Skill</button>
            </div>

            <script>
                function addLanguageSkill() {
                    const container = document.getElementById('language-container');
                    const newSkill = document.createElement('div');
                    newSkill.className = 'language-grid mt-2';

                    newSkill.innerHTML = `
            <input type="text" name="language[]" placeholder="Language" required>
            <select name="listening_level[]">
                <option value="passable">Passable</option>
                <option value="moderate">Moderate</option>
                <option value="excellent">Excellent</option>
            </select>
            <select name="speaking_level[]">
                <option value="passable">Passable</option>
                <option value="moderate">Moderate</option>
                <option value="excellent">Excellent</option>
            </select>
            <select name="writing_level[]">
                <option value="passable">Passable</option>
                <option value="moderate">Moderate</option>
                <option value="excellent">Excellent</option>
            </select>
        `;

                    container.appendChild(newSkill);
                }
            </script>


            <!-- Skills -->
            <label for="skill">Skill</label>
            <textarea name="skill" id="skill"></textarea>

            <!-- Contact Details -->
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="university">University</label>
            <input type="text" name="university" id="university" required>

            <label for="faculty">Faculty</label>
            <input type="text" name="faculty" id="faculty" required>

            <label for="department">Department</label>
            <input type="text" name="department" id="department" required>

            <label for="country">Country</label>
            <input type="text" name="country" id="country" required>

            <label for="address">Address</label>
            <textarea name="address" id="address"></textarea>

            <label for="experience">Experience</label>
            <textarea name="experience" id="experience"></textarea>

            <label for="contact_number">Contact Number</label>
            <input type="text" name="contact_number" id="contact_number">

            <!-- Buttons -->
            <!-- ปุ่ม Confirm และ Cancel -->
            <div class="buttons">
                <button type="submit" class="confirm">Confirm</button>
                <a href="../student/home_student.php" class="cancel">Cancel</a>
            </div>


        </form>
    </div>
    <!-- ปุ่มลอย -->
    <button class="floating-btn" onclick="addSkill()">
        <i class="fas fa-pencil-alt"></i>
    </button>

    <script>
        function addSkill() {
            alert("Do you want to edit it?");
            window.location.href = "../student/edit_student_skill.php";
            // สามารถเปลี่ยนเป็นเปิดฟอร์มหรือ redirect ไปหน้าที่ต้องการได้
        }
    </script>
</body>

</html>