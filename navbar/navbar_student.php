<style>
    .nav-item.dropdown:hover .dropdown-menu {
        display: block;
    }

    [id] {
        scroll-margin-top: 70px;
        /* ปรับตามความสูงของ Navbar */
    }
</style>

<ul class="navbar-nav">
    <li class="nav-item">
        <a class="nav-link" href="<?php echo $base_url; ?>/student/home_student.php">Home</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo $base_url; ?>/student/profile_student.php">Profile</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo $base_url; ?>/student/register_form_skill.php">International Cooperative Education</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo $base_url; ?>/student/student_view_advisor.php">Advisor</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo $base_url; ?>/student/job_company.php">Company</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo $base_url; ?>/student/daily.php">Daily</a>
    </li>
    <li class="nav-item">
        <form action="../realtime_chat/choose_receiver.php" method="GET">
            <input type="hidden" name="receiver_id" value="<?php echo isset($_GET['receiver_id']) ? htmlspecialchars($_GET['receiver_id']) : 0; ?>">
            <button type="submit" class="btn btn-link nav-link">
                Chat
            </button>
        </form>
    </li>
    <li>
        <a class="nav-link" href="<?php echo $base_url; ?>/student/student_reset_password.php">Reset Password</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo $base_url; ?>../index.php">Logout</a>
    </li>
</ul>
<span class="navbar-text ms-3">
    Welcome, <?php echo $_SESSION['username_account']; ?>
</span>