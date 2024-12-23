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
    <link rel="stylesheet" href="../css/style_register_form_skill.css">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Lato', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
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
            <div class="buttons">
                <button type="submit" class="confirm">Confirm</button>
                <a href="../student/home_student.php" class="btn btn-secondary cancel">Cancel</a>
            </div>

        </form>
    </div>
</body>

</html>