<?php

	echo '<applet code="avoir.realtime.presentations.client.presenter.ui.MainFrame" width="100%" height="600">';
	echo '	<param name="archive" value="'.$this->presentationsURL.'/presentations-client.jar,'.$this->presentationsURL.'/forms-1.1.0.jar"/> ';
	echo '	<param name="host" value="localhost"/>';
        echo '  <param name="port" value="1962"/>';
        echo '  <param name="contentBasePath" value="'.$this->objAltConfig->getcontentBasePath().'users/'.$this->userId.'"/>';
	echo "</applet> ";

?>
