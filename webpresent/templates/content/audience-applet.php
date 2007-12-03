<?php
    echo '<center>';
	echo '<applet code="avoir.realtime.presentations.client.viewer.ClientViewer" width="75%" height="600">';
	echo '	<param name="archive" value="'.$this->presentationsURL.'/presentations-client.jar"/>';
	echo '	<param name="host" value="localhost"/>';
    echo '  <param name="port" value="3128"/>';
    echo '  <param name="contentBasePath" value="'.$this->objConfig->getcontentBasePath().'webpresent/'.$id.'"/>';
    echo '  <param name="id" value="'.$id.'"/>';
	echo '  <param name="invokedThroughWebpresent" value="true"/>';
	echo '  <param name="user" value="'.$this->objUser->userName().'"/>';
	echo '  <param name="isLoggedIn" value="'.$this->objUser->isLoggedIn().'"/>';
	echo '  <param name="fullname" value="'.$this->objUser->fullname().'"/>';
	echo "</applet> ";
	echo '</center>';
?>
