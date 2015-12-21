<?php

/* JPEGCam Test Script */
/* Receives JPEG webcam submission and saves to local file. */
/* Make sure your directory has permission to write files as your web server user! */
//include 'config/config.php';
$filename = date('YmdHis') . '.jpg'; //format file name untuk webcam
$direktori="photo/"; //nama folder untuk lokasi webcam
$result = file_put_contents( $direktori.$filename, file_get_contents('php://input') );
if (!$result) {
	print "ERROR: Failed to write data to $filename, check permissions\n";
	exit();
}

$url = 'http://' . $_SERVER['HTTP_HOST'] .  dirname($_SERVER['REQUEST_URI']) . '/' . $direktori.$filename;
print "$url\n";

?>
