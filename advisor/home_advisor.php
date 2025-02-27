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
    <link rel="apple-touch-icon" sizes="180x180" href="../image/apple-touch-icon.png">
    <link rel="android-chrome" href="../image/android-chrome-192x192.png">
    <link rel="android-chrome" href="../image/android-chrome-512x512.png">
    <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="../image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../image/favicon-16x16.png">
    <link rel="manifest" href="../image/site.webmanifest">
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
            <img src="../image/logo-pxu.png" alt="SDIC Logo" width="40" height="40">SDIC</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <?php include('../navbar/navbar_advisor.php') ?>
            </div>
        </div>
    </nav>


    <h1 class="welcome-title">Student database system for international CWIE</h1>


    <div class="slideshow">
        <div class="items">
            <img src="https://img.giaoduc.net.vn/1200x630/Uploaded/2025/lwivvpiv/2024_10_11/green-floral-watercolor-welcome-spring-banner-2-7652.png" alt="">
        </div>
        <div class="items">
            <img src="https://phuxuan.edu.vn/wp-content/uploads/2023/11/dai-hoc-phu-xuan-2023-mau-do.jpeg" alt="">
        </div>
        <div class="items">
            <img src="https://equest.vn/wp-content/uploads/2024/04/432271273_444804648105697_7334330496683289317_n.jpg" alt="">
        </div>
        <div class="items">
            <img src="https://phuxuan.edu.vn/wp-content/uploads/2023/05/CHAT0103-scaled.jpg" alt="">
        </div>
        <div class="items">
            <img src="https://nbs.edu.vn/wp-content/uploads/2024/10/1.jpg" alt="">
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
        <img src="../image/pxu4.jpg" alt="Phu Xuan University">
        <img src="../image/pxu1.jpeg" alt="Phu Xuan University">
        <img src="../image/PHU2.jpg" alt="Phu Xuan University">
        <img src="../image/pxu5.jpg" alt="Phu Xuan University">
        <img src="../image/pxu6.jpg" alt="Phu Xuan University">
        <img src="../image/pxu7.jpg" alt="Phu Xuan University">
        <img src="../image/pxu8.jpg" alt="Phu Xuan University">
        <img src="../image/pxu1.jpeg" alt="Phu Xuan University">
        <img src="../image/pxu1.jpeg" alt="Phu Xuan University">
        <img src="../image/pxu1.jpeg" alt="Phu Xuan University">
        <img src="../image/pxu1.jpeg" alt="Phu Xuan University">
        <img src="../image/pxu1.jpeg" alt="Phu Xuan University">
        <img src="../image/pxu1.jpeg" alt="Phu Xuan University">
        <img src="../image/pxu1.jpeg" alt="Phu Xuan University">
        <img src="../image/pxu1.jpeg" alt="Phu Xuan University">
    </div>

    <!-- Footer Section -->
    <footer class="footer">
        <div class="footer-container">
            <!-- About Section -->
            <div class="footer-section about">
                <img src="../image/logo-ngang-trang-1.png" alt="Logo">

                <p>Phu Xuan University.</p>

                <!-- Contact Info -->
                <p>
                    <a href="tel:+842347306888" title="Call Now">
                        <i class="fas fa-phone-alt"></i> +84 234 7306888
                    </a>
                </p>
                <p>
                    <a href="tel:+84905984286" title="Call Now">
                        <i class="fas fa-phone-alt"></i> +84 905 984 286
                    </a>
                </p>
                <p>
                    <a href="https://maps.app.goo.gl/y2hUpzXqpbmUpmi58" target="_blank" title="View on Map">
                        <i class="fas fa-map-marker-alt"></i> 176 Trần Phú, Phước Vĩnh, TP. Huế
                    </a>
                </p>
                <p>
                    <a target="_blank" title="Send Email">
                        <i class="fas fa-envelope"></i> lienhe@pxu.edu.vn
                    </a>
                </p>

                <!-- Social Media -->
                <div class="social-icons">
                    <a href="https://www.facebook.com/phuxuan.edu.vn" target="_blank" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://www.youtube.com/@truongaihocphuxuan6257" target="_blank" title="YouTube">
                        <i class="fab fa-youtube"></i>
                    </a>
                    <a href="https://phuxuan.edu.vn/" target="_blank" title="Website">
                        <i class="fas fa-globe"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="footer-section links">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="#">About Us</a></li>
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
                <p>8:00 AM - 4:30 PM, Thursday - Monday</p>
                <p>Phu Xuan University.</p>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <p>Copyright © 2024 Phu Xuan University. All rights reserved.</p>
        </div>
    </footer>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>