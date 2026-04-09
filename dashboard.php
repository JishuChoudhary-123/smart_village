<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Submit complaint
if (isset($_POST['submit_complaint'])) {

    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $user_id = $_SESSION['user_id'];

    $image_name = null;

    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . "_" . $_FILES['image']['name'];
        $target = "uploads/" . $image_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    $stmt = $conn->prepare("INSERT INTO complaints (user_id, title, description, category, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $title, $description, $category, $image_name);
    $stmt->execute();

    $_SESSION['success'] = "✅ Complaint submitted successfully!";
}

// DELETE
if (isset($_GET['delete'])) {
    $complaint_id = $_GET['delete'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM complaints WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $complaint_id, $user_id);
    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}

// Fetch announcements
$announcements = $conn->query("SELECT * FROM announcements ORDER BY created_at DESC");

// Fetch complaints
$user_id = $_SESSION['user_id'];
$my_complaints = $conn->prepare("SELECT * FROM complaints WHERE user_id = ? ORDER BY created_at DESC");
$my_complaints->bind_param("i", $user_id);
$my_complaints->execute();
$complaint_result = $my_complaints->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>User Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

/* ===== PREMIUM UI ===== */
body {
    background: linear-gradient(135deg, #eef2f3, #dfe9f3);
}

/* Navbar */
.navbar {
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

/* Cards */
.card {
    border-radius: 15px;
    transition: 0.3s;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

/* Buttons */
.btn {
    border-radius: 8px;
}

/* Profile Button FIX */
.profile-btn {
    position: absolute;
    top: 80px;
    left: 20px;
    z-index: 1000;
}

/* Table */
.table {
    border-radius: 10px;
    overflow: hidden;
}

/* Image hover */
img:hover {
    transform: scale(1.1);
    transition: 0.3s;
}

/* Dark Mode */
.dark-mode {
    background: #121212 !important;
    color: white !important;
}
.dark-mode .card {
    background-color: #1e1e1e;
}
.dark-mode .navbar {
    background-color: #000 !important;
}

table tbody tr:hover {
    background-color: #f9f9f9;
    transition: 0.2s;
}

.card {
    border-radius: 12px;
}

</style>
</head>

<body>

<!-- SUCCESS MESSAGE AUTO HIDE -->
<script>
setTimeout(() => {
    let alertBox = document.querySelector(".alert");
    if(alertBox){
        alertBox.style.display = "none";
    }
}, 3000);
</script>



<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
<div class="container">

<!-- ✅ FIXED: Logo + Clickable -->
<a class="navbar-brand fw-bold" href="index.php">
    🏡 Rajaqpur
</a>

<div class="d-flex align-items-center">

<!-- ✅ Profile Button (Improved Look) -->
<a href="profile.php" class="btn btn-light btn-sm me-2 fw-semibold shadow-sm">
    👤 My Profile
</a>

<!-- Welcome Text -->
<span class="text-white me-3 fw-semibold">
    Welcome, <?php echo $_SESSION['user_name']; ?>
</span>

<!-- Logout -->
<a href="logout.php" class="btn btn-light btn-sm me-2">
    Logout
</a>

<!-- Dark Mode Toggle -->
<button onclick="toggleDarkMode()" class="btn btn-warning btn-sm">
    🌙
</button>

</div>
</div>
</nav>

<div class="container mt-5">

<!-- SUCCESS ALERT -->
<?php if(isset($_SESSION['success'])) { ?>
<div class="alert alert-success text-center shadow">
    <?php 
    echo $_SESSION['success']; 
    unset($_SESSION['success']);
    ?>
</div>
<?php } ?>

<div class="row g-4">

<!-- COMPLAINT -->
<div class="col-md-6">
<div class="card shadow border-0">
<div class="card-header bg-warning">
<h5>📝 Submit Complaint</h5>
</div>

<div class="card-body">

<form method="POST" enctype="multipart/form-data">

<input type="text" name="title" class="form-control mb-3" placeholder="Complaint Title" required>

<select name="category" class="form-control mb-3" required>
<option value="">-- Select Category --</option>
<option value="Water">Water</option>
<option value="Electricity">Electricity</option>
<option value="Road">Road</option>
<option value="Cleanliness">Cleanliness</option>
<option value="Other">Other</option>
</select>

<textarea name="description" class="form-control mb-3" placeholder="Describe your issue..." required></textarea>

<input type="file" name="image" class="form-control mb-3">

<button type="submit" name="submit_complaint" class="btn btn-dark w-100">
Submit Complaint
</button>

</form>

</div>
</div>
</div>

<!-- ANNOUNCEMENTS -->
<div class="col-md-6">
<div class="card shadow border-0">
<div class="card-header bg-info text-white">
<h5>📢 Village Announcements</h5>
</div>

<div class="card-body" style="max-height:400px; overflow-y:auto;">

<?php if($announcements->num_rows > 0) { ?>
<?php while($row = $announcements->fetch_assoc()) { ?>

<div class="alert alert-light border shadow-sm">
<h6><?php echo $row['title']; ?></h6>
<p><?php echo $row['message']; ?></p>
<small class="text-muted"><?php echo $row['created_at']; ?></small>
</div>

<?php } ?>
<?php } else { ?>
<p>No announcements available.</p>
<?php } ?>

</div>
</div>
</div>

</div>

<!-- ================= MY COMPLAINT HISTORY ================= -->

<div class="card shadow-lg mt-4 border-0 rounded-4">
<div class="card-header text-white" style="background: linear-gradient(135deg, #667eea, #764ba2);">
<h5 class="mb-0">📋 My Complaint History</h5>
</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-hover align-middle text-center">

<thead style="background-color:#f1f3f6;">
<tr style="color:#333;">
<tr>
<th>Title</th>
<th>Category</th>
<th>Description</th>
<th>Status</th>
<th>Date</th>
<th>Action</th>
<th>Image</th>
</tr>
</thead>

<tbody>

<?php if($complaint_result->num_rows > 0) { ?>
<?php while($row = $complaint_result->fetch_assoc()) { ?>

<tr>

<td class="fw-semibold">
    <?php echo $row['title']; ?>
</td>

<td>
    <span class="badge bg-primary">
        <?php echo $row['category']; ?>
    </span>
</td>

<td style="max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
    <?php echo $row['description']; ?>
</td>

<td>
<?php if($row['status'] == 'Pending') { ?>
<span class="badge bg-warning text-dark px-3 py-2">Pending</span>
<?php } else { ?>
<span class="badge bg-success px-3 py-2">Resolved</span>
<?php } ?>
</td>

<td class="text-muted small">
    <?php echo date("d M Y", strtotime($row['created_at'])); ?>
</td>

<td>
<a href="edit_complaint.php?id=<?php echo $row['id']; ?>" 
class="btn btn-sm btn-outline-primary me-1">
Edit
</a>

<a href="?delete=<?php echo $row['id']; ?>" 
class="btn btn-sm btn-outline-danger"
onclick="return confirm('Are you sure you want to delete this complaint?');">
Delete
</a>
</td>

<td>
<?php if($row['image']) { ?>
<img src="uploads/<?php echo $row['image']; ?>" 
width="50" height="50"
style="object-fit:cover; border-radius:8px; cursor:pointer;"
onclick="showImage(this.src)">
<?php } else { ?>
<span class="text-muted small">No Image</span>
<?php } ?>
</td>

</tr>

<?php } ?>
<?php } else { ?>

<tr>
<td colspan="7" class="text-center text-muted">
No complaints submitted yet.
</td>
</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>
</div>

<!-- IMAGE MODAL -->
<div class="modal fade" id="imageModal">
<div class="modal-dialog modal-dialog-centered modal-lg">
<div class="modal-content">

<div class="modal-header">
<h5>Image Preview</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body text-center">
<img id="modalImage" class="img-fluid rounded">
</div>

</div>
</div>
</div>

<script>
function showImage(src) {
    document.getElementById("modalImage").src = src;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}

function toggleDarkMode() {
    document.body.classList.toggle("dark-mode");
    localStorage.setItem("mode", document.body.classList.contains("dark-mode") ? "dark" : "light");
}

window.onload = function() {
    if(localStorage.getItem("mode") === "dark"){
        document.body.classList.add("dark-mode");
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>