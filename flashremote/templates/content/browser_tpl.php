<?php
$srvBrowser = $this->getResourceUri('browser/servicebrowser.swf', 'flashremote');
?>
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
        id="servicebrowser" width="100%" height="80%"
        codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab">
        <param name="movie" value="<?php echo $srvBrowser; ?>" />
        <param name="quality" value="high" />
        <param name="bgcolor" value="#869ca7" />
        <param name="allowScriptAccess" value="sameDomain" />
        <embed src="<?php echo $srvBrowser; ?>" bgcolor="#869ca7"
            width="100%" height="80%" name="servicebrowser" align="middle"
            play="true"
            loop="false"
            quality="high"
            allowScriptAccess="sameDomain"
            type="application/x-shockwave-flash"
            pluginspage="http://www.adobe.com/go/getflashplayer">
        </embed>
</object>