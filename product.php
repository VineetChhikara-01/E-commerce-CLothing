<?php
require('db_connect.php');
$page_css = 'product.css';
require('includes/header.php');

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "SELECT * FROM products WHERE id='$id'";
    $result = mysqli_query($conn, $sql);
    $product = mysqli_fetch_assoc($result);
}

if (!$product) {
    echo "<div class='container'><h2>Product not found</h2></div>";
    require('includes/footer.php');
    exit();
}
?>

<div class="product-page-wrapper">
    <div class="container product-container">
        <!-- Breadcrumb (Commented Out) -->
        <!-- <p class="breadcrumb"><a href="explore.php">Explore</a> > <?php echo $product['name']; ?></p> -->

        <div class="product-wrapper">
            <!-- Image Side -->
            <div class="product-image-section">
                <img src="assets/images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>"
                    class="product-image">
            </div>

            <!-- Details Side -->
            <div class="product-details-section">
                <h1 class="product-title"><?php echo $product['name']; ?></h1>
                <p class="price product-price">₹<?php echo $product['price']; ?></p>

                <div class="product-description-box">
                    <p><strong>Description:</strong></p>
                    <div class="product-description-scroll">
                        <?php echo nl2br($product['description']); ?>
                    </div>
                </div>
                <br>
                <p><strong>Color:</strong> <?php echo $product['color']; ?></p>
                <p><strong>Material:</strong> <?php echo $product['material']; ?></p>
                <p><strong>Size:</strong> <?php echo $product['size']; ?></p>
                <p><strong>Length:</strong> <?php echo $product['length']; ?></p>

                <form action="cart.php" method="post" class="product-form">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <label>Quantity:</label>
                    <input type="number" name="quantity" value="1" min="1" max="10" class="product-qty-input">
                    <button type="submit" name="add_to_cart" class="btn btn-primary add-to-cart-btn">Add to Cart <i
                            class="fas fa-shopping-cart"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require('includes/footer.php'); ?>