<!-- =========================================================
    FOOTER
    ========================================================== -->

<footer class="nv-footer">
    <div class="container">
        <div class="row gy-5">
            <div class="col-lg-5">
                <div class="nv-footer-brand">
                    <a href="<?php echo htmlspecialchars($base_url); ?>" class="nv-logo">
                        <div class="nv-logo-mark">
                            <i class="bi bi-globe2"></i>
                        </div>
                        <div class="nv-logo-text">
                            NEX<span>VORTA</span>
                        </div>
                    </a>
                    <p>
                        Connecting Indonesian products with global
                        opportunities through trusted export, import,
                        and international trading solutions.
                    </p>
                    <div class="nv-social">
                        <a href="https://www.facebook.com/handryansyahpurba"
                            target="_blank">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="https://www.instagram.com/handryan_"
                            target="_blank">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="https://www.linkedin.com/in/handryanpurba"
                            target="_blank">
                            <i class="bi bi-linkedin"></i>
                        </a>
                        <a href="https://github.com/mostjaww"
                            target="_blank">
                            <i class="bi bi-github"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4">
                <h4>Company</h4>
                <ul>
                    <li>
                        <a href="#about">About Us</a>
                    </li>
                    <li>
                        <a href="<?php echo nexvortaUrl($base_url, $todayToken, 'certification'); ?>">
                            Certification
                        </a>
                    </li>
                    <li>
                        <a href="#team">Our Team</a>
                    </li>
                    <li>
                        <a href="#contact">Contact</a>
                    </li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-4">
                <h4>Products</h4>
                <ul>
                    <li>
                        <a href="<?php echo nexvortaUrl($base_url, $todayToken, 'products/crafts-umkm'); ?>">
                            Crafts & UMKM
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo nexvortaUrl($base_url, $todayToken, 'products/agriculture-plantations'); ?>">
                            Agriculture
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo nexvortaUrl($base_url, $todayToken, 'products/livestockfarm'); ?>">
                            Livestock
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-4">
                <h4>Contact</h4>
                <ul>
                    <li>
                        <a href="https://maps.google.com/?q=Medan,Indonesia"
                            target="_blank">
                            Medan, Indonesia
                        </a>
                    </li>
                    <li>
                        <a href="tel:+6285119064758">
                            +62 851-1906-4758
                        </a>
                    </li>
                    <li>
                        <a href="mailto:nexvorta@gmail.com">
                            nexvorta@gmail.com
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="nv-footer-bottom">
            <div class="row align-items-center gy-2">
                <div class="col-md-6">
                    <?php echo date("Y"); ?> ©
                    <strong>NEXVORTA.</strong>
                    All Rights Reserved.
                </div>
                <div class="col-md-6 text-md-end">
                    Designed & Developed by Nexvorta Team
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- =========================================================
    NEXVA AI CHAT
    ========================================================== -->

<div class="nv-chat">
    <div class="nv-chat-window" id="nvChatWindow">
        <div class="nv-chat-header">
            <h5>
                <i class="bi bi-stars me-1"></i>
                Nexva AI Assistant
            </h5>
            <button type="button" onclick="toggleNexvaChat()" aria-label="Close chat">
                <i class="bi bi-x"></i>
            </button>
        </div>
        <div class="nv-chat-messages" id="nvChatMessages">
            <div class="nv-chat-message bot">
                <div class="nv-chat-bubble">
                    Hello! I'm Nexva, Nexvorta's AI Assistant.
                    How can I help you today?
                </div>
            </div>
        </div>
        <div class="nv-chat-input-area">
            <input type="text" id="nvChatInput" class="nv-chat-input" placeholder="Ask Nexva something...">
            <button type="button" class="nv-chat-send" onclick="sendNexvaMessage()">
                <i class="bi bi-send"></i>
            </button>
        </div>
    </div>

    <button type="button" class="nv-chat-button" onclick="toggleNexvaChat()" aria-label="Open Nexva AI">
        <i class="bi bi-stars"></i>
    </button>
</div>

<!-- SCROLL TOP -->
<a href="#" class="nv-scroll-top" id="nvScrollTop">
    <i class="bi bi-arrow-up"></i>
</a>