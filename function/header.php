<?php
include_once 'config.php';

/*
|--------------------------------------------------------------------------
| TELEGRAM CONTACT HANDLER
|--------------------------------------------------------------------------
| Token Telegram disimpan di server-side PHP, bukan JavaScript.
| Form akan POST ke file index.php ini sendiri.
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'send_contact') {
    header('Content-Type: application/json; charset=utf-8');
    $telegramToken = '7890870095:AAH-onk-Sv3eZnw7PlRz3aXxkCN1R-HREsw';
    $telegramChatId = '5183095350';
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    if ($name === '' || $email === '' || $subject === '' || $message === '') {
        echo json_encode([
            'success' => false,
            'message' => 'Please complete all required fields.'
        ]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'success' => false,
            'message' => 'Please enter a valid email address.'
        ]);
        exit;
    }

    $fullText =
        "📩 Nexvorta Contact Inquiry\n\n" .
        "Name: " . $name . "\n" .
        "Email: " . $email . "\n" .
        "Subject: " . $subject . "\n\n" .
        "Message:\n" . $message;

    $telegramUrl = "https://api.telegram.org/bot{$telegramToken}/sendMessage";
    $postData = [
        'chat_id' => $telegramChatId,
        'text'    => $fullText
    ];
    $ch = curl_init($telegramUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $postData,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_SSL_VERIFYPEER => true
    ]);
    $response = curl_exec($ch);
    $curlError = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($curlError) {
        echo json_encode([
            'success' => false,
            'message' => 'Unable to connect to Telegram.'
        ]);
        exit;
    }
    $telegramResponse = json_decode($response, true);
    if ($httpCode >= 200 && $httpCode < 300 && !empty($telegramResponse['ok'])) {
        echo json_encode([
            'success' => true,
            'message' => 'Your message has been sent successfully.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to send your message.'
        ]);
    }
    exit;
}

/*
|--------------------------------------------------------------------------
| URL HELPERS
|--------------------------------------------------------------------------
*/
$todayToken = encrypt(date('Ymd'));
function nexvortaUrl($base_url, $todayToken, $page)
{
    return $base_url . '?token=' . $todayToken . '&hal=' . $page;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="description" content="Nexvorta is an export and import company connecting Indonesian products with global markets.">
    <meta name="keywords" content="Nexvorta, export import Indonesia, trading company, Indonesian products, global market">
    <meta name="author" content="Nexvorta Team">
    <title>Nexvorta — Connecting Worlds, Exporting Opportunities</title>
    <link href="assets/img/nexva.png" rel="icon">
    <link href="assets/img/nexva.png" rel="apple-touch-icon">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <!-- AOS -->
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <!-- Swiper -->
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <!-- Main CSS -->
    <link href="assets/css/main.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>