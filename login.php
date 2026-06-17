<?php
include("config.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = "";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email == "" || $password == "") {
        $error = "Please enter email and password.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id, name, email, password FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password']) || $password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];

                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - ShopZone</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<?php include("header.php"); ?>

<section class="auth-page">
    <div class="auth-box">
        <span class="auth-label">Welcome Back</span>
        <h2>Login</h2>
        <p class="auth-subtitle">Login to your ShopZone account and continue shopping.</p>

        <?php if ($error != "") { ?>
            <div class="auth-alert error"><?php echo htmlspecialchars($error); ?></div>
        <?php } ?>

        <form method="POST" action="">
            <div class="auth-input-group">
                <i class="fa-solid fa-envelope"></i>
                <input type="email" name="email" placeholder="Email Address" required>
            </div>

            <div class="auth-input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <button type="submit" name="login" class="auth-btn">Login</button>
        </form>

        <p class="auth-switch">
            Don’t have an account?
            <a href="register.php">Register</a>
        </p>
    </div>
</section>

<script src="scripts.js?v=<?php echo time(); ?>"></script>
</body>
</html>