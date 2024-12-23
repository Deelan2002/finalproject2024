
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link " href="<?php echo $base_url; ?>/student/home_student.php"">Home</a>
        </li>
        <li class=" nav-item">
            <a class="nav-link " href="#">About</a>
        </li>
        <li class=" nav-item">
            <a class="nav-link " href="<?php echo $base_url; ?>/student/profile_student.php">Profile</a>
        </li>
        <li class="nav-item">
            <a class="nav-link " href="#">Advisor</a>
        </li>
        <li class="nav-item">
            <a class="nav-link " href="#">Company</a>
        </li>
        <li class="nav-item">
            <a class="nav-link " href="<?php echo $base_url; ?>../index.php">Logout</a>
        </li>
    </ul>
    <span class="navbar-text  ms-3">
        Welcome, <?php echo $_SESSION['username_account']; ?>
    </span>

    