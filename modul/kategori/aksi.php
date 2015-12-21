<?php
session_start();
	include '../../config/connect.php';
	include '../../config/function.php';
	include '../../config/config.php';
	
if (NULL !== cekAkses("$modul","$_SESSION[ylevel]","$act")) {
	$kat = anti($_POST['kat']);
	switch($_POST['tipe']) {
	case 'save':
	//cek data yang sama
	if (NULL !== cekData('ypos_kategori',"nama_kat='$kat'")) {
		header("location:../../$set->folder_modul=$modul&msg=error&errno=1000&nama=$kat");
	} else {
		yposSQL('ADD','ypos_kategori',"ids='$_SESSION[yids]', nama_kat='$kat'");
		header("location:../../$set->folder_modul=$modul&msg=done");
	}
	break;
	case 'edit':
		header("location:../../$set->folder_modul=$modul&msg=error&errno=1000&nama=$kat");
		yposSQL('EDIT','ypos_kategori',"nama_kat='$kat'","idkat=$id");
		header("location:../../$set->folder_modul=$modul&msg=done");
	break;
	}
} else {
	header("location:../../$set->folder_modul=$modul&msg=error&errno=1045");
}
?>