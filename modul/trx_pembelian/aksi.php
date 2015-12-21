<?php
session_start();
	include '../../config/connect.php';
	include '../../config/function.php';
	include '../../config/config.php';
if (NULL !== cekAkses("$modul","$_SESSION[ylevel]","$act")) {
	@$sup = anti($_POST['sup']);
	@$tgl = anti($_POST['tgl']);
	@$nota = anti($_POST['nota']);
	@$kdbeli = anti($_POST['kode']);
	@$b = explode(' - ',$_POST['brg']);
	@$brg = $b[0];
	@$qty = abs((int)($_POST['qty']));
	@$harga = abs((int)($_POST['total_harga']));
	@$h_pcs = $harga/$qty; //harga satuan
	
	switch($_POST['tipe']) {
	case 'save':
	$q = $mysqli->query("CALL ypos_trxPembelianDtl('$kdbeli','$nota',$sup,'$_SESSION[yuser]','$tgl','$brg', $qty, $h_pcs, $harga, @error)")->fetch_object();
	//echo $kdbeli.' - '.$nota. '-'.$sup.' - '.$_SESSION['yuser'].'-'.$tgl.'-'.$brg.'-'. $qty.'-'. $h_pcs.'-'. $harga;
	$errno = $q->error;
	if (!empty($errno)) {
		header("location:../../$set->folder_modul=$modul&act=new&id=$kdbeli&msg=error&errno=$errno");
	} else {
		header("location:../../$set->folder_modul=$modul&act=new&id=$kdbeli");
	}
	break;
	case 'edProd':
	$idp = abs((int)($_GET['idp'])); //untuk get id penjualan produk
	$ttl = abs((int)($_GET['ttl'])); //untuk get ttl produk
	$getNota = anti($_GET['nota']);
	
		yposSQL('EDIT','ypos_pembeliandtl',"kd_barang='$brg', qty_beli=$qty, harga_beli=$harga/$qty, total=$harga","idDtlPembelian=$idp && kdPembelian='$kode'");
   $t = yposSQL('SHOW','ypos_pembeliandtl','DISTINCT SUM(total) AS t_harga',"kdPembelian='$kode'")->fetch_array();
		yposSQL('EDIT','ypos_pembelian',"total_pembelian=$t[t_harga]","kdPembelian='$kode'");
		
		//update harga barang terbaru
		$h_brg = yposSQL('SHOW','ypos_barang','harga_beli',"kdbarang='$brg'")->fetch_array();
		if ($h_brg != ($harga/$qty)) {
			yposSQL('EDIT','ypos_barang',"harga_beli=$harga/$qty","kdbarang='$brg'");
		}
		
		header("location:../../$set->folder_modul=$modul&act=new&id=$kode&ttl=$t[t_harga]&nota=$getNota&msg=sucessfully");
	break;
	}
} else {
	header("location:../../$set->folder_modul=$modul&msg=error&errno=1045");
}
?>