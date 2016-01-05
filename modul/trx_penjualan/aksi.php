<?php
session_start();
	include '../../config/connect.php';
	include '../../config/function.php';
	include '../../config/config.php';
if (NULL !== cekAkses("$modul","$_SESSION[ylevel]","$act")) {
	$kd = anti($_POST['kode']);
	$tgl = anti($_POST['tgl']);
	$cust = anti($_POST['cust']);
	$b = explode(' - ',$_POST['brg']);
	$brg = $b[0];
	$harga = abs((int)($_POST['harga'])); //harga asli dari data barang
	$select_diskon = anti($_POST['select_diskon']);
	$disc_persen = abs((int)($_POST['disc_persen']));
	$disc_rp = abs((int)($_POST['disc_rp']));
	$disc_unit = abs((int)($_POST['disc_unit']));
	$qty = abs((int)($_POST['qty']));
	$jumlah = abs((int)($_POST['jumlah']));

	// $diskon = abs((int)($_POST['diskon']));
	// $ket = anti($_POST['ket']);		
	// $item_disc = $harga - $harga_disc;
	// item_disc = nominal pemberian diskon/potongan (auto). Jika harga real ketika transaksi berbeda dengan harga dari data barang
	// $ttl = $harga_disc * $qty;
	// total harga real transaksi * dengan qty

	echo $kd.'<br>';
	echo $tgl.'<br>';
	echo $cust.'<br>';
	echo $brg.'<br>';
	echo $harga.'<br>';
	echo $select_diskon.'<br>';
	echo $disc_persen.'<br>';
	echo $disc_rp.'<br>';
	echo $disc_unit.'<br>';
	echo $qty.'<br>';
	echo $jumlah.'<br>';
	echo '<hr>';	
	
	// echo $ket.'<br>';
	// echo $item_disc.'<br>';
	// echo $ttl.'<br>';
	
switch($_POST['tipe']) {
	case 'save':
	$q = $mysqli->query("CALL ypos_trxPenjualanDtl('$kd','$cust','$tgl','$brg',$harga_disc,$qty,'$_SESSION[yuser]',@error)")->fetch_object();
	$errno = $q->error;
	if (!empty($errno)) {
		header("location:../../$set->folder_modul=$modul&act=new&id=$kd&msg=error&errno=$errno");
	} else {
		header("location:../../$set->folder_modul=$modul&act=new&id=$kd");
	} 
	break;
	case 'edProd':
	$idp = abs((int)($_GET['idp'])); //untuk get id penjualan produk
		
	$ed = $mysqli->query("CALL ypos_trxPenjualanDtl_update($idp,'$kode',$qty,$harga_disc,$ttl,@error)")->fetch_object();
	//echo $idp .'-'.$kode.'-'.$qty.'-'.$harga_disc.'-'.$ttl;
	$errno = $ed->error;
	if (!empty($errno)) {
		header("location:../../$set->folder_modul=$modul&act=new&id=$kd&msg=error&errno=$errno");
	} else {
		header("location:../../$set->folder_modul=$modul&act=new&id=$kode");
	} 
	break;
	}
} else {
	header("location:../../$set->folder_modul=$modul&msg=error&errno=1045");
}
?>