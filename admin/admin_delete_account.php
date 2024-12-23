<?php
session_start();
include '../config/config.php';

if (isset($_GET['id'])) {
    $id_account = $_GET['id'];

    try {
        // เริ่ม Transaction
        mysqli_begin_transaction($conn);

        // ตรวจสอบการมีอยู่ของ id_account ใน accounts
        $check_sql = "SELECT id_account FROM accounts WHERE id_account = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "i", $id_account);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) == 0) {
            throw new Exception("Account ID does not exist.");
        }

        // ตารางที่มี id_account
        $tables_with_id_account = [
            'student_profile',
            'daily_work_record',
            'profile_students',
            'profile_advisor',
            'language_skills'
        ];

        foreach ($tables_with_id_account as $table) {
            $sql = "DELETE FROM $table WHERE id_account = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $id_account);
            mysqli_stmt_execute($stmt);
        }

        // ลบจาก accounts
        $sql_accounts = "DELETE FROM accounts WHERE id_account = ?";
        $stmt_accounts = mysqli_prepare($conn, $sql_accounts);
        mysqli_stmt_bind_param($stmt_accounts, "i", $id_account);
        mysqli_stmt_execute($stmt_accounts);

        // Commit Transaction
        mysqli_commit($conn);

        $_SESSION['Messager'] = "Account deleted successfully.";
        header("Location: ../admin/admin_manage_accounts.php");
        exit();
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $_SESSION['Messager'] = "Error: " . $e->getMessage();
        header("Location: ../admin/admin_manage_accounts.php");
        exit();
    }
} else {
    $_SESSION['Messager'] = "Invalid request.";
    header("Location: ../admin/admin_manage_accounts.php");
    exit();
}
?>
