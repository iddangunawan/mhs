<?php
session_start();
	include '../../config/connect.php';
	include '../../config/function.php';
	include '../../config/config.php';
$report = anti($_GET['rpt']);
$start = anti($_GET['start']);
$end = anti($_GET['end']);?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../css/report.css">
<title>Laporan <?php echo $report;?></title>
</head>
<body>
<div style="float:left; padding: 5px 0 5px 5; position:fixed; background-color:#F2F2F2; width:98.5%">
<a href="../../modul=laporan"><button type="button">Back</button></a> <a href=""><button type="button" name="save">Save As</button></a>
</div>
<div id="lap">
<?php include '../header.php';?>
<table width="100%" border="1" bgcolor="#000000">
  <tr align="center" bgcolor="#CCCCCC">
    <th>No</th>
    <th>Invoice</th>
    <th>Customer</th>
    <th>Tanggal</th>
    <th>Produk</th>
    <th>Qty</th>
    <th>H.Beli(s)</th>
    <th>H.Jual Std(s)</th>
    <th>H.Jual Real(s)</th>
    <th>Disc Item</th>
    <th>Disc Final</th>
    <th>T.Penjualan</th>
    <th>Keuntungan</th>
    <th>Staff</th>
  </tr>
  <?php
  $no = 1;
	$q = yposSQL('SHOW','ypos_vPenjualanDtl','*',"tgl_jual >= '$start' && tgl_jual <= '$end'",'kd_penjualan, tgl_jual');
	$tot = yposSQL('SHOW','ypos_vPenjualanDtl','sum(qty) as t_qty, sum(th_beli) as t_beli, sum(harga_jualreal) as t_jualreal, sum(diskon_produk) as t_diskon, sum(th_jual) as t_jual, sum(pendapatan) as t_untung',"tgl_jual >= '$start' && tgl_jual <= '$end'")->fetch_array();
  while($r=$q->fetch_array()) {?>
  <tr align="center" bgcolor="#F1F1F1">
    <td><?php echo $no;?></td>
    <td><?php echo $r['kd_penjualan'];?></td>
    <td><?php echo $r['customer'];?></td>
    <td><?php echo $r['tgl_jual'];?></td>
    <td><?php echo $r['nama_barang'];?></td>
    <td><?php echo $r['qty'];?></td>
    <td><?php echo idr($r['harga_beli']);?></td>
    <td><?php echo idr($r['harga_jualstd']);?></td>
    <td><?php echo idr($r['harga_jualreal']);?></td>
    <td><?php echo idr($r['diskon_produk']);?></td>
    <td><?php echo idr($r['diskon_final']);?></td>
    <td><?php echo idr($r['th_jual']);?></td>
    <td><?php echo idr($r['pendapatan']);?></td>
    <td><?php echo $r['userID'];?></td>
  </tr>
  <?php $no++;
  }?>
  <tr align="center" bgcolor="#CCCCCC">
  <th colspan="2">Total</th>
  <th colspan="3">&nbsp;</th>
  <th><?php echo $tot['t_qty'];?></th>
  <th><?php echo idr($tot['t_beli']);?></th>
  <th></th>
  <th><?php echo idr($tot['t_jualreal']);?></th>
  <th><?php echo idr($tot['t_diskon']);?></th>
  <th></th>
  <th><?php echo idr($tot['t_jual']);?></th>
  <th><?php echo idr($tot['t_untung']);?></th>
  <th></th>
  </tr>
</table>
</div>
<br/><br/>
<hr size="4" color="#000000" />
(s) : satuan
</body>
</html>