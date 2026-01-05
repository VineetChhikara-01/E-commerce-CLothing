<?php
$page_css = 'about.css';
require 'includes/header.php';
?>

<main class="about-main">
    <div class="about-container">
        
        <!-- Hero Section -->
        <section class="about-hero">
            <div class="about-hero-content">
                <h1 class="about-title">Clothing Shop</h1>
                <p class="about-tagline">Fashion that speaks your style</p>
            </div>
        </section>

        <!-- Story Section -->
        <section class="about-story">
            <div class="about-story-icon">
                <i class="fas fa-heart"></i>
            </div>
            <h2 class="about-story-title">Our Story</h2>
            <p class="about-text">
                Welcome to <strong>Clothing Shop</strong> – your ultimate destination for trendy and high-quality clothing. 
                We are dedicated to providing the best fashion trends with premium materials and exceptional craftsmanship.
                Our goal is to make you look good and feel confident in every outfit you wear.
            </p>
        </section>

        <!-- Values Section -->
        <section class="about-values">
            <h2 class="about-section-title">What We Stand For</h2>
            <div class="about-values-grid">
                <div class="about-value-card">
                    <div class="about-value-icon">
                        <i class="fas fa-gem"></i>
                    </div>
                    <h3 class="about-value-title">Quality</h3>
                    <p class="about-value-text">Premium materials and craftsmanship in every piece we create.</p>
                </div>
                <div class="about-value-card">
                    <div class="about-value-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3 class="about-value-title">Sustainability</h3>
                    <p class="about-value-text">Eco-friendly practices for a better tomorrow.</p>
                </div>
                <div class="about-value-card">
                    <div class="about-value-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3 class="about-value-title">Style</h3>
                    <p class="about-value-text">Trendy designs that make you stand out from the crowd.</p>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="about-stats">
            <div class="about-stat-item">
                <span class="about-stat-number">5000+</span>
                <span class="about-stat-label">Happy Customers</span>
            </div>
            <div class="about-stat-divider"></div>
            <div class="about-stat-item">
                <span class="about-stat-number">500+</span>
                <span class="about-stat-label">Products</span>
            </div>
            <div class="about-stat-divider"></div>
            <div class="about-stat-item">
                <span class="about-stat-number">50+</span>
                <span class="about-stat-label">Collections</span>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="about-contact">
            <h2 class="about-section-title">Get In Touch</h2>
            <p class="about-contact-subtitle">We'd love to hear from you! Reach out to us through any of these channels.</p>
            
            <div class="about-contact-grid">
                <!-- Phone Card -->
                <a href="tel:+910000000000" class="about-contact-card">
                    <div class="about-contact-icon about-contact-icon-phone">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <h3 class="about-contact-title">Call Us</h3>
                    <p class="about-contact-info">+91 0000000000</p>
                    <span class="about-contact-action">Tap to call <i class="fas fa-arrow-right"></i></span>
                </a>

                <!-- WhatsApp Card -->
                <a href="https://wa.me/910000000000?text=Hello%20JK%20Rathva%20Fashion!" target="_blank" rel="noopener" class="about-contact-card">
                    <div class="about-contact-icon about-contact-icon-whatsapp">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <h3 class="about-contact-title">WhatsApp</h3>
                    <p class="about-contact-info">+91 0000000000</p>
                    <span class="about-contact-action">Chat with us <i class="fas fa-arrow-right"></i></span>
                </a>

                <!-- Email Card -->
                <a href="mailto:demo@gmail.com" class="about-contact-card">
                    <div class="about-contact-icon about-contact-icon-email">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3 class="about-contact-title">Email Us</h3>
                    <p class="about-contact-info">demo@gmail.com</p>
                    <span class="about-contact-action">Send email <i class="fas fa-arrow-right"></i></span>
                </a>
            </div>
        </section>

        <!-- Owner Section -->
        <section class="about-owner">
            <div class="about-owner-content">
                <div class="about-owner-avatar">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="about-owner-info">
                    <span class="about-owner-label">Founder & Owner</span>
                    <h3 class="about-owner-name">Jagdish Rathva</h3>
                    <p class="about-owner-text">
                        Passionate about fashion and committed to bringing you the best styles at affordable prices.
                    </p>
                    <div class="about-owner-social">
                        <a href="tel:+910000000000" class="about-owner-social-link" title="Call">
                            <i class="fas fa-phone-alt"></i>
                        </a>
                        <a href="https://wa.me/910000000000" target="_blank" rel="noopener" class="about-owner-social-link" title="WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="mailto:demo@gmail.com" class="about-owner-social-link" title="Email">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="about-cta">
            <h2 class="about-cta-title">Ready to Explore?</h2>
            <p class="about-cta-text">Discover our latest collection and find your perfect style.</p>
            <a href="explore.php" class="about-cta-btn">
                <i class="fas fa-shopping-bag"></i>
                <span>Shop Now</span>
            </a>
        </section>

    </div>
</main>

<?php require 'includes/footer.php'; ?>