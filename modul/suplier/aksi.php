<?php
session_start();
	include '../../config/connect.php';
	include '../../config/function.php';
	include '../../config/config.php';
if (NULL !== cekAkses("$modul","$_SESSION[ylevel]","$act")) {
	$nama = anti($_POST['nm']);
	$hp = anti($_POST['hp']);
	$alamat = anti($_POST['alamat']);
	
	switch($_POST['tipe']) {
	case 'add':
	yposSQL('ADD','ypos_suplier',"ids='$_SESSION[yids]', nama_sup='$nama', tlp='$hp', alamat='$alamat', date_create='$getDate'");
	header("location:../../$set->folder_modul=$modul&msg=sucessfully");
	break;
	case 'edit':
	yposSQL('EDIT','ypos_suplier',"nama_sup='$nama', tlp='$hp', alamat='$alamat'","kdsup=$id");
	header("location:../../$set->folder_modul=$modul&msg=sucessfully");
	break;
	}
} else {
	header("location:../../$set->folder_modul=$modul&msg=error&errno=1045");
}
?>