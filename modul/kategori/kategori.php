<?php
if (!defined('YBASE')) exit ('Now Allowed');
include 'notification.php';
@$act = $_GET['act'];
switch($act) {
	default :?>
    <form method="post" action="<?php echo $set->folder_modul.'/'.$modul;?>/aksi.php?<?php echo $set->folder_modul.'='.$modul.'&act=add';?>" name="form" id="form">
    <fieldset class="atas">
<table>
		<tr>
		<th>Create New</th>
		<td><input type="text" class="inp-form" name="kat" placeholder="Kategori Name" value="<?php if (isset($_GET['no'])) { 
		echo $_GET['nama'];
		};?>" required="required" size="30"/></td>
        <td><button type="submit" name="save" value="ok">Simpan</button></td></tr>
</table>
</fieldset>
<input type="hidden" name="tipe" value="save">
</form>
<?php
break;
case 'edit':
$ed = yposSQL('SHOW','ypos_kategori','idkat, nama_kat',"idkat=$id")->fetch_array();
?>
<form method="post" action="<?php echo $set->folder_modul.'/'.$modul;?>/aksi.php?<?php echo $set->folder_modul.'='.$modul;?>&id=<?php echo $id;?>&act=edit" name="form" id="form">
<fieldset class="atas">
<table>
		<tr>
		<th>Edit Category Name</th>
		<td><input type="text" class="inp-form" name="kat" required="required" size="30" value="<?php echo $ed['nama_kat'];?>"/></td>
        <td><button type="submit" name="save" value="ok">Simpan</button></td></tr>
</table>
</fieldset>
<input type="hidden" name="tipe" value="edit">
</form>
<?php
break;
}
?>
<table id="dataTable" width="80%">
<tr id="tbl">
<th>No</th>
<th>Kategori</th>
<th></th></tr>
<?php
$no =1;
$q = yposSQL('SHOW','ypos_kategori','idkat, nama_kat',"ids=$_SESSION[yids]",'nama_kat');
				while ($r = $q->fetch_array()) {?>
				<tr align="center">			
                <td align="center"><?php echo $no;?></td>
					<td><?php echo $r['nama_kat'];?></td>
					<td align="center">
					<a href="<?php echo $set->folder_modul.'='.$modul?>&act=edit&id=<?php echo $r['idkat'];?>"><img src="images/icon-edit-on.png" border="0" width="20" height="20" /></a>
					</td>
				</tr>
                <?php
				$no++;
				}?>
				
				</table>
                <?php
$q->free_result();
?>