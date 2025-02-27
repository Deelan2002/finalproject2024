<?php
// เชื่อมต่อกับฐานข้อมูล
include('../config/config.php');

// หลังจากเลือก advisor และ student และกดปุ่ม 'Assign Advisor'
if (isset($_POST['student_id'], $_POST['advisor_id'])) {
    $student_id = $_POST['student_id'];
    $advisor_id = $_POST['advisor_id'];

    // เช็คว่ามีการจับคู่แชทนี้อยู่แล้วหรือไม่
    $check_query = "SELECT * FROM chat_rooms WHERE student_id = '$student_id' AND advisor_id = '$advisor_id'";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) == 0) {
        // ถ้ายังไม่มีก็ทำการสร้างห้องแชทใหม่
        $query = "INSERT INTO chat_rooms (student_id, advisor_id) VALUES ('$student_id', '$advisor_id')";
        if (mysqli_query($conn, $query)) {
            echo "Chat room created!";
        } else {
            echo "Error creating chat room: " . mysqli_error($conn);
        }
    } else {
        echo "Chat room already exists!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Advisor</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../image/apple-touch-icon.png">
    <link rel="android-chrome"  href="../image/android-chrome-192x192.png">
    <link rel="android-chrome"  href="../image/android-chrome-512x512.png">
    <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="../image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../image/favicon-16x16.png">
    <link rel="manifest" href="../image/site.webmanifest">
    <!-- เชื่อมโยงกับ CSS ของ Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fc;
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            background-color: #fff;
        }
        h2 {
            color: #4b8bf5;
        }
        .alert {
            margin-top: 20px;
            font-weight: bold;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow">
        <div class="card-body">
            <h2 class="text-center">Assign Advisor to Student</h2>

            <?php if (isset($message)): ?>
                <!-- แสดงผลข้อความเมื่อการบันทึกสำเร็จหรือเกิดข้อผิดพลาด -->
                <div class="alert <?php echo $message_class; ?> text-center">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form action="assign_advisor.php" method="POST">
                <div class="mb-3">
                    <label for="student" class="form-label">Select Student:</label>
                    <select name="student_id" class="form-select" required>
                        <option value="" disabled selected>Select a Student</option>
                        <?php
                        $query = "SELECT * FROM profile_students";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='".$row['id_student']."'>".$row['name_student']."</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="advisor" class="form-label">Select Advisor:</label>
                    <select name="advisor_id" class="form-select" required>
                        <option value="" disabled selected>Select an Advisor</option>
                        <?php
                        $query = "SELECT * FROM profile_advisor";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='".$row['id_advisor']."'>".$row['name_advisor']."</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Assign Advisor</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- เชื่อมโยงกับ JS ของ Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
