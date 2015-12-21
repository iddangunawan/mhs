<?php
session_start();
include 'config/connect.php';
include 'config/function.php';
include 'config/config.php';?>
<script type="text/javascript">
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
<?php
@$hal	= $_GET['hal'];// ? $_GET['page'] : 0;
@$lim	= $set->limit_page;
@$start	= ($hal-1)*$lim;
@$cari	= str_replace("'","\'",$_GET['cari']);
@$field	= $_GET['field'];
//@$modul = $_GET['modul'];

if (empty($cari)){
		$where = "ORDER BY nama_barang";
	} else {
		$where = "WHERE $field LIKE '%$cari%' ORDER BY $field";
	}
echo '<table id="dataTable"  class="table" width="100%">
	  <tr id="tbl">
	  <th>No</th>
	  <th>Nama Barang</th>
	  <th>Harga</th>
	  <th>Stok</th>
	  <th>Lokasi</th>
	  <th></th></tr>';
	$query = "SELECT * FROM ypos_barang $where LIMIT $start,$lim";
	$data = $mysqli->query($query);
	
	//echo $query;
	
	$row	= $data->num_rows;
	$no=1+$start; 
	if ($row > 0) {
	while($r=$data->fetch_array()){
	echo "<tr align=center>			
                <td align=center width=80>$no</td>
				<td>$r[nama_barang]</td>
				<td>$r[harga_jual]</td>
                <td width=100>$r[stok]</td>
                <td width=50>$r[lokasi]</td>
				<td align=center width=125>
				<a href='$set->folder_modul=barang&act=edit&id=$r[kdbarang]'>Edit</a> - <a href='$set->folder_modul=$modul&act=delete&id=$r[kdbarang]' onClick=return confirm('Anda yakin ingin menghapus data ini?')>Delete</a></td>
				</tr>";
				$no++;
				} // end while
echo '</table>';
	} else {
		echo '<tr><td colspan="6" align="center">Tidak ada</td></tr>';
	}
//echo "<input type='hidden' id='jml'>";
