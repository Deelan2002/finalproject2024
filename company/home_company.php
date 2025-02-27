<?php
session_start();
include '../config/config.php';

// ตรวจสอบการเข้าสู่ระบบและบทบาทผู้ใช้
if (empty($_SESSION['id_account']) || $_SESSION['role_account'] !== 'company') {
    $_SESSION['Messager'] = 'You are not authorized!';
    header("Location: {$base_url}../index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Home</title>
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <style>
        /* พื้นหลังและฟอนต์ */
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            color: #343a40;
        }

        /* Navbar */
        .navbar {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.7rem;
            color: #ff9e00ed !important;
        }

        .nav-link {
            color: #343a40 !important;
            font-weight: 500;
        }

        .nav-link:hover {
            color: #ff9e00ed !important;
            transition: color 0.3s ease;
        }

        /* Banner */
        .banner-img {
            height: 550px;
            object-fit: cover;
            filter: brightness(75%);
        }

        .carousel-caption h1 {
            font-size: 3rem;
            animation: fadeInDown 1s ease;
            color: #eea80f;
        }

        .carousel-caption p {
            font-size: 1.2rem;
            animation: fadeInUp 1.5s ease;
            color: #eea80f;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* การ์ดแสดงตำแหน่งงาน */
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #ff9e00ed;
        }

        .btn-primary {
            background-color: #ff9e00ed;
            border: none;
            transition: background-color 0.3s ease, transform 0.3s;
        }

        .btn-primary:hover {
            background-color:rgb(255, 189, 82);
            transform: translateY(-3px);
        }

        /* Footer */
        footer {
            background: #222;
            color: #ddd;
            padding: 20px 0;
        }

        footer p {
            margin: 0;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
   <!-- Navbar -->
   <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Company Portal</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="home_company.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage_jobs.php">Manage Jobs</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage_applications.php">Manage Applications</a></li>
                    <li class="nav-item"><a class="nav-link" href="company_profile.php">Company Profile</a></li>
                
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Banner -->
    <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="../image/cpu-Photoroom.png" class="d-block w-100 banner-img" alt="CPU">
                <div class="carousel-caption">
                    <h1>Welcome to the Company Portal</h1>
                    <p>Explore the latest opportunities in tech and innovation</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="../image/software-engineer.png" class="d-block w-100 banner-img" alt="Software Engineer">
                <div class="carousel-caption">
                    <h1>Join Our Engineering Team</h1>
                    <p>Shape the future with cutting-edge technology</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="../image/graduated.png" class="d-block w-100 banner-img" alt="Graduated">
                <div class="carousel-caption">
                    <h1>Empowering the Next Generation</h1>
                    <p>Start your career with us and grow together</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Job Details Section -->
    <div class="container my-5">
        <h2 class="text-center mb-4">Available Job Positions</h2>
        <div class="row">
            <!-- Job Card 1 -->
            <div class="col-md-4 mb-4">
                <div class="card shadow">
                    <img src="../image/PHU3.jpg" class="card-img-top" alt="Job 1">
                    <div class="card-body">
                        <h5 class="card-title">Software Engineer</h5>
                        <p class="card-text">Design, develop, and implement software solutions. Required experience: 3+ years.</p>
                        <a href="#" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
            <!-- Job Card 2 -->
            <div class="col-md-4 mb-4">
                <div class="card shadow">
                    <img src="../image/PHU1.jpg" class="card-img-top" alt="Job 2">
                    <div class="card-body">
                        <h5 class="card-title">Graphic Designer</h5>
                        <p class="card-text">Create compelling visual content and marketing materials. Required experience: 2+ years.</p>
                        <a href="#" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
            <!-- Job Card 3 -->
            <div class="col-md-4 mb-4">
                <div class="card shadow">
                    <img src="../image/PHU2.jpg" class="card-img-top" alt="Job 3">
                    <div class="card-body">
                        <h5 class="card-title">Project Manager</h5>
                        <p class="card-text">Lead and manage projects from start to finish. Required experience: 5+ years.</p>
                        <a href="#" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <p>&copy; 2024 Company Portal. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
