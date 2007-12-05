<?php
	public $userLevel;
		if ($this->objUser->isAdmin())
		{
			$this->userLevel = 'admin';
		}
		elseif ($this->objUser->isLecturer())
		{
			$this->userLevel = 'lecturer';
		}
		elseif ($this->objUser->isStudent())
		{
			$this->userLevel = 'student';
		} else
		{
			$this->userLevel = 'guest';
		}
    echo '<center>';
    echo '<applet code="avoir.realtime.presentations.client.presenter.PresenterFrame" width="75%" height="600" align="middle">';
	echo '	<param name="archive" value="'.$this->presentationsURL.'/presentations-client.jar"/> ';
	echo '	<param name="host" value="localhost"/>';
    echo '  <param name="port" value="3128"/>';
    echo '  <param name="contentBasePath" value="'.$this->objConfig->getcontentBasePath().'webpresent/'.$id.'"/>';
    echo '  <param name="userName" value="'.$this->objUser->userName().'"/>';
	echo '  <param name="slideId" value="'.$id.'"/>';
	echo '  <param name="jodconverterPath" value="'.$jodconverterPath.'"/>';
	echo '  <param name="fullname" value="'.$this->objUser->fullname().'"/>'; 
	echo '  <param name="jmfResourcePath" value="'.$this->objAltConfig->getModulePath().'/realtime/resources/jmf/"/>';
	echo '  <param name="userLevel" value="'.$this->userLevel.'"/>';
    echo "</applet> ";
    echo '</center>';
?>
