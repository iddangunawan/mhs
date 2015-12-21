<div id="judul">
<br /><br />
<font size="+3" style="font-weight:bold">
<?php echo $set->nama_toko;?></font><br />
<?php echo $set->alamat.'<br/>'.$set->keckab.'<br/>'.$set->tlp;?>
</div>
<hr size="4" color="#000000" />
<b>Laporan <?php echo $report;?> Periode : <?php 
echo tgl_indo($start);?> - <?php echo tgl_indo($end);?></b>
<br/>