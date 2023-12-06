<?php

$headers = apache_request_headers();
$cache = '/var/cache/phptracker';

function update($host, $ip) {
	global $cache;

	$filename = sprintf('%s/dyndns-%s', $cache, $host);
	if ($fh = fopen($filename, 'w')) {
		fwrite($fh, $ip);
		fclose($fh);
	}

	return 0;
}

if (isset($headers['X-Auth-Key'])) {
	$api_key = $headers['X-Auth-Key'];

	if ($api_key == 'f4db5f63-20ee-4733-ac9f-272fc7562944') {
		$host = "delilah";
		$ip = $_SERVER['REMOTE_ADDR'];
	}

	update($host, $ip);
}
?>
