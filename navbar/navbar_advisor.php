<style>
    .nav-item.dropdown:hover .dropdown-menu {
        display: block;
    }
</style>

<ul class="navbar-nav">
    <li class="nav-item">
        <a class="nav-link" href="<?php echo $base_url; ?>/advisor/home_advisor.php">Home</a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link" href="<?php echo $base_url; ?>/advisor/profile_advisor.php">Profile</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo $base_url; ?>/advisor/advisor_view_student.php">Student</a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?php echo $base_url; ?>/advisor/advisor_manage_applications.php">Form Doc Approval Request</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo $base_url; ?>/advisor/advisor_show_register_international.php">Student Skill</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo $base_url; ?>/advisor/daily_list.php">Daily</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo $base_url; ?>/advisor/company_details.php">Company</a>
    </li>

    <li class="nav-item" style="display: inline-block;">
        <form action="../realtime_chat/choose_receiver.php" method="GET" ">
            <input type="hidden" name="receiver_id" value="<?php echo isset($_GET['receiver_id']) ? htmlspecialchars($_GET['receiver_id']) : 0; ?>">
            <button type="submit" class="btn btn-link nav-link" ">
                Chat
            </button>
        </form>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo $base_url; ?>../index.php">Logout</a>
    </li>
</ul>
<span class="navbar-text ms-3">
    Welcome, <?php echo $_SESSION['username_account']; ?>
</span>
