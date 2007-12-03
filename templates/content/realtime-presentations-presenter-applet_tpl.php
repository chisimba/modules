<?php

    echo '<center>';
    echo '<applet code="avoir.realtime.presentations.client.presenter.PresenterFrame" width="75%" height="600" align="middle">';
	echo '	<param name="archive" value="'.$this->presentationsURL.'/presentations-client.jar"/> ';
	echo '	<param name="host" value="localhost"/>';
    echo '  <param name="port" value="3128"/>';
    echo '  <param name="contentBasePath" value="'.$this->objConfig->getcontentBasePath().'realtime_presentations/'.$id.'"/>';
    echo '  <param name="user" value="'.$this->objUser->userName().'"/>';
	 echo '  <param name="fullname" value="'.$this->objUser->fullname().'"/>';
	echo '  <param name="slideId" value="'.$id.'"/>';
	echo '  <param name="jodconverterPath" value="'.$jodconverterPath.'"/>';
echo '  <param name="fullname" value="'.$this->objUser->fullname().'"/>';
	echo "</applet> ";
    echo '</center>';
?>
