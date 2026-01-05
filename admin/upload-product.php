<?php
require 'auth_session.php';
require '../db_connect.php';

$message = "";

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $color = mysqli_real_escape_string($conn, $_POST['color']);
    $cloth_type = mysqli_real_escape_string($conn, $_POST['cloth_type']);
    $length = mysqli_real_escape_string($conn, $_POST['length']);
    $size = mysqli_real_escape_string($conn, $_POST['size']);
    $material = mysqli_real_escape_string($conn, $_POST['material']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Image Upload Logic
    $target_dir = "../assets/images/";
    // Create connection-safe filename
    $filename = time() . "_" . basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $filename;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        $message = "File is not an image.";
        $uploadOk = 0;
    }

    // Allow sure file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Insert into DB
            $sql = "INSERT INTO products (name, price, color, cloth_type, length, size, material, description, image, status)
                    VALUES ('$name', '$price', '$color', '$cloth_type', '$length', '$size', '$material', '$description', '$filename', 'active')";

            if (mysqli_query($conn, $sql)) {
                $message = "Product uploaded successfully!";
            } else {
                $message = "Database Error: " . mysqli_error($conn);
            }
        } else {
            $message = "Sorry, there was an error uploading your file.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Upload Product - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-nav {
            background: #eee;
            padding: 10px;
            text-align: center;
            margin-bottom: 20px;
        }

        .admin-nav a {
            margin: 0 15px;
            font-weight: bold;
            color: #333;
        }

        .form-container {
            width: 500px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
        }
    </style>
</head>

<body>

    <div class="admin-nav">
        <span>Welcome, <?php echo $_SESSION['admin_username']; ?></span>
        <a href="upload-product.php">Upload Product</a>
        <a href="orders.php">View Orders</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <div class="form-container">
            <h2 style="text-align: center;">Upload New Product</h2>
            <?php if ($message) {
                echo "<p style='text-align:center; color:blue; font-weight:bold;'>$message</p>";
            } ?>

            <form action="" method="post" enctype="multipart/form-data">
                <label for="name">Product Name</label>
                <input type="text" id="name" name="name" required>

                <label for="price">Price (₹)</label>
                <input type="number" step="0.1" min="0" id="price" name="price" required>

                <label for="color">Color</label>
                <input type="text" id="color" name="color">

                <label for="cloth_type">Cloth Type</label>
                <input type="text" id="cloth_type" name="cloth_type">

                <label for="length">Length</label>
                <input type="text" id="length" name="length">

                <label for="size">Size</label>
                <input type="text" id="size" name="size" placeholder="e.g. S, M, L, XL">

                <label for="material">Material</label>
                <input type="text" id="material" name="material">

                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4"
                    style="resize: vertical; max-width: 100%;"></textarea>

                <label for="image">Product Image</label>
                <input type="file" id="image" name="image" required>

                <button type="submit" name="submit" class="btn btn-primary" style="width:100%; margin-top: 1rem;">Upload
                    Product</button>
            </form>
        </div>
    </div>

</body>

</html>