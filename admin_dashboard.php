<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

/* ================= STATISTICS ================= */

$total_users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$total_complaints = $conn->query("SELECT COUNT(*) AS total FROM complaints")->fetch_assoc()['total'];
$pending = $conn->query("SELECT COUNT(*) AS total FROM complaints WHERE status='Pending'")->fetch_assoc()['total'];
$resolved = $conn->query("SELECT COUNT(*) AS total FROM complaints WHERE status='Resolved'")->fetch_assoc()['total'];

/* ================= ADD ANNOUNCEMENT ================= */

if (isset($_POST['add_announcement'])) {
    $title = $_POST['title'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO announcements (title, message) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $message);
    $stmt->execute();
    $_SESSION['success'] = "Announcement posted successfully!";
}

/* ================= MARK COMPLAINT RESOLVED ================= */

if (isset($_GET['resolve'])) {
    $complaint_id = $_GET['resolve'];

    $stmt = $conn->prepare("UPDATE complaints SET status='Resolved' WHERE id=?");
    $stmt->bind_param("i", $complaint_id);
    $stmt->execute();

    header("Location: admin_dashboard.php");
    exit();
}

/* ================= FETCH DATA ================= */

$users = $conn->query("SELECT * FROM users");

/* ================= FETCH COMPLAINTS WITH FILTER ================= */

$where = "";

/* ================= FETCH COMPLAINTS WITH SEARCH + FILTER ================= */

$whereConditions = [];

if (isset($_GET['status']) && $_GET['status'] != "") {
    $status = $conn->real_escape_string($_GET['status']);
    $whereConditions[] = "complaints.status = '$status'";
}

if (isset($_GET['search']) && $_GET['search'] != "") {
    $search = $conn->real_escape_string($_GET['search']);
    $whereConditions[] = "(complaints.title LIKE '%$search%' 
                           OR users.name LIKE '%$search%' 
                           OR complaints.category LIKE '%$search%')";
}

$where = "";

if (!empty($whereConditions)) {
    $where = "WHERE " . implode(" AND ", $whereConditions);
}

