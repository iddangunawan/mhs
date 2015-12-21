<?php
if (!defined('YBASE')) exit ('Now Allowed');
include 'notification.php';
$profile = yposSQL('SHOW','ypos_users','username, nama_lengkap',"username='$_SESSION[yuser]'")->fetch_array();
echo '<h3>My Profile</h3>';?>
<form method="post" action="<?php echo $set->folder_modul.'/'.$modul;?>/aksi.php?<?php echo $set->folder_modul.'='.$modul;?>" name="form" id="form">
<table border="0">
  <tr>
    <td>Username</td>
    <td>:</td>
    <td><?php echo $profile['username'];?></td>
  </tr>
  <tr>
    <td>Nama Lengkap</td>
    <td>:</td>
    <td><?php echo $profile['nama_lengkap'];?></td>
  </tr>
  <tr>
    <td>Old Password</td>
    <td>:</td>
    <td><input type="password" name="old" required="required"/></td>
  </tr>
   <tr>
    <td>New Password</td>
    <td>:</td>
    <td><input type="password" name="new" required="required"/></td>
  </tr>
   <tr>
    <td>Re-Type New Password</td>
    <td>:</td>
    <td><input type="password" name="reType" required="required"/></td>
  </tr>
     <tr>
    <td></td>
    <td></td>
    <td><input type="submit" name="update" value="Update" class="submit"/></td>
  </tr>
</table>
