<?php
include("config.php");

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";

if(isset($_POST['submit'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $category = strtolower(trim(mysqli_real_escape_string($conn, $_POST['category'])));
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $allowed_categories = ["men", "women", "accessories"];

    if(!in_array($category, $allowed_categories)) {
        $message = "Please select a valid category.";
    } elseif(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

        $image_name = time() . "_" . basename($_FILES['image']['name']);
        $target = $image_name;

        $file_type = strtolower(pathinfo($target, PATHINFO_EXTENSION));
        $allowed_types = array("jpg", "jpeg", "png", "jfif", "webp");

        if(in_array($file_type, $allowed_types)) {

            if(move_uploaded_file($_FILES['image']['tmp_name'], $target)) {

                $query = "INSERT INTO products(name, price, category, description, image)
                          VALUES('$name', '$price', '$category', '$description', '$image_name')";

                if(mysqli_query($conn, $query)) {
                    $message = "Product added successfully.";
                } else {
                    $message = "Database error: " . mysqli_error($conn);
                }

            } else {
                $message = "Image upload failed.";
            }

        } else {
            $message = "Only JPG, JPEG, PNG, JFIF and WEBP images are allowed.";
        }

    } else {
        $message = "Please select product image.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="style.css?v=100000">
</head>
<body>

<?php include("header.php"); ?>

<div class="form-box product-form">
    <h2>Add New Product</h2>

    <?php if($message != "") { ?>
        <p class="success"><?php echo htmlspecialchars($message); ?></p>
    <?php } ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Product Name" required>

        <input type="number" name="price" placeholder="Product Price" required>

        <select name="category" required>
            <option value="">Select Category</option>
            <option value="men">Men</option>
            <option value="women">Women</option>
            <option value="accessories">Accessories</option>
        </select>

        <textarea name="description" placeholder="Product Description" required></textarea>

        <input type="file" name="image" required>

        <button type="submit" name="submit">Add Product</button>
    </form>
</div>

<script src="scripts.js?v=50"></script>
</body>
</html>