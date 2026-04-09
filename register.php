<?php
session_start();
include 'includes/db.php';

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $raw_password = $_POST['password'];
    $phone = trim($_POST['phone']);
    $role = "user";

    // Basic Validation
    if(strlen($raw_password) < 6){
        $message = "Password must be at least 6 characters.";
        $message_type = "danger";
    } else {

        $password = password_hash($raw_password, PASSWORD_DEFAULT);

        // Check if email already exists
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $message = "Email already registered!";
            $message_type = "danger";
        } else {

            $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $email, $password, $phone, $role);
            $stmt->execute();

            $message = "Registration successful! You can login now.";
            $message_type = "success";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register - Rajaqpur Smart Village</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(to right, #2c3e50, #4ca1af);
    min-height: 100vh;
}
.register-card {
    max-width: 500px;
    margin: auto;
    margin-top: 70px;
    padding: 30px;
    border-radius: 12px;
}
.brand-title {
    font-weight: 600;
    letter-spacing: 1px;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-dark shadow-sm">
<div class="container">
<a class="navbar-brand brand-title" href="index.php">🏡 Rajaqpur Smart Village</a>
<a href="login.php" class="btn btn-outline-light btn-sm">Login</a>
</div>
</nav>

<div class="container">
<div class="card shadow-lg register-card">

<h3 class="text-center mb-4">Create Your Account</h3>

<?php if($message != "") { ?>
<div class="alert alert-<?php echo $message_type; ?> text-center">
<?php echo $message; ?>
</div>
<?php } ?>

<form method="POST" autocomplete="off">

<div class="mb-3">
<label class="form-label">Full Name</label>
<input type="text" name="name" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Email Address</label>
<input type="email" name="email" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Password</label>
<input type="password" name="password" class="form-control" required>
<small class="text-muted">Minimum 6 characters</small>
</div>

<div class="mb-3">
<label class="form-label">Phone Number</label>
<input type="text" name="phone" class="form-control" required>
</div>

<button type="submit" class="btn btn-dark w-100">Register</button>

<div class="text-center mt-3">
Already have an account? 
<a href="login.php">Login here</a>
</div>

</form>

</div>
</div>

</body>
</html>