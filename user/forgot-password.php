<?php
session_start();
include_once "config.php";

$error_msg = '';
$success_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user = mysqli_real_escape_string(
        $link,
        trim($_POST['username'] ?? '')
    );

    $role = '';
    $data = null;

    if (empty($user)) {
        $error_msg = "Please enter your username or email.";
    } else {

        /* =========================================
           CHECK CUSTOMER
        ========================================= */
        $q1 = mysqli_query(
            $link,
            "SELECT * FROM tblcustomer
             WHERE username='$user'
             OR email='$user'
             LIMIT 1"
        );

        if ($q1 && mysqli_num_rows($q1) > 0) {
            $data = mysqli_fetch_assoc($q1);
            $role = "customer";
        } else {

            /* =========================================
               CHECK SELLER
            ========================================= */
            $q2 = mysqli_query(
                $link,
                "SELECT * FROM tblseller
                 WHERE username='$user'
                 OR email='$user'
                 LIMIT 1"
            );

            if ($q2 && mysqli_num_rows($q2) > 0) {
                $data = mysqli_fetch_assoc($q2);
                $role = "seller";
            } else {
                $error_msg = "Username or email was not found.";
            }
        }
    }

    /* =========================================
       GENERATE RESET TOKEN
    ========================================= */
    if ($data) {
        $reset_token = bin2hex(random_bytes(32));

        $expired = date(
            "Y-m-d H:i:s",
            strtotime("+5 minutes")
        );

        if ($role === "customer") {
            mysqli_query(
                $link,
                "UPDATE tblcustomer
                 SET token='$reset_token',
                     token_expired='$expired'
                 WHERE id='" . intval($data['id']) . "'"
            );
        } else {
            mysqli_query(
                $link,
                "UPDATE tblseller
                 SET token='$reset_token',
                     token_expired='$expired'
                 WHERE id='" . intval($data['id']) . "'"
            );
        }

        /* =========================================
           RESET LINK
        ========================================= */
        $reset_link =
            $base_url .
            "index.php?token=" .
            encrypt(date('Ymd')) .
            "&hal=user/reset-password" .
            "&reset_token=" .
            urlencode($reset_token);

        /* =========================================
           TELEGRAM MESSAGE
        ========================================= */
        $text =
            "🔐 Nexvorta Password Reset\n\n" .
            "Hello " . $data['name'] . ",\n\n" .
            "We received a request to reset your Nexvorta account password.\n\n" .
            "Please click the button below to create a new password:\n\n" .
            $reset_link . "\n\n" .
            "⏱ This link is valid for 5 minutes.\n\n" .
            "If you did not request this password reset, please ignore this message.\n\n" .
            "— Nexvorta Team";

        @file_get_contents(
            "https://api.telegram.org/bot" .
                $token_bottelegram .
                "/sendMessage?chat_id=" .
                $data['telegram_id'] .
                "&text=" .
                urlencode($text)
        );

        $success_msg = "A password reset link has been sent to your Telegram.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Reset your Nexvorta account password securely.">
    <meta name="author" content="Nexvorta Team">

    <title>Forgot Password | Nexvorta</title>

    <link rel="shortcut icon" href="assets/img/nexva.png" type="image/x-icon">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.7.2/css/fontawesome.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.7.2/css/all.min.css" rel="stylesheet">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel=stylesheet href="assets/css/forgot-password.css">
</head>

<body>

    <div class="forgot-wrapper">

        <!-- LEFT BRANDING -->
        <div class="brand-panel">
            <div class="brand-content">
                <img src="assets/img/nexva.png" alt="Nexvorta" class="brand-logo">

                <div class="brand-title">Nexvorta</div>

                <div class="brand-subtitle">
                    Global Export & Import Platform designed to simplify your international business journey.
                </div>

                <div class="brand-feature">
                    <span class="brand-feature-icon">
                        <i class="fa fa-shield-halved"></i>
                    </span>
                    Secure account recovery
                </div>

                <div class="brand-feature">
                    <span class="brand-feature-icon">
                        <i class="fa fa-paper-plane"></i>
                    </span>
                    Reset link delivered via Telegram
                </div>

                <div class="brand-feature">
                    <span class="brand-feature-icon">
                        <i class="fa fa-clock"></i>
                    </span>
                    Secure link valid for 5 minutes
                </div>
            </div>
        </div>

        <!-- RIGHT FORM -->
        <div class="form-panel">
            <a href="<?php echo $base_url; ?>index.php?token=<?php echo encrypt(date('Ymd')) . "&hal=user/login"; ?>" class="back-link">
                <i class="fa fa-arrow-left"></i> Back to Login
            </a>

            <div class="form-header">
                <div class="form-icon">
                    <i class="fa fa-key"></i>
                </div>
                <h2>Forgot your password?</h2>
                <p>
                    No worries. Enter your username or email address and we'll send you a secure password reset link through Telegram.
                </p>
            </div>

            <form method="POST" autocomplete="off">
                <div class="mb-3">
                    <div class="input-wrapper">
                        <i class="fa fa-user"></i>
                        <input type="text"
                            name="username"
                            class="form-control"
                            placeholder="Username or Email"
                            autocomplete="username"
                            required>
                    </div>
                </div>

                <button type="submit" class="btn btn-reset w-100">
                    <i class="fa fa-paper-plane me-2"></i> Send Reset Link
                </button>

                <div class="reset-info">
                    <i class="fa fa-circle-info"></i>
                    <span>For your security, the password reset link will expire automatically after 5 minutes.</span>
                </div>
            </form>

            <div class="form-footer">
                &copy; <?php echo date('Y'); ?> <strong>Nexvorta</strong>. All rights reserved.
            </div>
        </div>

    </div>

    <script>
        <?php if (!empty($error_msg)) : ?>
            Swal.fire({
                icon: 'error',
                title: 'Reset Failed',
                text: <?php echo json_encode($error_msg); ?>,
                confirmButtonColor: '#0077b6',
                confirmButtonText: 'Try Again'
            });
        <?php endif; ?>

        <?php if (!empty($success_msg)) : ?>
            Swal.fire({
                icon: 'success',
                title: 'Check Your Telegram',
                text: <?php echo json_encode($success_msg); ?>,
                confirmButtonColor: '#0077b6',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = "index.php?token=<?php echo encrypt(date('Ymd')); ?>&hal=user/forgot-password";
            });
        <?php endif; ?>
    </script>
</body>

</html>