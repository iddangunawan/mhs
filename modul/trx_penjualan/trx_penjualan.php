<?php
if (!defined('YBASE')) exit ('Now Allowed');
include 'notification.php';
if (@$_GET['op'] == 'edprod') {
	$idp = abs((int)($_GET['idp']));
	$edprod = yposSQL('SHOW','ypos_vPenjualanDtl','kdbarang, nama_barang, harga_jualstd, idDtlPen, harga_jualreal, qty, th_jual',"idDtlPen=$idp")->fetch_array(); 
}
switch($act) {
	default :?>
<a href="<?php echo $set->folder_modul.'='.$modul.'&act=new';?>"><button name="save" value="add">Buat Baru</button></a>
<table id="dataTable"  class="table" width="100%">
<tr id="tbl">
<th width="15">No</th>
<th width="130">Kode Penjualan</th>
<th width="130">Tanggal</th>
<th width="100">Sub-Total</th>
<th width="80">Diskon %</th>
<th width="80">Diskon Rp</th>
<th width="85">Grand-Total</th>
<th width="80">Bayar</th>
<th width="80">Operator</th>
<th></th></tr>
<?php
    $no =1;
    $q = yposSQL('SHOW','ypos_penjualan','kd_penjualan, tgl_input, subtotal, diskon, diskon_rp, grand_total, uang_bayar, uang_kembali, userID',"ids=$_SESSION[yids]",'tgl_input DESC');
	while ($r = $q->fetch_array()) {?>
				<tr align="center">			
                <td align="center"><?php echo $no;?></td>
				<td><?php echo $r['kd_penjualan'];?></td>
				<td><?php echo $r['tgl_input'];?></td>
                <td><?php echo idr($r['subtotal']);?></td>
                <td><?php echo idr($r['diskon']);?></td>
				<td><?php echo idr($r['diskon_rp']);?></td>
                <td><?php echo idr($r['grand_total']);?></td>
                <td><?php echo idr($r['uang_bayar']);?></td>
                <td><?php echo $r['userID'];?></td>
				<td align="center">
                <?php 
                    if ($r['userID'] == $_SESSION['yuser']) {
                ?>
                        <a href="<?php echo $set->folder_modul.'='.$modul?>&act=new&id=<?php echo $r['kd_penjualan'].'&ttl='.$r['subtotal'];?>"><img src="images/printer.png" border="0" width="20" height="20" />
                            <img src="images/icon-edit-on.png" border="0" width="20" height="20" />
                        </a>
                <?php 
                    } else {
                ?>
                        <img src="images/icon-edit-off.png" border="0" width="20" height="20" />
                <?php 
                    }
                ?>
                </td></tr>
<?php
    $no++;
	} // end while
?>
</table>
<?php
	break;
	case 'new':
	if(!empty($kode)) {
		$ed = yposSQL('SHOW','ypos_penjualan','kd_penjualan as kode, customer, tgl_jual as tgl, subtotal as sttl, diskon, grand_total as gt, uang_bayar as bayar, uang_kembali as kembali, keterangan as ket, userID',"kd_penjualan='$kode'")->fetch_array();
	} else {
		$genCode = genCode("INV-".date('Ymd'),'kd_penjualan','ypos_penjualan','8');
	};?>
    <form method="post" action="<?php echo $set->folder_modul.'/'.$modul;?>/aksi.php?<?php echo $set->folder_modul.'='.$modul.'&id='.@$ed['kode'].'&act='.$act.'&idp='.@$edprod['idDtlPen'].'&ttl='.@$_GET['ttl'];?>" name="form" id="form">
    <fieldset>
    <legend>Data Penjualan</legend>
    <table width="99%">
		<tr align="left">
    		<td width="125"><b>Kode Penjualan</b></td>
    		<td width="3">:</td>
    		<td width="240" class="kode">
        		<?php if(!empty($kode)) {
        			echo $ed['kode'];?>
        			<input type="hidden" class="inp-form" name="kode" required="required" size="25" value="<?php echo $ed['kode'];?>"/>
        		<?php } else { echo $genCode;?>
                	<input type="hidden" class="inp-form" name="kode" required="required" size="25" value="<?php echo $genCode;?>"/>
                <?php }?>
            </td>
    		<td width="120"></td>
            <td rowspan="4"><div id="total"><br/> Rp <?php echo idr(@$ed['sttl']);?></div></td>
		</tr>
        <tr align="left">
    		<td width="125"><b>Tanggal Transaksi</b></td>
    		<td width="3">:</td>
    		<td width="240">
                <input type="text" name="tgl" class="tgl" size="18" 
                <?php 
                    if(!empty($kode)) {
                        echo 'value="'.$ed['tgl'].'"'. $disabled;
                    } else { 
                        echo 'value="'.$getDate.'"';} 
                ?> />
            </td>
    		<td width="89"></td>
		</tr>
        <tr>
    		<td><b>Nama Pelanggan</b></td>
            <td>:</td>
    		<td><input type="text" name="cust" onclick="clearInput(this)" size="30" required
            <?php 
                if(!empty($kode)) { 
                    echo "value='$ed[customer]' $disabled";
                }
            ?> />
            </td>
    		<td></td>
		</tr>
        <tr>
    		<td></td>
            <td></td>
    		<td></td>
    		<td></td>
		</tr>
        <tr>
    		<td></td>
            <td></td>
    		<td colspan="3"></td>
		</tr>
</table>
</fieldset>
<fieldset>
<legend>Item Penjualan</legend>
<table border="0">
    <tr>
      	<td><b>Cari Barang</b></td>
        <td>:</td>
        <td>
        <?php 
            if (@$_GET['op'] == 'edprod') { 
                echo "<input type=text name=brg required=required disabled=disabled value=$edprod[kdbarang] - $edprod[nama_barang] (Rp : idr($edprod[harga_jualstd]/>";
    	    } else {
                echo '<input type="text" name="brg" required="required" placeholder="Nama Barang" id="brg" onclick="clearInput(this)"/>';
    	   }; ?>
        </td>
    </tr>
    <tr>
        <td><b>Harga</b></td>
        <td>:</td>
        <!-- <td><input type="text" name="harga_disc" id="harga_disc" disabled="disabled" onclick="clearInput(this)" placeholder="Harga" value="<?php echo @$edprod['harga_jualreal'];?>"/></td> -->
        <td><input type="text" name="harga" id="harga" readonly="readonly" placeholder="Harga" value="<?php echo @$edprod['harga_jualreal'];?>"/></td>
    </tr>
    <tr>
        <td><b>Diskon</b></td>
        <td>:</td>
        <td>
            <select name="select_diskon" id="select_diskon" class="styled-select slate semi-square">
                <option value="">Select</option>
                <option value="persen">% (Persen)</option>
                <option value="rp">Rp (Rupiah)</option>
            </select>
        </td>
    <tr class="persen box">
        <td></td>
        <td></td>
        <td><input type="text" name="disc_persen" id="disc_persen" placeholder="Disc %" onclick="clearInput(this)" onkeyup="hitTotaldiscpersen(getElementById('harga').value,this.value);"/> %</td>
    </tr>
    <tr class="rp box">
        <td></td>
        <td></td>
        <td><input type="text" name="disc_rp" id="disc_rp" placeholder="Disc Rp. " onclick="clearInput(this)" onkeyup="hitTotaldiscrp(getElementById('harga').value,this.value);"/> ,-</td>
    </tr>            
    </tr>
    <tr>
        <td><b>Harga Diskon</b></td>
        <td>:</td>
        <!-- <td><input type="text" name="jumlah" id="jum" disabled="disabled" placeholder="Total" value="<?php echo @$edprod['th_jual'];?>"/></td> -->
        <td><input type="text" name="disc_unit" id="disc_unit" readonly="readonly"></td>
    </tr>
    <tr>
        <td><b>Qty</b></td>
        <td>:</td>
        <td><input type="text" name="qty" id="qty" required="required" onclick="clearInput(this)" placeholder="Qty" value="<?php echo @$edprod['qty'];?>" onkeyup="hitTotalUnit(getElementById('disc_unit').value,this.value);"/></td>
    </tr>
    <tr>
        <td><b>Total</b></td>
        <td>:</td>
        <td><input type="text" name="jumlah" id="jum" readonly="readonly" placeholder="Total"/></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td>
            <?php 
                if (@$_GET['op'] == 'edprod') {
    				echo '<input type="hidden" name="tipe" value="edProd"/>';
    				echo '<input type="submit" class="submit" name="save" value="Simpan"/>';
                } else {
    				echo '<input type="hidden" name="tipe" value="save"/>';
    				echo '<input type="submit" class="submit" name="save" value="Tambahkan"/>';
                }
            ?>
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
$Qitem = yposSQL('SHOW','ypos_barang a, ypos_penjualandtl b','kdbarang, a.nama_barang, idDtlPenjualan, kd_penjualan, b.harga_jual, qty, total_harga',"kdbarang=kd_barang && kd_penjualan='$kode' && 1=1");
$no = 1;
while ($getItem = $Qitem->fetch_array()) {?>
  <tr>
    <td align="center"><?php echo $no;?></td>
    <td align="center"><?php echo $getItem['kdbarang'];?></td>
    <td align="center"><?php echo $getItem['nama_barang'];?></td>
    <td align="center"><?php echo $getItem['qty'];?></td>
    <td align="right"><?php echo idr($getItem['harga_jual']);?></td>
    <td align="right"><?php echo idr($getItem['total_harga']);?></td>
    <td align="center"><!-- <a href="<?php echo $set->folder_modul.'='.$modul?>&act=new&op=edprod&idp=<?php echo $getItem['idDtlPenjualan'].'&id='.$getItem['kd_penjualan'];?>"><img src="images/icon-edit-on.png" border="0" width="20" height="20" /></a> -->
    <a href="<?php echo $set->folder_modul.'='.$modul?>&act=delprod&id=<?php echo $getItem['idDtlPenjualan'].'&kdp='.$getItem['kd_penjualan'];?>" onClick="return confirm('Anda yakin ingin menghapus data ini?')"><img src="images/delete-icon.png" border="0" width="20" height="20" /></a></td>
  </tr>
  <?php $no++; }
  $total = yposSQL('SHOW','ypos_penjualandtl','DISTINCT SUM(qty) AS t_qty, SUM(harga_jual) AS t_harga',"kd_penjualan='$kode' && 1=1")->fetch_array();?>
    <tr>
    <td colspan="3" align="center">Sub Total</td>
    <td align="center"><?php echo @$total['t_qty'];?></td>
    <td align="right"><?php echo idr(@$total['t_harga']);?></td>
    <td align="right"><?php echo idr(@$ed['sttl']);?></td>
    <td></td>
  </tr>
  <tr>
    <td colspan="6" align="center"></td>
    <td align="center">
    <a href="#dialog-proses" id="<?php echo $ed['kode'];?>" class="proses" data-toggle="modal"><img src="images/proses.png" border="0"/></a>
    </td>
  </tr>
</table>
<!-- awal untuk modal dialog -->
<div id="dialog-proses" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">[x]</button>
		<h3 id="myModalLabel">Transaksi Penjualan</h3>
	</div>
	<!-- tempat untuk menampilkan form mahasiswa -->
	<div class="modal-body"></div>
	<div class="modal-footer">
	<button id="simpan-penjualan" class="submit">Finish</button>
	</div>
</div>
<!-- akhir kode modal dialog -->
<?php
break;
case 'delprod':
$kdp = anti($_GET['kdp']);
yposSQL('DELETE','ypos_penjualandtl',"idDtlPenjualan=$id && kd_penjualan='$kdp'");
//mendapatkan total harga pembelian
$t = yposSQL('SHOW','ypos_penjualandtl','DISTINCT IFNULL(SUM(total_harga),0) AS t_harga',"kd_penjualan='$kdp'")->fetch_array();
yposSQL('EDIT','ypos_penjualan',"subtotal=$t[t_harga]","kd_penjualan='$kdp'");
echo "<meta content='0; url=$set->folder_modul=$modul&act=new&id=$kdp' http-equiv='refresh'/>";
break;
}?>
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/action.js"></script>
<script type="text/javascript">
    $("select, select_diskon").change(function(){
        $( "select option:selected").each(function(){
            if($(this).attr("value")==""){
                $("#disc_persen").val(0);
                $("#disc_rp").val(0);
                $("#disc_unit").val(0);
                $(".box").hide();
            }
            if($(this).attr("value")=="persen"){
                $("#disc_rp").val(0);
                $(".box").hide();
                $(".persen").show();
            }
            if($(this).attr("value")=="rp"){
                $("#disc_persen").val(0);
                $(".box").hide();
                $(".rp").show();
            }
        });
    }).change();

    function hitTotaldiscpersen(harga,disc_persen) {
        var disc = (harga/100)*disc_persen;
        var hasil = eval(harga) - eval(disc);
        $("#disc_unit").val(hasil);
    }

    function hitTotaldiscrp(harga,disc_rp){
        var hasil = eval(harga) - eval(disc_rp);
        $("#disc_unit").val(hasil);
    }

    function hitTotalUnit(disc_unit,qty){
        var hasil = eval(disc_unit) * eval(qty);
        $("#jum").val(hasil);
    }

</script>