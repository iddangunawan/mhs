<?php
session_start();
	include '../../config/connect.php';
	include '../../config/function.php';
	include '../../config/config.php';
if (NULL !== cekAkses("$modul","$_SESSION[ylevel]")) {
	@$nama = anti($_POST['nama']);
	@$url = anti($_POST['url']);
	@$folder = anti($_POST['folder']);
	@$aktif = anti($_POST['aktif']);
	@$menu = abs((int)($_POST['menu']));
	@$order = abs((int)($_POST['order']));
	@$level = anti($_GET['level']);
	
	switch($_POST['tipe']) {
	case 'saveMod':
	$q = $mysqli->query("CALL ypos_modManag($_SESSION[ylevel],'$nama','$folder','Y','$_SESSION[yuser]',$menu,@error)")->fetch_object();
	$errno = $q->error;
	if (!empty($errno)) {
		header("location:../../$set->folder_modul=$modul&sub=modul&msg=error&errno=$errno");
	} else {
		header("location:../../$set->folder_modul=$modul&sub=modul&msg=done");
	} 
	break;
	case 'edMod':
		yposSQL('EDIT','ypos_modul',"nama_modul='$nama', modul_folder='$folder', aktif='$aktif', menuID=$menu","modulID=$id");
		header("location:../../index.php?$set->folder_modul=$modul&sub=modul&msg=done");
	break;
	case 'saveMenu':
	//cek ada yang sama apa nggak
	$cek = yposRec("'x'",'ypos_menu',"menu='$nama'",'','')->num_rows;
	if($cek > 0) {
		header("location:../../index.php?$set->folder_modul=$modul&sub=$act&msg=error&no=1&nama=$nama");
	} else {
		yposADD('ypos_menu',"menu='$nama', aktif='Y', sort=$order");
		header("location:../../index.php?$set->folder_modul=$modul&sub=menu&msg=done");
	}
	break;
	case 'edMenu':
		yposSQL('EDIT','ypos_menu',"menu='$nama', aktif='$aktif', sort=$order","menuID=$id");
		header("location:../../index.php?$set->folder_modul=$modul&sub=menu&msg=done");
	break;
	case 'saveLvl':
	$q = $mysqli->query("CALL ypos_AddLvl('$nama','$_SESSION[yuser]',@newID,@error)")->fetch_object();
	
	@$errno = $q->error;
	@$newID = $q->newID;
	if (!empty($errno)) {
		header("location:../../$set->folder_modul=$modul&sub=level&msg=error&errno=$errno");
	} else {
		header("location:../../$set->folder_modul=$modul&sub=modul-akses&op=ed&id=$newID&level=$nama&msg=done");
	} 
	break;
	case 'edLvl':
	if ($id == 1) {
		yposSQL('EDIT','ypos_level',"lvl='$nama'","idlevel=$id");
	} else {
		yposSQL('EDIT','ypos_level',"lvl='$nama', aktif='$aktif'","idlevel=$id");
	}
	header("location:../../index.php?$set->folder_modul=$modul&sub=level&msg=done");
	break;
	case 'edLM': //not use
	if ($id == 1) {
		yposSQL('EDIT','ypos_grouplvlmdl',"modulID=NULL","idlevel=$id && (modulID IS NULL OR modulID !=0)");
		//$JM = yposSQL('SHOW','ypos_modul','count(modulID) as JM',"1=1")->fetch_array();
	} else {
		yposSQL('EDIT','ypos_grouplvlmdl',"modulID=NULL","idlevel=$id");
		//$JM = yposSQL('SHOW','ypos_modul','count(modulID) as JM',"1=1")->fetch_array();
	}
	
	
	$m = $_POST['mod'];
	if (isset($m)) {
		foreach($m as $mods) {
			$mod = explode('-',$mods);
			$idm = $mod[0];
			$mid = $mod[1];
			yposSQL('EDIT','ypos_grouplvlmdl','modulID='.$mid.', userID="CED"',"idGroupLM=$idm");
			echo $idm .'-'. $mid.'<br/>';
		}
	}
	$mysqli->query("CALL ypos_modManag('$id')");
	break;
	case 'saveRptAkses':
	yposSQL('DELETE','ypos_rptpriv',"idlevel=$id");
	
	$JP = yposSQL('SHOW','ypos_paramchild','count(idpc) as JP',"idpm=2 && 1=1")->fetch_array();
	for ($i=0; $i < $JP['JP']; $i++) {
		$rpt = $_POST['rpt'][$i];
		if (isset($rpt)) {
			yposSQL('ADD','ypos_rptpriv',"idparam=$rpt, idlevel=$id, userID='$_SESSION[yuser]', akses='Y'");
		} else {
			yposSQL('ADD','ypos_rptpriv',"idparam=0, idlevel=$id, userID='$_SESSION[yuser]', akses='N'");
		}
		header("location:../../index.php?$set->folder_modul=$modul&sub=rpt-akses&level=$level&id=$id&msg=done");
}
	break;
	case 'addPrm':
	$prm = anti($_POST['prm']);
	$desc = anti($_POST['desc']);
	
	if (NULL !== cekData('ypos_parameter',"nama_param='$prm'")) {
		header("location:../../index.php?$set->folder_modul=$modul&sub=parameter&msg=error&errno=1000");
	} else {
		yposSQL('ADD','ypos_parameter',"nama_param='$prm', ket='$desc', userid='$_SESSION[yuser]'");
		header("location:../../index.php?$set->folder_modul=$modul&sub=parameter&msg=done");
	}
	break;
	case 'edPrm':
	$prm = anti($_POST['prm']);
	$desc = anti($_POST['desc']);
	
	if (NULL !== cekData('ypos_parameter',"idpm != $id && nama_param='$prm'")) {
		header("location:../../index.php?$set->folder_modul=$modul&sub=parameter&msg=error&errno=1000");
	} else {
		yposSQL('EDIT','ypos_parameter',"nama_param='$prm', ket='$desc', userid='$_SESSION[yuser]'","idpm=$id");
		header("location:../../index.php?$set->folder_modul=$modul&sub=parameter&msg=done");
	}
	break;
	case 'addPrmChild':
	$prm = anti($_POST['nm']);
	$desc = anti($_POST['desc']);
	
	if (NULL !== cekData('ypos_paramchild',"child_name='$prm'")) {
		header("location:../../index.php?$set->folder_modul=$modul&sub=parameter-child&id=$id&msg=error&errno=1000");
	} else {
		yposSQL('ADD','ypos_paramchild',"child_name='$prm', idpm=$id, ket='$desc', aktif='Y'");
		header("location:../../index.php?$set->folder_modul=$modul&sub=parameter-child&id=$id&msg=done");
	}
	
	break;
	case 'edPrmChild':
	$idpc = abs((int)($_GET['idpc']));
	$prm = anti($_POST['nm']);
	$desc = anti($_POST['desc']);
	
	if (NULL !== cekData('ypos_paramchild',"idpc != $idpc && child_name='$prm'")) {
		header("location:../../index.php?$set->folder_modul=$modul&sub=parameter-child&id=$id&msg=error&errno=1000");
	} else {
		yposSQL('EDIT','ypos_paramchild',"child_name='$prm', idpm=$id, ket='$desc', aktif='$aktif'","idpc=$idpc");
		header("location:../../index.php?$set->folder_modul=$modul&sub=parameter-child&id=$id&msg=done");
	}
	
	break;
	} 
} else {
	echo $akses;
}
?>