<style>
.submit {
  display: inline-block;
  *display: inline;
  /* IE7 inline-block hack */
  *zoom: 1;
  padding: 4px 12px;
  margin-bottom: 0;
  font-size: 12px;
  line-height: 15px;
  text-align: center;
  vertical-align: middle;
  cursor: pointer;
  color: #333333;
  text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
  background-color: #f5f5f5;
  background-image: -moz-linear-gradient(top, #ffffff, #e6e6e6);
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ffffff), to(#e6e6e6));
  background-image: -webkit-linear-gradient(top, #ffffff, #e6e6e6);
  background-image: -o-linear-gradient(top, #ffffff, #e6e6e6);
  background-image: linear-gradient(to bottom, #ffffff, #e6e6e6);
  background-repeat: repeat-x;
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffffff', endColorstr='#ffe6e6e6', GradientType=0);
  border-color: #e6e6e6 #e6e6e6 #bfbfbf;
  border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
  *background-color: #e6e6e6;
  /* Darken IE7 buttons by default so they stand out more given they won't have borders */

  filter: progid:DXImageTransform.Microsoft.gradient(enabled = false);
  border: 1px solid #cccccc;
  *border: 0;
  border-bottom-color: #b3b3b3;
  -webkit-border-radius: 4px;
  -moz-border-radius: 4px;
  border-radius: 4px;
  *margin-left: .3em;
  -webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,.2), 0 1px 2px rgba(0,0,0,.05);
  -moz-box-shadow: inset 0 1px 0 rgba(255,255,255,.2), 0 1px 2px rgba(0,0,0,.05);
  box-shadow: inset 0 1px 0 rgba(255,255,255,.2), 0 1px 2px rgba(0,0,0,.05);
}
</style>
<script type="text/javascript">
    // return the value to the parent window
    function returnYourChoice(choice){
        opener.setSearchResult(targetField, choice);
        window.close();
    }
</script>
<form name="thisForm">
<table width="600" border="0">
  <tr>
    <td width="300">    <!-- First, include the JPEGCam JavaScript Library -->
	<script type="text/javascript" src="webcam.js"></script>
	<!-- Configure a few settings -->
	<script language="JavaScript">
		webcam.set_api_url( 'capture.php' );
		webcam.set_quality( 100 ); // JPEG quality (1 - 100)
		webcam.set_shutter_sound( true ); // play shutter click sound
	</script>
	<!-- Next, write the movie to the page at 320x240 -->
	<script language="JavaScript">
		document.write( webcam.get_html(300,250) );
	</script>
    </td>
    <td width="300"> 
    <!-- Some buttons for controlling things -->
    <div id="upload_results"></div>
    <!-- Code to handle the server response (see capture.php) -->
	<script language="JavaScript">
		webcam.set_hook( 'onComplete', 'my_completion_handler' );
		
		function take_snapshot() {
			// take snapshot and upload to server
			document.getElementById('upload_results').innerHTML = '<p>Save..</p>';
			webcam.snap();
		}
		
		function my_completion_handler(msg) {
			// extract URL out of PHP output
			if (msg.match(/(http\:\/\/\S+)/)) {
				var image_url = RegExp.$1;
				// show JPEG image in page
				document.getElementById('upload_results').innerHTML = 
					
					'<img src="' + image_url + '">'+
					'<input type="hidden" id="result" name="result" value="'+image_url+'" size="70">';
				
				// reset camera for another shot
				webcam.reset();
			}
			else alert("PHP Error: " + image_url);
		}
	</script></td>
  </tr>
    <tr>
    <td align="center"><input type="button" value="Config" onClick="webcam.configure()" class="submit"><input type="button" id="ambil_photo" value="Take It!" onClick="take_snapshot()" class="submit"></td>
    <td align="right"><input type="button" onclick="returnYourChoice(document.getElementById('result').value)" value="Done!" class="submit"/></td>
  </tr>
</table>
</form>
