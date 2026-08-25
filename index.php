<?php
$maintenance = false;
$ip_boleh = array(
    "192.168.80.47",
    "192.168.80.88",
    "192.168.80.11",
    "192.168.80.86",
    "103.15.240.106"
);

if ($maintenance) {
    if (isset($_SERVER['REMOTE_ADDR']) && in_array($_SERVER['REMOTE_ADDR'], $ip_boleh)) {
        // boleh akses
    } else {
        require_once 'maintenance.php';
        return;
    }
}

include_once 'config.php';

if (@$_GET['token'] == '') {
    include_once 'dashboard.php';
} elseif ($_GET['token'] <> encrypt(date('Ymd'))) {
    include_once 'dashboard.php';
} else {

    $halaman = @$_GET['hal'] . ".php";

    if (!empty($_GET['hal']) && file_exists($halaman)) {
        include_once $halaman;
    } else {
        include_once '404.php';
    }
}
