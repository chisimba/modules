<?php

        //$homeLink = new link ($this->uri(array('action'=>'home')));
       // $homeLink->link = 'Home';
        $this->homeLink=& $this->newObject('link', 'htmlelements');
        $homeLink->link = 'Home';
        echo '  <param name="contentBasePath" value="'.$this->objConfig->getcontentBasePath().'webpresent/'.$id.'"/>';
         
        echo '<center>';
        //echo '<p>'.$homeLink->show().'</p>';
	echo '<applet code="avoir.realtime.presentations.client.presenter.ui.MainFrame" width="720" height="418" align="middle">';
	echo '	<param name="archive" value="'.$this->presentationsURL.'/presentations-client.jar"/> ';
	echo '	<param name="host" value="localhost"/>';
        echo '  <param name="port" value="1962"/>';
        echo '  <param name="contentBasePath" value="'.$this->objConfig->getcontentBasePath().'webpresent/'.$id.'"/>';
        echo '  <param name="files" value="'.$files.'"/>';
	echo "</applet> ";
        echo '</center>';
?>
