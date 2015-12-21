<?php
include 'config/connect.php';
include 'config/function.php';
include 'config/config.php';
session_start();
$user = $_SESSION['yuser'];

$LgnUsr = yposSQL('SHOW','ypos_lgnhistories','*',"username='$user' && 1=1",'idLgn DESC LIMIT 1')->fetch_array();
LgnLogs($user,$ip,$hostname,cekBrowser(),'IN');
yposSQL('EDIT','ypos_users',"sessionID='$_SESSION[ysess]', online='Y', last_seen=NOW()","username='$_SESSION[yuser]'");

echo "<b>USER ID : $user, LAST LOGIN [IP ADDRESS : $LgnUsr[ip]  , HOST NAME : $LgnUsr[hostname]]
THIS USER HAS ALREADY LOGGED IN ON OTHER DEVICE, <a href='index.php'>CONTINUE</a></b>";
?>