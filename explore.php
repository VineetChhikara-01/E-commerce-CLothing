<?php
session_start();
require 'db_connect.php';

// Handle Add to Cart (AJAX POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set headers
    header('Content-Type: application/json');
    header('Cache-Control: no-cache, no-store, must-revalidate');

    // Disable error output for clean JSON
    error_reporting(0);
    ini_set('display_errors', 0);

    $response = [
        'success' => false,
        'message' => '',
        'cart_count' => 0
    ];

    try {
        // Get product_id & quantity
        $product_id = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
        $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;

        // Validation
        if ($product_id <= 0) {
            throw new Exception('Invalid product');
        }
        if ($quantity <= 0)
            $quantity = 1;

        // Check product exists and is active
        $stmt = $conn->prepare("SELECT id, name, status FROM products WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception('Product not found');
        }

        $product = $result->fetch_assoc();
        $stmt->close();

        if ($product['status'] !== 'active') {
            throw new Exception('Product unavailable');
        }

        // Initialize cart
        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Add/Update cart
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }

        // Calculate total
        $cart_count = array_sum($_SESSION['cart']);

        // Success Response
        $response['success'] = true;
        $response['message'] = 'Added to cart';
        $response['cart_count'] = $cart_count;
        $response['product_name'] = $product['name'];

    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }

    echo json_encode($response);
    exit; // Stop executing the rest of the page
}

$page_css = 'explore.css';

// Grid settings - max 6 rows
$rows = 6;
$default_columns = 4;
$items_per_page = $rows * $default_columns; // 24 items max

// Get current page
$current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$current_page = max(1, $current_page);

// Get sort parameter
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Get search parameter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Default ordering
$order_by = "created_at DESC, id DESC";

// Build WHERE clause - Active only
$where_clause = "WHERE status='active'";

// Fetch ALL products
$sql = "SELECT id, name, price, image, color, size FROM products $where_clause ORDER BY $order_by";
$result = $conn->query($sql);

$products = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
$product_count = count($products);

require 'includes/header.php';
?>

<main class="explore-container">

    <!-- Page Header -->
    <div class="explore-header">
        <h1 class="explore-title">Explore Collection</h1>
        <p class="explore-subtitle">Curated styles for the modern individual.</p>
    </div>

    <!-- Product Grid -->
    <div class="explore-product-grid" id="productGrid">
        <?php if ($product_count > 0): ?>
            <?php
            $card_index = 0;
            foreach ($products as $product):
                $card_index++;
                $pid = (int) $product['id'];
                $pname = htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8');
                $pprice = number_format($product['price']);
                $pimage = htmlspecialchars($product['image']);
                ?>
                <article class="explore-product-card" data-index="<?php echo $card_index; ?>">
                    <a href="product.php?id=<?php echo $pid; ?>" class="explore-product-image-link">
                        <img src="assets/images/<?php echo $pimage; ?>" alt="<?php echo $pname; ?>"
                            class="explore-product-image" loading="lazy">
                    </a>

                    <div class="explore-product-info">
                        <h3 class="explore-product-name">
                            <a href="product.php?id=<?php echo $pid; ?>" class="explore-product-name-link">
                                <?php echo $pname; ?>
                            </a>
                        </h3>

                        <div class="explore-product-price">
                            <span class="explore-currency">₹</span><?php echo $pprice; ?>
                        </div>

                        <button type="button" class="explore-add-cart-btn"
                            onclick="addToCart(<?php echo $pid; ?>, '<?php echo addslashes($pname); ?>', this)">
                            <i class="fas fa-shopping-bag explore-cart-icon"></i>
                            <span class="explore-cart-text">Add to Cart</span>
                        </button>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="explore-empty-state">
                <div class="explore-empty-icon">
                    <i class="fas fa-box-open"></i>
                </div>
                <h3 class="explore-empty-title">No Products Available</h3>
                <p class="explore-empty-text">Check back later for new arrivals.</p>
            </div>
        <?php endif; ?>
    </div>

</main>

<!-- Toast Notification -->
<div class="explore-toast" id="cartToast">
    <div class="explore-toast-icon-wrap" id="toastIconWrap">
        <i class="fas fa-check" id="toastIcon"></i>
    </div>
    <div class="explore-toast-body">
        <p class="explore-toast-title" id="toastTitle">Success</p>
        <p class="explore-toast-msg" id="toastMessage">Added to cart</p>
    </div>
    <button class="explore-toast-close" type="button" onclick="hideToast()">
        <i class="fas fa-times"></i>
    </button>
</div>

<script>
    // Add to Cart function
    function addToCart(productId, productName, btn) {
        if (btn.disabled) return;

        // Disable button and show loading
        btn.disabled = true;
        var originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin explore-cart-icon"></i><span class="explore-cart-text">Adding...</span>';

        // Create XMLHttpRequest
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'explore.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function () {
            btn.disabled = false;
            btn.innerHTML = originalHTML;

            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        showToast(productName + ' added to cart!', true);
                        updateCartBadge(response.cart_count);
                    } else {
                        showToast(response.message || 'Failed to add to cart', false);
                    }
                } catch (e) {
                    showToast('Error processing request', false);
                }
            } else {
                showToast('Network error occurred', false);
            }
        };

        xhr.onerror = function () {
            btn.disabled = false;
            btn.innerHTML = originalHTML;
            showToast('Network error occurred', false);
        };

        xhr.send('product_id=' + productId + '&quantity=1');
    }

    // Update cart badge
    function updateCartBadge(count) {
        var badge = document.querySelector('.header-cart-badge');

        // If badge doesn't exist and we have items, create it
        if (!badge && count > 0) {
            var cartLink = document.querySelector('a[href="cart.php"]');
            if (cartLink) {
                badge = document.createElement('span');
                badge.className = 'header-cart-badge';
                cartLink.appendChild(badge);
            }
        }

        if (badge) {
            if (count > 0) {
                badge.style.display = 'inline-flex';
                badge.textContent = count;
                badge.style.transform = 'scale(1.3)';
                setTimeout(function () {
                    badge.style.transform = 'scale(1)';
                }, 200);
            } else {
                badge.style.display = 'none';
            }
        }
    }

    // Show toast notification
    function showToast(message, isSuccess) {
        var toast = document.getElementById('cartToast');
        var title = document.getElementById('toastTitle');
        var msg = document.getElementById('toastMessage');
        var icon = document.getElementById('toastIcon');

        // Reset classes
        toast.className = 'explore-toast';

        if (isSuccess) {
            toast.classList.add('explore-toast-success');
            title.textContent = 'Success!';
            icon.className = 'fas fa-check';
        } else {
            toast.classList.add('explore-toast-error');
            title.textContent = 'Error';
            icon.className = 'fas fa-exclamation-triangle';
        }

        msg.textContent = message;
        toast.classList.add('explore-toast-show');

        // Auto hide
        setTimeout(hideToast, 4000);
    }

    // Hide toast
    function hideToast() {
        var toast = document.getElementById('cartToast');
        toast.classList.remove('explore-toast-show');
    }
</script>

<?php require 'includes/footer.php'; ?>