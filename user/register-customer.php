<?php
session_start();
include_once "config.php";

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name     = mysqli_real_escape_string($link, trim($_POST['name'] ?? ''));
    $username = mysqli_real_escape_string($link, trim($_POST['username'] ?? ''));
    $email    = mysqli_real_escape_string($link, trim($_POST['email'] ?? ''));
    $birth    = mysqli_real_escape_string($link, trim($_POST['birth'] ?? ''));
    $password = trim($_POST['password'] ?? '');
    $telegram = mysqli_real_escape_string($link, trim($_POST['telegram_id'] ?? ''));

    /* =================================
       VALIDATION
    ================================= */
    if (
        empty($name) ||
        empty($username) ||
        empty($email) ||
        empty($birth) ||
        empty($password) ||
        empty($telegram)
    ) {
        $error = "All fields are required!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long!";
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $error = "Password must contain at least one uppercase letter!";
    } elseif (!preg_match('/[0-9]/', $password)) {
        $error = "Password must contain at least one number!";
    } elseif (!preg_match('/[^A-Za-z0-9]/', $password)) {
        $error = "Password must contain at least one symbol!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address!";
    } else {

        /* =================================
           CEK USERNAME / EMAIL
        ================================= */
        $cek = mysqli_query(
            $link,
            "SELECT id
             FROM tblcustomer
             WHERE username='$username'
             OR email='$email'
             LIMIT 1"
        );

        if ($cek && mysqli_num_rows($cek) > 0) {
            $error = "Username or Email already in use!";
        } else {

            /* =================================
               PASSWORD
            ================================= */
            $password_hash = md5($keycode . $password);

            /* =================================
               OTP
            ================================= */
            $otp = random_int(100000, 999999);

            /* =================================
               INSERT CUSTOMER
            ================================= */
            $query = "
                INSERT INTO tblcustomer
                (
                    name,
                    username,
                    email,
                    birth,
                    password,
                    telegram_id,
                    otp,
                    is_active,
                    created_at
                )
                VALUES
                (
                    '$name',
                    '$username',
                    '$email',
                    '$birth',
                    '$password_hash',
                    '$telegram',
                    '$otp',
                    'Need Activation',
                    NOW()
                )
            ";

            if (mysqli_query($link, $query)) {

                /* =================================
                   TELEGRAM MESSAGE
                ================================= */
                $text =
                    "🔐 Nexvorta Account Activation\n\n" .
                    "Hello " . $name . " 👋\n\n" .
                    "Thank you for registering an account at Nexvorta.\n\n" .
                    "Your account activation OTP is:\n\n" .
                    "🔑 " . $otp . "\n\n" .
                    "This OTP is confidential and should not be shared with anyone.\n\n" .
                    "Thank you for choosing Nexvorta.\n\n" .
                    "— Nexvorta Team";

                $telegram_url =
                    "https://api.telegram.org/bot" .
                    $token_bottelegram .
                    "/sendMessage?chat_id=" .
                    $telegram .
                    "&text=" .
                    urlencode($text);

                @file_get_contents($telegram_url);

                /* =================================
                   SESSION
                ================================= */
                $_SESSION['pending_activation'] = $username;

                echo "
                    <script>
                        window.location.href =
                        'index.php?token=" .
                        encrypt(date('Ymd')) .
                        "&hal=user/activated';
                    </script>
                ";

                exit;

            } else {
                $error = "An error occurred while creating your account.";
            }
        }
    }
}

/* =================================
   LOGIN URL
================================= */
$login_url =
    "index.php?token=" .
    encrypt(date('Ymd')) .
    "&hal=user/login";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Create your Nexvorta customer account">
    <meta name="author" content="Nexvorta Team">

    <title>Create Account | Nexvorta</title>

    <link rel="shortcut icon" href="<?php echo $base_url; ?>assets/img/nexva.png" type="image/x-icon">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.7.2/css/all.min.css">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Register CSS -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/register.css">
</head>

