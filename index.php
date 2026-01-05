<?php
require 'db_connect.php';
$page_css = 'home.css';

// Fetch latest products
$sql_latest = "SELECT id, name, price, image FROM products WHERE status='active' ORDER BY id DESC LIMIT 8";
$result_latest = $conn->query($sql_latest);
$latest_products = [];
if ($result_latest) {
    while ($row = $result_latest->fetch_assoc()) {
        $latest_products[] = $row;
    }
}

require 'includes/header.php';
?>

<!-- Hero Slider Section -->
<div class="hero-slider">
    <div class="slide active slide-1">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h2>New Collection 2026</h2>
            <p>Discover the best trends in fashion.</p>
            <a href="explore.php" class="hero-btn">Shop Now</a>
        </div>
    </div>
    <div class="slide slide-2">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h2>Urban & Street Style</h2>
            <p>Express yourself with our latest city looks.</p>
            <a href="explore.php" class="hero-btn">Explore</a>
        </div>
    </div>
    <div class="slide slide-3">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h2>Minimalist Aesthetics</h2>
            <p>Simplicity is the ultimate sophistication.</p>
            <a href="explore.php" class="hero-btn">Browse</a>
        </div>
    </div>
    <div class="slide slide-4">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h2>Summer Vibes</h2>
            <p>Get ready for the sunny days ahead.</p>
            <a href="explore.php" class="hero-btn">Summer Sale</a>
        </div>
    </div>
    <div class="slide slide-5">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h2>Accessories & More</h2>
            <p>Complete your outfit with the perfect touch.</p>
            <a href="explore.php" class="hero-btn">Shop Accessories</a>
        </div>
    </div>
</div>

<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-slider .slide');
    const totalSlides = slides.length;

    function nextSlide() {
        slides[currentSlide].classList.remove('active');
        currentSlide = (currentSlide + 1) % totalSlides;
        slides[currentSlide].classList.add('active');
    }

    setInterval(nextSlide, 5000);
</script>

<!-- Highlights / Categories -->
<div class="container home-container">
    <h2>Featured Products</h2>
    <p>Check out our latest arrivals on the Explore page!</p>
    <br>

    <div class="home-product-grid">
        <?php if (!empty($latest_products)): ?>
            <?php
            $card_index = 0;
            foreach ($latest_products as $product):
                $card_index++;
                $pid = (int) $product['id'];
                $pname = htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8');
                $pprice = number_format($product['price']);
                $pimage = htmlspecialchars($product['image']);
                ?>
                <article class="home-product-card" data-index="<?php echo $card_index; ?>">
                    <a href="product.php?id=<?php echo $pid; ?>" class="home-product-image-link">
                        <img src="assets/images/<?php echo $pimage; ?>" alt="<?php echo $pname; ?>" class="home-product-image"
                            loading="lazy">
                    </a>

                    <div class="home-product-info">
                        <h3 class="home-product-name">
                            <a href="product.php?id=<?php echo $pid; ?>" class="home-product-name-link">
                                <?php echo $pname; ?>
                            </a>
                        </h3>

                        <div class="home-product-price">
                            <span class="home-currency">₹</span><?php echo $pprice; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-products">No new products available at the moment.</p>
        <?php endif; ?>
    </div>

    <a href="explore.php" class="btn">View All Products</a>
</div>

<?php require 'includes/footer.php'; ?>