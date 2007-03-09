<?php
echo '<applet code="avoir.realtime.JMFInstallerApplet.class" width="300" height="120">';
echo '	<param name="archive" value="'.$this->voiceURL.'/jmf-installer.jar"/> ';
echo '	<param name="userName" value="' . $this->userName . '"/>';
echo '  <param name="userLevel" value="' . $this->userLevel . '"/>';
echo '  <param name="downloadLocation" value="'.$this->voiceURL.'/JMF-2.1.1e-linux/"/>';
echo "</applet> ";
?>