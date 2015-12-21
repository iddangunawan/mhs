<?php
session_start();
	include '../../config/connect.php';
	include '../../config/function.php';
	include '../../config/config.php';
@$kd = $_POST['id'];

if (@$_GET['proses'] == 'finish') {
	$subttl = abs((int)($_POST['subttl']));
	$diskon = abs((int)($_POST['diskon']));
    $diskon_rp = abs((int)($_POST['diskon_rp']));
	$bayar = abs((int)($_POST['bayar']));
    $grandTotal = abs((int)($_POST['grandTotal']));
	$ket = anti($_POST['ket']);
	$kembali = $bayar - $grandTotal;
	
	yposSQL('EDIT','ypos_penjualan',"diskon=$diskon, diskon_rp=$diskon_rp, grand_total=$grandTotal, uang_bayar=$bayar, uang_kembali=$kembali, keterangan='$ket'", "kd_penjualan='$kd'");	
	if (isset($_POST['p'])) {
		//auto direct printing struk penjualan at here		
	}
} else {
    $getData = yposSQL('SHOW','ypos_penjualan','kd_penjualan, subtotal',"kd_penjualan='$kd'")->fetch_array();
?>
<style>
    .input-proses {
        width: 300px;
        height: 20px;
        border: 1px solid #78d0ed;
        font: 1.5em Arial, sans-serif;
    }

    .font-proses {
    	font: 1.1em Arial, sans-serif;
    	text-align:left;
    	font-weight:bold;
    }
</style>
<script type="text/javascript">
    function validateNumber(event) {
        var key = window.event ? event.keyCode : event.which;

        if (event.keyCode == 8 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 39) {
            return true;
    	}
        else if ( key < 48 || key > 57 ) {
            return false;
    	}
        else return true;
	};

	$(document).ready(function() {
   		$('[id^=idr]').keypress(validateNumber);
	});

    var subttl = $("#subttl");

    $("select, select_diskon").change(function(){
        $( "select option:selected").each(function(){
            if($(this).attr("value")==""){
                $("#idr1").val(0);
                $("#idr2").val(0);
                $("#idrbayar").val(0);
                document.getElementById('kembali').innerHTML = 0;
                $(".box").hide();
            }
            if($(this).attr("value")=="persen"){
                $("#idr2").val(0);
                $("#idrbayar").val(0);
                document.getElementById('kembali').innerHTML = 0;
                $(".box").hide();
                $(".persen").show();
            }
            if($(this).attr("value")=="rp"){
                $("#idr1").val(0);
                $("#idrbayar").val(0);
                document.getElementById('kembali').innerHTML = 0;
                $(".box").hide();
                $(".rp").show();
            }
        });
    }).change();
	
	function hitTotaldiscpersen(subttl,idr1) {
        var disc = (subttl/100)*idr1;
		var hasil = eval(subttl) - eval(disc);
		document.getElementById('grandTotal1').innerHTML = hasil;
        $("#grandTotal").val(hasil);
	}

    function hitTotaldiscrp(subttl,idr2){
        var hasil = eval(subttl) - eval(idr2);
        document.getElementById('grandTotal1').innerHTML = hasil;
        $("#grandTotal").val(hasil);
    }

	function kembalian(idrbayar, grandTotal) {
		var total = eval(idrbayar) - eval(grandTotal);
		document.getElementById('kembali').innerHTML = total;
	}    
</script>
<form method="post">
<table border="0">
    <tr>
        <th class="font-proses">Kode Transaksi</th>
        <td>:</td>
        <td class="font-proses"><b style="color:#00F"><?php echo $getData['kd_penjualan'];?></b></td>
    </tr>
    <tr>
        <th class="font-proses">Sub-Total</th>
        <td>:</td>
        <td class="font-proses">
            Rp. <b style="color:#00F"><?php echo idr($getData['subtotal']);?></b>  ,-
            <input type="hidden" name="subttl" value="<?php echo $getData['subtotal'];?>" id="subttl"/>
        </td>
    </tr>
    <tr>
        <th class="font-proses">Select Diskon</th>
        <td>:</td>
        <td><select name="select_diskon" id="select_diskon" class="styled-select slate semi-square">
            <option value="">Select</option>
            <option value="persen">% (Persen)</option>
            <option value="rp">Rp (Rupiah)</option>
        </select></td>
    </tr>
    <tr class="persen box">
        <th class="font-proses">Diskon %</th>
        <td>:</td>
        <td><input type="text" size="30" name="diskon" class="input-proses" id="idr1" value="" onkeyup="hitTotaldiscpersen(getElementById('subttl').value,this.value);"> %</td>
    </tr>
    <tr class="rp box">
        <th class="font-proses">Diskon Rp</th>
        <td>:</td>
        <td><input type="text" size="30" name="diskon_rp" class="input-proses" id="idr2" value="" onkeyup="hitTotaldiscrp(getElementById('subttl').value,this.value);"> ,-</td>
    </tr>
    <tr>
        <th class="font-proses">Grand Total</th>
        <td>:</td>
        <td class="font-proses">
            Rp. <span id="grandTotal1" style="color:#00F"><?php echo idr($getData['subtotal']);?></span> ,-
            <input type="hidden" name="grandTotal" value="<?php echo $getData['subtotal'];?>" id="grandTotal"/>
        </td>
    </tr>
    <tr>
        <th class="font-proses">Uang Bayar</th>
        <td>:</td>
        <td><input type="text" size="30" name="bayar" class="input-proses" value="0" id="idrbayar" onkeyup="kembalian(this.value, getElementById('grandTotal').value);" required></td>
    </tr>
    <tr>
        <th class="font-proses">Kembalian</th>
        <td>:</td>
        <td class="font-proses">Rp. <span id="kembali" style="color:#00F"></span> ,-</td>
    </tr>
    <tr>
        <th class="font-proses">Catatan</th>
        <td>:</td>
        <td><input type="text" size="30" name="ket" class="input-proses"></td>
    </tr>
    <tr>
        <th class="font-proses">Print Struck</th>
        <td>:</td>
        <td><input type="checkbox" name="print" value="p" checked="checked"/></td>
    </tr>
</table>
</form>
<?php } ?>