<style>
        .nav-item.dropdown:hover .dropdown-menu {
            display: block;
        }
 </style>

<ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link " href="<?php echo $base_url; ?>/advisor/home_advisor.php"">Home</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          About
          </a>
          <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
            <li><a class="dropdown-item" href="#news">News</a></li>
            <li><a class="dropdown-item" href="#">Form Docapproval Request </a></li>
          </ul>
        </li>
        <li class=" nav-item">
            <a class="nav-link " href="<?php echo $base_url; ?>/advisor/profile_advisor.php">Profile</a>
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

    