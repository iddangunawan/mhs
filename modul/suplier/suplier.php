<?php
if (!defined('YBASE')) exit ('Now Allowed');
include 'notification.php';
switch($act) {
	default :?>
<a href="<?php echo $set->folder_modul.'='.$modul.'&act=add';?>"><button name="save" value="add">Tambah</button></a>
<table id="dataTable" width="80%">
<tr id="tbl">
<th>No</th>
<th>Nama Suplier</th>
<th>TLP</th>
<th>Alamat</th>
<th></th>
<th></th></tr>
<?php
$no =1;
$q = yposSQL('SHOW','ypos_suplier','kdsup, nama_sup, tlp, alamat',"ids=$_SESSION[yids] && 1=1",'kdsup DESC');
if (empty($q)) {
	echo '<tr><td colspan="5" align="center">Belum Ada</td></tr>';
} else {
while ($r = $q->fetch_array()) {?>
				<tr align="center">			
                <td align="center"><?php echo $no;?></td>
					<td><?php echo $r['nama_sup'];?></td>
					<td><?php echo $r['tlp'];?></td>
                    <td><?php echo $r['alamat'];?></td>
					<td align="center">
					<a href="<?php echo $set->folder_modul.'='.$modul?>&act=edit&id=<?php echo $r['kdsup'];?>"><img src="images/icon-edit-on.png" border="0" width="20" height="20" /></a></td><td align="center"><a href="<?php echo $set->folder_modul.'='.$modul?>&act=delete&id=<?php echo $r['kdsup'];?>" onClick="return confirm('Anda yakin ingin menghapus data ini?')"><img src="images/delete-icon.png" border="0" width="20" height="20" /></a>
					</td>
				</tr>
                <?php
				$no++;
				} //end while
} ?>
</table>
<?php
break;
case 'add':?>
 <form method="post" action="<?php echo $set->folder_modul.'/'.$modul;?>/aksi.php?<?php echo $set->folder_modul.'='.$modul.'&act='.$act;?>" name="form" id="form">
    <fieldset class="atas">
<table>
		<tr>
			<th>Nama Suplier</th>
			<td><input type="text" class="inp-form" name="nm" required="required" size="25"/> <b>HP :</b>
            <input type="text" class="inp-form" name="hp" required="required" size="20"/>
            </td>
		</tr>
                <tr>
		<th>Alamat</th>
        <td><textarea rows="5" cols="42" name="alamat"></textarea></td>
        </tr>
        <tr><th></th>
        <td align="right">
        <input type="hidden" name="tipe" value="add"/>
        <button type="submit" name="save" value="ok">Simpan</button>
        <a href="<?php echo $set->folder_modul.'='.$modul;?>"><button>Back</button></a>
        </td></tr>
</table>
</fieldset>
</form>
<?php
break;
case 'edit': 
$ed = yposSQL('SHOW','ypos_suplier','kdsup, nama_sup, tlp, alamat',"kdsup=$id && 1=1")->fetch_array();?>
    <form method="post" action="<?php echo $set->folder_modul.'/'.$modul;?>/aksi.php?<?php echo $set->folder_modul.'='.$modul.'&act='.$act;?>&id=<?php echo $id;?>" name="form" id="form">
        <fieldset class="atas">
<table>
		<tr>
			<th>Nama Suplier</th>
			<td><input type="text" class="inp-form" name="nm" required="required" value="<?php echo $ed['nama_sup'];?>" size="25"/>
            <b>HP :</b><input type="text" class="inp-form" name="hp" required="required" value="<?php echo $ed['tlp'];?>" size="20"/>
            </td>
		</tr>
                <tr>
		<th>Alamat</th>
        <td><textarea rows="5" cols="42" name="alamat"><?php echo $ed['alamat'];?></textarea></td>
        </tr>
        <tr><th></th>
        <td align="right">
        <input type="hidden" name="tipe" value="edit"/>
        <button type="submit" name="save" value="ok">Simpan</button>
        <a href="<?php echo $set->folder_modul.'='.$modul;?>"><button type="button">Back</button></a>
        </td></tr>
</table>
</fieldset>
</form>
<?php
break;
case 'delete':
yposSQL('DELETE','ypos_suplier',"kdsup=$id");
echo "<meta content='0; url=$set->folder_modul=$modul' http-equiv='refresh'/>";
break;
}
?>