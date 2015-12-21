<?php
if (!defined('YBASE')) exit ('Now Allowed');
include 'notification.php';
if (@$_GET['op'] == 'edprod') {
	$idp = abs((int)($_GET['idp']));
	$edprod = yposSQL('SHOW','ypos_vPembelianDtl','kdbarang, nama_barang, harga_pokok_jual, idDtlPembelian, qty_beli, harga_beli_satBaru, total_beli',"idDtlPembelian=$idp")->fetch_array(); 
}

switch($act) {
	default :?>
<a href="<?php echo $set->folder_modul.'='.$modul.'&act=new';?>"><button name="save" value="add">Buat Baru</button></a>
<table id="dataTable"  class="table" width="80%">
<tr id="tbl">
<th>No</th>
<th>Kode Pembelian</th>
<th>Nota</th>
<th>Suplier</th>
<th>Total</th>
<th>Tanggal</th>
<th>Operator</th>
<th></th></tr>
<?php
$no =1;
$q = yposSQL('SHOW','ypos_pembelian a, ypos_suplier b','kdPembelian, no_nota, total_pembelian, userID, tgl_input, nama_sup',"a.kdsup=b.kdsup && a.ids=$_SESSION[yids] && 1=1",'tgl_input DESC');
	while ($r = $q->fetch_array()) {?>
				<tr align="center">			
                <td align="center"><?php echo $no;?></td>
				<td><?php echo $r['kdPembelian'];?></td>
				<td><?php echo $r['no_nota'];?></td>
                <td><?php echo $r['nama_sup'];?></td>
                <td><?php echo idr($r['total_pembelian']);?></td>
                <td><?php echo $r['tgl_input'];?></td>
                <td><?php echo $r['userID'];?></td>
				<td align="center">
                <?php if ($r['userID'] == $_SESSION['yuser']) {?>
                <a href="<?php echo $set->folder_modul.'='.$modul?>&act=new&id=<?php echo $r['kdPembelian'].'&ttl='.$r['total_pembelian'].'&nota='.$r['no_nota'];?>"><img src="images/icon-edit-on.png" border="0" width="20" height="20" /></a><?php } else {?>
                <img src="images/icon-edit-off.png" border="0" width="20" height="20" />
                <?php }?></td></tr>
                <?php
				$no++;
				} // end while
?>
                </table><?php
	break;
	case 'new':
	if(!empty($kode)) {
		$ed = yposSQL('SHOW','ypos_pembelian a, ypos_suplier b','kdPembelian as kode, no_nota as nota, total_pembelian as ttl, a.kdsup, tgl_beli as tgl, nama_sup as sup',"a.kdsup=b.kdsup && kdPembelian='$kode'")->fetch_array();
	} else {
		$genCode = genCode("P-".$set->kdSET.date('Ymd'),'kdPembelian','ypos_pembelian','7');
	};?>
    <form method="post" action="<?php echo $set->folder_modul.'/'.$modul;?>/aksi.php?<?php echo $set->folder_modul.'='.$modul.'&id='.@$ed['kode'].'&nota='.@$ed['nota'].'&act='.@$act.'&idp='.@$edprod['idDtlPembelian'];?>" name="form" id="form">
    <fieldset>
    <legend>Data Pembelian</legend>
<table>
		<tr align="left">
		<td><b>Kode Pembelian</b></td>
		<td>:</td>
		<td>
		<?php if(!empty($kode)) {
			echo $ed['kode'];?>
			<input type="hidden" class="inp-form" name="kode" required="required" size="25" value="<?php echo $ed['kode'];?>"/>
		<?php } else { echo $genCode;?>
        	<input type="hidden" class="inp-form" name="kode" required="required" size="25" value="<?php echo $genCode;?>"/>
        <?php }?>
        </td>
		<td width="200"></td>
		<td></td>
		<td></td>
        <td align="right"></td>
		</tr>
        <tr>
		<td><b>Tanggal Transaksi</b></td>
        <td>:</td>
		<td><input type="text" name="tgl" class="tgl" size="25" <?php if(!empty($kode)) { echo 'value="'.$ed['tgl'].'" '.$read .'';} else { echo 'value="'.$getDate.'".';} ?>/></td>
		<td></td>
		<td colspan="3" rowspan="2" width="300"><div id="total"><br/> Rp <?php echo idr(@$ed['ttl']);?></div></td>
		</tr>
        <tr>
		<td><b>Suplier</b></td>
        <td>:</td>
		<td><div class="styled-select slate semi-square"><select name="sup" <?php if(!empty($kode)) { echo $read;}?>>
    <?php $sup = yposSQL('SHOW','ypos_suplier','kdsup, nama_sup',"ids=$_SESSION[yids] && 1=1",'nama_sup');
	while ($rs = $sup->fetch_array()) {
		if ($ed['kdsup'] == $rs['kdsup']) {
			echo "<option value='$rs[kdsup]' selected=selected>$rs[nama_sup]</option>";
		} else {
			echo "<option value='$rs[kdsup]'>$rs[nama_sup]</option>";
		}
	} $sup->free_result();?>
    </select></div></td>
		<td></td>
		</tr>
        <tr>
		<td><b>No. Nota</b></td>
        <td>:</td>
		<td><input type="text" name="nota" size="25" value="<?php echo @$ed['nota'];?>" <?php if(!empty($kode)) { echo $read;}?>/></td>
		<td></td>
		<td></td>
		<td></td>
        <td></td>
        </tr>
</table>
</fieldset>
<fieldset>
<legend>Item Pembelian</legend>
<table border="0">
  <tr>
  	<td><b>Cari Barang</b></td>
    <td><input type="text" name="brg" size="50" required="required" placeholder="Nama Barang" id="brg" value="<?php if (@$_GET['op'] == 'edprod') { echo @$edprod['kdbarang'].' - '.@$edprod['nama_barang'].' (Rp : '.idr($edprod['harga_pokok_jual']).')';};?>"/></td>
    <td><input type="text" name="qty" id="qty_p" required="required" placeholder="Qty" size="10" value="<?php echo @$edprod['qty_beli'];?>"/></td>
    <td><input type="text" name="total_harga" id="total_harga" required="required" placeholder="Total Harga Beli" value="<?php echo @$edprod['total_beli'];?>"/></td>
    <td><input type="text" name="harga_satuan" id="hs" placeholder="Harga Satuan" disabled="disabled" value="<?php echo @$edprod['harga_beli_satBaru'];?>"/></td>
    <td>
        <?php if (@$_GET['op'] == 'edprod') {
				echo '<input type="hidden" name="tipe" value="edProd"/>';
				echo '<input type="submit" class="submit" name="save" value="Simpan"/>';
		}
		  else {
				echo '<input type="hidden" name="tipe" value="save"/>';
				echo '<input type="submit" class="submit" name="save" value="Tambahkan"/>';
		}?>
	</td>
  </tr>
</table>
</fieldset>
</form>
<table border="0" id="dataTable" width="90%">
  <tr id="tbl" align="center">
    <td width="35">No</td>
    <td>Kode Barang</td>
    <td>Nama Barang</td>
    <td>Qty</td>
    <td>Harga</td>
    <td>Total</td>
    <td width="100"></td>
  </tr>
<?php
$Qitem = yposSQL('SHOW','ypos_vPembelianDtl','kdbarang, nama_barang, nama_sup, no_nota, total_pembelian, idDtlPembelian, kdPembelian, qty_beli, harga_beli_satBaru',"kdPembelian='$kode' && 1=1");
$no = 1;
while ($getItem = $Qitem->fetch_array()) {?>
  <tr>
    <td align="center"><?php echo $no;?></td>
    <td align="center"><?php echo $getItem['kdbarang'];?></td>
    <td><?php echo $getItem['nama_barang'];?></td>
    <td align="center"><?php echo $getItem['qty_beli'];?></td>
    <td align="right"><?php echo idr($getItem['harga_beli_satBaru']);?></td>
    <td align="right"><?php echo idr($getItem['qty_beli'] * $getItem['harga_beli_satBaru']);?></td>
    <td align="center"><a href="<?php echo $set->folder_modul.'='.$modul?>&act=new&op=edprod&id=<?php echo $getItem['kdPembelian'].'&idp='.$getItem['idDtlPembelian'].'&ttl='.$getItem['total_pembelian'].'&nota='.$getItem['no_nota'];?>"><img src="images/icon-edit-on.png" border="0" width="20" height="20" /></a>
    <a href="<?php echo $set->folder_modul.'='.$modul?>&act=delete&id=<?php echo $getItem['idDtlPembelian'].'&kdp='.$getItem['kdPembelian'];?>" onClick="return confirm('Anda yakin ingin menghapus data ini?')"><img src="images/delete-icon.png" border="0" width="20" height="20" /></a></td>
  </tr>
  <?php $no++; }
  $total = yposSQL('SHOW','ypos_pembeliandtl','DISTINCT SUM(qty_beli) AS t_qty, SUM(harga_beli) AS t_harga',"kdPembelian='$kode' && 1=1")->fetch_array();?>
    <tr>
    <td colspan="3" align="center">Grand Total</td>
    <td align="center"><?php echo $total['t_qty'];?></td>
    <td align="right"><?php echo idr($total['t_harga']);?></td>
    <td align="right"><?php echo idr(@$ed['ttl']);?></td>
    <td></td>
  </tr>
  <tr>
    <td colspan="6" align="center"></td>
    <td align="center"><a href="<?php echo $set->folder_modul.'='.$modul;?>"><button class="submit">Selesai</button></a></td>
  </tr>
</table>
<?php
break;
case 'delete':
$kdp = anti($_GET['kdp']);
$mysqli->query("CALL ypos_trxPembelianDtl_delProd($id,'$kdp',@error)");
echo "<meta content='0; url=$set->folder_modul=$modul&act=new&id=$kdp' http-equiv='refresh'/>";
break;
}?>