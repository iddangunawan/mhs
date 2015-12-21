<?php
session_start();
include 'config/connect.php';
include 'config/function.php';
include 'config/config.php';
LgnLogs($_SESSION['yuser'],$ip,$hostname,cekBrowser(),'OUT');
yposSQL('EDIT','ypos_users',"sessionID='', online='N', last_seen=NOW()","username='$_SESSION[yuser]'");
session_destroy();
echo 'Processing . . .';
echo '<meta http-equiv="refresh" content="0; url=index.php">';
?>