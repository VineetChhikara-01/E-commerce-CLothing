<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cart count
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $quantity) {
        $cart_count += $quantity;
    }
}

// Get current page for active state
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clothing Setup</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <?php if (isset($page_css)): ?>
        <?php if (is_array($page_css)): ?>
            <?php foreach ($page_css as $css): ?>
                <link rel="stylesheet" href="assets/css/<?php echo $css; ?>">
            <?php endforeach; ?>
        <?php else: ?>
            <link rel="stylesheet" href="assets/css/<?php echo $page_css; ?>">
        <?php endif; ?>
    <?php endif; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

    <header class="header" id="main-header">
        <div class="header-container">

            <!-- Logo -->
            <div class="header-logo">
                <a href="index.php" class="header-logo-link">
                    <h1 class="header-logo-text">Clothing Shop</h1>
                </a>
            </div>

            <!-- Hamburger Menu -->
            <button class="header-hamburger" id="header-hamburger" aria-label="Toggle Menu">
                <span class="header-hamburger-line"></span>
                <span class="header-hamburger-line"></span>
                <span class="header-hamburger-line"></span>
            </button>

            <!-- Navigation -->
            <nav class="header-nav" id="header-nav">
                <ul class="header-nav-list">
                    <li class="header-nav-item">
                        <a href="index.php"
                            class="header-nav-link <?php echo ($current_page == 'index.php') ? 'header-nav-link-active' : ''; ?>">
                            <i class="fas fa-home header-nav-icon"></i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li class="header-nav-item">
                        <a href="explore.php"
                            class="header-nav-link <?php echo ($current_page == 'explore.php') ? 'header-nav-link-active' : ''; ?>">
                            <i class="fas fa-compass header-nav-icon"></i>
                            <span>Explore</span>
                        </a>
                    </li>
                    <li class="header-nav-item">
                        <a href="about.php"
                            class="header-nav-link <?php echo ($current_page == 'about.php') ? 'header-nav-link-active' : ''; ?>">
                            <i class="fas fa-info-circle header-nav-icon"></i>
                            <span>About</span>
                        </a>
                    </li>
                    <li class="header-nav-item">
                        <a href="cart.php"
                            class="header-nav-link <?php echo ($current_page == 'cart.php') ? 'header-nav-link-active' : ''; ?>">
                            <i class="fas fa-shopping-cart header-nav-icon"></i>
                            <span>Cart</span>
                            <?php if ($cart_count > 0): ?>
                                <span class="header-cart-badge"><?php echo $cart_count; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>
            </nav>

        </div>
    </header>

    <script>
        // Toggle mobile menu
        const hamburger = document.getElementById('header-hamburger');
        const nav = document.getElementById('header-nav');

        hamburger.addEventListener('click', function () {
            this.classList.toggle('header-hamburger-active');
            nav.classList.toggle('header-nav-active');
        });

        // Add scrolled class to header on scroll
        window.addEventListener('scroll', function () {
            const header = document.getElementById('main-header');
            if (window.scrollY > 50) {
                header.classList.add('header-scrolled');
            } else {
                header.classList.remove('header-scrolled');
            }
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function (event) {
            const isClickInsideNav = nav.contains(event.target);
            const isClickInsideHamburger = hamburger.contains(event.target);

            if (!isClickInsideNav && !isClickInsideHamburger && nav.classList.contains('header-nav-active')) {
                nav.classList.remove('header-nav-active');
                hamburger.classList.remove('header-hamburger-active');
            }
        });
    </script>