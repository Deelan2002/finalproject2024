<?php
session_start();
include '../config/config.php';

// ตรวจสอบสิทธิ์ผู้ใช้งาน
if (!isset($_SESSION['role_account']) || $_SESSION['role_account'] !== 'advisor') {
    $_SESSION['Messager'] = 'You are not authorized!';
    header("location: {$base_url}../index.php");
    exit();
}

//ดึงข้อมูลมาแสดงผล
$id_account = $_SESSION['id_account'];
$query = mysqli_query($conn, "SELECT * FROM accounts WHERE id_account='{$id_account}'");
$username_account = mysqli_fetch_assoc($query);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../css/style_home_advisor.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;600&display=swap" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>


</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark ">
        <div class="container-fluid">
            <a class="navbar-brand" href="../advisor/home_advisor.php">CIWE</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <?php include('../navbar/navbar_advisor.php') ?>
            </div>
        </div>
    </nav>


    <h1 class="welcome-title">Welcome Home!</h1>


    <div class="slideshow">
        <div class="items">
            <img src="https://cpu-robot.cpu.ac.th/wp-content/uploads/2024/12/p1.jpg" alt="">
        </div>
        <div class="items">
            <img src="https://cpu-robot.cpu.ac.th/wp-content/uploads/2024/12/%E0%B9%80%E0%B8%84%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B8%82%E0%B9%88%E0%B8%B2%E0%B8%A2net-4.jpg" alt="">
        </div>
        <div class="items">
            <img src="https://cpu-robot.cpu.ac.th/wp-content/uploads/2024/12/1.1.jpg" alt="">
        </div>
        <div class="items">
            <img src="https://cpu-robot.cpu.ac.th/wp-content/uploads/2024/11/DJI_0053-scaled.jpg" alt="">
        </div>
        <div class="items">
            <img src="https://cpu-robot.cpu.ac.th/wp-content/uploads/2024/12/DSCF4561-copy.jpg" alt="">
        </div>
    </div>
    <!-- script slideshow -->
    <script>
        $(document).ready(() => {
            $('.slideshow').slick({
                dots: true,
                autoplay: true,
                autoplaySpeed: 2000,
            });
            $(".slideshow .slick-prev").html('<i class="fa-solid fa-chevron-left"></i>')
            $(".slideshow .slick-next").html('<i class="fa-solid fa-chevron-right"></i>')
            $(".slideshow .slick-dots li").html('')
        })
    </script>


    
    
        
    <div class="container-image">
        <img src="../image/PHU1.jpg" alt="Phu Xuan University">
        <img src="../image/PHU3.jpg" alt="Phu Xuan University">
        <img src="../image/PHU2.jpg" alt="Phu Xuan University">
    </div>

    <div class="news-container">
        <!-- กล่องแสดงข่าว -->
        <div class="news-box">
            <h2>News</h2>
            <div class="content">
                <!-- เพิ่มเนื้อหาข่าวตรงนี้ -->
                <p>ข่าวสารที่สำคัญจะปรากฏที่นี่</p>
            </div>
        </div>

        <!-- กล่องแสดงรางวัล -->
        <div class="news-box">
            <h2>Award</h2>
            <div class="content">
                <!-- เพิ่มเนื้อหารางวัลตรงนี้ -->
                <p>รางวัลและความสำเร็จจะปรากฏที่นี่</p>
            </div>
        </div>
    </div>

    


    <!-- Footer Section -->
    <footer class="footer">

        <!-- Footer Content -->
        <div class="footer-container">
            <!-- Left Section -->
            <div class="footer-section about">
                <img src="../image/cpu-Photoroom.png" alt="Logo">

                <p>Chaopraya University is a higher educational institute committing to high-standard academic services for Nakhon Sawan province and nearby provinces.</p>
                <div class="social-icons">
                    <a href="#">X</a>
                    <a href="#">F</a>
                    <a href="#">I</a>
                    <a href="#">Y</a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="footer-section links">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="#">About us</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">News & Articles</a></li>
                    <li><a href="#">Legal Notice</a></li>
                </ul>
            </div>

            <!-- Useful Links -->
            <div class="footer-section links">
                <h4>Useful Links</h4>
                <ul>
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Parent Community</a></li>
                </ul>
            </div>

            <!-- School Hours -->
            <div class="footer-section hours">
                <h4>School Hours</h4>
                <p>8:00 AM - 4:30 PM , Thursday - Monday</p>
                <p>Chaopraya University,<br>
                    13/1 Moo. 6, Nong Krot Subdistrict,<br>
                    Mueang Nakhon Sawan District,<br>
                    Nakhon Sawan Province 60240</p>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <p>Copyright © 2024 Chaopraya University. All rights reserved.</p>
        </div>
    </footer>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>