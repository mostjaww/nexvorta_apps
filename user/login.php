<?php
session_start();
include_once "config.php";

// Reset OTP ketika membuka halaman login
if (!isset($_POST['btnOTP']) && !isset($_POST['btnLogin'])) {
    unset($_SESSION['otp']);
}

$error_msg   = "";
$success_msg = "";
$info_msg    = "";

/* =================================
    STEP 1 : KIRIM OTP
================================= */
if (isset($_POST['btnOTP'])) {

    $username     = mysqli_real_escape_string($link, $_POST['username'] ?? '');
    $password_raw = mysqli_real_escape_string($link, $_POST['passwd'] ?? '');

    $passwd = md5($keycode . $password_raw);

    /* ===============================
       RESET LOGIN SESSION
    =============================== */
    unset(
        $_SESSION['login_type'],
        $_SESSION['user_id'],
        $_SESSION['username'],
        $_SESSION['name'],
        $_SESSION['telegram']
    );

    /* ===============================
       CEK CUSTOMER
    =============================== */
    $query = mysqli_query(
        $link,
        "SELECT * FROM tblcustomer
         WHERE username='$username'
         AND password='$passwd'
         LIMIT 1"
    );

    if ($query && mysqli_num_rows($query) === 1) {

        $r = mysqli_fetch_assoc($query);

        $_SESSION['login_type'] = "customer";
        $_SESSION['user_id']    = $r['id'];
        $_SESSION['username']   = $r['username'];
        $_SESSION['name']       = $r['name'];
        $_SESSION['telegram']   = $r['telegram_id'];
    } else {

        /* ===============================
           CEK SELLER
        =============================== */
        $query = mysqli_query(
            $link,
            "SELECT * FROM tblseller
             WHERE username='$username'
             AND password='$passwd'
             LIMIT 1"
        );

        if ($query && mysqli_num_rows($query) === 1) {

            $r = mysqli_fetch_assoc($query);

            $_SESSION['login_type'] = "seller";
            $_SESSION['user_id']    = $r['id'];
            $_SESSION['username']   = $r['username'];
            $_SESSION['name']       = $r['name'];
            $_SESSION['telegram']   = $r['telegram_id'];
        } else {
            $error_msg = "Username atau password yang Anda masukkan salah.";
        }
    }

    /* ===============================
       JIKA USER DITEMUKAN
    =============================== */
    if (
        isset($_SESSION['user_id']) &&
        isset($_SESSION['login_type'])
    ) {

        $otp = random_int(1000, 9999);

        $_SESSION['otp'] = $otp;

        if ($_SESSION['login_type'] === "customer") {

            mysqli_query(
                $link,
                "UPDATE tblcustomer
                 SET otp='$otp'
                 WHERE id='" . intval($_SESSION['user_id']) . "'"
            );
        } else {

            mysqli_query(
                $link,
                "UPDATE tblseller
                 SET otp='$otp'
                 WHERE id='" . intval($_SESSION['user_id']) . "'"
            );
        }

        $text =
            "🔐 Nexvorta Login OTP\n\n" .
            "Hai " . $_SESSION['name'] . ",\n\n" .
            "Kode OTP login Anda adalah:\n\n" .
            "👉 " . $otp . "\n\n" .
            "Jangan berikan kode ini kepada siapapun.";

        $url =
            "https://api.telegram.org/bot" .
            $token_bottelegram .
            "/sendMessage?chat_id=" .
            $_SESSION['telegram'] .
            "&text=" .
            urlencode($text);

        @file_get_contents($url);

        $success_msg = "Kode OTP berhasil dikirim ke Telegram Anda.";
    }
}

/* =================================
    STEP 2 : VALIDASI OTP
================================= */
if (isset($_POST['btnLogin'])) {

    $otp_input = trim($_POST['otp'] ?? '');

    if (
        isset($_SESSION['otp']) &&
        isset($_SESSION['login_type']) &&
        hash_equals((string) $_SESSION['otp'], (string) $otp_input)
    ) {

        if ($_SESSION['login_type'] === "customer") {
            $page = encrypt(date('Ymd')) . "&hal=dashboard/customer/index";
        } else {
            $page = encrypt(date('Ymd')) . "&hal=dashboard/seller/index";
        }

        $link_tujuan = "index.php?token=" . $page;

        unset($_SESSION['otp']);

        header("Location: $link_tujuan");
        exit;
    } else {
        $error_msg = "Kode OTP tidak sesuai. Silakan periksa kembali Telegram Anda.";
    }
}

