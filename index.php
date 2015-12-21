<?php
session_start();
if (empty($_SESSION['yuser'])) {
	include 'login.php';
} else {
include 'home.php';
}
?>
