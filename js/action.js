(function($) {
	$(document).ready(function(e) {
		var kd_jual = 0;
		$('.proses').live("click", function(){
			var url = "modul/trx_penjualan/proses.php";
			// ambil nilai id dari tombol ubah
			kd_jual = this.id;
				$("#myModalLabel").html("Transaksi Penjualan");
			$.post(url, {id: kd_jual} ,function(data) {
				$(".modal-body").html(data).show();
			});
		});
		
		$("#simpan-penjualan").bind("click", function(event) {
			var url = "modul/trx_penjualan/proses.php?proses=finish";
			var v_subttl = $('input:hidden[name=subttl]').val();
			var v_diskon = $('input:text[name=diskon]').val();
			var v_bayar = $('input:text[name=bayar]').val();
			var v_ket = $('input:text[name=ket]').val();
			var p = $('input:checkbox[name=p]').val();

			$.post(url, {subttl : v_subttl, diskon: v_diskon, bayar: v_bayar, ket: v_ket, p: p, id: kd_jual} ,function() {
				location.href="index.php?modul=trx_penjualan";
			});
		});
	});
}) (jQuery);
