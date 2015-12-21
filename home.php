<?php
define('YBASE',true);
	if (isset($_SESSION['yuser'])) {
			include 'config/connect.php';
			include 'config/function.php';
			include 'config/config.php';
			cekSession($_SESSION['yuser']);
			include_once 'config/log.php';
	$mtime = microtime(); $mtime = explode (" ", $mtime); $mtime = $mtime[1] + $mtime[0]; $tstart = $mtime;?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title><?php echo $set->nama_toko;?></title>
    <link rel="stylesheet" type="text/css" href="css/layout.css">
    <link rel="stylesheet" type="text/css" href="css/stylesheet.css">
    <link rel="stylesheet" type="text/css" href="css/easyui.css">
    <link rel="stylesheet" type="text/css" href="css/icon.css">
	<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.css">
    <link rel="stylesheet" type="text/css" href="css/jquery_notification.css">
    <link rel="stylesheet" type="text/css" href="css/jquery.autocomplete.css"/>
    <link rel="stylesheet" type="text/css" href="css/jpaging.css">
    <link rel="stylesheet" type="text/css" href="css/ui.datepicker.css"/>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
	<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.8.20.custom.min.js"></script>
    <script type='text/javascript' src='js/jquery.paginate.js'></script>
    <script type="text/javascript" src="js/ui.core.js"></script>
    <script type='text/javascript' src="js/jquery.autocomplete.js"></script>
    <script type="text/javascript" src="js/ui.datepicker.js"></script>
    <script type="text/javascript" src="js/jquery.PrintArea.js"></script>
	<script type="text/javascript" src="js/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="js/clock.js"></script>
    <script type="text/javascript" src="js/jquery_notification_v.1.js"></script>
	<script type="text/javascript">
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
		$("input[name^='mods']").change(function() {
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
</script>
</head>
<body>
<div class="header" style="height:70px;background:white;padding:2px;margin:0;">	
		<div style="float:left; padding:0px; margin:0px;">
        <img src="css/images/logo.png" style="padding:0; margin:0;" height="75" width="246">
        </div>
		<div style="float:right; line-height:3px; text-align:center; margin-right:10px">
        <br><br>
        <h1><?php echo $set->nama_toko;?></h1>
        <?php echo $set->alamat;?>
        </div>
	</div>
    <!-- bawah header -->
    <div class="panel-header" fit="true" style="height:21px;padding-top:8px;padding-right:15px">
		<div style="float:left;"><a style="color:#fff;" href="index.php" iconcls="icon-home"><span class="l-btn-left">
        <span style="padding-left: 20px;" class="l-btn-text icon-home">Home</span></span></a>
        <a style="color:#fff;" href="page=about" iconcls="icon-home"><span class="l-btn-left">
        <span style="padding-left: 20px;" class="l-btn-text icon-home">About</span></a>
        <a style="color:#fff;" href="logout.php" iconcls="icon-logout"><span class="l-btn-left">
        <span style="padding-left: 20px;" class="l-btn-text icon-logout">(<?php echo $_SESSION['yuser'];?>) Logout</span></span></a>
		</div>
		<div style="float:right;">
		<font color='#fff'><span id="clock"></span></font>		
		</div>
	</div>
    <!-- end bawah header -->
	<div class="easyui-layout" style="width:100%;height:470px;">
	<div region="west" split="true" title="Menu Utama" style="width:175px;">
	<?php
	include 'menu.php';
	?>
	</div> <!-- end region -->
		<div id="content" region="center" title="Contents <?php echo @$modul;?>" style="padding:5px; text-transform:capitalize;">
        <!-- konten -->
        <?php
		include 'load.php';?>
		</div>
	</div> <!-- end layout -->
<div style="height:20px;text-align:center; padding-bottom:0px;" fit="true" class="panel-header"> BENGKEL MOTOR (MHS)</div>
</body>
</html>
<?php } else {
	header('location:login.php');
}