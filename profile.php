<?php
include("config.php");

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

$result = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");

if(!$result) {
    die("User query failed: " . mysqli_error($conn));
}

$user = mysqli_fetch_assoc($result);

if(isset($_POST['update'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $query = "UPDATE users SET name='$name', email='$email' WHERE id='$user_id'";

    if(mysqli_query($conn, $query)) {
        $_SESSION['user'] = $name;
        $message = "Profile updated successfully.";

        $result = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
        $user = mysqli_fetch_assoc($result);
    } else {
        $message = "Profile update failed.";
    }
}

if(isset($_POST['change_password'])) {

    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    if(password_verify($old_password, $user['password'])) {

        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        if(mysqli_query($conn, "UPDATE users SET password='$hashed_password' WHERE id='$user_id'")) {
            $message = "Password changed successfully.";
        } else {
            $message = "Password update failed.";
        }

    } else {
        $message = "Old password is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="style.css?v=50">
</head>
<body>

<?php include("header.php"); ?>

<section class="page-header">
    <h1>My Profile</h1>
    <p>Update your account information and password.</p>
</section>

<div class="profile-top-card">
    <div class="profile-avatar">
        <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
    </div>

    <div>
        <h2><?php echo htmlspecialchars($user['name']); ?></h2>
        <p><?php echo htmlspecialchars($user['email']); ?></p>
    </div>
</div>

<?php if($message != "") { ?>
    <p class="success profile-message"><?php echo htmlspecialchars($message); ?></p>
<?php } ?>

<div class="profile-container">

    <div class="form-box">
        <h2>Update Profile</h2>

        <form method="POST">
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <button type="submit" name="update">Update Profile</button>
        </form>
    </div>

    <div class="form-box">
        <h2>Change Password</h2>

        <form method="POST">
            <input type="password" name="old_password" placeholder="Old Password" required>

            <input type="password" name="new_password" placeholder="New Password" required>

            <button type="submit" name="change_password">Change Password</button>
        </form>
    </div>

</div>

<script src="scripts.js?v=50"></script>
</body>
</html>