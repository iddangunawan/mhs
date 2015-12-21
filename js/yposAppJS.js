// JavaScript Document	<script type="text/javascript">
	//untuk paging halaman (ajax)
		$(document).ready(function(){
			var modul 	= $("#modul").val();
			$("#tampil_data").load('tampil_data.php?hal=1&modul='+modul);
			$("#paging").load('paging.php');
	
			$("#text").keyup(function(){
				var hal		= 1;							  	
				var cari	= $("#text").val();
				var field	= $("#ypos_field").val();
				$.ajax({
				type		: "GET",
				url			: "tampil_data.php",
				data		: "hal="+hal+"&cari="+cari+"&field="+field+"&modul="+modul,
				success	: function(data){
					$("#tampil_data").html(data);
					$("#paging").load('paging.php?hal=1'+'&cari='+cari+'&field='+field);
				}
			});	
		});
	});
	//untuk komponen core modul management
	$(document).ready(function() {
		$("input[name^='item']").change(function() {
			if($(this).attr("checked")) $(this).closest("tr").children().children().removeAttr("disabled")
					else {
						//remove checks
						$(this).closest("tr").children().children().removeAttr("checked")
						//make all disabled
						$(this).closest("tr").children().children().attr("disabled","true")
						//but fix me
						$(this).removeAttr("disabled")
				}
			})
		})
		//autocomple pencarian barang
		$().ready(function() {
			$("#brg").autocomplete("list-barang.php", {
			width: 408,
			matchContains: true,
			selectFirst: true
				});
			$("#brg").result(function(event, data, formatted) {
				var kode = formatted.split(' - ');
				$.ajax({
					type : "POST",
					data : "kdbrg="+kode[0],
					url  : "getBarang.php",
					dataType : "json",
					success : function(data) {
						$("#qty").val('1');
						$("#harga").val(data.harga);
						$("#harga_disc").val(data.harga);
						$("#jum").val(data.harga * 1)
					}
				});
			});
			$("#brg").keyup(function() {
				var kode = ('#brg').val().split(' - ');
				$.ajax({
					type : "POST",
					data : "kdbrg="+kode[0],
					url : "getBarang.php",
					dataType : "json",
					success : function(data) {
						$("#qty").val('1');
						$("#harga").val(data.harga);
						$("#harga_disc").val(data.harga);
						$("#jum").val(data.harga * 1)
					}
				})
			})	
		});

	 //fungsi clear input pada textbox
	 function clearInput(element){
				element.value="";
			}
	//hitung otomatis pada transaksi penjualan/pembelian
   	 $(function(){
    		$("#harga_disc").keyup(function(){
    			var jum = $(this).parent().next().find('#jum');
    			var item = $(this).parent().prev().find('#qty');
    			$(jum).val($(this).val() * $(item).val());
    	});
    		$("#qty").keyup(function(){
   				var jum = $(this).parent().next().next().find('#jum');
    			var harga = $(this).parent().next().find('#harga_disc');
   			 	$(jum).val($(this).val() * $(harga).val());
    	});
			$("#total_harga").keyup(function() {
				var hs = $(this).parent().next().find("#hs");
				var qty = $(this).parent().prev().find("#qty_p");
				$(hs).val($(this).val() / $(qty).val());
			});
			$("#qty_p").keyup(function() {
				var hs = $(this).parent().next().next().find("#hs");
				var th = $(this).parent().next().find("#total_harga");
				$(hs).val($(th).val() / $(this).val());
			});
    });
	//fungsi datepicker
      $(document).ready(function(){
		 $(".tgl").datepicker({
					dateFormat  : "yy-mm-dd",        
          changeMonth : true,
          changeYear  : true					
        });
      });
	//fungsi upload webcam
		$(document).ready(function(){
			$("#upload_results").hide();$("#simpan").hide();
			$("#ambil_photo").click(function(){
			$("#upload_results").show(500);
			$("#simpan").show(500);
			$("#print_area").hide();
				});	
		});		

	//fungsi print page
	(function($) {
		$(document).ready(function(e) {
			$("#print").bind("click", function(event) {
				$('#resi').printArea();
			});
		});
	}) (jQuery);

	//Pewarnaan pada table (belang-belang)
	$(function() {
		$("#dataTable tr:even").addClass("stripe1");
		$("#dataTable tr:odd").addClass("stripe2");
		$("#dataTable tr").hover(
		
		function() {
			$(this).toggleClass("highlight");
		},
		function() {
			$(this).toggleClass("highlight");
		}
	);
});