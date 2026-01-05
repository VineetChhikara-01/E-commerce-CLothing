<?php
$page_css = 'success.css';
require('includes/header.php');
?>

<div class="container success-container">
    <div class="success-card">
        <!-- Success Icon -->
        <div class="success-icon-wrapper">
            <div class="success-icon-circle">
                <i class="fas fa-check"></i>
            </div>
        </div>

        <!-- Success Message Box -->
        <div class="success-message-box">
            <h1 class="success-title">Order Placed Successfully!</h1>
            <p class="success-text">Thank you for shopping with us.</p>
            <p class="success-subtext">Your order has been confirmed and will be shipped soon.</p>
        </div>

        <!-- Order ID Section -->
        <?php if (isset($_GET['orderid'])): ?>
            <div class="order-id-box">
                <p class="order-id-label">Your Order ID</p>
                <p class="order-id-text">#<?php echo htmlspecialchars($_GET['orderid']); ?></p>
                <p class="order-id-note">Please save this for your records</p>
            </div>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div class="success-actions">
            <a href="explore.php" class="btn-continue-shopping">
                <i class="fas fa-shopping-bag"></i>
                Continue Shopping
            </a>
            <a href="index.php" class="btn-go-home">
                <i class="fas fa-home"></i>
                Go to Home
            </a>
        </div>

        <!-- Contact Support -->
        <div class="support-info">
            <p class="support-text">
                <i class="fas fa-headset"></i>
                Need help? Call us at <span class="support-phone">+91 0000000000</span>
            </p>
        </div>
    </div>
</div>

<?php require('includes/footer.php'); ?>