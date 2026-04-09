<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// GET complaint data
if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM complaints WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows != 1) {
        echo "Invalid access!";
        exit();
    }

    $complaint = $result->fetch_assoc();
}

// UPDATE complaint
if (isset($_POST['update'])) {

    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];

    $stmt = $conn->prepare("UPDATE complaints SET title=?, description=?, category=? WHERE id=? AND user_id=?");
    $stmt->bind_param("sssii", $title, $description, $category, $id, $user_id);
    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Complaint</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
<div class="card shadow">
<div class="card-header bg-warning">
<h4>Edit Complaint</h4>
</div>

<div class="card-body">

<form method="POST">

<input type="hidden" name="id" value="<?php echo $complaint['id']; ?>">

<div class="mb-3">
<label>Title</label>
<input type="text" name="title" class="form-control"
value="<?php echo $complaint['title']; ?>" required>
</div>

<div class="mb-3">
<label>Category</label>
<select name="category" class="form-control" required>
<option value="Water" <?php if($complaint['category']=='Water') echo 'selected'; ?>>Water</option>
<option value="Electricity" <?php if($complaint['category']=='Electricity') echo 'selected'; ?>>Electricity</option>
<option value="Road" <?php if($complaint['category']=='Road') echo 'selected'; ?>>Road</option>
<option value="Cleanliness" <?php if($complaint['category']=='Cleanliness') echo 'selected'; ?>>Cleanliness</option>
<option value="Other" <?php if($complaint['category']=='Other') echo 'selected'; ?>>Other</option>
</select>
</div>

<div class="mb-3">
<label>Description</label>
<textarea name="description" class="form-control" required><?php echo $complaint['description']; ?></textarea>
</div>

<button type="submit" name="update" class="btn btn-success">Update Complaint</button>
<a href="dashboard.php" class="btn btn-secondary">Cancel</a>

</form>

</div>
</div>
</div>

</body>
</html>