<header class="nv-header">
    <div class="container-xl">
        <div class="nv-navbar d-flex align-items-center justify-content-between">
            <a href="<?php echo htmlspecialchars($base_url); ?>" class="nv-logo">
                <div class="nv-logo-mark">
                    <i class="bi bi-globe2"></i>
                </div>
                <div class="nv-logo-text">
                    NEX<span>VORTA</span>
                </div>
            </a>
            <button class="nv-mobile-toggle" id="nvMobileToggle" type="button">
                <i class="bi bi-list"></i>
            </button>
            <div class="nv-nav-wrapper" id="nvNavWrapper">
                <ul class="nv-nav">
                    <li>
                        <a href="<?php echo htmlspecialchars($base_url); ?>" class="active">
                            Home
                        </a>
                    </li>
                    <li class="nv-dropdown-parent">
                        <a href="javascript:void(0)">
                            Company
                            <i class="bi bi-chevron-down"></i>
                        </a>
                        <ul class="nv-dropdown">
                            <li>
                                <a href="<?php echo nexvortaUrl($base_url, $todayToken, 'about-us'); ?>">
                                    <i class="bi bi-building"></i>
                                    About Us
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo nexvortaUrl($base_url, $todayToken, 'certification'); ?>">
                                    <i class="bi bi-patch-check"></i>
                                    Certification
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nv-dropdown-parent">
                        <a href="javascript:void(0)">
                            Products
                            <i class="bi bi-chevron-down"></i>
                        </a>
                        <ul class="nv-dropdown">
                            <li>
                                <a href="<?php echo nexvortaUrl($base_url, $todayToken, 'products/crafts-umkm'); ?>">
                                    <i class="bi bi-basket"></i>
                                    Crafts & UMKM Products
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo nexvortaUrl($base_url, $todayToken, 'products/agriculture-plantations'); ?>">
                                    <i class="bi bi-flower1"></i>
                                    Agriculture & Plantations
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo nexvortaUrl($base_url, $todayToken, 'products/livestockfarm'); ?>">
                                    <i class="bi bi-box-seam"></i>
                                    Livestock Farm Products
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="<?php echo htmlspecialchars($base_url); ?>#team">
                            Our Team
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo htmlspecialchars($base_url); ?>#contact">
                            Contact
                        </a>
                    </li>
                    <li class="nv-dropdown-parent">
                        <a href="javascript:void(0)">
                            Download
                            <i class="bi bi-chevron-down"></i>
                        </a>
                        <ul class="nv-dropdown">
                            <li>
                                <a href="https://play.google.com/store/apps" target="_blank">
                                    <i class="bi bi-google-play"></i>
                                    Google Play Store
                                </a>
                            </li>
                            <li>
                                <a href="https://www.apple.com/id/app-store/" target="_blank">
                                    <i class="bi bi-apple"></i>
                                    Apple App Store
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a class="nv-login"
                            href="<?php echo nexvortaUrl($base_url, $todayToken, 'user/login'); ?>">
                            Login
                            <i class="bi bi-arrow-up-right"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>  
</header>