<?php
$keycode = "NexvortaApps2026";
date_default_timezone_set("Asia/Jakarta");

// --- KONEKSI 1 (Dashboard) ---
$host	= "localhost";
$user	= "root";
$pass	= "";
$name   = "admin_nexvorta";
$port	= 3308;
$link = mysqli_connect($host, $user, $pass, $name, $port);

if (!$link) {
	die("Koneksi ke conn gagal: " . mysqli_connect_error());
}

$base_url = 'http://localhost:8888/nexvorta_apps/';


$idx 		= mysqli_real_escape_string($link, '1');
$QConf = "SELECT * FROM konfigurasi WHERE id= $idx";
$RstConf = mysqli_query($link, $QConf);
if (!$RstConf) {
	die("Koneksi ke informan gagal: " . mysqli_error($link));
}
$rconf = mysqli_fetch_assoc($RstConf);

$title_dashboard 		= $rconf['title_dashboard'];
$subtitle_footer		= $rconf['subtitle_footer'];
$title					= $rconf['title'];
$title_icon 			= $rconf['title_icon'];
$login_nama 			= $rconf['login_nama'];
$login_logo 			= $rconf['login_logo'];
$login_footer 			= $rconf['login_footer'];
$token_bottelegram		= $rconf['token_bottelegram'];
$ip_smsgateway			= $rconf['ip_smsgateway'];
$bot_notif				= $rconf['bot_notif'];

function encrypt($str)
{
	$hasil = crc32($str);
	return $hasil;
}

function getUserIP()
{
	$ipKeys = [
		'HTTP_CLIENT_IP',
		'HTTP_X_FORWARDED_FOR', // bisa berisi banyak IP, dipisah koma
		'HTTP_X_FORWARDED',
		'HTTP_X_CLUSTER_CLIENT_IP',
		'HTTP_FORWARDED_FOR',
		'HTTP_FORWARDED',
		'REMOTE_ADDR'
	];

	foreach ($ipKeys as $key) {
		if (!empty($_SERVER[$key])) {
			$ips = explode(',', $_SERVER[$key]); // untuk memisahkan jika ada banyak IP
			foreach ($ips as $ip) {
				$ip = trim($ip); // hilangkan spasi
				// Validasi: hanya IP publik, bukan IP lokal atau private
				if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
					return $ip;
				}
			}
		}
	}

	return 'UNKNOWN';
}
?>

<!-- <script>
	document.addEventListener("contextmenu", function(e) {
		e.preventDefault();
	});

	document.addEventListener("keydown", function(e) {
		if (e.ctrlKey && (
				e.key === "u" || e.key === "U" ||
				e.key === "i" || e.key === "I" ||
				e.key === "j" || e.key === "J" ||
				e.key === "p" || e.key === "P" ||
				e.key === "s" || e.key === "S")) {
			e.preventDefault();
		}
	});
</script> -->