<?php
        echo '<center>';
	echo '<applet code="avoir.realtime.presentations.client.ClientViewer" width="720" height="418">';
	echo '	<param name="archive" value="'.$this->presentationsURL.'/presentations-client.jar"/>';
	echo '	<param name="host" value="localhost"/>';
        echo '  <param name="port" value="1962"/>';
	echo "</applet> ";
       echo '</center>';
?>
