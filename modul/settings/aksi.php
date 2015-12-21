<?php
session_start();
	include '../../config/connect.php';
	include '../../config/function.php';
	include '../../config/config.php';
if (NULL !== cekAkses("$modul","$_SESSION[ylevel]","$act")) {
	$kdset = anti($_POST['kdset']);
	$nama = anti($_POST['nm']);
	$alamat = anti($_POST['alamat']);
	$limit = anti($_POST['limit']);
	$url = anti($_POST['url']);
	$keckab = anti($_POST['keckab']);
	$tlp = anti($_POST['tlp']);
	$p = anti($_POST['printer']);
	
	yposSQL('EDIT','ypos_settings',"kdSET='$kdset', nama_toko='$nama', alamat='$alamat', keckab='$keckab', tlp='$tlp', printer='$p', url_web='$url', last_update='$_SESSION[yuser]', limit_page='$limit'","ids=$_SESSION[yids]");
	header("location:../../$set->folder_modul=$modul&msg=sucessfully");
} else {
	header("location:../../$set->folder_modul=$modul&msg=error&errno=1045");
};
?>