<body>

    <main class="register-page">
        <div class="register-wrapper">

            <!-- LEFT BRANDING -->
            <section class="register-visual">
                <div class="visual-grid"></div>
                <div class="visual-glow glow-one"></div>
                <div class="visual-glow glow-two"></div>

                <div class="visual-content">
                    <div class="brand-mark">
                        <img src="<?php echo $base_url; ?>assets/img/nexva.png" alt="Nexvorta Logo">
                    </div>

                    <span class="eyebrow">JOIN NEXVORTA</span>

                    <h1>
                        Build Your
                        <span>Global Journey.</span>
                    </h1>

                    <p class="visual-description">
                        Create your Nexvorta customer account and get access to a smarter platform for your international trade journey.
                    </p>

                    <div class="benefits">
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fa-solid fa-globe"></i>
                            </div>
                            <div>
                                <strong>Global Access</strong>
                                <span>Connect with international business opportunities.</span>
                            </div>
                        </div>

                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                            <div>
                                <strong>Secure Account</strong>
                                <span>Protected with Telegram verification.</span>
                            </div>
                        </div>

                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fa-solid fa-chart-line"></i>
                            </div>
                            <div>
                                <strong>Business Growth</strong>
                                <span>Manage your international business more efficiently.</span>
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

            <!-- RIGHT FORM -->
            <section class="register-form-panel">
                <div class="form-container">

                    <!-- Mobile Brand -->
                    <div class="mobile-brand">
                        <img src="<?php echo $base_url; ?>assets/img/nexva.png" alt="Nexvorta">
                        <span>NEXVORTA</span>
                    </div>

                    <!-- Back -->
                    <a href="<?php echo $login_url; ?>" class="back-home">
                        <span class="back-icon">
                            <i class="fa-solid fa-arrow-left"></i>
                        </span>
                        <span>Back to Login</span>
                    </a>

                    <!-- Header -->
                    <div class="register-header">
                        <span class="form-eyebrow">CUSTOMER REGISTRATION</span>
                        <h2>Create your account</h2>
                        <p>Join Nexvorta and start your international business journey.</p>
                    </div>

                    <!-- FORM -->
                    <form method="POST" id="registerForm" autocomplete="off">

                        <!-- Full Name -->
                        <div class="input-group-modern">
                            <label for="name">Full Name</label>
                            <div class="input-wrapper">
                                <span class="input-icon">
                                    <i class="fa-regular fa-user"></i>
                                </span>
                                <input type="text"
                                       id="name"
                                       name="name"
                                       placeholder="Enter your full name"
                                       value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
                                       required>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="input-group-modern">
                            <label for="email">Email Address</label>
                            <div class="input-wrapper">
                                <span class="input-icon">
                                    <i class="fa-regular fa-envelope"></i>
                                </span>
                                <input type="email"
                                       id="email"
                                       name="email"
                                       placeholder="you@example.com"
                                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                       required>
                            </div>
                        </div>

                        <!-- Birth + Username -->
                        <div class="form-row">
                            <div class="input-group-modern">
                                <label for="birth">Date of Birth</label>
                                <div class="input-wrapper">
                                    <span class="input-icon">
                                        <i class="fa-regular fa-calendar"></i>
                                    </span>
                                    <input type="date"
                                           id="birth"
                                           name="birth"
                                           value="<?php echo isset($_POST['birth']) ? htmlspecialchars($_POST['birth']) : ''; ?>"
                                           required>
                                </div>
                            </div>

                            <div class="input-group-modern">
                                <label for="username">Username</label>
                                <div class="input-wrapper">
                                    <span class="input-icon">
                                        <i class="fa-solid fa-at"></i>
                                    </span>
                                    <input type="text"
                                           id="username"
                                           name="username"
                                           placeholder="username"
                                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                                           required>
                                </div>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="input-group-modern">
                            <label for="password">Password</label>
                            <div class="input-wrapper">
                                <span class="input-icon">
                                    <i class="fa-solid fa-lock"></i>
                                </span>
                                <input type="password"
                                       id="password"
                                       name="password"
                                       placeholder="Create a strong password"
                                       onkeyup="checkStrength()"
                                       required>
                                <button type="button"
                                        class="password-toggle"
                                        onclick="togglePassword()">
                                    <i class="fa-regular fa-eye" id="toggleIcon"></i>
                                </button>
                            </div>

                            <!-- Password Rules -->
                            <div class="password-rules">
                                <div id="ruleLength" class="rule-item">
                                    <i class="fa-solid fa-circle"></i>
                                    <span>At least 6 characters</span>
                                </div>
                                <div id="ruleUpper" class="rule-item">
                                    <i class="fa-solid fa-circle"></i>
                                    <span>One uppercase letter</span>
                                </div>
                                <div id="ruleNumber" class="rule-item">
                                    <i class="fa-solid fa-circle"></i>
                                    <span>One number</span>
                                </div>
                                <div id="ruleSymbol" class="rule-item">
                                    <i class="fa-solid fa-circle"></i>
                                    <span>One special character</span>
                                </div>
                            </div>

                            <!-- Strength -->
                            <div class="strength-container">
                                <div class="strength-track">
                                    <div id="strengthBar" class="strength-bar"></div>
                                </div>
                                <span id="strengthText">Password strength</span>
                            </div>
                        </div>

                        <!-- Telegram -->
                        <div class="input-group-modern">
                            <label for="telegram_id">Telegram ID</label>
                            <div class="input-wrapper">
                                <span class="input-icon telegram-icon">
                                    <i class="fa-brands fa-telegram"></i>
                                </span>
                                <input type="text"
                                       id="telegram_id"
                                       name="telegram_id"
                                       placeholder="Your Telegram ID"
                                       value="<?php echo isset($_POST['telegram_id']) ? htmlspecialchars($_POST['telegram_id']) : ''; ?>"
                                       required>
                            </div>
                            <div class="field-help">
                                <i class="fa-solid fa-circle-info"></i> Your Telegram ID will be used for account verification.
                            </div>
                        </div>

                        <!-- Register Button -->
                        <button type="submit" class="btn-register">
                            <span>Create Customer Account</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>

                        <!-- Login Link -->
                        <div class="login-link">
                            <span>Already have an account?</span>
                            <a href="<?php echo $login_url; ?>">
                                Sign in <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        </div>

                    </form>

                    <!-- Footer -->
                    <div class="form-footer">
                        <span>© <?php echo date('Y'); ?> Nexvorta</span>
                        <span>All rights reserved.</span>
                    </div>

                </div>
            </section>

        </div>
    </main>

    <script>
        /* PASSWORD TOGGLE */
        function togglePassword() {
            const password = document.getElementById("password");
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

        /* PASSWORD STRENGTH */
        function checkStrength() {
            const password = document.getElementById("password").value;
            const bar = document.getElementById("strengthBar");
            const text = document.getElementById("strengthText");

            const hasLength = password.length >= 6;
            const hasUpper = /[A-Z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSymbol = /[^A-Za-z0-9]/.test(password);

            updateRule("ruleLength", hasLength);
            updateRule("ruleUpper", hasUpper);
            updateRule("ruleNumber", hasNumber);
            updateRule("ruleSymbol", hasSymbol);

            let strength = 0;
            if (hasLength) strength++;
            if (hasUpper) strength++;
            if (hasNumber) strength++;
            if (hasSymbol) strength++;

            const percent = (strength / 4) * 100;
            bar.style.width = percent + "%";
            bar.className = "strength-bar";

            if (strength === 0) {
                text.innerText = "Password strength";
            } else if (strength === 1) {
                bar.classList.add("weak");
                text.innerText = "Weak password";
            } else if (strength === 2) {
                bar.classList.add("fair");
                text.innerText = "Fair password";
            } else if (strength === 3) {
                bar.classList.add("good");
                text.innerText = "Good password";
            } else {
                bar.classList.add("strong");
                text.innerText = "Strong password";
            }

            return (hasLength && hasUpper && hasNumber && hasSymbol);
        }

        /* UPDATE RULE */
        function updateRule(id, valid) {
            const element = document.getElementById(id);
            const icon = element.querySelector("i");

            if (valid) {
                element.classList.add("valid");
                icon.classList.remove("fa-circle");
                icon.classList.add("fa-circle-check");
            } else {
                element.classList.remove("valid");
                icon.classList.remove("fa-circle-check");
                icon.classList.add("fa-circle");
            }
        }

        /* FORM VALIDATION */
        document.getElementById("registerForm").addEventListener("submit", function (event) {
            const valid = checkStrength();
            if (!valid) {
                event.preventDefault();
                Swal.fire({
                    icon: "warning",
                    title: "Password too weak",
                    text: "Your password must contain at least 6 characters, one uppercase letter, one number, and one special character.",
                    confirmButtonColor: "#0877b9",
                    confirmButtonText: "Understood"
                });
            }
        });

        /* SERVER ERROR */
        <?php if (!empty($error)): ?>
        Swal.fire({
            icon: "error",
            title: "Registration Failed",
            text: <?php echo json_encode($error); ?>,
            confirmButtonColor: "#0877b9",
            confirmButtonText: "Try Again"
        });
        <?php endif; ?>
    </script>
</body>

</html>