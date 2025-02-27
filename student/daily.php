<?php
session_start();
include '../config/config.php';

// ตรวจสอบสิทธิ์ผู้ใช้
if (!isset($_SESSION['role_account']) || $_SESSION['role_account'] !== 'student') {
    $_SESSION['Messager'] = 'You are not authorized!';
    header("location: {$base_url}../index.php");
    exit();
}

$id_account = $_SESSION['id_account'];

// ดึงข้อมูลบันทึกงานของผู้ใช้จากฐานข้อมูล
$query = mysqli_query($conn, "SELECT * FROM worklog WHERE id_account = '$id_account' ORDER BY work_date DESC");

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Work Log</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../image/apple-touch-icon.png">
    <link rel="android-chrome" href="../image/android-chrome-192x192.png">
    <link rel="android-chrome" href="../image/android-chrome-512x512.png">
    <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="../image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../image/favicon-16x16.png">
    <link rel="manifest" href="../image/site.webmanifest">
    <link rel="stylesheet" href="../css/style_daily.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Lato', sans-serif;
        }

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

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            /* กำหนดความกว้างของ Sidebar */
            height: 100%;
            background: #7a0f0f;
            padding: 20px;
            color: white;
            overflow-y: auto;
        }

        .container {
            margin-left: 270px;
            /* ขยับ Container ออกจาก Sidebar */
            padding: 20px;
            max-width: 80%;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
    <script>
        function checkTableData() {
            const table = document.getElementById('workTable').getElementsByTagName('tbody')[0];
            const addButton = document.getElementById('addButton');
            addButton.textContent = (table.rows.length === 0) ? 'เพิ่มข้อมูล' : 'เพิ่มแถวใหม่';
        }

        function addRow() {
            const table = document.getElementById('workTable').getElementsByTagName('tbody')[0];
            const row = table.insertRow();

            row.innerHTML = `
                <td><input type="date"></td>
                <td><textarea></textarea></td>
                <td><button onclick="saveRow(this)">บันทึก</button></td>
            `;
            checkTableData();
        }

        function saveRow(button) {
            const row = button.parentNode.parentNode;
            const id = row.getAttribute('data-id') || ''; // ตรวจสอบว่ามี id_worklog หรือไม่
            const dateInput = row.cells[0].querySelector('input').value;
            const details = row.cells[1].querySelector('textarea').value;

            if (!dateInput.trim() || !details.trim()) {
                alert('กรุณากรอกข้อมูลให้ครบ');
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "save_daily.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    row.cells[0].innerHTML = dateInput;
                    row.cells[1].innerHTML = details;
                    button.textContent = 'แก้ไข';
                    button.onclick = function() {
                        editRow(this);
                    };

                    if (!id) {
                        row.setAttribute("data-id", xhr.responseText); // ถ้าเป็นแถวใหม่ให้เพิ่ม data-id
                    }
                }
            };
            xhr.send(`id=${id}&date=${dateInput}&details=${details}`);
        }

        function editRow(button) {
            const row = button.parentNode.parentNode;
            const dateText = row.cells[0].innerText;
            const detailsText = row.cells[1].innerText;

            row.cells[0].innerHTML = `<input type="date" value="${dateText}">`;
            row.cells[1].innerHTML = `<textarea>${detailsText}</textarea>`;
            button.textContent = 'บันทึก';
            button.onclick = function() {
                saveRow(this);
            };
        }
    </script>
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
            <li><a href="<?php echo $base_url; ?>../index.php" onclick="return confirm('Are you sure you want to log out?');"> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="container">
        <h2>บันทึกการทำงานประจำวัน</h2>
        <table id="workTable">
            <thead>
                <tr>
                    <th>วัน/เดือน/ปี</th>
                    <th>รายละเอียด</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($query)): ?>
                    <tr data-id="<?= $row['id']; ?>">
                        <td><?= $row['work_date']; ?></td>
                        <td><?= $row['details']; ?></td>
                        <td><button onclick="editRow(this)">แก้ไข</button></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <button onclick="location.href='home_student.php'" style="margin-bottom: 10px; padding: 8px 16px; font-size: 16px;">🏠 กลับไปหน้าโฮม</button>
        <button id="addButton" onclick="addRow()">เพิ่มข้อมูล</button>
    </div>
</body>


</html>