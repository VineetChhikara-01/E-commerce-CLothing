<?php
require 'db_connect.php';

// Ensure session is started for cart manipulation
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Handle AJAX Update
if (isset($_POST['ajax_update'])) {
    $product_id = $_POST['product_id'];
    $quantity = (int) $_POST['quantity'];

    if ($quantity <= 0) {
        unset($_SESSION['cart'][$product_id]);
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    // Recalculate totals
    $total_price = 0;
    $item_subtotal = 0;
    $total_items = 0;

    foreach ($_SESSION['cart'] as $id => $qty) {
        $total_items += $qty;
        $sql = "SELECT price FROM products WHERE id='$id'";
        $result = mysqli_query($conn, $sql);
        $product = mysqli_fetch_assoc($result);

        if ($product) {
            $subtotal = $product['price'] * $qty;
            $total_price += $subtotal;

            if ($id == $product_id) {
                $item_subtotal = $subtotal;
            }
        }
    }

    echo json_encode([
        'subtotal' => number_format($item_subtotal),
        'total' => number_format($total_price),
        'count' => $total_items
    ]);
    exit;
}

$page_css = 'cart.css';
require 'includes/header.php';

// Handle Add to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
    echo "<script>window.location.href='cart.php';</script>";
}

// Handle Remove
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    unset($_SESSION['cart'][$id]);
    echo "<script>window.location.href='cart.php';</script>";
}

// Handle Update
if (isset($_POST['qty'])) {
    foreach ($_POST['qty'] as $id => $qty) {
        if ($qty == 0) {
            unset($_SESSION['cart'][$id]);
        } else {
            $_SESSION['cart'][$id] = $qty;
        }
    }
    echo "<script>window.location.href='cart.php';</script>";
}
?>

<main class="cart-main">
    <div class="cart-container">
        <h1 class="cart-title">Shopping Cart</h1>

        <?php if (empty($_SESSION['cart'])): ?>
            <div class="cart-empty">
                <p class="cart-empty-text">Your cart is empty.</p>
                <a href="explore.php" class="cart-shop-now-btn">Shop Now</a>
            </div>
        <?php else: ?>
            <form action="" method="post">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_price = 0;
                        foreach ($_SESSION['cart'] as $id => $qty) {
                            $sql = "SELECT * FROM products WHERE id='$id'";
                            $result = mysqli_query($conn, $sql);
                            $product = mysqli_fetch_assoc($result);

                            if ($product) {
                                $subtotal = $product['price'] * $qty;
                                $total_price += $subtotal;
                                $product_name = htmlspecialchars($product['name']);
                                ?>
                                <tr>
                                    <td class="cart-td-product">
                                        <a href="product.php?id=<?php echo $id; ?>">
                                            <img src="assets/images/<?php echo $product['image']; ?>"
                                                alt="<?php echo $product_name; ?>" class="cart-product-img">
                                        </a>
                                        <a href="product.php?id=<?php echo $id; ?>" class="cart-product-link">
                                            <span class="cart-product-name"><?php echo $product_name; ?></span>
                                        </a>
                                    </td>
                                    <td class="cart-td-price" data-product-name="<?php echo $product_name; ?>">
                                        ₹<?php echo number_format($product['price']); ?></td>
                                    <td class="cart-td-qty">
                                        <input type="number" name="qty[<?php echo $id; ?>]" value="<?php echo $qty; ?>" min="1"
                                            class="cart-qty-input">
                                    </td>
                                    <td class="cart-td-total">₹<?php echo number_format($subtotal); ?></td>
                                    <td class="cart-td-action">
                                        <a href="cart.php?remove=<?php echo $id; ?>" class="cart-remove-link">Remove</a>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr class="cart-summary-row">
                            <td colspan="3" class="cart-summary-label">Grand Total:</td>
                            <td class="cart-summary-total">₹<?php echo number_format($total_price); ?></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>

                <div class="cart-actions">
                    <a href="explore.php" class="cart-btn cart-update-btn">Continue Shopping</a>
                    <a href="checkout.php" class="cart-btn cart-checkout-btn">Proceed to Checkout</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const qtyInputs = document.querySelectorAll('.cart-qty-input');

        qtyInputs.forEach(input => {
            input.addEventListener('input', function () {
                const qty = this.value;
                const productId = this.name.match(/\[(\d+)\]/)[1];
                const row = this.closest('tr');
                const totalCell = row.querySelector('.cart-td-total');
                const grandTotalCell = document.querySelector('.cart-summary-total');

                if (qty < 1) return; // Prevent invalid updates

                const formData = new FormData();
                formData.append('ajax_update', '1');
                formData.append('product_id', productId);
                formData.append('quantity', qty);

                fetch('cart.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        totalCell.textContent = '₹' + data.subtotal;
                        grandTotalCell.textContent = '₹' + data.total;

                        // Update Header Cart Count
                        let badge = document.querySelector('.header-cart-badge');
                        const cartLink = document.querySelector('a[href="cart.php"]');

                        if (data.count > 0) {
                            if (!badge && cartLink) {
                                badge = document.createElement('span');
                                badge.className = 'header-cart-badge';
                                cartLink.appendChild(badge);
                            }
                            if (badge) {
                                badge.textContent = data.count;
                                badge.style.display = 'inline-block';
                            }
                        } else if (badge) {
                            badge.style.display = 'none';
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    });
</script>

<?php require('includes/footer.php'); ?>