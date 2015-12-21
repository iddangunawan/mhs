<?php
if (!defined('YBASE')) exit ('Now Allowed');
switch(@$_GET['errno']) {
	case '1000' :
	$err_id = 'Data sudah ada, data tidak boleh sama! (1000)';
	break;
	case '1001' :
	$err_id = 'Produk sudah ada di keranjang, silahkan di update! (1001)';
	break;
	case '1002' :
	$err_id = 'Stok barang tidak mencukupi! (1002)';
	break;
	case '1045' :
	$err_id = 'Hak akses anda terbatas untuk modul ini, silahkan hubungi administrator! (1045)';
	break;
}

if (isset($_GET["msg"])) {
	if ($_GET['msg'] == 'error') {
    	$message = 'Error : '. $err_id;
	} else {
		$message = 'Done!';
	}
    $msg = $_GET["msg"];
    ?>
    <script type="text/javascript">
    showNotification({
    message: "<?php echo $message; ?>",
    type: "<?php echo $msg; ?>",
    autoClose: true,
    duration: 5
    });
    </script>
    <?php
}
    ?>