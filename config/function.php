<?php
//fungsi anti_inject
function anti($data){
  @$filter_sql = stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES)));
  @$filter_sql->$mysqli->real_escape_string;
  return $filter_sql;
}

function yposSQL($sql, $table, $field, $where=NULL, $order=NULL, $group=NULL) {
	global $mysqli;
	switch($sql) {
		case 'SHOW':
		if (empty($order) && empty($group)) {
			$query = $mysqli->query("SELECT $field FROM $table WHERE $where");
		} elseif (empty($group)) {
			$query = $mysqli->query("SELECT $field FROM $table WHERE $where ORDER BY $order");
		} else {
			$query = $mysqli->query("SELECT $field FROM $table WHERE $where GROUP BY $group ORDER BY $order");
		}
		break;
		case 'ADD':
			$query = $mysqli->query("INSERT INTO $table SET $field");
		break;
		case 'EDIT':
			$query = $mysqli->query("UPDATE $table SET $field WHERE $where");
		break;
		case 'DELETE':
			$query = $mysqli->query("DELETE FROM $table WHERE $field");
		break;
		} // end case
		return $query;
	} // end function yposSQL
	
//fungsi generate auto kode
function genCode($first, $field, $table, $char){ //kode awal, field kode, nama table dan panjang kode
global $mysqli;
	$get = yposSQL('SHOW',"$table","MAX(RIGHT($field, $char)) as maxID",'1=1',"$field");
	$code = $get->fetch_array(); 
	$genKode = $code['maxID']; 
	$getCode = (int) substr($genKode, 1, $char); 
	$getCode++; 
	$theCode = $first.sprintf("%0".$char."s", $getCode); 
	return $theCode; 
}

function cekAkses ($modul, $level, $act=NULL) {
	global $mysqli;
	if (empty($act)) {
		$akses = yposSQL('SHOW','ypos_modul a, ypos_grouplvlmdl b',"'x'","a.modulID=b.modulID && modul_folder='$modul' && idlevel=$level && aktif='Y'")->fetch_array();
	} elseif ($act == 'edit') {
		$akses = yposSQL('SHOW','ypos_modul a, ypos_grouplvlmdl b',"'x'","a.modulID=b.modulID && modul_folder='$modul' && idlevel=$level && aktif='Y' && e='Y'")->fetch_array();
	} elseif ($act == 'delete') {
		$akses = yposSQL('SHOW','ypos_modul a, ypos_grouplvlmdl b',"'x'","a.modulID=b.modulID && modul_folder='$modul' && idlevel=$level && aktif='Y' && d='Y'")->fetch_array();
	} elseif ($act == 'add' || 'new') {
		$akses = yposSQL('SHOW','ypos_modul a, ypos_grouplvlmdl b',"'x'","a.modulID=b.modulID && modul_folder='$modul' && idlevel=$level && aktif='Y' && c='Y'")->fetch_array();
	}
	return $akses;
}

function cekBrowser() {
	if	(strpos($_SERVER['HTTP_USER_AGENT'], 'Netscape')){
    		$browser = 'Netscape';
	} else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox')){
    		$browser = 'Firefox';
	} else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')){
    		$browser = 'Chrome';
	} else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera')){
    		$browser = 'Opera';
	} else if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')){
    		$browser = 'Internet Explorer';
	} else  {
			$browser = 'Other';
        }
		return $browser;
}

function LgnLogs($user,$ip,$host,$agent,$ket) {
	global $mysqli;
	yposSQL('ADD','ypos_lgnhistories',"username='$user', ip='$ip', hostname='$host', browser='$agent',ket='$ket'");
}

function cekSession($ssi) {
	global $mysqli;
	$ip = $_SERVER['REMOTE_ADDR'];
	$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	
	$s = yposSQL('SHOW','ypos_users','sessionID',"username='$ssi' && 1=1")->fetch_array();
		if ($s['sessionID'] != $_SESSION['ysess']) {
		LgnLogs($_SESSION['yuser'],$ip,$hostname,cekBrowser(),'OUT');
		session_destroy();
		echo 'Logout . . .';
		echo '<meta http-equiv="refresh" content="0; url=index.php">';
	}
	
}

//tanggal indo
function tgl_indo($tgl){
			$tanggal = substr($tgl,8,2);
			$bulan = getBulan(substr($tgl,5,2));
			$tahun = substr($tgl,0,4);
			return $tanggal.' '.$bulan.' '.$tahun;		 
	}
function getBulan($bln){
				switch ($bln){
					case 1: 
						return "Januari";
						break;
					case 2:
						return "Februari";
						break;
					case 3:
						return "Maret";
						break;
					case 4:
						return "April";
						break;
					case 5:
						return "Mei";
						break;
					case 6:
						return "Juni";
						break;
					case 7:
						return "Juli";
						break;
					case 8:
						return "Agustus";
						break;
					case 9:
						return "September";
						break;
					case 10:
						return "Oktober";
						break;
					case 11:
						return "November";
						break;
					case 12:
						return "Desember";
						break;
				}

}

function idr($angka){
  $rupiah=number_format($angka,0,',','.');
  return $rupiah;
}

function cekData($table, $where, $field=NULL) {
	global $mysqli;
	if ($field==NULL) {
		$cek = yposSQL('SHOW',"$table","'x'","$where")->fetch_array();
	} else {
		$cek = yposSQL('SHOW',"$table","$field","$where")->fetch_array();
	}
	return $cek;
}

function stdChoice($data,$name='aktif') {
	global $mysqli;
	$ds = yposSQL('SHOW','ypos_paramchild','*',"idpm=1 && aktif='Y' && 1=1");
	$r = '';
	$r .= '<td>';
	$r .= "<select name=$name>";
	while ($chz = $ds->fetch_array()) {
			if ($chz['child_name'] == $data) {
		$r .= "<option value=$chz[child_name] selected=selected>$chz[child_name]</option>";
			} else {
		$r .= "<option value=$chz[child_name]>$chz[child_name]</option>";
			}
		}
    $r .= '</select>';
	$r .= '</td>';
	return $r;
}