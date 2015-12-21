<?php
include 'config/connect.php';
include 'config/function.php';
include 'config/config.php';

$user = anti($_POST['user']);
$pass  = strip_tags(stripslashes(htmlspecialchars(md5($_POST['pass']))));

if (!empty($user) AND ($pass)) {
$login = yposSQL('SHOW','ypos_users','username, online, level, ids',"username='$user' && pass='$pass' && aktif='Y' && 1=1");
$ketemu= $login->num_rows;

session_start(); //jalankan session
$sid = session_id();
$token = $_POST['token'];
	if ($token == $sid) {
			if ($ketemu > 0){
				$r= $login->fetch_array(); 
				//isi session login
				$_SESSION['yuser'] = $r['username'];
				$_SESSION['ylevel'] = $r['level'];
				$_SESSION['ysess'] = $sid;
				$_SESSION['yids'] = $r['ids'];
		if ($r['online'] == 'Y') {
				header('location:redirect.php');
			} else {
				LgnLogs($_SESSION['yuser'],$ip,$hostname,cekBrowser(),'IN');
				yposSQL('EDIT','ypos_users',"sessionID='$_SESSION[ysess]', online='Y', last_seen=NOW()","username='$_SESSION[yuser]'");
				header('location:index.php');
			} //end cek online
} else {
	echo "<meta http-equiv='refresh' content='0; url=index.php?get=error-password-is-wrong'>";
	}
} else {
	echo "<meta http-equiv='refresh' content='0; url=index.php?get=error-token-login'>";
	}
} // end jika token <>
else { // jika kosong
	echo "<meta http-equiv='refresh' content='0; url=index.php?get=error-password-is-empty'>";
}
?>