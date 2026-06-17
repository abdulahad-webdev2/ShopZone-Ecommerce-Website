<?php
include("config.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = "";
$success = "";

if (isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($name == "" || $email == "" || $password == "") {
        $error = "Please fill all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        $check = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        $checkResult = mysqli_stmt_get_result($check);

        if ($checkResult && mysqli_num_rows($checkResult) > 0) {
            $error = "Email already registered. Please login.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = mysqli_prepare($conn, "INSERT INTO users(name, email, password) VALUES(?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashedPassword);

            if (mysqli_stmt_execute($stmt)) {
                $success = "Registration successful. You can now login.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - ShopZone</title>

    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<?php include("header.php"); ?>

<section class="auth-page premium-auth-page">
    <div class="auth-box premium-auth-box">
        <div class="auth-top-badge">Create Account</div>

        <h2>Register</h2>
        <p class="auth-subtitle">
            Create your ShopZone account and enjoy a smooth, secure and premium shopping experience.
        </p>

        <?php if ($error != "") { ?>
            <div class="auth-alert error"><?php echo htmlspecialchars($error); ?></div>
        <?php } ?>

        <?php if ($success != "") { ?>
            <div class="auth-alert success"><?php echo htmlspecialchars($success); ?></div>
        <?php } ?>

        <form method="POST" action="" class="premium-auth-form">
            <div class="auth-input-group">
                <span class="auth-input-icon"><i class="fa-solid fa-user"></i></span>
                <input type="text" name="name" placeholder="Full Name" required>
            </div>

            <div class="auth-input-group">
                <span class="auth-input-icon"><i class="fa-solid fa-envelope"></i></span>
                <input type="email" name="email" placeholder="Email Address" required>
            </div>

            <div class="auth-input-group">
                <span class="auth-input-icon"><i class="fa-solid fa-lock"></i></span>
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <button type="submit" name="register" class="auth-btn premium-auth-btn">
                Create Account
            </button>
        </form>

        <p class="auth-switch">
            Already have an account?
            <a href="login.php">Login</a>
        </p>
    </div>
</section>

<script src="scripts.js?v=<?php echo time(); ?>"></script>
</body>
</html>