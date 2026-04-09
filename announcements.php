<?php
include 'includes/db.php';
$result = $conn->query("SELECT * FROM announcements ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>All Announcements - Rajaqpur</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
<h2 class="text-center mb-4">📢 Village Announcements</h2>

<?php while($row = $result->fetch_assoc()): ?>
<div class="card mb-3 shadow-sm">

<div class="card-body">
<h5 class="card-title"><?php echo $row['title']; ?></h5>

<p class="card-text text-muted">
<?php echo $row['message']; ?>
</p>

<small class="text-secondary">
📅 Posted on <?php echo date("d M Y, h:i A", strtotime($row['created_at'])); ?>
</small>
</div>

</div>
<?php endwhile; ?>

<div class="text-center mt-4">
<a href="index.php" class="btn btn-dark">Back to Home</a>
</div>

</div>

</body>
</html>