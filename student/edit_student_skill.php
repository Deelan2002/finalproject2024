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
    header("Location: success.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student Profile</title>
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
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Your Profile</h2>
        <form method="POST" action="edit_student_skill.php">

            <!-- Student Information -->
            <label for="year_level">Year Level</label>
            <input type="number" name="year_level" value="<?php echo $student['year_level']; ?>" required>

            <label for="student_name">Student Name</label>
            <input type="text" name="student_name" value="<?php echo $student['student_name']; ?>" required>

            <label for="candidate_qualifications">Candidate Qualifications</label>
            <textarea name="candidate_qualifications"><?php echo $student['candidate_qualifications']; ?></textarea>

            <!-- Language Skills -->
            <div class="language-skills">
                <h3>Language Skills</h3>
                <div class="language-grid">
                    <label>Language</label>
                    <label>Listening</label>
                    <label>Speaking</label>
                    <label>Writing</label>

                    <?php foreach ($languages as $lang): ?>
                        <input type="text" name="language[]" value="<?php echo $lang['language']; ?>">
                        <select name="listening_level[]">
                            <option value="passable" <?php echo $lang['listening_level'] == 'passable' ? 'selected' : ''; ?>>Passable</option>
                            <option value="moderate" <?php echo $lang['listening_level'] == 'moderate' ? 'selected' : ''; ?>>Moderate</option>
                            <option value="excellent" <?php echo $lang['listening_level'] == 'excellent' ? 'selected' : ''; ?>>Excellent</option>
                        </select>

                        <select name="speaking_level[]">
                            <option value="passable" <?php echo $lang['speaking_level'] == 'passable' ? 'selected' : ''; ?>>Passable</option>
                            <option value="moderate" <?php echo $lang['speaking_level'] == 'moderate' ? 'selected' : ''; ?>>Moderate</option>
                            <option value="excellent" <?php echo $lang['speaking_level'] == 'excellent' ? 'selected' : ''; ?>>Excellent</option>
                        </select>

                        <select name="writing_level[]">
                            <option value="passable" <?php echo $lang['writing_level'] == 'passable' ? 'selected' : ''; ?>>Passable</option>
                            <option value="moderate" <?php echo $lang['writing_level'] == 'moderate' ? 'selected' : ''; ?>>Moderate</option>
                            <option value="excellent" <?php echo $lang['writing_level'] == 'excellent' ? 'selected' : ''; ?>>Excellent</option>
                        </select>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Skills -->
            <label for="skill">Skill</label>
            <textarea name="skill"><?php echo $student['skill']; ?></textarea>

            <!-- Contact Details -->
            <label for="email">Email</label>
            <input type="email" name="email" value="<?php echo $student['email']; ?>" required>

            <label for="university">University</label>
            <input type="text" name="university" value="<?php echo $student['university']; ?>" required>

            <label for="faculty">Faculty</label>
            <input type="text" name="faculty" value="<?php echo $student['faculty']; ?>" required>

            <label for="department">Department</label>
            <input type="text" name="department" value="<?php echo $student['department']; ?>" required>

            <label for="country">Country</label>
            <input type="text" name="country" value="<?php echo $student['country']; ?>" required>

            <label for="address">Address</label>
            <textarea name="address"><?php echo $student['address']; ?></textarea>

            <label for="experience">Experience</label>
            <textarea name="experience"><?php echo $student['experience']; ?></textarea>

            <label for="contact_number">Contact Number</label>
            <input type="text" name="contact_number" value="<?php echo $student['contact_number']; ?>">

            <!-- Buttons -->
            <div class="buttons">
                <button type="submit" class="confirm">Update</button>
                <a href="../student/home_student.php" class="btn btn-secondary cancel">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
