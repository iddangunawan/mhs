<?php
include 'config/connect.php';
include 'config/function.php';

$q = strtolower($_GET["q"]);
if (!$q) return;
$SQLbrg = yposSQL('SHOW','ypos_barang','*',"nama_barang LIKE '%$q%'");
$cekBrg = $SQLbrg->num_rows;
	if ($cekBrg > 0) {
		while($brg = $SQLbrg->fetch_array()) {
		$resBrg = $brg['kdbarang']. ' - ' .$brg['nama_barang']. ' (Rp : ' .idr($brg['harga_jual']).')';
		echo "$resBrg\n";
			}
		} else {
			echo "\n";
			echo '<b>Data tidak ada ..</b>';
	}
?>