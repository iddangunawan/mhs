<?php
session_start();
	include '../../config/connect.php';
	include '../../config/function.php';
	include '../../config/config.php';
if (NULL !== cekAkses("$modul","$_SESSION[ylevel]")) {
	$rpt = anti($_POST['rpt']);
	$str = anti($_POST['start']);
	$end = anti($_POST['end']);
	
	$rpt = yposSQL('SHOW','ypos_paramchild','*',"idpc=$rpt && 1=1")->fetch_array();
	header("location:../../$rpt[ket].php?rpt=$rpt[child_name]&start=$str&end=$end");
} else {
	header("location:../../$set->folder_modul=$modul&msg=error&errno=1045");
}
?>