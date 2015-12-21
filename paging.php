<?php
session_start();
include 'config/connect.php';
include 'config/function.php';
include 'config/config.php';?>
<script type="text/javascript">
$(function() {
var jml	=  $("#jml").val();						  		   
$("#page").paginate({
	count 					: jml,
	start 					: 1,
	//display     			: 5,
	border					: false,
	text_color  			: '#000',
	background_color    	: '#78D0ED',	
	text_hover_color  		: 'black',
	background_hover_color	: '#FFF', 
	images					: true,
	mouse					: 'press',
	onChange     			: function(page){
								$("#tampil_data").load('tampil_data.php?hal='+page);
							  }
});
});
</script>
<?php
$limit 	= $set->limit_page;
@$cari	= str_replace("'","\'",$_GET['cari']);
@$field	= $_GET['field'];

if(empty($cari)){
	$where = "ORDER BY nama_barang";
}else{
	$where = "WHERE $field LIKE '%$cari%' ORDER BY $field";
}

$sql = "SELECT * FROM ypos_barang $where";	
$rsd = $mysqli->query($sql);
$count = $rsd->num_rows;
$pages = ceil($count/$limit);

echo "<input type='hidden' id='jml' value='$pages'>";
echo "<div id='page'></div>";
?>