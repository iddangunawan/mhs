<?php
session_start();
	include '../../config/connect.php';
	include '../../config/function.php';
	include '../../config/config.php';
if (NULL !== cekAkses("$modul","$_SESSION[ylevel]","$act")) {
	$kd = anti($_POST['kode']);
	$nama = anti($_POST['nama']);
	$stok = abs((int)$_POST['stok']);
	$hj = anti($_POST['hrgaJual']);
	$hb = anti($_POST['hrgaBeli']);
	$cat = anti($_POST['cat']);
	$lok = anti($_POST['lokasi']);
	$jurl = anti($_POST['jURL']);
	$urlPIC = anti($_POST['pic']);
	$pic = anti(substr($urlPIC,$jurl,18));
	
	$tipe = $_POST['tipe'];
	switch($tipe) {
		case 'save':
		yposSQL('ADD','ypos_barang',"kdbarang='$kd', nama_barang='$nama', harga_beli='$hb', harga_jual='$hj', stok=$stok, lokasi='$lok', gambar='$pic', idkat=$cat, ids=$_SESSION[yids]");
		header("location:../../$set->folder_modul=$modul&msg=sucessfully");
		break;
		case 'edit':
		if (!empty($urlPIC)) {
		yposSQL('EDIT','ypos_barang',"nama_barang='$nama', harga_beli='$hb', harga_jual='$hj', stok=$stok, lokasi='$lok', gambar='$pic', idkat=$cat, ids=$_SESSION[yids]","kdbarang='$kd'");
		} else {
			yposSQL('EDIT','ypos_barang',"nama_barang='$nama', harga_beli='$hb', harga_jual='$hj', stok=$stok, lokasi='$lok', idkat=$cat, ids=$_SESSION[yids]","kdbarang='$kd'");
		}
		header("location:../../$set->folder_modul=$modul&msg=sucessfully");
		break;
	} //end case
} else {
	header("location:../../$set->folder_modul=$modul&msg=error&errno=1045");
}
?>