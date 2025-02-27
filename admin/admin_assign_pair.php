<?php
include('../config/config.php'); // แก้ไข path ถ้าจำเป็น
session_start();

// ตรวจสอบสิทธิ์แอดมิน
if ($_SESSION['role_account'] !== 'admin') {
    die("Access denied!");
}

// เพิ่มคู่แชท
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $advisor_id = $_POST['advisor_id'];

    if (!empty($student_id) && !empty($advisor_id)) {
        $stmt = $conn->prepare("INSERT INTO chat_room (id_student, id_advisor) VALUES (?, ?)");
        $stmt->bind_param("ii", $student_id, $advisor_id);

        if ($stmt->execute()) {
            $success = "Pair assigned successfully!";
        } else {
            $error = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "Please select both Student and Advisor.";
    }
}

// ดึงรายชื่อนักเรียน
$students = $conn->query("SELECT id_student, name_student FROM profile_students");

// ดึงรายชื่อที่ปรึกษา
$advisors = $conn->query("SELECT id_advisor, name_advisor FROM profile_advisor");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Assign Chat Pair</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../image/apple-touch-icon.png">
    <link rel="android-chrome"  href="../image/android-chrome-192x192.png">
    <link rel="android-chrome"  href="../image/android-chrome-512x512.png">
    <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="../image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../image/favicon-16x16.png">
    <link rel="manifest" href="../image/site.webmanifest">
</head>
<body>
    <h1>Assign Chat Pair</h1>
    <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST">
        <label for="student_id">Select Student:</label>
        <select name="student_id" id="student_id">
            <option value="">-- Select Student --</option>
            <?php while ($student = $students->fetch_assoc()): ?>
                <option value="<?= $student['id_student'] ?>"><?= $student['name_student'] ?></option>
            <?php endwhile; ?>
        </select>

        <label for="advisor_id">Select Advisor:</label>
        <select name="advisor_id" id="advisor_id">
            <option value="">-- Select Advisor --</option>
            <?php while ($advisor = $advisors->fetch_assoc()): ?>
                <option value="<?= $advisor['id_advisor'] ?>"><?= $advisor['name_advisor'] ?></option>
            <?php endwhile; ?>
        </select>

        <button type="submit">Assign Pair</button>
    </form>
</body>
</html>
