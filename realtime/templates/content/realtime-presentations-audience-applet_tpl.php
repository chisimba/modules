<?php
    
	echo '<center>';
	echo '<applet code="avoir.realtime.presentations.client.ClientViewer" width="75%" height="500">';
	echo '	<param name="archive" value="'.$this->presentationsURL.'/presentations-client.jar"/>';
	echo '	<param name="host" value="localhost"/>';
    echo '  <param name="port" value="3128"/>';
    //echo '  <param name="thumbnail" value="'.$this->objConfig->getcontentBasePath().'webpresent_thumbnails/'.$id.'"/>';
    echo '  <param name="id" value="'.$id.'"/>';
	 echo '  <param name="fullname" value="'.$this->objUser->fullname().'"/>';
	echo '  <param name="invokedThroughWebpresent" value="false"/>';
	echo '  <param name="user" value="'.$this->objUser->userName().'"/>';
	echo '  <param name="isLoggedIn" value="'.$this->objUser->isLoggedIn().'"/>';
	echo "</applet> ";
	echo '</center>';
?>
