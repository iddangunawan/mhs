<?php 
if (!defined('YBASE')) exit ('Now Allowed');
include 'notification.php';
?>
<table id="dataTable"  class="table" width="100%">
<tr id="tbl">
<th width="30">No</th>
<th width="300">Activities</th>
<th>Before</th>
<th>After</th>
<th width="100">Date</th>
<th width="100">User ID</th>
</tr>
<?php
$no =1;
$q = yposSQL('SHOW','ypos_logs','*',"1=1",'createdDate DESC');
	while ($r = $q->fetch_array()) {?>
				<tr align="center">			
                <td><?php echo $no;?></td>
				<td><?php echo $r['Activity'];?></td>
				<td><?php echo $r['act_before'];?></td>
                <td><?php echo $r['act_after'];?></td>
                <td><?php echo $r['createdDate'];?></td>
                <td><?php echo $r['user'];?></td></tr>
                <?php
				$no++;
				} // end while
?>
                </table>