$complaints = $conn->query("
    SELECT complaints.*, users.name 
    FROM complaints 
    JOIN users ON complaints.user_id = users.id
    $where
    ORDER BY complaints.created_at DESC
");


?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.dark-mode {
    background-color: #121212 !important;
    color: white !important;
}

.dark-mode .card {
    background-color: #1e1e1e;
    color: white;
}

.dark-mode .navbar {
    background-color: #000 !important;
}
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body {
    background-color: #f5f7fa;
}

.navbar {
    background: linear-gradient(to right, #2c3e50, #4ca1af);
}

.card {
    border-radius: 12px;
}

.table th {
    font-weight: 600;
}

.badge {
    font-size: 13px;
}
</style>

</head>

<body>

<script>
setTimeout(() => {
    let alertBox = document.querySelector(".alert");
    if(alertBox){
        alertBox.style.display = "none";
    }
}, 3000);
</script>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark shadow">
<div class="container">
<span class="navbar-brand fw-bold">👑 Smart Village Admin Panel</span>
<div>
<span class="text-white me-3">
Welcome, <?php echo $_SESSION['user_name']; ?>
</span>
<a href="logout.php" class="btn btn-light btn-sm">Logout</a>
<button onclick="toggleDarkMode()" class="btn btn-light btn-sm ms-2">
🌙
</button>
</div>
</div>
</nav>

<div class="container mt-4">
 <?php if(isset($_SESSION['success'])) { ?>
<div class="alert alert-success text-center">
    <?php 
    echo $_SESSION['success']; 
    unset($_SESSION['success']);
    ?>
</div>
<?php } ?>   

<!-- ================= STATISTICS ================= -->

<div class="row mb-4">

<div class="col-md-3">
<div class="card shadow-sm border-0 text-center p-3">
<h6 class="text-muted">Total Users</h6>
<h2 class="fw-bold"><?php echo $total_users; ?></h2>
</div>
</div>

<div class="col-md-3">
<div class="card shadow-sm border-0 text-center p-3">
<h6 class="text-muted">Total Complaints</h6>
<h2 class="fw-bold"><?php echo $total_complaints; ?></h2>
</div>
</div>

<div class="col-md-3">
<div class="card shadow-sm border-0 text-center p-3">
<h6 class="text-muted">Pending</h6>
<h2 class="fw-bold text-warning"><?php echo $pending; ?></h2>
</div>
</div>

<div class="col-md-3">
<div class="card shadow-sm border-0 text-center p-3">
<h6 class="text-muted">Resolved</h6>
<h2 class="fw-bold text-success"><?php echo $resolved; ?></h2>
</div>
</div>

</div>

<!-- ================= CHART ================= -->

<div class="card shadow-sm border-0 mb-4">
<div class="card-header bg-white border-0">
<h5 class="fw-semibold mb-0">📊 Complaint Status Overview</h5>
</div>
<div class="card-body d-flex justify-content-center">
<div style="width:280px; height:280px;">
<canvas id="complaintChart"></canvas>
</div>
</div>
</div>

<!-- ================= ANNOUNCEMENT ================= -->

<div class="card shadow-sm border-0 mb-4">
<div class="card-header bg-white border-0">
<h5 class="fw-semibold mb-0">📢 Add Village Announcement</h5>
</div>
<div class="card-body">

<form method="POST">
<div class="mb-3">
<input type="text" name="title" class="form-control" placeholder="Announcement Title" required>
</div>

<div class="mb-3">
<textarea name="message" class="form-control" placeholder="Write announcement here..." required></textarea>
</div>

<button type="submit" name="add_announcement" class="btn btn-outline-primary">
Post Announcement
</button>
</form>

</div>
</div>


<!-- ================= COMPLAINTS ================= -->

<div class="card shadow-sm border-0 mb-4">
<div class="card-header bg-white border-0">
<h5 class="fw-semibold mb-0">📋 Village Complaints</h5>
</div>

<div class="card-body table-responsive">

<!-- Search + Filter Form -->
<form method="GET" class="row g-2 mb-3 align-items-end">

<div class="col-md-4">
<label class="form-label">Search</label>
<input type="text" name="search" class="form-control" placeholder="Search by title, user or category">
</div>

<div class="col-md-3">
<label class="form-label">Status</label>
<select name="status" class="form-select">
<option value="">All Status</option>
<option value="Pending">Pending</option>
<option value="Resolved">Resolved</option>
</select>
</div>

<div class="col-md-2">
<button type="submit" class="btn btn-primary w-100">
Search
</button>
</div>

</form>


<table class="table table-hover align-middle">
<thead class="bg-light">
<tr>
<th>ID</th>
<th>User</th>
<th>Title</th>
<th>Category</th>
<th>Description</th>
<th>Status</th>
<th>Action</th>
<th>Date</th>
<th>Image</th>
</tr>
</thead>
<tbody>

<?php if($complaints->num_rows > 0) { ?>
<?php while($row = $complaints->fetch_assoc()) { ?>
<tr>

<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['title']; ?></td>
<td>
<span class="badge bg-secondary">
<?php echo $row['category']; ?>
</span>
</td>
<td><?php echo $row['description']; ?></td>

<td>
<?php if($row['status'] == 'Pending') { ?>
<span class="badge rounded-pill bg-warning text-dark px-3">Pending</span>
<?php } else { ?>
<span class="badge rounded-pill bg-success px-3">Resolved</span>
<?php } ?>
</td>

<td>
<?php if($row['status'] == 'Pending') { ?>
<a href="?resolve=<?php echo $row['id']; ?>" 
class="btn btn-sm btn-outline-success">
Mark as Resolved
</a>
<?php } else { ?>
<span class="text-success fw-semibold">Completed</span>
<?php } ?>
</td>

<td><?php echo $row['created_at']; ?></td>

<td>
<?php if($row['image']) { ?>
<img src="uploads/<?php echo $row['image']; ?>" 
width="60" 
style="cursor:pointer;"
onclick="showImage(this.src)">
<?php } else { ?>
No Image
<?php } ?>
</td>

</tr>
<?php } ?>
<?php } else { ?>
<tr><td colspan="7" class="text-center">No complaints found.</td></tr>
<?php } ?>

</tbody>
</table>

</div>
</div>

<!-- ================= USERS ================= -->

<div class="card shadow-sm border-0">
<div class="card-header bg-white border-0">
<h5 class="fw-semibold mb-0">👥 Registered Users</h5>
</div>
<div class="card-body table-responsive">

<table class="table table-hover align-middle">
<thead class="bg-light">
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Role</th>

</tr>
</thead>
<tbody>

<?php while($row = $users->fetch_assoc()) { ?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['email']; ?></td>
<td>
<span class="badge bg-secondary"><?php echo $row['role']; ?></span>
</td>
</tr>
<?php } ?>

</tbody>
</table>

</div>
</div>

</div>

<!-- ================= CHART SCRIPT ================= -->

<script>
const ctx = document.getElementById('complaintChart');

new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'Resolved'],
        datasets: [{
            data: [<?php echo $pending; ?>, <?php echo $resolved; ?>],
            backgroundColor: ['#f4a261', '#2a9d8f'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '60%',
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>

<!-- IMAGE PREVIEW MODAL -->
<div class="modal fade" id="imageModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Image Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body text-center">
        <img id="modalImage" src="" class="img-fluid rounded">
      </div>

    </div>
  </div>
</div>

<script>
function showImage(src) {
    document.getElementById("modalImage").src = src;

    var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
    myModal.show();
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
function toggleDarkMode() {
    document.body.classList.toggle("dark-mode");

    // Save user choice
    if(document.body.classList.contains("dark-mode")){
        localStorage.setItem("mode", "dark");
    } else {
        localStorage.setItem("mode", "light");
    }
}

// Load saved mode automatically
window.onload = function() {
    if(localStorage.getItem("mode") === "dark"){
        document.body.classList.add("dark-mode");
    }
}
</script>

</body>
</html>