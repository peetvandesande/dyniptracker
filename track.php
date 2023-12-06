<?php

if (isset($_REQUEST)) {
	if (isset($_REQUEST['token'])) {
		$remote_token = $_REQUEST['token'];
	} else {
	//	die();
		phpinfo();
	}
}

$track_cache = '/var/cache/phptracker/';

if ($remote_token = '7c23af04-ef02-11e6-a05d-00163c6fd216') {
	$hostname = 'delilah';
	track('delilah');
}


function track( $hostname ) {
	global $track_cache;
	$oldrecords = array();
	$host_address = $_SERVER['REMOTE_ADDR'];
	$timestamp = date('U');
	$newrecord = "$hostname,$host_address,$timestamp";
	$append = true;
	$filename = $track_cache . $hostname;

	if (readin($filename, $oldrecords) === 0) {
		$nrofrecords  = count($oldrecords);
		$nrlastrecord = $nrofrecords-1;
		$nrpenultimaterecord = $nrofrecords-2;
		$lastrecord = explode(',', $oldrecords[$nrlastrecord]);
		if ($host_address == $lastrecord[1]) {
			// IP Address same as last record
			if ($nrofrecords > 1) {
				$penultimaterecord = explode(',', $oldrecords[$nrpenultimaterecord]);
				if ($host_address == $penultimaterecord[1]) {
					// update timestamp on last record
					$append = false;
					$lastrecord[2] = $timestamp;
					$oldrecords[$nrlastrecord] = implode(',', $lastrecord);
				}
			}
		}
	}

	if ($append) {
		append($filename, $newrecord);
	} else {
		writeout($filename, $oldrecords);
	}
}

function append( $filename, $newcontent) {
	if ($fh = fopen($filename, 'a')) {
		fwrite($fh, $newcontent);
		fwrite($fh, PHP_EOL);
		fclose($fh);
	}
}

function writeout( $filename, $lines='' ) {
	if ($fh = fopen($filename, 'w')) {
		foreach ($lines as $line) {
			fwrite($fh, $line);
		}
		fwrite($fh, PHP_EOL);
		fclose($fh);
		return 0;
	} else {
		// Can't open file for writing
		return 1;
	}
}

function readin( $filename, &$lines ) {
	if (file_exists($filename)) {
		if ($fh = fopen($filename, 'r')) {
			while (!feof($fh)) {
				$line = fgets($fh);
				if ( $line != '' ) {
					$lines[] = $line;
				}
			}
			fclose($fh);
			return 0;
		}
	} else {
		// Can't open file for reading
		return -1;
	}
}
?>
