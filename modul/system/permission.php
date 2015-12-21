<style>
select {
    padding:3px;
    margin: 0;
    -webkit-border-radius:3px;
    -moz-border-radius:3px;
    border-radius:3px;
    background: #78D0ED;
	width:30px;
    color:#333;
    border:none;
    outline:none;
    display: inline-block;
    -webkit-appearance:none;
    -moz-appearance:none;
    appearance:none;
    cursor:pointer;
	height:30px;
}
</style>
<?php
session_start();
	include '../../config/connect.php';
	include '../../config/function.php';
	include '../../config/config.php';
//@$idlevel = $_POST['id'];
@$idLM = $_POST['id'];

if (@$_GET['act'] == 'update') {
	@$r = $_POST['r'];
	@$c = $_POST['c'];
	@$e = $_POST['e'];
	@$d = $_POST['d'];
	
	yposSQL('EDIT','ypos_grouplvlmdl',"r='$r', c='$c', e='$e',d='$d'","idGroupLM=$idLM");
	
} else {
$q = yposSQL('SHOW','ypos_modul a, ypos_grouplvlmdl b','a.nama_modul, b.*',"a.modulID=b.modulID && idGroupLM=$idLM");
$getData = $q->fetch_array();
$getLevel = yposSQL('SHOW','ypos_level','lvl',"idlevel=$getData[idlevel]")->fetch_array();
	?>
<form method="post">
<h3>Modul : <?php echo $getData['nama_modul'];?></h3>
<table border="0" width="100%" align="center">
  <tr>
    <th>Read</th>
    <th>Create</th>
    <th>Edit</th>
    <th>Delete</th>
  </tr>
  <tr align="center"> <?php
echo stdChoice($getData['r'],'r');
echo stdChoice($getData['c'],'c');
echo stdChoice($getData['e'],'e');
echo stdChoice($getData['d'],'d');
?>
  </tr>
</table>
<input type="hidden" name="idlvl" value="<?php echo $getData['idlevel'];?>"/>
<input type="hidden" name="lvl" value="<?php echo $getLevel['lvl'];?>"/>
</form>
<?php
} //end act update
?>