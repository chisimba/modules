<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

  <head>
    <title>User Calendar - Adding an Event</title>


<?php

if (isset($_REQUEST['skin'])) {

    echo '<link rel="stylesheet" type="text/css" href="'.$_REQUEST['skin'].'">';
}

?>


</head>
<body >
<script>

var scr_w = screen.availWidth;
var scr_h = screen.availHeight;
var browseWidth, browseHeight;

if (document.layers){
   browseWidth=window.outerWidth;
   browseHeight=window.outerHeight;
}
if (document.all){
   browseWidth=document.body.clientWidth;
   browseHeight=document.body.clientHeight;
}

//if ( browseWidth < 700 || browseHeight < 500) {
   self.resizeTo(730, 550);
   window.moveTo(10, 10);
//}
        
</script> 
<h1>User Calendar - Adding an Event</h1>
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" width="700" height="400">
                <param name="movie" value="calendar.swf">
                <param name="quality" value="high">
                <param name="bgcolor" value="#ffffff">
                <embed src="calendar.swf" quality="high" bgcolor="#ffffff" width="700" height="400" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash">
          </object>
<p align="center"><br /><br /><a href="javascript:window.close()">Close Window</a></p>
    </body>
    


</html>
