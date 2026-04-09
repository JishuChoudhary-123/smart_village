<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$stmt = $conn->prepare("SELECT name, email, phone FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Update profile
if (isset($_POST['update'])) {

    $name = $_POST['name'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("UPDATE users SET name=?, phone=? WHERE id=?");
    $stmt->bind_param("ssi", $name, $phone, $user_id);
    $stmt->execute();

    $_SESSION['user_name'] = $name;

    header("Location: profile.php");
    exit();
}

// Change password
if (isset($_POST['change_password'])) {

    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
    $stmt->bind_param("si", $new_password, $user_id);
    $stmt->execute();

    $message = "Password updated successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>My Profile</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

<h3 class="mb-4">👤 My Profile</h3>

<?php if(isset($message)) { ?>
<div class="alert alert-success"><?php echo $message; ?></div>
<?php } ?>

<div class="row">

<!-- PROFILE INFO -->
<div class="col-md-6">
<div class="card shadow">
<div class="card-header bg-primary text-white">
Profile Information
</div>
<div class="card-body">

<form method="POST">

<div class="mb-3">
<label>Name</label>
<input type="text" name="name" class="form-control"
value="<?php echo $user['name']; ?>" required>
</div>

<div class="mb-3">
<label>Email (readonly)</label>
<input type="email" class="form-control"
value="<?php echo $user['email']; ?>" readonly>
</div>

<div class="mb-3">
<label>Phone</label>
<input type="text" name="phone" class="form-control"
value="<?php echo $user['phone']; ?>" required>
</div>

<button type="submit" name="update" class="btn btn-success">
Update Profile
</button>

</form>

</div>
</div>
</div>

<!-- CHANGE PASSWORD -->
<div class="col-md-6">
<div class="card shadow">
<div class="card-header bg-warning">
Change Password
</div>
<div class="card-body">

<form method="POST">

<div class="mb-3">
<label>New Password</label>
<input type="password" name="password" class="form-control" required>
</div>

<button type="submit" name="change_password" class="btn btn-dark">
Update Password
</button>

</form>

</div>
</div>
</div>

</div>

<div class="mt-4">
<a href="dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
</div>

</div>

</body>
</html>