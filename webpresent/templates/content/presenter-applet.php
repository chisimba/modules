<?php
    echo '<center>';
    echo '<applet code="avoir.realtime.presentations.client.presenter.ui.PresenterFrame" width="75%" height="600" align="middle">';
	echo '	<param name="archive" value="'.$this->presentationsURL.'/presentations-client.jar"/> ';
	echo '	<param name="host" value="localhost"/>';
    echo '  <param name="port" value="3128"/>';
    echo '  <param name="contentBasePath" value="'.$this->objConfig->getcontentBasePath().'webpresent/'.$id.'"/>';
    echo "</applet> ";
    echo '</center>';
?>