/* =================================
    URL REGISTER
================================= */
$register_customer =
    "index.php?token=" .
    encrypt(date('Ymd')) .
    "&hal=user/register-customer";

$register_seller =
    "index.php?token=" .
    encrypt(date('Ymd')) .
    "&hal=user/register-seller";

$forgot_password =
    "index.php?token=" .
    encrypt(date('Ymd')) .
    "&hal=user/forgot-password";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login to Nexvorta Dashboard">
    <meta name="author" content="Nexvorta Team">

    <title>Login | Nexvorta</title>

    <link rel="shortcut icon" href="<?php echo $base_url; ?>assets/img/nexva.png" type="image/x-icon">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.7.2/css/all.min.css" rel="stylesheet">

    <!-- Login CSS -->
    <link href="<?php echo $base_url; ?>assets/css/login.css" rel="stylesheet">
</head>

<body>

    <main class="login-page">
        <div class="login-wrapper">

            <!-- LEFT PANEL -->
            <section class="login-visual">
                <div class="visual-grid"></div>
                <div class="visual-glow glow-one"></div>
                <div class="visual-glow glow-two"></div>

                <div class="visual-content">
                    <div class="brand-mark">
                        <img src="<?php echo $base_url; ?>assets/img/nexva.png" alt="Nexvorta Logo">
                    </div>

                    <div class="visual-heading">
                        <span class="eyebrow">GLOBAL TRADE SOLUTIONS</span>
                        <h1>
                            Move Your Business
                            <span>Beyond Borders.</span>
                        </h1>
                        <p>
                            A smarter way to manage your export and import operations with Nexvorta.
                        </p>
                    </div>

                    <div class="visual-stats">
                        <div class="stat-item">
                            <strong><i class="fa-solid fa-globe"></i></strong>
                            <div>
                                <span>Global</span>
                                <small>Trade Platform</small>
                            </div>
                        </div>

                        <div class="stat-divider"></div>

                        <div class="stat-item">
                            <strong><i class="fa-solid fa-shield-halved"></i></strong>
                            <div>
                                <span>Secure</span>
                                <small>Authentication</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="visual-footer">
                    <span>© <?php echo date('Y'); ?> Nexvorta</span>
                    <span class="footer-dot"></span>
                    <span>Export & Import Solutions</span>
                </div>
            </section>

            <!-- RIGHT PANEL -->
            <section class="login-form-panel">
                <div class="form-container">

                    <!-- Mobile Logo -->
                    <div class="mobile-brand">
                        <img src="<?php echo $base_url; ?>assets/img/nexva.png" alt="Nexvorta">
                        <span>NEXVORTA</span>
                    </div>

                    <!-- Back -->
                    <a href="<?php echo $base_url; ?>" class="back-home">
                        <span class="back-icon">
                            <i class="fa-solid fa-arrow-left"></i>
                        </span>
                        <span>Back to Home</span>
                    </a>

                    <!-- Header -->
                    <div class="login-header">
                        <?php if (isset($_SESSION['otp'])): ?>
                            <span class="form-eyebrow">TWO-FACTOR AUTHENTICATION</span>
                            <h2>Verify your identity</h2>
                            <p>We've sent a verification code to your registered Telegram account.</p>
                        <?php else: ?>
                            <span class="form-eyebrow">WELCOME BACK</span>
                            <h2>Sign in to Nexvorta</h2>
                            <p>Enter your credentials to access your account.</p>
                        <?php endif; ?>
                    </div>

                    <!-- LOGIN FORM -->
                    <form method="post" autocomplete="off" id="loginForm">

                        <?php if (!isset($_SESSION['otp'])): ?>

                            <!-- Username -->
                            <div class="input-group-modern">
                                <label for="username">Username</label>
                                <div class="input-wrapper">
                                    <span class="input-icon">
                                        <i class="fa-regular fa-user"></i>
                                    </span>
                                    <input type="text"
                                        id="username"
                                        name="username"
                                        placeholder="Enter your username"
                                        value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                                        required
                                        autofocus>
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="input-group-modern">
                                <div class="label-row">
                                    <label for="passwd">Password</label>
                                    <a href="<?php echo $forgot_password; ?>">Forgot password?</a>
                                </div>
                                <div class="input-wrapper">
                                    <span class="input-icon">
                                        <i class="fa-solid fa-lock"></i>
                                    </span>
                                    <input type="password"
                                        id="passwd"
                                        name="passwd"
                                        placeholder="Enter your password"
                                        required>
                                    <button type="button"
                                        class="password-toggle"
                                        onclick="togglePassword()"
                                        aria-label="Show password">
                                        <i class="fa-regular fa-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Telegram Button -->
                            <button type="submit" name="btnOTP" value="telegram" class="btn-login btn-telegram">
                                <span>
                                    <i class="fa-brands fa-telegram"></i> Send OTP via Telegram
                                </span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>

                            <div class="security-note">
                                <i class="fa-solid fa-shield-halved"></i>
                                <span>A verification code will be sent to your registered Telegram account.</span>
                            </div>

                        <?php else: ?>

                            <!-- OTP -->
                            <div class="otp-container">
                                <div class="otp-icon">
                                    <i class="fa-brands fa-telegram"></i>
                                </div>
                                <div>
                                    <strong>Check your Telegram</strong>
                                    <p>Enter the 4-digit code we sent to you.</p>
                                </div>
                            </div>

                            <div class="input-group-modern otp-group">
                                <label for="otp">Verification Code</label>
                                <div class="input-wrapper">
                                    <span class="input-icon">
                                        <i class="fa-solid fa-key"></i>
                                    </span>
                                    <input type="text"
                                        id="otp"
                                        name="otp"
                                        placeholder="0000"
                                        inputmode="numeric"
                                        maxlength="4"
                                        pattern="[0-9]{4}"
                                        autocomplete="one-time-code"
                                        autofocus
                                        required>
                                </div>
                            </div>

                            <button type="submit" name="btnLogin" class="btn-login">
                                <span>Verify & Login</span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>

                            <button type="button" class="change-account" onclick="location.reload()">
                                <i class="fa-solid fa-arrow-left"></i> Use another account
                            </button>

                        <?php endif; ?>

                        <input type="hidden" id="koor" name="koor">

                    </form>

                    <!-- Register -->
                    <div class="register-section">
                        <span>Don't have an account?</span>
                        <a href="#" onclick="pilihRegister(event)">
                            Create an account <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </a>
                    </div>

                    <!-- Footer -->
                    <div class="form-footer">
                        <span>© <?php echo date('Y'); ?> Nexvorta</span>
                        <span>All rights reserved.</span>
                    </div>

                </div>
            </section>

        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        /* REGISTER SELECTOR */
        function pilihRegister(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Create your account',
                text: 'Choose the account type you want to create.',
                icon: 'question',
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: '<i class="fa-solid fa-user"></i> Customer',
                denyButtonText: '<i class="fa-solid fa-store"></i> Seller',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#0877b9',
                denyButtonColor: '#102a43',
                cancelButtonColor: '#94a3b8',
                reverseButtons: true,
                customClass: {
                    popup: 'nexvorta-alert',
                    confirmButton: 'swal-btn',
                    denyButton: 'swal-btn',
                    cancelButton: 'swal-btn'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?php echo $register_customer; ?>";
                } else if (result.isDenied) {
                    window.location.href = "<?php echo $register_seller; ?>";
                }
            });
        }

        /* PASSWORD TOGGLE */
        function togglePassword() {
            const password = document.getElementById("passwd");
            const icon = document.getElementById("toggleIcon");

            if (password.type === "password") {
                password.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                password.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }

        /* OTP INPUT */
        const otpInput = document.getElementById("otp");
        if (otpInput) {
            otpInput.addEventListener("input", function() {
                this.value = this.value.replace(/\D/g, '').slice(0, 4);
            });
        }

        /* GEOLOCATION */
        window.addEventListener("load", function() {
            if (navigator.geolocation && document.getElementById("koor")) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        document.getElementById("koor").value =
                            position.coords.latitude + ", " + position.coords.longitude;
                    },
                    function() {
                        document.getElementById("koor").value = "";
                    }
                );
            }
        });

        /* SWEETALERT MESSAGES */
        <?php if (!empty($error_msg)): ?>
            Swal.fire({
                icon: 'error',
                title: 'Login Failed',
                text: <?php echo json_encode($error_msg); ?>,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Try Again'
            });
        <?php endif; ?>

        <?php if (!empty($success_msg)): ?>
            Swal.fire({
                icon: 'success',
                title: 'OTP Sent',
                text: <?php echo json_encode($success_msg); ?>,
                confirmButtonColor: '#0877b9',
                confirmButtonText: 'Enter OTP'
            });
        <?php endif; ?>

        <?php if (!empty($info_msg)): ?>
            Swal.fire({
                icon: 'info',
                title: 'Information',
                text: <?php echo json_encode($info_msg); ?>,
                confirmButtonColor: '#0877b9'
            });
        <?php endif; ?>
    </script>
</body>

</html>