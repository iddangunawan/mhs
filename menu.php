<?php
if (!defined('YBASE')) exit ('Now Allowed');
?><br/>
<ul class="easyui-tree" style="text-transform:capitalize">
<?php
//menu dinamis based on modul hak akses
$qm = yposSQL('SHOW','ypos_menu','menuID, menu',"aktif='Y' && 1=1",'sort');
while ($menu = $qm->fetch_array()) {
	$qm2 = yposSQL('SHOW','ypos_modul a, ypos_grouplvlmdl b','a.modulID, nama_modul, modul_folder, menuID',"a.modulID=b.modulID && idlevel=$_SESSION[ylevel] && menuID=$menu[menuID] && aktif='Y' && r='Y' && 1=1");
	$cekqm2 = $qm2->num_rows;
	if ($cekqm2 > 0) {
		echo "<li><span>$menu[menu]</span>";
		echo '<ul>';
	while ($sub = $qm2->fetch_array()) {
		echo "<li><a href=$set->folder_modul=$sub[modul_folder]>$sub[nama_modul]</a></li>";
		} //end while
	$qm2->free_result();
		echo '</ul>';
	} //end if
		echo '</li>';
}
$qm->free_result();
if ($_SESSION['ylevel'] == 1) {
echo '<li><span>Parameter System</span>
<ul>
<li><a href="'.$set->folder_modul.'=system&sub=level">Level & Privilege</a></li>
<li><a href="'.$set->folder_modul.'=system&sub=menu">Menu</a></li>
<li><a href="'.$set->folder_modul.'=system&sub=modul">Module</a></li>
<li><a href="'.$set->folder_modul.'=system&sub=parameter">Parameter</a></li>
</ul></li>
</ul>'; }?>