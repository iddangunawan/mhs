<?php 
if (!defined('YBASE')) exit ('Now Allowed');
include 'notification.php';
switch(@$_GET['sub']) {
	case 'modul-akses':
	$l = anti($_GET['level']);
	echo '<h3>Settings Hak Akses '.$l.'</h3>';
	echo '<hr width="470" align="left">';
	$qm = yposSQL('SHOW','ypos_modul LEFT JOIN ypos_grouplvlmdl ON ypos_modul.`modulID`=ypos_grouplvlmdl.`modulID` OR ypos_grouplvlmdl.`modulID` IS NULL INNER JOIN ypos_level ON ypos_level.`idlevel`=ypos_grouplvlmdl.`idlevel`','ypos_modul.`modulID` AS modID, ypos_modul.nama_modul,idGroupLM, ypos_grouplvlmdl.modulID,r,c,e,d, ypos_grouplvlmdl.idlevel, ypos_level.`lvl`',"ypos_modul.modulID != 0 && ypos_modul.aktif='Y' && ypos_grouplvlmdl.idlevel=$id && lvl='$l'",'ypos_modul.nama_modul','ypos_modul.nama_modul');
?>
<table>
<tr bgcolor="#78d0ed">
<th rowspan="2" width="30">No</th>
<th rowspan="2" align="centre" width="150">Module</th>
<th colspan="4" align="center">Permission</th>
<th rowspan="2" align="center">Change</th>
</tr>
<tr bgcolor="#78d0ed">
  <th width="50">Read</th>
  <th width="50">Create</th>
  <th width="50">Edit</th>
  <th width="50">Delete</th>
  </tr>
<tr>
<tr>
<form method="post" action="<?php echo $set->folder_modul.'/'.$modul;?>/aksi.php?<?php echo $set->folder_modul.'='.$modul.'&sub='.$act.'&level='.anti($_GET['level']).'&id='.$id;?>" name="form" id="form">
<?php
$no = 1;
while ($rm = $qm->fetch_array()) {?>
<tr>
<td align='center'><?php echo $no;?></td>
<td width="20" align="center">
<input type='hidden' class="Blocked" name='mod[]' value='<?php echo $rm['idGroupLM'].'-'.$rm['modID'];?>'>
<?php echo $rm['nama_modul'];?></td>
<td align="center"><input type='checkbox' name='R[]' <?php if ($rm['r'] == 'Y') { echo $checked; };?> disabled="disabled"/></td>
<td align="center"><input type='checkbox' name='C[]' <?php if ($rm['c'] == 'Y') { echo $checked; };?> disabled="disabled"/></td>
<td align="center"><input type='checkbox' name='E[]' <?php if ($rm['e'] == 'Y') { echo $checked; };?>/ disabled="disabled"></td>
<td align="center"><input type='checkbox' name='D[]' <?php if ($rm['d'] == 'Y') { echo $checked; };?> disabled="disabled"/></td>
<td align="center"><a href="#dialog-permission" id="<?php echo $rm['idGroupLM'];?>" class="proses" data-toggle="modal"><img src="images/icon-edit-on.png" border="0" width="20" height="20" /></a></td>
</tr>
<?php $no++; };?>
<input type="hidden" name="tipe" value="edLM"/>
</form>
<tr>
<td></td>
<td></td>
<td colspan="4" align="center"></td>
<td align="center"><a href="modul=system&sub=level"><button>Back</button></a></td>
</tr>
</table>
<!-- awal untuk modal dialog -->
<div id="dialog-permission" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">[x]</button>
		<h3 id="myModalLabel">Edit Permission</h3>
	</div>
	<!-- tempat untuk menampilkan form mahasiswa -->
	<div class="modal-body"></div>
	<div class="modal-footer">
	<button id="simpan-permission" class="submit">Update</button>
	</div>
</div>
<!-- akhir kode modal dialog -->
<?php
	break;
	case 'rpt-akses':
	$l = anti($_GET['level']);
	echo '<h3>Settings Report Akses '.$l.'</h3>';
	echo '<hr width="445" align="left">';
	if (NULL !== cekData('ypos_grouplvlmdl',"idlevel=$id && modulID=7 && r='Y'")) {
?>
<table>
<tr bgcolor="#78d0ed">
<th width="25">No</th>
<th align="centre" width="350">Report Name</th>
<th align="center"></th>
</tr>
<form method="post" action="<?php echo $set->folder_modul.'/'.$modul;?>/aksi.php?<?php echo $set->folder_modul.'='.$modul.'&sub=rpt-akses&level='.anti($_GET['level']).'&id='.$id;?>" name="form" id="form">
<?php
$no = 1;
$q = yposSQL('SHOW','ypos_paramchild ypc RIGHT JOIN ypos_rptpriv yrp2 ON ypc.idpc=yrp2.idparam OR yrp2.idparam=0','DISTINCT ypc.idpc as idparam, ypc.child_name as nama_report, yrp2.akses',"ypc.idpm=2 && ypc.aktif='Y' && yrp2.idlevel=$id && 1=1",'child_name','child_name');
while ($rpt = $q->fetch_array()) {
	?>
<tr>
<td align="center"><?php echo $no;?></td>
<td><?php echo $rpt['nama_report'];?></td>
<td><input type="checkbox" name="rpt[]" value="<?php echo $rpt['idparam'];?>" <?php 
	if ($rpt['idparam'] != 0 && $rpt['akses'] == 'Y') { 
		echo $checked;
	}?>/></td>
</tr>
<?php $no++;
}?>
<tr>
<td>
<a href="modul=system&sub=level"><img src="images/back_button.jpg" border="0" /></a></td>
<td colspan="2" align="right">
<input type="hidden" name="tipe" value="saveRptAkses"/>
<input type="submit" class="submit" name="save" value="Save" /></td>
</tr>
</form>
</table>
<?php
} else {
	echo '<b>Error! : Modul "Reports" belum di aktifkan untuk level akses ini!</b> <a href="modul=system&sub=level"><button>Back</button></a>';
}
	break;
	case 'level':
	if (isset($_GET['op'])) {
	$ed = yposSQL('SHOW','ypos_level','*',"idlevel=$id && 1=1")->fetch_array();
	};?>
	<form method="post" action="<?php echo $set->folder_modul.'/'.$modul;?>/aksi.php?<?php echo $set->folder_modul.'='.$modul.'&sub='.$act.'&id='.@$ed['idlevel'];?>" name="form" id="form">
    <fieldset class="atas">
<table>
		<tr>
		<th><?php if (isset($_GET['op'])) { echo 'Edit'; } else { echo 'New';}?> Level</th>
		<td><input type="text" class="inp-form" name="nama" placeholder="Level Name" value="<?php echo @$ed['lvl'];?>" required="required" size="30"/></td>
        <td>
        <?php 
		if (isset($_GET['op'])) {
			if ($ed['idlevel'] == 1) { 
			echo "<input type='text' name='aktif' value='$ed[aktif]' size='2' disabled=disabled";
			} else {
			echo stdChoice($ed['aktif']);
			}
		}?>
        </td>
        <td><button type="submit" name="save" value="ok">Simpan</button></td></tr>
</table>
</fieldset>
<input type="hidden" name="tipe" value="<?php if (isset($_GET['op'])) { echo 'edLvl'; } else { echo 'saveLvl';};?>">
</form>
<table id="dataTable">
<tr id="tbl">
<th width="30">No</th>
<th width="200">Level</th>
<th width="25">Aktif</th>
<th width="100">Modul Akses</th>
<th width="100">Report Akses</th>
<th width="25"></th>
<th width="25"></th></tr>
<?php
$no =1;
$q = yposSQL('SHOW','ypos_level','*','1=1','lvl');
				while ($r = $q->fetch_array()) {?>
				<tr align="center">			
                <td><?php echo $no;?></td>
					<td><?php echo $r['lvl'];?></td>
                    <td align="center"><?php echo $r['aktif'];?></td>
                    <td><a href="<?php echo $set->folder_modul.'='.$modul?>&sub=modul-akses&op=ed&id=<?php echo $r['idlevel'].'&level='.$r['lvl'];?>">Set</a></td>
                <td><a href="<?php echo $set->folder_modul.'='.$modul?>&sub=rpt-akses&op=ed&id=<?php echo $r['idlevel'].'&level='.$r['lvl'];?>">Set</a></td>
					<td align="center"><?php
                    if ($r['idlevel'] == 1) {
						echo '<img src="images/icon-edit-off.png" border="0" width="20" height="20" />';
					} else{?>
					<a href="<?php echo $set->folder_modul.'='.$modul?>&sub=level&op=ed&id=<?php echo $r['idlevel'];?>"><img src="images/icon-edit-on.png" border="0" width="20" height="20" /></a><?php }?></td>
                    <td align="center">
                    <?php
					if ($r['idlevel'] == 1) {
						echo '<img src="images/delete-icon-off.png" border="0" width="20" height="20" />';
					} else {?>
                    <a href="<?php echo $set->folder_modul.'='.$modul?>&sub=level&op=del&id=<?php echo $r['idlevel'];?>" onClick="return confirm('Anda yakin ingin menghapus data ini?')"><img src="images/delete-icon.png" border="0" width="20" height="20" /></a>
                    <?php }?>
					</td>
				</tr>
                <?php
				$no++;
				}?>
				
				</table>
<?php
if (@$_GET['sub'] == 'level' && @$_GET['op']== 'del') {
	yposSQL('DELETE','ypos_level',"idlevel=$id");
	echo "<meta content='0; url=$set->folder_modul=$modul&sub=level&msg=sucessfully' http-equiv='refresh'>";
};
	break;
	case 'modul':
	if (isset($_GET['op'])) {
	$ed = yposSQL('SHOW','ypos_modul a, ypos_menu b','a.modulID, a.nama_modul, a.modul_folder, a.aktif, b.menuID',"a.modulID=$id and a.menuID=b.menuID and 1=1")->fetch_array();
	};?>
	<form method="post" action="<?php echo $set->folder_modul.'/'.$modul;?>/aksi.php?<?php echo $set->folder_modul.'='.$modul.'&sub='.$act.'&id='.$ed['modulID'];?>" name="form" id="form">
    <fieldset class="atas">
<table>
		<tr>
		<th><?php if (isset($_GET['op'])) { echo 'Edit'; } else { echo 'New';}?> Modul</th>
		<td><input type="text" class="inp-form" name="nama" placeholder="Nama Modul" value="<?php echo @$ed['nama_modul'];?>" required="required" size="30"/> <input type="text" required="required" name="folder" size="30" value="<?php echo @$ed['modul_folder'];?>"/></td>
        <?php if (isset($_GET['op'])) {
			echo stdChoice($ed['aktif']);
		}
		?>
        <td>
        Set in <select name="menu"><option value="#">Pilih Menu</option>
    <?php
	if (isset($_GET['op'])) { 
    	$m = yposSQL('SHOW','ypos_menu','menuID, menu',"1=1",'menu'); //jika kondisi edit
	} else {
		$m = yposSQL('SHOW','ypos_menu','menuID, menu',"aktif='Y' and 1=1",'menu'); //jika kondisi create
	}
	while ($rm = $m->fetch_array()) {
		if ($ed['menuID'] == $rm['menuID']) {
			echo "<option value='$rm[menuID]' selected='selected'>$rm[menu]</option>";
		} else {
			echo "<option value='$rm[menuID]'>$rm[menu]</option>";
		}
	}?>
    </select>
    <input type="hidden" name="tipe" value="<?php if (isset($_GET['op'])) { echo 'edMod'; } else { echo 'saveMod';};?>">
        <td><button type="submit" name="save" value="ok">Simpan</button></td></tr>
</table>
</fieldset>
</form>
<table id="dataTable">
<tr id="tbl">
<th width="30">No</th>
<th width="200">Modul</th>
<th width="200">Folder Name</th>
<th width="200">Menu</th>
<th width="25">Aktif</th>
<th width="50"></th></tr>
<?php
$no =1;
$q = yposSQL('SHOW','ypos_modul a, ypos_menu b',"a.modulID, a.nama_modul, a.modul_folder, a.aktif, b.menu","a.menuID=b.menuID and 1=1",'a.nama_modul');
				while ($r = $q->fetch_array()) {?>
				<tr align="center">			
                <td><?php echo $no;?></td>
					<td><?php echo $r['nama_modul'];?></td>
                    <td><?php echo $r['modul_folder'];?></td>
                    <td><?php echo $r['menu'];?></td>
                    <td align="center"><?php echo $r['aktif'];?></td>
					<td align="center">
					<a href="<?php echo $set->folder_modul.'='.$modul?>&sub=modul&op=ed&id=<?php echo $r['modulID'];?>"><img src="images/icon-edit-on.png" border="0" width="20" height="20" /></a>
					</td>
				</tr>
                <?php
				$no++;
				}?>
				
				</table>
<?php
	break;
	case 'menu':
	if (isset($_GET['op'])) {
	$ed = yposSQL('SHOW','ypos_menu','*',"menuID=$id")->fetch_array();
	}; ?>
	<form method="post" action="<?php echo $set->folder_modul.'/'.$modul;?>/aksi.php?<?php echo $set->folder_modul.'='.$modul.'&sub='.$act.'&id='.$ed['menuID'];?>" name="form" id="form">
    <fieldset class="atas">
<table>
		<tr>
		<th><?php if (isset($_GET['op'])) { echo 'Edit'; } else { echo 'New';}?> Menu</th>
		<td><input type="text" class="inp-form" name="nama" placeholder="Nama Menu" value="<?php echo @$ed['menu'];?>" required="required" size="30"/></td>
        <td><input type="text" name="order" placeholder="Order" size="3" value="<?php echo @$ed['sort'];?>"/></td>
		<?php if (isset($_GET['op'])) {
			echo stdChoice($ed['aktif']);
		}
		?>
        <td><button type="submit" name="save" value="ok">Simpan</button></td></tr>
</table>
</fieldset>
<input type="hidden" name="tipe" value="<?php if (isset($_GET['op'])) { echo 'edMenu'; } else { echo 'saveMenu';};?>">
</form>
<table id="dataTable" width="90%">
<tr id="tbl">
<th width="20">No</th>
<th width="300">Menu</th>
<th width="20">Order</th>
<th width="20">Aktif</th>
<th width="50"></th></tr>
<?php
$no =1;
$q = yposSQL('SHOW','ypos_menu','menuID, menu, aktif, sort','1=1','menu');
				while ($r = $q->fetch_array()) {?>
				<tr align="center">			
                <td><?php echo $no;?></td>
					<td><?php echo $r['menu'];?></td>
                    <td><?php echo $r['sort'];?></td>
                    <td align="center"><?php echo $r['aktif'];?></td>
					<td align="center">
					<a href="<?php echo $set->folder_modul.'='.$modul?>&sub=menu&op=ed&id=<?php echo $r['menuID'];?>"><img src="images/icon-edit-on.png" border="0" width="20" height="20" /></a>
					</td>
				</tr>
                <?php
				$no++;
				}?>
				
				</table><?php
	break;
	case 'parameter':
	$ed = yposSQL('SHOW','ypos_parameter','*',"idpm=$id")->fetch_array(); ?>
     <form method="post" action="<?php echo $set->folder_modul.'/'.$modul;?>/aksi.php?<?php echo $set->folder_modul.'='.$modul;?>&sub=parameter&id=<?php echo $id;?>" name="form" id="form">
    <fieldset class="atas">
<table>
		<tr>
			<th>Parameter Name</th>
			<td><input type="text" class="inp-form" name="prm" required="required" size="30" maxlength="50" value="<?php echo @$ed['nama_param'];?>"/></td>
            <th>Descriptions</th>
            <td><input type="text" class="inp-form" name="desc" required="required" size="50" value="<?php echo @$ed['ket'];?>"/></td>
            <td>
            <?php if (isset($_GET['op'])) {
				echo '<input type="hidden" name="tipe" value="edPrm"/>';
			} else {
				echo '<input type="hidden" name="tipe" value="addPrm"/>';
			}?>
        	<button type="submit" name="save" value="ok">Save</button></td>
		</tr>
</table>
</fieldset>
</form>
<table id="dataTable" width="90%">
<tr id="tbl">
<th width="20">No</th>
<th width="300">Parameter Name</th>
<th>Descriptions</th>
<th width="50"></th></tr>
<?php
$no =1;
$q = yposSQL('SHOW','ypos_parameter','*','1=1','nama_param');
				while ($r = $q->fetch_array()) {?>
				<tr align="center">			
                <td><?php echo $no;?></td>
					<td><a href="<?php echo $set->folder_modul.'='.$modul?>&sub=parameter-child&id=<?php echo $r['idpm'];?>"><?php echo $r['nama_param'];?></a></td>
                    <td><?php echo $r['ket'];?></td>
					<td align="center">
					<a href="<?php echo $set->folder_modul.'='.$modul?>&sub=parameter&op=edparam&id=<?php echo $r['idpm'];?>"><img src="images/icon-edit-on.png" border="0" width="20" height="20" /></a>
					</td>
				</tr>
                <?php
				$no++;
				}?>
				</table><?php
	break;
	case 'parameter-child':
	@$idpc = abs((int)($_GET['child']));
	@$param = anti($_GET['param']);
	
	if ($id != 1) {
	$ed = yposSQL('SHOW','ypos_paramchild','*',"idpc=$idpc && idpm=$id && child_name='$param'")->fetch_array();?>
     <form method="post" action="<?php echo $set->folder_modul.'/'.$modul;?>/aksi.php?<?php echo $set->folder_modul.'='.$modul.'&id='.$id.'&idpc='.@$ed['idpc'];?>" name="form" id="form">
    <fieldset class="atas">
<table>
		<tr>
			<th>Name</th>
			<td><input type="text" class="inp-form" name="nm" required="required" size="30" maxlength="50" value="<?php echo @$ed['child_name'];?>"/></td>
            <th>Descriptions</th>
            <td><input type="text" class="inp-form" name="desc" required="required" size="50" maxlength="100" value="<?php echo @$ed['ket'];?>"/></td>
            <?php if (isset($_GET['op'])) {
			echo stdChoice($ed['aktif']);
		}
		?>
            <td>
            <?php if (isset($_GET['op'])) {
				echo '<input type="hidden" name="tipe" value="edPrmChild"/>';
			} else {
				echo '<input type="hidden" name="tipe" value="addPrmChild"/>';
			}?>
            
        	<button type="submit" name="save" value="ok">Save</button></td>
		</tr>
</table>
</fieldset>
</form><?php }?>
<table id="dataTable">
<tr id="tbl">
<th width="20">No</th>
<th width="200">Parameter Name</th>
<th width="500">Descriptions</th>
<th width="20">Status</th>
<th width="50"></th></tr>
<?php
$no =1;
$q = yposSQL('SHOW','ypos_paramchild','*',"idpm=$id && 1=1",'child_name');
				while ($r = $q->fetch_array()) {?>
				<tr align="center">			
                <td><?php echo $no;?></td>
					<td><?php echo $r['child_name'];?></td>
                    <td><?php echo $r['ket'];?></td>
                    <td><?php echo $r['aktif'];?></td>
					<td align="center"><?php
                    if ($r['idpm'] == 1) {
						echo '<img src="images/icon-edit-off.png" border="0" width="20" height="20"/>';
					} else {?>
					<a href="<?php echo $set->folder_modul.'='.$modul?>&sub=parameter-child&op=edprm&id=<?php echo $r['idpm'].'&child='.$r['idpc'].'&param='.$r['child_name'];?>"><img src="images/icon-edit-on.png" border="0" width="20" height="20" /></a>
                    <?php }?>
					</td>
				</tr>
                <?php
				$no++;
				}?>
				</table>
				<?php
				echo "<a href=index.php?$set->folder_modul=$modul&sub=parameter><img src=images/back_button.jpg border=0 /></a>";
				$q->free_result();
	break;
}?>
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/action_permission.js"></script>