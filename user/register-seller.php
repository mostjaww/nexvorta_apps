<?php
session_start();
include_once "config.php";

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name     = mysqli_real_escape_string($link, trim($_POST['name']));
    $username = mysqli_real_escape_string($link, trim($_POST['username']));
    $email    = mysqli_real_escape_string($link, trim($_POST['email']));
    $birth    = mysqli_real_escape_string($link, trim($_POST['birth']));
    $password = trim($_POST['password']);
    $telegram = mysqli_real_escape_string($link, trim($_POST['telegram_id']));

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
    } else {

        // Cek username / email
        $cek = mysqli_query(
            $link,
            "SELECT id FROM tblseller 
             WHERE username='$username' 
             OR email='$email'"
        );

        if (mysqli_num_rows($cek) > 0) {
            $error = "Username or Email already in use!";
        } else {

            // Password Hash
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Generate OTP
            $otp = rand(100000, 999999);

            $query = "INSERT INTO tblseller 
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
                      )";

            if (mysqli_query($link, $query)) {

                // Telegram Message
                $text = "Hello $name 👋\n\n"
                    . "Welcome to Nexvorta.\n\n"
                    . "Your seller account registration has been received successfully.\n\n"
                    . "To activate your seller account, please use the OTP below:\n\n"
                    . "🔐 $otp\n\n"
                    . "This OTP is confidential and should not be shared with anyone.\n\n"
                    . "Thank you for choosing Nexvorta.\n\n"
                    . "— Nexvorta Team";

                @file_get_contents(
                    "https://api.telegram.org/bot" .
                        $token_bottelegram .
                        "/sendMessage?chat_id=" .
                        $telegram .
                        "&text=" .
                        urlencode($text)
                );

                $_SESSION['pending_activation'] = $username;

                echo "<script>
                    window.location='index.php?token=" .
                    encrypt(date('Ymd')) .
                    "&hal=user/activated';
                </script>";

                exit;
            } else {
                $error = "An error occurred while saving the data.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Create your Nexvorta Seller Account">
    <meta name="author" content="Nexvorta Team">

    <title>Register Seller | Nexvorta</title>

    <link rel="shortcut icon" href="assets/img/nexva.png" type="image/x-icon">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.7.2/css/all.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #0077b6;
            --primary-dark: #023e8a;
            --primary-light: #00b4d8;
            --background: #f5f8fc;
            --text-dark: #172033;
            --text-muted: #7b8494;
            --border: #e4e9f0;
            --success: #16a34a;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at top left, rgba(0, 180, 216, 0.10), transparent 35%), #f5f8fc;
            color: var(--text-dark);
        }

        /* MAIN LAYOUT */
        .register-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: stretch;
        }

        /* LEFT BRAND PANEL */
        .brand-panel {
            position: relative;
            width: 48%;
            min-height: 100vh;
            overflow: hidden;
            display: flex;
            align-items: center;
            padding: 70px;
            color: white;
            background: linear-gradient(145deg, #023e8a 0%, #0077b6 50%, #00a8cc 100%);
        }

        .brand-panel::before {
            content: "";
            position: absolute;
            width: 550px;
            height: 550px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
            top: -180px;
            right: -180px;
        }

        .brand-panel::after {
            content: "";
            position: absolute;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.10);
            bottom: -200px;
            left: -120px;
        }

        .brand-content {
            position: relative;
            z-index: 2;
            max-width: 560px;
        }

        .brand-logo {
            width: 76px;
            height: 76px;
            object-fit: contain;
            margin-bottom: 28px;
            filter: drop-shadow(0 15px 30px rgba(0, 0, 0, 0.18));
        }

        .brand-name {
            font-size: 42px;
            font-weight: 800;
            letter-spacing: -1.5px;
            margin-bottom: 12px;
        }

        .brand-tagline {
            font-size: 19px;
            font-weight: 500;
            margin-bottom: 20px;
            color: rgba(255, 255, 255, 0.92);
        }

        .brand-description {
            max-width: 480px;
            line-height: 1.8;
            font-size: 15px;
            color: rgba(255, 255, 255, 0.75);
        }

        /* SELLER BADGE */
        .seller-badge {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 8px 14px;
            margin-bottom: 24px;
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            font-size: 13px;
            font-weight: 600;
        }

        .seller-badge i {
            font-size: 14px;
        }

        /* BENEFITS */
        .benefits {
            margin-top: 38px;
            display: grid;
            gap: 16px;
        }

        .benefit-item {
            display: flex;
            align-items: center;
            gap: 13px;
            color: rgba(255, 255, 255, 0.85);
            font-size: 14px;
        }

        .benefit-icon {
            width: 36px;
            height: 36px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.10);
            flex-shrink: 0;
        }

        /* RIGHT PANEL */
        .form-panel {
            width: 52%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px 70px;
            background: #f8fafc;
            overflow-y: auto;
        }

        .form-container {
            width: 100%;
            max-width: 560px;
            animation: fadeUp 0.55s ease both;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(25px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* TOP NAV */
        .top-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: #667085;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: 0.25s ease;
        }

        .back-link i {
            transition: 0.25s ease;
        }

        .back-link:hover {
            color: var(--primary);
        }

        .back-link:hover i {
            transform: translateX(-4px);
        }

        .account-label {
            font-size: 12px;
            color: #98a2b3;
        }

        /* FORM HEADER */
        .form-header {
            margin-bottom: 28px;
        }

        .form-header h1 {
            margin: 0 0 8px;
            font-size: 30px;
            font-weight: 800;
            letter-spacing: -0.8px;
            color: var(--text-dark);
        }

        .form-header p {
            margin: 0;
            color: var(--text-muted);
            font-size: 14px;
            line-height: 1.6;
        }

        /* FORM CARD */
        .form-card {
            background: #ffffff;
            border: 1px solid rgba(228, 233, 240, 0.9);
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 20px 50px rgba(16, 24, 40, 0.07);
        }

        /* INPUT */
        .form-floating {
            position: relative;
        }

        .form-floating>.form-control {
            height: 58px;
            border-radius: 13px;
            border: 1px solid var(--border);
            background: #fbfcfe;
            padding-left: 16px;
            font-size: 14px;
            transition: border-color 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
        }

        .form-floating>.form-control:focus {
            background: #ffffff;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(0, 119, 182, 0.08);
        }

        .form-floating>label {
            color: #8b95a5;
            font-size: 13px;
        }

        /* INPUT ICON */
        .input-icon {
            position: absolute;
            top: 50%;
            right: 17px;
            transform: translateY(-50%);
            color: #98a2b3;
            z-index: 5;
            font-size: 15px;
            pointer-events: none;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 16px;
            transform: translateY(-50%);
            z-index: 10;
            border: none;
            background: transparent;
            color: #98a2b3;
            cursor: pointer;
            padding: 4px;
            transition: 0.2s ease;
        }

        .password-toggle:hover {
            color: var(--primary);
        }

        /* PASSWORD RULES */
        .password-section {
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .password-title {
            font-size: 12px;
            font-weight: 600;
            color: #667085;
            margin-bottom: 10px;
        }

        .password-rules {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 7px 15px;
        }

        .rule-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            color: #98a2b3;
            transition: 0.25s ease;
        }

        .rule-item i {
            font-size: 9px;
            color: #c4cbd4;
            transition: 0.25s ease;
        }

        .rule-item.valid {
            color: var(--success);
        }

        .rule-item.valid i {
            color: var(--success);
        }

        /* PASSWORD PROGRESS */
        .strength-container {
            margin-top: 13px;
        }

        .strength-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
            font-size: 10px;
            color: #98a2b3;
        }

        .progress {
            height: 5px;
            border-radius: 20px;
            background: #edf0f4;
            overflow: hidden;
        }

        #strengthBar {
            height: 100%;
            width: 0%;
            border-radius: 20px;
            transition: width 0.3s ease, background-color 0.3s ease;
        }

        /* TELEGRAM INFO */
        .telegram-hint {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            margin-top: -8px;
            margin-bottom: 22px;
            font-size: 11px;
            line-height: 1.5;
            color: #8b95a5;
        }

        .telegram-hint i {
            color: #0088cc;
            font-size: 14px;
            margin-top: 1px;
        }

        /* REGISTER BUTTON */
        .btn-register {
            width: 100%;
            height: 54px;
            border: none;
            border-radius: 13px;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            color: white;
            font-size: 14px;
            font-weight: 700;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .btn-register:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(0, 119, 182, 0.25);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        /* LOGIN LINK */
        .login-area {
            text-align: center;
            margin-top: 22px;
            font-size: 12px;
            color: #98a2b3;
        }

        .login-area a {
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
            transition: 0.2s ease;
        }

        .login-area a:hover {
            color: var(--primary-dark);
        }

        /* FOOTER */
        .copyright {
            text-align: center;
            margin-top: 22px;
            font-size: 10px;
            color: #adb5bd;
        }

        /* MOBILE */
        @media (max-width: 1100px) {
            .brand-panel {
                width: 43%;
                padding: 45px;
            }

            .form-panel {
                width: 57%;
                padding: 40px;
            }

            .brand-name {
                font-size: 34px;
            }
        }

        @media (max-width: 992px) {
            .register-wrapper {
                display: block;
            }

            .brand-panel {
                display: none;
            }

            .form-panel {
                width: 100%;
                min-height: 100vh;
                padding: 30px 20px;
            }

            .form-container {
                max-width: 570px;
            }
        }

        @media (max-width: 576px) {
            .form-panel {
                padding: 20px 14px;
            }

            .form-card {
                padding: 24px 20px;
                border-radius: 20px;
            }

            .form-header h1 {
                font-size: 25px;
            }

            .top-navigation {
                margin-bottom: 24px;
            }

            .password-rules {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <div class="register-wrapper">

        <!-- LEFT BRANDING -->
        <section class="brand-panel">
            <div class="brand-content">
                <div class="seller-badge">
                    <i class="fa-solid fa-store"></i> Seller Account
                </div>

                <img src="assets/img/nexva.png" alt="Nexvorta" class="brand-logo">

                <div class="brand-name">Nexvorta</div>

                <div class="brand-tagline">Build. Connect. Grow Globally.</div>

                <p class="brand-description">
                    Create your seller account and connect your business with a global export and import ecosystem built for modern businesses.
                </p>

                <div class="benefits">
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fa-solid fa-globe"></i>
                        </div>
                        <span>Reach customers across global markets</span>
                    </div>

                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <span>Grow and manage your business efficiently</span>
                    </div>

                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <span>Secure account verification via Telegram</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- RIGHT FORM -->
        <main class="form-panel">
            <div class="form-container">

                <!-- TOP NAV -->
                <div class="top-navigation">
                    <a href="<?php echo $base_url; ?>" class="back-link">
                        <i class="fa-solid fa-arrow-left"></i> Back to Home
                    </a>
                    <span class="account-label">Seller Registration</span>
                </div>

                <!-- HEADER -->
                <div class="form-header">
                    <h1>Create Seller Account</h1>
                    <p>Join Nexvorta and start growing your business in the global marketplace.</p>
                </div>

                <!-- FORM CARD -->
                <div class="form-card">
                    <form method="POST" id="registerForm" autocomplete="off">

                        <!-- FULL NAME -->
                        <div class="mb-3 form-floating">
                            <input type="text"
                                name="name"
                                id="name"
                                class="form-control"
                                placeholder="Full Name"
                                value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
                                required>
                            <label for="name">Full Name</label>
                            <i class="input-icon fa-regular fa-user"></i>
                        </div>

                        <!-- EMAIL -->
                        <div class="mb-3 form-floating">
                            <input type="email"
                                name="email"
                                id="email"
                                class="form-control"
                                placeholder="Email Address"
                                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                required>
                            <label for="email">Email Address</label>
                            <i class="input-icon fa-regular fa-envelope"></i>
                        </div>

                        <!-- DATE OF BIRTH -->
                        <div class="mb-3 form-floating">
                            <input type="date"
                                name="birth"
                                id="birth"
                                class="form-control"
                                value="<?php echo isset($_POST['birth']) ? htmlspecialchars($_POST['birth']) : ''; ?>"
                                required>
                            <label for="birth">Date of Birth</label>
                            <i class="input-icon fa-regular fa-calendar"></i>
                        </div>

                        <!-- USERNAME -->
                        <div class="mb-3 form-floating">
                            <input type="text"
                                name="username"
                                id="username"
                                class="form-control"
                                placeholder="Username"
                                value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                                required>
                            <label for="username">Username</label>
                            <i class="input-icon fa-solid fa-at"></i>
                        </div>

                        <!-- PASSWORD -->
                        <div class="mb-2 form-floating position-relative">
                            <input type="password"
                                id="password"
                                name="password"
                                class="form-control"
                                placeholder="Password"
                                onkeyup="checkStrength()"
                                required>
                            <label for="password">Password</label>
                            <button type="button"
                                onclick="togglePassword()"
                                class="password-toggle"
                                aria-label="Show password">
                                <i class="fa-regular fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>

                        <!-- PASSWORD RULES -->
                        <div class="password-section">
                            <div class="password-title">Password requirements</div>
                            <div class="password-rules">
                                <div id="ruleLength" class="rule-item">
                                    <i class="fa-solid fa-circle"></i> Minimum 6 characters
                                </div>
                                <div id="ruleUpper" class="rule-item">
                                    <i class="fa-solid fa-circle"></i> Uppercase letter
                                </div>
                                <div id="ruleNumber" class="rule-item">
                                    <i class="fa-solid fa-circle"></i> Number
                                </div>
                                <div id="ruleSymbol" class="rule-item">
                                    <i class="fa-solid fa-circle"></i> Special symbol
                                </div>
                            </div>

                            <div class="strength-container">
                                <div class="strength-header">
                                    <span>Password strength</span>
                                    <span id="strengthText">Weak</span>
                                </div>
                                <div class="progress">
                                    <div id="strengthBar"></div>
                                </div>
                            </div>
                        </div>

                        <!-- TELEGRAM -->
                        <div class="mb-2 form-floating">
                            <input type="text"
                                name="telegram_id"
                                id="telegram_id"
                                class="form-control"
                                placeholder="Telegram ID"
                                value="<?php echo isset($_POST['telegram_id']) ? htmlspecialchars($_POST['telegram_id']) : ''; ?>"
                                required>
                            <label for="telegram_id">Telegram ID</label>
                            <i class="input-icon fa-brands fa-telegram"></i>
                        </div>

                        <div class="telegram-hint">
                            <i class="fa-brands fa-telegram"></i>
                            <span>Your Telegram ID will be used to send the OTP required to activate your account.</span>
                        </div>

                        <!-- REGISTER -->
                        <button type="submit" class="btn-register">
                            <i class="fa-solid fa-user-plus me-2"></i> Create Seller Account
                        </button>

                        <!-- LOGIN -->
                        <div class="login-area">
                            Already have a seller account?
                            <a href="index.php?token=<?php echo encrypt(date('Ymd')) . '&hal=user/login'; ?>">
                                Login Now
                            </a>
                        </div>

                    </form>
                </div>

                <div class="copyright">
                    © <?php echo date('Y'); ?> <strong>Nexvorta</strong>. All rights reserved.
                </div>

            </div>
        </main>

    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            const strengthText = document.getElementById("strengthText");

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

            if (strength === 0) {
                bar.style.background = "#e5e7eb";
                strengthText.innerText = "Enter password";
            } else if (strength === 1) {
                bar.style.background = "#ef4444";
                strengthText.innerText = "Weak";
            } else if (strength === 2) {
                bar.style.background = "#f59e0b";
                strengthText.innerText = "Fair";
            } else if (strength === 3) {
                bar.style.background = "#0ea5e9";
                strengthText.innerText = "Good";
            } else {
                bar.style.background = "#16a34a";
                strengthText.innerText = "Strong";
            }

            return (hasLength && hasUpper && hasNumber && hasSymbol);
        }

        /* UPDATE PASSWORD RULE */
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
        document.getElementById("registerForm").addEventListener("submit", function(e) {
            const valid = checkStrength();
            if (!valid) {
                e.preventDefault();
                Swal.fire({
                    icon: "warning",
                    title: "Password Too Weak",
                    text: "Please complete all password requirements before continuing.",
                    confirmButtonColor: "#0077b6",
                    borderRadius: "18px"
                });
            }
        });

        /* SERVER ERROR */
        <?php if (!empty($error)): ?>
            Swal.fire({
                icon: "error",
                title: "Registration Failed",
                text: <?php echo json_encode($error); ?>,
                confirmButtonColor: "#0077b6",
                borderRadius: "18px"
            });
        <?php endif; ?>
    </script>
</body>

</html>