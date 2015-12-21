<?php
session_start();
	include '../../config/connect.php';
	include '../../config/function.php';
	include '../../config/config.php';
if (NULL !== cekAkses("$modul","$_SESSION[ylevel]","$act")) {
	$old = md5($_POST['old']);
	$new = md5($_POST['new']);
	$reNew = md5($_POST['reType']);
	
	$cekPass = yposSQL('SHOW','ypos_users',"'x'","username='$_SESSION[yuser]' && pass='$old'")->num_rows;
	if ($cekPass == 1) {
		if ($new == $reNew) {
			yposSQL('EDIT','ypos_users',"pass='$new'","username='$_SESSION[yuser]'");
			header("location:../../$set->folder_modul=$modul&msg=sucessfully");
		} else {
			header("location:../../$set->folder_modul=$modul&msg=error&case=password-not-same");
		}
	} else {
		header("location:../../$set->folder_modul=$modul&msg=error&case=wrong-password");
	}
	
} else {
	header("location:../../$set->folder_modul=$modul&msg=error&errno=1045");
}
?>