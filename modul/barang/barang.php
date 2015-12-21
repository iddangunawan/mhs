<script type="text/javascript">
    // open a pop up window
    function openPopUpWindow(targetField){
		LeftPosition = (screen.width) ? (screen.width-630)/2 : 0;
		TopPosition = (screen.height) ? (screen.height-300)/2 : 0;
        var w = window.open('TakePicture.php','_blank','height=300,width=630,top='+TopPosition+',left='+LeftPosition+',toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no');
        // pass the targetField to the pop up window
       w.targetField = targetField;
       //w.focus();
    }
    // this function is called by the pop up window
    function setSearchResult(targetField, returnValue){
        targetField.value = returnValue;
        window.focus();
    }
</script>
<?php
if (!defined('YBASE')) exit ('Now Allowed');
include 'notification.php';
switch($act) {
	default :?>
<a href="<?php echo $set->folder_modul.'='.$modul.'&act=add';?>"><button name="save" value="add">Tambah</button></a>
<div id="search_box">
<b>Cari Barang :</b> <input type="text" id="text" size="100" class="inp-form" placeholder="Masukan nama barang untuk mencari">
<input type="hidden" name="ypos_field" id="ypos_field" value="nama_barang"/>
<input type="hidden" id="modul" value="<?php echo $modul;?>"/>
</div>
<div id="tampil_data"></div>
<div id="paging"></div>
<?php
	break;
	case 'add':
?>
<form method="post" action="<?php echo $set->folder_modul.'/'.$modul;?>/aksi.php?<?php echo $set->folder_modul.'='.$modul.'&act='.$act;?>" name="thisForm" id="form">
<fieldset class="atas">
<table>
  <tr>
    <td>Kode Barang</td>
    <td>:</td>
    <td><input type="text" class="inp-form" name="kode" required="required" size="25" value="<?php echo genCode('B','kdbarang','ypos_barang','4');?>"/></td>
    <td>Nama Barang (Diberi Keterangan, Harga 1. <br>Eceran, 2. Retail, 3. Grosir) </td>
    <td>:</td>
    <td><input type="text" class="inp-form" name="nama" required="required" size="35"/></td>
    <td></td>
  </tr>
    <tr>
    <td>Stok Awal</td>
    <td>:</td>
    <td><input type="text" class="inp-form" name="stok" required="required" size="7"/></td>
    <td>Harga Jual</td>
    <td>:</td>
    <td><input type="text" class="inp-form" name="hrgaJual" required="required" size="15"/></td>
    <td></td>
  </tr>
    <tr>
    <td>Lokasi/Rak</td>
    <td>:</td>
    <td><input type="text" class="inp-form" name="lokasi" required="required" size="7"/></td>
    <td>Harga Beli</td>
    <td>:</td>
    <td><input type="text" class="inp-form" name="hrgaBeli" required="required" size="15"/></td>
    <td></td>
  </tr>
  <tr>
    <td>Kategori</td>
    <td>:</td>
    <td colspan="4"><div class="styled-select slate semi-square"><select name="cat"><option value="#">Kategori</option>
    <?php $cat = yposSQL('SHOW','ypos_kategori','idkat, nama_kat',"ids=$_SESSION[yids] && 1=1",'nama_kat');
	while ($rc = $cat->fetch_array()) {
		echo "<option value='$rc[idkat]'>$rc[nama_kat]</option>";
	} $cat->free_result();?>
    </select></div></td>
    <td></td>
  </tr>
  <tr>
    <td>Photo</td>
    <td>:</td>
    <td colspan="7"><input type="text" name="pic" size="70" readonly="readonly"/> <input type="button" onclick="openPopUpWindow(document.thisForm.pic)" value="Take Picture" class="submit"/></td>
  </tr>
      <tr>
    <td>&nbsp;</td>
    <td></td>
    <td colspan="2"><button type="submit" name="save" value="ok">Simpan</button></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
</table>
</fieldset>
<input type="hidden" name="jURL" value="<?php echo strlen($set->url_web.$set->folder_modul)+1;?>"/>
<input type="hidden" name="tipe" value="save">
</form>
<?php 
break;
case 'edit':
$ed = yposSQL('SHOW','ypos_barang a, ypos_kategori b','kdbarang, nama_barang, harga_beli, harga_jual, stok, lokasi, gambar, a.idkat, nama_kat',"kdbarang='$kode' && a.idkat=b.idkat && a.ids=$_SESSION[yids] && 1=1")->fetch_array();?>
<form method="post" action="<?php echo $set->folder_modul.'/'.$modul;?>/aksi.php?<?php echo $set->folder_modul.'='.$modul;?>" name="thisForm" id="form">
<fieldset class="atas">
<table>
  <tr>
    <td>Kode Barang</td>
    <td>:</td>
    <td><input type="text" class="inp-form" name="kode" required="required" size="25" value="<?php echo $ed['kdbarang'];?>" readonly="readonly"/></td>
    <td>Nama Barang (Diberi Keterangan, Harga 1. <br>Eceran, 2. Retail, 3. Grosir)</td>
    <td>:</td>
    <td><input type="text" class="inp-form" name="nama" required="required" size="35" value="<?php echo $ed['nama_barang'];?>"/></td>
    <td></td>
  </tr>
    <tr>
    <td>Stok Awal</td>
    <td>:</td>
    <td><input type="text" class="inp-form" name="stok" required="required" size="7" value="<?php echo $ed['stok'];?>"/></td>
    <td>Harga Jual</td>
    <td>:</td>
    <td><input type="text" class="inp-form" name="hrgaJual" required="required" size="15" value="<?php echo $ed['harga_jual'];?>"/> <select name="cat">
    <?php $cat = yposSQL('SHOW','ypos_kategori','idkat, nama_kat',"ids=$_SESSION[yids] && 1=1");
	while ($rc = $cat->fetch_array()) {
		if ($ed['idkat'] == $rc['idkat']) {
			echo "<option value='$rc[idkat]' selected='selected'>$rc[nama_kat]</option>";
		} else {
			echo "<option value='$rc[idkat]'>$rc[nama_kat]</option>";
		}
	} ?>
    </select></td>
    <td></td>
  </tr>
    <tr>
    <td>Lokasi</td>
    <td>:</td>
    <td><input type="text" class="inp-form" name="lokasi" required="required" size="7" value="<?php echo $ed['lokasi'];?>"/></td>
    <td>Harga Beli</td>
    <td>:</td>
    <td><input type="text" class="inp-form" name="hrgaBeli" required="required" size="15" value="<?php echo $ed['harga_beli'];?>"/></td>
    <td></td>
  </tr>
  <tr>
    <td>Photo</td>
    <td>:</td>
    <td colspan="7"><input type="text" name="pic" size="70" readonly="readonly"/> <input type="button" onclick="openPopUpWindow(document.thisForm.pic)" value="Take Picture"/></td>
  </tr>
      <tr>
    <td>&nbsp;</td>
    <td></td>
    <td colspan="2"><button type="submit" name="save" value="ok">Simpan</button></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
</table>
</fieldset>
<input type="hidden" name="jURL" value="<?php echo strlen($set->url_web.$set->folder_modul)+1;?>"/>
<input type="hidden" name="tipe" value="edit">
</form><?php
}?>