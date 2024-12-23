<?php
session_start();
include '../config/config.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_account = $_SESSION['id_account'];
    $name_advisor = mysqli_real_escape_string($conn, $_POST['name_advisor'] ?? '');
    $position_advisor = mysqli_real_escape_string($conn, $_POST['position_advisor'] ?? '');
    $email_advisor = mysqli_real_escape_string($conn, $_POST['email_advisor'] ?? '');
    $number_advisor = mysqli_real_escape_string($conn, $_POST['number_advisor'] ?? '');
    $university_advisor = mysqli_real_escape_string($conn, $_POST['university_advisor'] ?? '');
    $faculty_advisor = mysqli_real_escape_string($conn, $_POST['faculty_advisor'] ?? '');
    $department_advisor = mysqli_real_escape_string($conn, $_POST['department_advisor'] ?? '');
    $country_advisor = mysqli_real_escape_string($conn, $_POST['country_advisor'] ?? '');

    $profile_image = '';
    if (!empty($_FILES['profile_image']['name'])) {
        $profile_image = basename($_FILES['profile_image']['name']);
        move_uploaded_file($_FILES['profile_image']['tmp_name'], "../uploads/$profile_image");
    }

    $check_sql = "SELECT id_account FROM profile_advisor WHERE id_account = '$id_account'";
    $result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($result) > 0) {
        $sql = "
            UPDATE profile_advisor SET 
                name_advisor = IF('$name_advisor' != '', '$name_advisor', name_advisor),
                position_advisor = IF('$position_advisor' != '', '$position_advisor', position_advisor),
                email_advisor = IF('$email_advisor' != '', '$email_advisor', email_advisor),
                number_advisor = IF('$number_advisor' != '', '$number_advisor', number_advisor),
                university_advisor = IF('$university_advisor' != '', '$university_advisor', university_advisor),
                faculty_advisor = IF('$faculty_advisor' != '', '$faculty_advisor', faculty_advisor),
                department_advisor = IF('$department_advisor' != '', '$department_advisor', department_advisor),
                country_advisor = IF('$country_advisor' != '', '$country_advisor', country_advisor),
                profile_advisor = IF('$profile_image' != '', '$profile_image', profile_advisor)
            WHERE id_account = '$id_account'
        ";
    } else {
        $sql = "
            INSERT INTO profile_advisor (
                id_account, name_advisor, position_advisor, email_advisor, 
                number_advisor, university_advisor, faculty_advisor, 
                department_advisor, country_advisor, profile_advisor
            ) VALUES (
                '$id_account', '$name_advisor', '$position_advisor', '$email_advisor', 
                '$number_advisor', '$university_advisor', '$faculty_advisor', 
                '$department_advisor', '$country_advisor', '$profile_image'
            )
        ";
    }

    if (mysqli_query($conn, $sql)) {
        $_SESSION['Messager'] = "Profile updated successfully!";
        header("Location: profile_advisor.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request method.";
    exit();
}
?>
