<?php
session_start();
include '../config/config.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่า id_account จาก session
    $id_account = $_SESSION['id_account'];
    
    // รับค่าจากฟอร์ม และทำความสะอาดข้อมูล
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id'] ?? '');
    $name_student = mysqli_real_escape_string($conn, $_POST['name_student'] ?? '');
    $nickname_student = mysqli_real_escape_string($conn, $_POST['nickname_student'] ?? '');
    $age_student = mysqli_real_escape_string($conn, $_POST['age_student'] ?? '');
    $birthday_student = mysqli_real_escape_string($conn, $_POST['birthday_student'] ?? '');
    $gender_student = mysqli_real_escape_string($conn, $_POST['gender_student'] ?? '');
    $email_student = mysqli_real_escape_string($conn, $_POST['email_student'] ?? '');
    $number_student = mysqli_real_escape_string($conn, $_POST['number_student'] ?? '');
    $university_student = mysqli_real_escape_string($conn, $_POST['university_student'] ?? '');
    $faculty_student = mysqli_real_escape_string($conn, $_POST['faculty_student'] ?? '');
    $department_student = mysqli_real_escape_string($conn, $_POST['department_student'] ?? '');
    $country_student = mysqli_real_escape_string($conn, $_POST['country_student'] ?? '');
    $address_student = mysqli_real_escape_string($conn, $_POST['address_student'] ?? '');

    // ตรวจสอบและอัปโหลดไฟล์ (เฉพาะที่มีการอัปโหลดใหม่)
    $profile_image = '';
    if (!empty($_FILES['profile_image']['name'])) {
        $profile_image = basename($_FILES['profile_image']['name']);
        move_uploaded_file($_FILES['profile_image']['tmp_name'], "../uploads/$profile_image");
    }

    $passport_image = '';
    if (!empty($_FILES['passport_image']['name'])) {
        $passport_image = basename($_FILES['passport_image']['name']);
        move_uploaded_file($_FILES['passport_image']['tmp_name'], "../uploads/$passport_image");
    }

    $visa_image = '';
    if (!empty($_FILES['visa_image']['name'])) {
        $visa_image = basename($_FILES['visa_image']['name']);
        move_uploaded_file($_FILES['visa_image']['tmp_name'], "../uploads/$visa_image");
    }

    $evisa_image = '';
    if (!empty($_FILES['evisa_image']['name'])) {
        $evisa_image = basename($_FILES['evisa_image']['name']);
        move_uploaded_file($_FILES['evisa_image']['tmp_name'], "../uploads/$evisa_image");
    }

    // ตรวจสอบว่ามีข้อมูลอยู่แล้วหรือไม่
    $check_sql = "SELECT id_account FROM profile_students WHERE id_account = '$id_account'";
    $result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($result) > 0) {
        // ถ้ามีข้อมูลอยู่แล้ว → UPDATE
        $sql = "
            UPDATE profile_students SET 
                student_id = IF('$student_id' != '', '$student_id', student_id),
                name_student = IF('$name_student' != '', '$name_student', name_student),
                nickname_student = IF('$nickname_student' != '', '$nickname_student', nickname_student),
                age_student = IF('$age_student' != '', '$age_student', age_student),
                birthday_student = IF('$birthday_student' != '', '$birthday_student', birthday_student),
                gender_student = IF('$gender_student' != '', '$gender_student', gender_student),
                email_student = IF('$email_student' != '', '$email_student', email_student),
                number_student = IF('$number_student' != '', '$number_student', number_student),
                university_student = IF('$university_student' != '', '$university_student', university_student),
                faculty_student = IF('$faculty_student' != '', '$faculty_student', faculty_student),
                department_student = IF('$department_student' != '', '$department_student', department_student),
                country_student = IF('$country_student' != '', '$country_student', country_student),
                address_student = IF('$address_student' != '', '$address_student', address_student),
                profile_image_student = IF('$profile_image' != '', '$profile_image', profile_image_student),
                passport_image_student = IF('$passport_image' != '', '$passport_image', passport_image_student),
                visa_image_student = IF('$visa_image' != '', '$visa_image', visa_image_student),
                evisa_image_student = IF('$evisa_image' != '', '$evisa_image', evisa_image_student)
            WHERE id_account = '$id_account'
        ";
    } else {
        // ถ้าไม่มีข้อมูล → INSERT
        $sql = "
            INSERT INTO profile_students (
                id_account, student_id, name_student, nickname_student, age_student, 
                birthday_student, gender_student, email_student, number_student, 
                university_student, faculty_student, department_student, 
                country_student, address_student, profile_image_student, 
                passport_image_student, visa_image_student, evisa_image_student
            ) VALUES (
                '$id_account', '$student_id', '$name_student', '$nickname_student', '$age_student', 
                '$birthday_student', '$gender_student', '$email_student', '$number_student', 
                '$university_student', '$faculty_student', '$department_student', 
                '$country_student', '$address_student', '$profile_image', 
                '$passport_image', '$visa_image', '$evisa_image'
            )
        ";
    }

    // ประมวลผล SQL
    if (mysqli_query($conn, $sql)) {
        $_SESSION['Messager'] = "Profile updated successfully!";
        header("Location: profile_student.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request method.";
    exit();
}
?>
