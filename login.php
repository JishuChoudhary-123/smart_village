<?php
session_start();
include 'includes/db.php';

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // 🔥 ROLE BASED REDIRECTION
            if ($user['role'] == 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();

        } else {
            $message = "Incorrect password!";
            $message_type = "danger";
        }

    } else {
        $message = "User not found!";
        $message_type = "danger";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login - Rajaqpur Smart Village</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(to right, #4ca1af, #2c3e50);
    min-height: 100vh;
}
.login-card {
    max-width: 450px;
    margin: auto;
    margin-top: 90px;
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
<a href="register.php" class="btn btn-outline-light btn-sm">Register</a>
</div>
</nav>

<div class="container">
<div class="card shadow-lg login-card">

<h3 class="text-center mb-4">User Login</h3>

<?php if($message != "") { ?>
<div class="alert alert-<?php echo $message_type; ?> text-center">
<?php echo $message; ?>
</div>
<?php } ?>

<form method="POST" autocomplete="off">

<div class="mb-3">
<label class="form-label">Email Address</label>
<input type="email" name="email" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Password</label>
<input type="password" name="password" class="form-control" required>
</div>

<button type="submit" class="btn btn-dark w-100">Login</button>

<div class="text-center mt-3">
Don't have an account? 
<a href="register.php">Register here</a>
</div>

</form>

</div>
</div>

</body>
</html>