<?php
include 'config/connect.php';
include 'config/function.php';

@$kdbrg	= anti($_POST[kdbrg]);
$sql = yposSQL('SHOW','ypos_barang','harga_jual',"kdbarang='$kdbrg'");
$row = $sql->num_rows;
 if ($row > 0){
		$r = $sql->fetch_array();
		$brg = explode('.',$r['harga_jual']);
		$data['harga'] = $brg[0];
		echo json_encode($data);
	} else {
		$data['harga'] = '';
		echo json_encode($data);
	}
?>
