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
        <a class="nav-link " href="<?php echo $base_url; ?>/student/home_student.php"">Home</a>
        </li>
        <li class=" nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                About
            </a>
            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                <li><a class="dropdown-item" href="#news">News</a></li>
                <li><a class="dropdown-item" href="#register">international cooperative education</a></li>
            </ul>
    </li>
    <li class=" nav-item">
        <a class="nav-link " href="<?php echo $base_url; ?>/student/profile_student.php">Profile</a>
    </li>
    <li class="nav-item">
        <a class="nav-link " href="#">Advisor</a>
    </li>
    <li class="nav-item">
        <a class="nav-link " href="<?php echo $base_url; ?>/student/job_company.php">Company</a>
    </li>
    <li class="nav-item">
        <a class="nav-link " href="<?php echo $base_url; ?>../index.php">Logout</a>
    </li>
</ul>
<span class="navbar-text  ms-3">
    Welcome, <?php echo $_SESSION['username_account']; ?>
</span>