<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobSelect - Your Career Starts Here</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Global Styles */
body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    background: #f8f9fa;
}

/* Navbar */
.navbar {
    background: white;
    padding: 15px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.navbar-brand {
    font-size: 1.8rem;
    font-weight: bold;
    color: #ff4e50 !important;
}

.navbar-nav .nav-link {
    font-weight: 500;
    transition: 0.3s;
}

.navbar-nav .nav-link:hover {
    color: #fc913a !important;
}

/* Hero Section */
.hero {
    background: linear-gradient(to right, #ff4e50, #fc913a);
    color: white;
    padding: 120px 0;
    text-align: center;
}

.hero h1 {
    font-size: 3.5rem;
    font-weight: bold;
    text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
}

.hero p {
    font-size: 1.3rem;
    margin-bottom: 20px;
}

.hero .btn {
    font-size: 1.2rem;
    padding: 12px 30px;
    border-radius: 50px;
    transition: 0.3s;
}

.hero .btn:hover {
    background: #fff;
    color: #ff4e50;
}

/* About Section */
#about {
    padding: 80px 0;
}

#about h2 {
    color: #333;
    font-size: 2.5rem;
}

#about p {
    font-size: 1.1rem;
    color: #555;
}

#about img {
    border-radius: 10px;
    box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.2);
}

/* Features Section */
.features {
    background: linear-gradient(to right, #36d1dc, #5b86e5);
    padding: 80px 0;
    color: white;
}

.features h2 {
    font-size: 2.5rem;
}

.features .feature-box {
    background: rgba(255, 255, 255, 0.2);
    padding: 30px;
    border-radius: 10px;
    transition: 0.3s;
}

.features .feature-box i {
    color: #fff;
}

.features .feature-box:hover {
    transform: translateY(-10px);
    background: rgba(255, 255, 255, 0.3);
}

/* Contact Section */
#contact {
    padding: 60px 0;
}

#contact h2 {
    font-size: 2.5rem;
    color: #333;
}

/* Footer */
footer {
    background: #343a40;
    color: white;
    padding: 15px;
    text-align: center;
    margin-top: 50px;
    font-size: 1rem;
}

    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="index.php">JobSelect</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link text-dark" href="#about">About</a></li>
                <li class="nav-item"><a class="nav-link text-dark" href="users/login.php">Login</a></li>
                <li class="nav-item"><a class="nav-link text-dark" href="users/register.php">Register</a></li>
                <li class="nav-item"><a class="nav-link text-dark" href="companies/login.php">Company</a></li>
                <li class="nav-item"><a class="nav-link text-dark" href="admin/login.php">Admin</a></li>


           
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<header class="hero text-center text-white">
    <div class="container">
        <h1 class="display-3 fw-bold">Find Your Dream Job</h1>
        <p class="lead">Connecting job seekers with top employers worldwide.</p>
        <a href="users/register.php" class="btn btn-light btn-lg shadow-lg mt-3">Get Started <i class="fa fa-arrow-right"></i></a>
    </div>
</header>

<!-- About Section -->
<section id="about" class="container my-5">
    <div class="row align-items-center">
        <div class="col-md-6">
            <img src="about.png" class="img-fluid rounded shadow-lg" alt="About JobSelect">
        </div>
        <div class="col-md-6">
            <h2 class="fw-bold">Why JobSelect?</h2>
            <p>JobSelect is an advanced job-matching platform that connects job seekers with relevant opportunities.</p>
            <ul class="list-unstyled">
                <li><i class="fa fa-check text-success"></i> AI-Powered Job Matching</li>
                <li><i class="fa fa-check text-success"></i> Verified Companies</li>
                <li><i class="fa fa-check text-success"></i> Hassle-Free Job Applications</li>
                <li><i class="fa fa-check text-success"></i> Career Growth & Training</li>
            </ul>
        </div>
    </div>
</section>

<!-- Features -->
<section class="features text-center text-white py-5">
    <div class="container">
        <h2 class="fw-bold">Why Choose Us?</h2>
        <p class="lead">We provide a seamless job search experience.</p>
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="feature-box p-4 shadow-lg">
                    <i class="fa fa-building fa-3x"></i>
                    <h4 class="mt-3">Trusted Companies</h4>
                    <p>Work with top employers across multiple industries.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box p-4 shadow-lg">
                    <i class="fa fa-search fa-3x"></i>
                    <h4 class="mt-3">Smart Job Search</h4>
                    <p>Get personalized job recommendations instantly.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box p-4 shadow-lg">
                    <i class="fa fa-briefcase fa-3x"></i>
                    <h4 class="mt-3">Easy Application</h4>
                    <p>Apply with one click and track applications effortlessly.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="container my-5 text-center">
    <h2 class="fw-bold">Get in Touch</h2>
    <p>Email: support@jobselect.com | Phone: +123 456 7890</p>
</section>

<!-- Footer -->
<footer class="text-center py-3 bg-dark text-white">
    <p>&copy; 2025 JobSelect | All Rights Reserved</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
