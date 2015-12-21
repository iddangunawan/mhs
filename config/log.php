<?php
if (!empty($modul)) {
@$cetak = "
User    	  : " . $_SESSION['yuser'] . "<br/>
Time    	  : " . $getDate . " / ". $jam . "<br/>
IP     		  : " . $ip . "<br/>
From   		  : " . $referrer . "<br/>
Url     	  : " . $site.$url . "<br/>
UserAgent     : " . $browser . " (". $hostname .")<br/>
============================================================================================<br/>";
    $fopen = fopen("ylogs.php", "a");
    fwrite($fopen, $cetak);
    fclose($fopen);
}
    ?>