<!-- =========================================================
    JAVASCRIPT
    ========================================================== -->

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
<script src="assets/js/main.js"></script>

<script>
    /* =========================================================
    AOS
        ========================================================== */

    document.addEventListener("DOMContentLoaded", function() {
        if (typeof AOS !== "undefined") {
            AOS.init({
                duration: 800,
                easing: "ease-out-cubic",
                once: true,
                offset: 70
            });
        }
    });

    /* =========================================================
    MOBILE NAVIGATION
    ========================================================== */

    const nvMobileToggle = document.getElementById("nvMobileToggle");
    const nvNavWrapper = document.getElementById("nvNavWrapper");
    if (nvMobileToggle) {
        nvMobileToggle.addEventListener("click", function() {
            nvNavWrapper.classList.toggle("active");
            const icon = nvMobileToggle.querySelector("i");
            if (nvNavWrapper.classList.contains("active")) {
                icon.className = "bi bi-x-lg";
            } else {
                icon.className = "bi bi-list";
            }
        });
    }

    /* =========================================================
    MOBILE DROPDOWN
    ========================================================== */

    document.querySelectorAll(".nv-dropdown-parent > a").forEach(function(link) {
            link.addEventListener("click", function(e) {
                if (window.innerWidth <= 991) {
                    e.preventDefault();
                    const parent = this.parentElement;
                    parent.classList.toggle("dropdown-open");
                }
            });
        });

    /* =========================================================
    CLOSE MOBILE MENU
    ========================================================== */

    document.querySelectorAll(".nv-nav a").forEach(function(link) {
        link.addEventListener("click", function() {
            if (
                window.innerWidth <= 991 &&
                !this.parentElement.classList.contains("nv-dropdown-parent")
            ) {
                nvNavWrapper.classList.remove("active");
                const icon = nvMobileToggle.querySelector("i");
                icon.className = "bi bi-list";
            }
        });
    });

    /* =========================================================
    SCROLL TOP
    ========================================================== */

    const nvScrollTop = document.getElementById("nvScrollTop");
    window.addEventListener("scroll", function() {
        if (window.scrollY > 500) {
            nvScrollTop.classList.add("active");
        } else {
            nvScrollTop.classList.remove("active");
        }
    });

    nvScrollTop.addEventListener("click", function(e) {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    });


    /* =========================================================
    NEXVA CHAT
    ========================================================== */

    function toggleNexvaChat() {
        const chatWindow = document.getElementById("nvChatWindow");
        chatWindow.classList.toggle("active");
        if (chatWindow.classList.contains("active")) {
            setTimeout(function() {
                document
                    .getElementById("nvChatInput")
                    .focus();
            }, 200);
        }
    }

    function addNexvaMessage(sender, message) {
        const chatMessages = document.getElementById("nvChatMessages");
        const messageWrapper = document.createElement("div");
        messageWrapper.className = "nv-chat-message " + sender;
        const bubble = document.createElement("div");
        bubble.className = "nv-chat-bubble";
        bubble.innerHTML = message;
        messageWrapper.appendChild(bubble);
        chatMessages.appendChild(messageWrapper);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    async function sendNexvaMessage() {
        const input = document.getElementById("nvChatInput");
        const message = input.value.trim();
        if (!message) {
            return;
        }
        addNexvaMessage("user", escapeHtml(message));
        input.value = "";
        addNexvaMessage(
            "bot",
            `
                <div class="nv-typing">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                `
        );

        try {
            const response = await fetch("function/nexva-ai.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    message: message
                })
            });
            if (!response.ok) {
                throw new Error("Request failed");
            }
            const data = await response.json();
            const botMessages = document.querySelectorAll(
                ".nv-chat-message.bot"
            );
            const lastBotMessage = botMessages[botMessages.length - 1];
            if (lastBotMessage) {
                lastBotMessage.remove();
            }
            addNexvaMessage(
                "bot",
                data.reply || "Sorry, I couldn't process that request."
            );
        } catch (error) {
            const botMessages = document.querySelectorAll(
                ".nv-chat-message.bot"
            );
            const lastBotMessage = botMessages[botMessages.length - 1];
            if (lastBotMessage) {
                lastBotMessage.remove();
            }
            addNexvaMessage(
                "bot",
                "Sorry, Nexva is temporarily unavailable."
            );
            console.error(error);
        }
    }

    function escapeHtml(text) {
        const div = document.createElement("div");
        div.textContent = text;
        return div.innerHTML;
    }

    /* =========================================================
    CHAT ENTER KEY
    ========================================================== */

    const nvChatInput = document.getElementById("nvChatInput");
    if (nvChatInput) {
        nvChatInput.addEventListener("keydown", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                sendNexvaMessage();
            }
        });
    }

    /* =========================================================
    CONTACT FORM
    ========================================================== */

    const contactForm = document.getElementById("nexvortaContactForm");
    if (contactForm) {
        contactForm.addEventListener("submit", async function(event) {
            event.preventDefault();
            const submitButton = document.getElementById("nvSubmitButton");
            const loading = document.getElementById("nvFormLoading");
            const success = document.getElementById("nvFormSuccess");
            const error = document.getElementById("nvFormError");
            loading.style.display = "block";
            success.style.display = "none";
            error.style.display = "none";
            submitButton.disabled = true;
            const formData = new FormData(contactForm);
            try {
                const response = await fetch(window.location.href, {
                    method: "POST",
                    body: formData
                });
                const data = await response.json();
                loading.style.display = "none";
                if (data.success) {
                    success.style.display = "block";
                    contactForm.reset();
                    setTimeout(function() {
                        success.style.display = "none";
                    }, 6000);
                } else {
                    error.innerHTML = `<i class="bi bi-exclamation-circle"></i> ${escapeHtml(data.message || "Something went wrong.")}`;
                    error.style.display = "block";
                }
            } catch (err) {
                loading.style.display = "none";
                error.innerHTML = `<i class="bi bi-exclamation-circle"></i> Unable to send your message. Please try again later.`;
                error.style.display = "block";
                console.error(err);
            } finally {
                submitButton.disabled = false;
            }
        });
    }

    /* =========================================================
    CLOSE CHAT WHEN CLICKING OUTSIDE
    ========================================================== */

    document.addEventListener("click", function(event) {
        const chat = document.querySelector(".nv-chat");
        const chatWindow = document.getElementById("nvChatWindow");
        if (
            chatWindow.classList.contains("active") &&
            !chat.contains(event.target)
        ) {
            chatWindow.classList.remove("active");
        }
    });
</script>

</body>

</html>