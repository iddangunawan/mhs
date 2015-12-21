<?php
if (!defined('YBASE')) exit ('Now Allowed');
include 'notification.php';
switch($act) {
default :?>
<h3>General Settings</h3>
 <form method="post" action="<?php echo $set->folder_modul.'/'.$modul;?>/aksi.php?<?php echo $set->folder_modul.'='.$modul.'&act=edit';?>" name="form" id="form">
<table>
		<tr>
		<th>Nama Toko</th>
		<td><input type="text" class="inp-form" name="nm" required="required" size="70" value="<?php echo $set->nama_toko;?>"/></td>
		</tr>
        <tr>
		<th>Kode</th>
		<td><input type="text" class="inp-form" name="kdset" required="required" size="20" maxlength="3" value="<?php echo $set->kdSET;?>"/> <i>*Digunakan untuk kode awal pembelian</td>
		</tr>
        <tr>
        <th>Web URL</th>
        <td><input type="text" name="url" required size="70" value="<?php echo $set->url_web;?>"/></td></tr>
        <tr>
		<th>Alamat</th>
        <td><input type="text" name="alamat" size="70" required="required" value="<?php echo $set->alamat;?>"/></td>
        </tr>
        <tr>
        <th>Kec/Kab</th>
        <td><input type="text" name="keckab" size="50" required="required" value="<?php echo $set->keckab;?>"/></td>
        </tr>
        <tr>
        <th>TLP</th>
        <td><input type="text" name="tlp" size="50" required="required" value="<?php echo $set->tlp;?>"/></td></tr>
        <tr>
        <th>Printer</th>
        <td><input type="text" name="printer" size="50" required="required" value="<?php echo $set->printer;?>"/></td></tr>
        <tr><th>Folder Module</th><td><input type="text" disabled="disabled" name="folder" size="20" required value="<?php echo $set->folder_modul;?>"/> <b>Limit Per Page</b> <input type="text" name="limit" size="3" value="<?php echo $set->limit_page;?>"/></td></tr>
        <tr>
        <td>
        <input type="hidden" name="tipe" value="add"/></td><td>
        <button type="submit" name="save" value="ok">Simpan</button>
        </td></tr>
</table>
</form>
<i>*Untuk URL web "/".</i>
<?php
break;
}
?>