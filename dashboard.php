<?php
include_once 'function/header.php';
include_once 'function/topbar.php';
?>

<main>

    <!-- =====================================================
        HERO
    ====================================================== -->
    <section class="nv-hero">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div data-aos="fade-up">
                        <div class="nv-eyebrow">
                            <i class="bi bi-globe-americas"></i>
                            Global Trade & Export Solutions
                        </div>

                        <h1>
                            Connecting
                            <span>Indonesia</span>
                            to the World.
                        </h1>

                        <p class="nv-hero-description">
                            We connect high-quality Indonesian products with international markets through trusted export, import, and trading solutions.
                        </p>

                        <div class="nv-hero-actions">
                            <a href="#products" class="nv-btn-primary">
                                Explore Products <i class="bi bi-arrow-right"></i>
                            </a>
                            <a href="#contact" class="nv-btn-outline">
                                Talk With Us <i class="bi bi-chat"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="nv-hero-visual" data-aos="zoom-in" data-aos-delay="150">
                        <div class="nv-hero-image-wrap">
                            <img src="assets/img/hero.png" class="nv-hero-image" alt="Nexvorta Global Export">

                            <div class="nv-floating-card top">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="nv-floating-icon">
                                        <i class="bi bi-box-seam"></i>
                                    </div>
                                    <div>
                                        <strong>Global Products</strong>
                                        <small>Ready to expand</small>
                                    </div>
                                </div>
                            </div>

                            <div class="nv-floating-card bottom">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="nv-floating-icon">
                                        <i class="bi bi-graph-up-arrow"></i>
                                    </div>
                                    <div>
                                        <strong>Global Opportunities</strong>
                                        <small>Worldwide market access</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =====================================================
        STATS
    ====================================================== -->
    <section class="nv-trust">
        <div class="container">
            <div class="nv-trust-box" data-aos="fade-up">
                <div class="nv-stat">
                    <h3>01</h3>
                    <p>Trusted Trading Partner</p>
                </div>
                <div class="nv-stat">
                    <h3>03+</h3>
                    <p>Product Categories</p>
                </div>
                <div class="nv-stat">
                    <h3>Global</h3>
                    <p>Market Perspective</p>
                </div>
                <div class="nv-stat">
                    <h3>24/7</h3>
                    <p>Business Connectivity</p>
                </div>
            </div>
        </div>
    </section>

    <!-- =====================================================
        ABOUT
    ====================================================== -->
    <section id="about" class="nv-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="nv-about-image" data-aos="fade-right">
                        <img src="assets/img/about.png" alt="About Nexvorta">
                        <div class="nv-about-badge">
                            <strong>Global</strong>
                            <span>Business Perspective</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="nv-about-content" data-aos="fade-left">
                        <span class="nv-section-label">About Nexvorta</span>
                        <h2>
                            Turning Local Products Into <span>Global Opportunities.</span>
                        </h2>
                        <p>
                            Nexvorta is an export and import company focused on connecting Indonesian products with buyers and opportunities across international markets.
                        </p>
                        <p>
                            We believe every great local product deserves an opportunity to reach the world. Through market research, product positioning, and international business development, we help bridge local producers with global demand.
                        </p>

                        <ul class="nv-feature-list">
                            <li>
                                <i class="bi bi-check-lg"></i>
                                <span>Market-oriented export strategy and product positioning.</span>
                            </li>
                            <li>
                                <i class="bi bi-check-lg"></i>
                                <span>Carefully selected Indonesian products with strong market potential.</span>
                            </li>
                            <li>
                                <i class="bi bi-check-lg"></i>
                                <span>Long-term business relationships built on trust and professionalism.</span>
                            </li>
                        </ul>

                        <a href="<?php echo nexvortaUrl($base_url, $todayToken, 'about-us'); ?>" class="nv-btn-primary">
                            Discover Nexvorta <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =====================================================
        PRODUCTS
    ====================================================== -->
    <section id="products" class="nv-section nv-section-soft">
        <div class="container">
            <div class="nv-section-header" data-aos="fade-up">
                <span class="nv-section-label">Our Products</span>
                <h2>Products With Global Potential.</h2>
                <p>
                    We work with selected product categories from Indonesia, helping connect them with suitable international markets and business opportunities.
                </p>
            </div>

            <div class="row g-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up">
                    <div class="nv-product-card">
                        <div class="nv-product-number">01</div>
                        <div class="nv-product-icon">
                            <i class="bi bi-basket"></i>
                        </div>
                        <h3>Crafts & UMKM Products</h3>
                        <p>
                            Discover authentic Indonesian crafts and products from local businesses with unique cultural and commercial value.
                        </p>
                        <a href="<?php echo nexvortaUrl($base_url, $todayToken, 'products/crafts-umkm'); ?>" class="nv-product-link">
                            Explore Category <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="nv-product-card">
                        <div class="nv-product-number">02</div>
                        <div class="nv-product-icon">
                            <i class="bi bi-flower1"></i>
                        </div>
                        <h3>Agriculture & Plantations</h3>
                        <p>
                            Indonesian agricultural and plantation commodities selected for their quality, origin, and international market potential.
                        </p>
                        <a href="<?php echo nexvortaUrl($base_url, $todayToken, 'products/agriculture-plantations'); ?>" class="nv-product-link">
                            Explore Category <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="nv-product-card">
                        <div class="nv-product-number">03</div>
                        <div class="nv-product-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <h3>Livestock Farm Products</h3>
                        <p>
                            Explore products from Indonesia's livestock sector with a focus on quality, reliability, and responsible business development.
                        </p>
                        <a href="<?php echo nexvortaUrl($base_url, $todayToken, 'products/livestockfarm'); ?>" class="nv-product-link">
                            Explore Category <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =====================================================
        WHY NEXVORTA
    ====================================================== -->
    <section class="nv-section nv-why">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div data-aos="fade-right">
                        <span class="nv-section-label">Why Nexvorta</span>
                        <h2 style="font-size:44px; line-height:1.15; font-weight:800; letter-spacing:-1.5px;">
                            Built Around Your <br> Global Ambition.
                        </h2>
                        <p class="nv-why-description">
                            International trade requires more than simply moving products from one country to another. It requires market understanding, reliability, communication, and long-term relationships.
                        </p>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div data-aos="fade-left">
                        <div class="nv-why-item">
                            <div class="nv-why-icon">
                                <i class="bi bi-search"></i>
                            </div>
                            <div>
                                <h4>Market Research</h4>
                                <p>We study potential markets to identify opportunities that match your products.</p>
                            </div>
                        </div>

                        <div class="nv-why-item">
                            <div class="nv-why-icon">
                                <i class="bi bi-diagram-3"></i>
                            </div>
                            <div>
                                <h4>Strategic Positioning</h4>
                                <p>We help position products according to market needs and international demand.</p>
                            </div>
                        </div>

                        <div class="nv-why-item">
                            <div class="nv-why-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div>
                                <h4>Trusted Partnership</h4>
                                <p>We prioritize transparency, professionalism, and sustainable business relationships.</p>
                            </div>
                        </div>

                        <div class="nv-why-item">
                            <div class="nv-why-icon">
                                <i class="bi bi-globe2"></i>
                            </div>
                            <div>
                                <h4>Global Perspective</h4>
                                <p>Our approach is designed to connect Indonesian opportunities with global markets.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =====================================================
        TEAM
    ====================================================== -->
    <section id="team" class="nv-section">
        <div class="container">
            <div class="nv-section-header" data-aos="fade-up">
                <span class="nv-section-label">The Team</span>
                <h2>People Behind Nexvorta.</h2>
                <p>Driven by technology, business strategy, and a vision to create meaningful global connections.</p>
            </div>

            <div class="nv-team-card" data-aos="fade-up">
                <div class="nv-team-photo">
                    <img src="assets/img/team/ryan.jpg" alt="Handryansyah Purba">
                </div>
                <div class="nv-team-info">
                    <h3>Handryansyah Purba</h3>
                    <span class="nv-team-role">Project Lead & Backend Specialist</span>
                    <p>
                        "Connecting Worlds, Exporting Opportunities — driven by integrity, innovation, and excellence."
                    </p>
                    <div class="nv-social">
                        <a href="https://www.facebook.com/handryansyahpurba" target="_blank" aria-label="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="https://www.instagram.com/handryan_" target="_blank" aria-label="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="https://www.linkedin.com/in/handryanpurba" target="_blank" aria-label="LinkedIn">
                            <i class="bi bi-linkedin"></i>
                        </a>
                        <a href="https://github.com/mostjaww" target="_blank" aria-label="GitHub">
                            <i class="bi bi-github"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =====================================================
        CONTACT
    ====================================================== -->
    <section id="contact" class="nv-section nv-section-soft">
        <div class="container">
            <div class="nv-contact-box">
                <div class="row g-0">
                    <div class="col-lg-5">
                        <div class="nv-contact-info">
                            <span class="nv-section-label">Get In Touch</span>
                            <h2>Let's Build a Global Connection.</h2>
                            <p>
                                Have a product, partnership opportunity, or business inquiry? Tell us what you have in mind and our team will get back to you.
                            </p>

                            <div class="nv-contact-item">
                                <div class="nv-contact-item-icon">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <div>
                                    <strong>Our Location</strong>
                                    <span>Medan, Indonesia</span>
                                </div>
                            </div>

                            <div class="nv-contact-item">
                                <div class="nv-contact-item-icon">
                                    <i class="bi bi-telephone"></i>
                                </div>
                                <div>
                                    <strong>Phone</strong>
                                    <a href="tel:+6285119064758">+62 851-1906-4758</a>
                                </div>
                            </div>

                            <div class="nv-contact-item">
                                <div class="nv-contact-item-icon">
                                    <i class="bi bi-envelope"></i>
                                </div>
                                <div>
                                    <strong>Email</strong>
                                    <a href="mailto:nexvorta@gmail.com">nexvorta@gmail.com</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="nv-contact-form">
                            <form id="nexvortaContactForm">
                                <input type="hidden" name="action" value="send_contact">

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="nv-form-label">Your Name</label>
                                        <input type="text" name="name" class="nv-form-control" placeholder="John Doe" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="nv-form-label">Your Email</label>
                                        <input type="email" name="email" class="nv-form-control" placeholder="john@example.com" required>
                                    </div>

                                    <div class="col-12">
                                        <label class="nv-form-label">Subject</label>
                                        <input type="text" name="subject" class="nv-form-control" placeholder="Business Inquiry" required>
                                    </div>

                                    <div class="col-12">
                                        <label class="nv-form-label">Message</label>
                                        <textarea name="message" class="nv-form-control" placeholder="Tell us about your business or inquiry..." required></textarea>
                                    </div>

                                    <div class="col-12">
                                        <div id="nvFormLoading" class="nv-form-status loading">
                                            <i class="bi bi-arrow-repeat"></i> Sending your message...
                                        </div>

                                        <div id="nvFormSuccess" class="nv-form-status success">
                                            <i class="bi bi-check-circle"></i> Your message has been sent successfully.
                                        </div>

                                        <div id="nvFormError" class="nv-form-status error"></div>

                                        <button type="submit" id="nvSubmitButton" class="nv-btn-primary border-0">
                                            Send Message <i class="bi bi-send"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<?php
include_once 'function/bottombar.php';
include_once 'function/footer.php';
?>