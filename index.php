<?php
session_start();
include 'includes/db.php';
$announcements = $conn->query("SELECT * FROM announcements ORDER BY id DESC LIMIT 5");
?>

<!DOCTYPE html>
<html>
<head>
<title>Rajaqpur Village Portal</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<style>

body {
    scroll-behavior: smooth;
}

/* HERO SECTION */
.hero {
    background: url('images/village.jpeg') center/cover no-repeat;
    height: 90vh;
    position: relative;
    color: white;
}

.hero::before {
    content: "";
    position: absolute;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background: rgba(0,0,0,0.6);
}

.hero-content {
    position: relative;
    z-index: 2;
    text-align: center;
    top: 50%;
    transform: translateY(-50%);
}

.section-padding {
    padding: 70px 0;
}

.stat-box {
    background: #f8f9fa;
    padding: 30px;
    border-radius: 10px;
    transition: 0.3s;
}

.stat-box:hover {
    background: #e9ecef;
    transform: scale(1.05);
}

.service-card {
    transition: 0.3s;
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

/* ANNOUNCEMENT BOX */
.announcement-box {
    background: #ffffff;
    border-left: 5px solid #198754;
    padding: 20px;
    border-radius: 8px;
    transition: 0.3s;
}

.announcement-box:hover {
    transform: scale(1.02);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
<div class="container">
<a class="navbar-brand" href="index.php">🏡 Rajaqpur</a>

<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
<span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="navmenu">
<ul class="navbar-nav ms-auto">

<li class="nav-item"><a class="nav-link" href="#about">About</a></li>
<li class="nav-item"><a class="nav-link" href="#history">History</a></li>
<li class="nav-item"><a class="nav-link" href="heritage.php">Heritage</a></li>
<li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>
<li class="nav-item"><a class="nav-link" href="#announcements">Announcements</a></li>
<li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>

<?php if(isset($_SESSION['user_id'])): ?>

    <!-- Welcome Text -->
    <li class="nav-item">
        <span class="nav-link text-warning fw-semibold">
            👋 Welcome, <?php echo $_SESSION['user_name']; ?>
        </span>
    </li>

    <!-- Role Based Button -->
    <?php if($_SESSION['role'] == 'admin'): ?>
        <li class="nav-item">
            <a class="nav-link btn btn-warning text-dark ms-2 px-3 fw-semibold" href="admin_dashboard.php">
                👑 Admin Panel
            </a>
        </li>
    <?php else: ?>
        <li class="nav-item">
            <a class="nav-link btn btn-success ms-2 px-3 fw-semibold" href="dashboard.php">
                📊 Dashboard
            </a>
        </li>
    <?php endif; ?>

    <!-- Logout -->
    <li class="nav-item">
        <a class="nav-link btn btn-danger ms-2 px-3 fw-semibold" href="logout.php">
            🚪 Logout
        </a>
    </li>

<?php else: ?>

    <!-- Login -->
    <li class="nav-item">
        <a class="nav-link btn btn-outline-light ms-2 px-3 fw-semibold" href="login.php">
            🔑 Login
        </a>
    </li>

    <!-- Register -->
    <li class="nav-item">
        <a class="nav-link btn btn-light ms-2 px-3 text-dark fw-semibold" href="register.php">
            📝 Register
        </a>
    </li>

<?php endif; ?>

</ul>
</div>
</div>
</nav>

<!-- HERO SECTION -->
<div class="hero">
<div class="hero-content">
<h1 class="display-3 fw-bold">Welcome to Rajaqpur</h1>
<p class="lead">A Village of Heritage, Unity & Rural Strength</p>
</div>
</div>

<!-- SMART VILLAGE SERVICES -->
<div class="section-padding bg-light">
<div class="container">
<h2 class="text-center mb-5">Smart Village Services</h2>

<div class="row g-4">

<!-- Service 1 -->
<div class="col-md-4">
<div class="card shadow-sm h-100 border-0 text-center p-4 service-card">
<h4 class="mb-3">📝 Register Complaint</h4>
<p>Submit village issues directly to administration.</p>
<?php if(isset($_SESSION['user_id'])) { ?>
    <a href="dashboard.php" class="btn btn-outline-dark">Access</a>
<?php } else { ?>
    <a href="login.php" class="btn btn-outline-dark">Access</a>
<?php } ?>
</div>
</div>

<!-- Service 2 -->
<div class="col-md-4">
<div class="card shadow-sm h-100 border-0 text-center p-4 service-card">

<h4 class="mb-3">📢 Announcements</h4>
<p>Stay updated with latest village notifications.</p>

<!-- Buttons -->
<div class="d-flex justify-content-center gap-2 mt-2">

<a href="#announcements" class="btn btn-outline-dark btn-sm">
Latest
</a>

<a href="announcements.php" class="btn btn-dark btn-sm">
View All
</a>

</div>

</div>
</div>

<!-- Service 3 -->
<div class="col-md-4">
<div class="card shadow-sm h-100 border-0 text-center p-4 service-card">
<h4 class="mb-3">🏥 Health Services</h4>
<p>Information about local health facilities.</p>
<button class="btn btn-outline-dark btn-sm mt-2" disabled>Coming Soon</button>
</div>
</div>

<!-- Service 4 -->
<div class="col-md-4">
<div class="card shadow-sm h-100 border-0 text-center p-4 service-card">
<h4 class="mb-3">🎓 Education</h4>
<p>Details of schools and educational programs.</p>
<button class="btn btn-outline-dark btn-sm mt-2" disabled>Coming Soon</button>
</div>
</div>

<!-- Service 5 -->
<div class="col-md-4">
<div class="card shadow-sm h-100 border-0 text-center p-4 service-card">
<h4 class="mb-3">🚜 Agriculture Support</h4>
<p>Guidance and support for farmers.</p>
<button class="btn btn-outline-dark btn-sm mt-2" disabled>Coming Soon</button>
</div>
</div>

<!-- Service 6 -->
<div class="col-md-4">
<div class="card shadow-sm h-100 border-0 text-center p-4 service-card">
<h4 class="mb-3">🏛 Panchayat Services</h4>
<p>Access public records and panchayat services.</p>
<button class="btn btn-outline-dark btn-sm mt-2" disabled>Coming Soon</button>
</div>
</div>

</div>
</div>
</div>

<!-- ================= ANNOUNCEMENTS SECTION ================= -->
<div id="announcements" class="container mt-5">

<h3 class="text-center mb-4">📢 Latest Announcements</h3>

<div class="row">

<?php
include 'includes/db.php';
$latest = $conn->query("SELECT * FROM announcements ORDER BY created_at DESC LIMIT 3");

if($latest->num_rows > 0){
    while($row = $latest->fetch_assoc()){
?>

<div class="col-md-4">
<div class="card shadow-sm border-0 mb-3">
<div class="card-body">

<h6 class="fw-bold"><?php echo $row['title']; ?></h6>

<p class="text-muted small">
<?php echo $row['message']; ?>
</p>

<small class="text-secondary">
<?php echo date("d M Y", strtotime($row['created_at'])); ?>
</small>

</div>
</div>
</div>

<?php } } else { ?>

<p class="text-center">No announcements available.</p>

<?php } ?>

</div>

</div>

<!-- ABOUT SECTION -->
<div id="about" class="container section-padding">
<div class="row">
<div class="col-md-6">
<h2>About Rajaqpur</h2>
<p>
Rajaqpur is a village located in the Amroha Tehsil of Amroha District 
(formerly Jyotiba Phule Nagar) in Uttar Pradesh, India.
</p>
<p>
As per the 2011 Census, Rajaqpur has a population of 1,899 residents.
The village represents strong agricultural traditions and rural development.
</p>
</div>
<div class="col-md-6">
<img src="images/village2.jpeg" class="img-fluid rounded shadow">
</div>
</div>
</div>


<!-- ANNOUNCEMENTS SECTION -->
<?php
include 'includes/db.php';
$announcements = $conn->query("SELECT * FROM announcements ORDER BY id DESC LIMIT 2");
?>


<!-- STATISTICS -->
<div class="section-padding text-center">
<div class="container">
<h2 class="mb-5">Village Statistics</h2>
<div class="row">

<div class="col-md-4">
<div class="stat-box shadow">
<h3>1,899</h3>
<p>Population (2011 Census)</p>
</div>
</div>

<div class="col-md-4">
<div class="stat-box shadow">
<h3>Amroha</h3>
<p>District</p>
</div>
</div>

<div class="col-md-4">
<div class="stat-box shadow">
<h3>244221</h3>
<p>Pincode</p>
</div>
</div>

</div>
</div>
</div>

<!-- CIVIC ADMINISTRATION SECTION -->
<div class="section-padding bg-white">
<div class="container">
<h2 class="text-center mb-5">🏛 Civic Administration – Rajaqpur</h2>

<div class="table-responsive shadow-sm">
<table class="table table-bordered text-center align-middle">
<thead class="table-dark">
<tr>
<th>Name</th>
<th>Position</th>
<th>Contact</th>
</tr>
</thead>
<tbody>

<tr>
<td>Mr. Example Sharma</td>
<td>Nagar Palika Member</td>
<td>+91 9876543210</td>
</tr>

<tr>
<td>Mrs. Example Verma</td>
<td>Ward Representative</td>
<td>+91 9123456780</td>
</tr>

<tr>
<td>Mr. Example Khan</td>
<td>Public Service Coordinator</td>
<td>+91 9988776655</td>
</tr>

</tbody>
</table>
</div>

<div class="text-center mt-4">
<p><strong>Office Address:</strong> Nagar Palika Office, Amroha, Uttar Pradesh</p>
<p><strong>Office Hours:</strong> Monday – Friday | 10:00 AM – 5:00 PM</p>
</div>

</div>
</div>

<!-- HISTORY -->
<div id="history" class="container section-padding text-center">
<h2 class="mb-4">Historical Background</h2>
<p>
Rajaqpur reflects the deep-rooted heritage of Western Uttar Pradesh 
while embracing rural development and modernization.
</p>
</div>

<!-- CONTACT -->
<div id="contact" class="bg-dark text-white text-center section-padding">
<h2>Contact Gram Panchayat Rajaqpur</h2>
<p>Amroha District, Uttar Pradesh - 244221</p>
<p>Email: jishuchoudhary46@email.com</p>
</div>

<footer class="bg-secondary text-white text-center p-3">
© <?php echo date("Y"); ?> Rajaqpur | Smart Village Portal
</footer>

</body>
</html>