<?php 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Bengkel Motor (MHS)</title>
<link rel="stylesheet" href="css/style_login.css" type="text/css">
<link href="css/stylesheet.css" rel="stylesheet" type="text/css">
<style type="text/css">
button {margin: 0; padding: 0;}
button {margin: 2px; position: relative; padding: 4px 4px 4px 2px; 
cursor: pointer; float: left;  list-style: none;}
button span.ui-icon {float: left; margin: 0 4px;}
</style>
</head>
<body>
<div class="blok_header">
    <div class="clr"></div>
    <div class="header_text_bg">
    	<div class="clr"></div>
        <div id="header">
        <h1>MHS</h1>
        </div> 
	</div>       
</div>              
<form action="cek.php" method="post" accept-charset="utf-8"><fieldset>
    <legend>Login</legend>
    <table width="100%">
    <tbody><tr>
    	<td>Username</td>
        <td>:</td>
		<td><input name="user" id="username" class="input-teks-login" size="30" placeholder="Username .." type="text" required="required"></td>
	</tr>
    <tr>       
		<td>Password</td>
        <td>:</td>
		<td><input name="pass" value="" id="password" class="input-teks-login" size="30" placeholder="Password .." type="password" required="required"></td>
	</tr>
    </tbody></table>        
</fieldset>
<fieldset class="tblFooters">
<?php 
if (!empty($_GET['get'])) {?>
<div id="error">
<br>Username atau Password salah!! </div>
<?php
}
?>
<input type="hidden" name="token" value="<?php echo session_id();?>">
<button name="submit" type="submit" id="submit" class="easyui-linkbutton" data-options="iconCls:'icon-lock_open'" >Login</button></fieldset>
</form>
<div id="footer" align="center">
	<p>BENGKEL MOTOR (MHS)</p>
</div>
</body></html>