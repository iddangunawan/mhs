<?php
if (!defined('YBASE')) exit ('Now Allowed');
include 'notification.php';
@$user = anti($_GET['user']);

switch($act) {
default :?>
    <form method="post" action="<?php echo $set->folder_modul.'/'.$modul;?>/aksi.php?<?php echo $set->folder_modul.'='.$modul;?>" name="form" id="form">
    <fieldset class="atas">
<table>
		<tr>
		<th>Username</th>
		<td><input type="text" class="inp-form" name="username" placeholder="Username" required="required"/></td>
		<td><input type="text" class="inp-form" name="nm" required="required" placeholder="Nama Lengkap" size="40"/></td>
        <td><input type="text" class="inp-form" name="hp" required="required" placeholder="No HP" size="20"/></td>
		</tr>
        <tr>
		<th>Password</th>
        <td><input type="text" class="inp-form" name="pass" placeholder="Password" required="required" /></td>
		<td><div class="styled-select slate semi-square"><select name="level">
    <?php $l = yposSQL('SHOW','ypos_level','*','1=1','lvl');
	while ($lvl = $l->fetch_array()) {
		echo "<option value='$lvl[idlevel]'>$lvl[lvl]</option>";
	} $l->free_result();?>
    </select></div></td>
    <td align="right">
    <input type="hidden" name="tipe" value="add"/>
    <button type="submit" name="save" id="cari" value="ok">Simpan</button></td></tr>
</table>
</fieldset>
</form>
<?php
break;
case 'delete':
if ($_SESSION['yuser'] == "$user") {
	echo "<meta content='0; url=$set->folder_modul=$modul&msg=error' http-equiv='refresh'/>";
} else {
	yposSQL('DELETE','ypos_users',"username='$user'");
	echo "<meta content='0; url=$set->folder_modul=$modul&msg=sucessfully' http-equiv='refresh'/>";
}
break;
case 'edit':
$ed = yposSQL('SHOW','ypos_level a, ypos_users b','idlevel, b.*',"idlevel=level && username='$user' && 1=1")->fetch_array();?>
<form method="post" action="<?php echo $set->folder_modul.'/'.$modul;?>/aksi.php?<?php echo $set->folder_modul.'='.$modul.'&act='.$act.'&user='.$user;?>" name="form" id="form">
    <fieldset class="atas">
<table>
		<tr>
			<th>Username</th>
			<td><input type="text" class="inp-form" name="username" placeholder="Username" required="required" value="<?php echo $ed['username'];?>" readonly="readonly"/></td>
			<td colspan="2"><input type="text" class="inp-form" name="nm" required="required" placeholder="Nama Lengkap" size="40" value="<?php echo $ed['nama_lengkap'];?>"/></td>
            <td><input type="text" class="inp-form" name="hp" required="required" placeholder="No HP" size="20" value="<?php echo $ed['hp'];?>"/></td>
		</tr>
                <tr>
		<th>Password</th>
        <td><input type="text" class="inp-form" name="pass" placeholder="Password"/></td><?php
			echo stdChoice($ed['aktif']);?>
    <td><div class="styled-select slate semi-square">
        <select name="level">
    <?php $l = yposSQL('SHOW','ypos_level','*','1=1','lvl');
	while ($lvl = $l->fetch_array()) {
		if ($ed['level'] == $lvl['idlevel']) {
			echo "<option value='$lvl[idlevel]' selected=selected>$lvl[lvl]</option>";
		} else {
			echo "<option value='$lvl[idlevel]'>$lvl[lvl]</option>";
		}
	} $l->free_result();?>
    </select>
    </div></td>
    <td align="right">
    <input type="hidden" name="tipe" value="edit"/>
    <button type="submit" name="save" id="cari" value="ok">Simpan</button></td></tr>
</table>
<i><b>Note :</b><br/>
- Username Tidak bisa diganti.<br/>
- Kosongkan password jika tidak rubah.
</i>
</fieldset>
</form><?php
break;
case 'kill':
yposSQL('EDIT','ypos_users',"sessionID='0', online='N'","username='$user'");
echo "<meta content='0; url=$set->folder_modul=$modul&msg=sucessfully' http-equiv='refresh'>";
//header("location:$set->folder_modul=$modul&msg=sucessfully");
break;
}
?>
<table id="dataTable" width="90%">
<tr id="tbl">
<th>No</th>
<th>Username</th>
<th>Nama</th>
<th>HP</th>
<th>Online</th>
<th>Aktif</th>
<th width="100"></th>
<th width="80"></th></tr>
<?php
$no =1;
if ($act == 'edit') {
	$q = yposSQL('SHOW','ypos_users','username, nama_lengkap, hp, online, aktif',"username != '$user' && ids=$_SESSION[yids] && 1=1",'username');
} else {
	$q = yposSQL('SHOW','ypos_users','username, nama_lengkap, hp, online, aktif',"ids=$_SESSION[yids] && 1=1",'username');
}
				while ($r = $q->fetch_array()) {?>
				<tr align="center">			
                <td align="center"><?php echo $no;?></td>
					<td><?php echo $r['username'];?></td>
					<td><?php echo $r['nama_lengkap'];?></td>
                    <td><?php echo $r['hp'];?></td>
                    <td><?php
					if ($r['online'] == 'Y') {
						echo '<img src=images/online.jpg>';
					} else {
					    echo '<img src=images/offline.jpg>';
					};?></td>
                        
                    <td><?php echo $r['aktif'];?></td>
                    <td><a href="<?php echo $set->folder_modul.'='.$modul?>&act=kill&user=<?php echo $r['username'];?>">Kill Session</a></td>
					<td align="center">
					<a href="<?php echo $set->folder_modul.'='.$modul?>&act=edit&user=<?php echo $r['username'];?>"><img src="images/icon-edit-on.png" width="20" height="20" border="0"/></a> <a href="<?php echo $set->folder_modul.'='.$modul?>&act=delete&user=<?php echo $r['username'];?>" onClick="return confirm('Anda yakin ingin menghapus data ini?')"><img src="images/delete-icon.png" width="20" height="20" border="0" /></a>
					</td>
				</tr>
                <?php
				$no++;
				} $q->free_result();?>
				</table>