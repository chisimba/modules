<?php

        $homeLink = new link ($this->uri(array('action'=>'presenter_applet')));
        $homeLink->link = 'Presentations Home';
        $webPresentLink = new link ($this->uri(array('action'=>'presenter_applet')));
        $webPresetLink->link = 'Web Present';
      
        echo '<center>';
        echo '<p>'.$homeLink->show().'</p>';
	echo '<applet code="avoir.realtime.presentations.client.presenter.ui.MainFrame" width="720" height="418" align="middle">';
	echo '	<param name="archive" value="'.$this->presentationsURL.'/presentations-client.jar"/> ';
	echo '	<param name="host" value="localhost"/>';
        echo '  <param name="port" value="1962"/>';
        echo '  <param name="contentBasePath" value="'.$this->objAltConfig->getcontentBasePath().'webpresent/'.$id.'"/>';
        echo '  <param name="files" value="'.$files.'"/>';
	echo "</applet> ";
        echo '</center>';
?>
