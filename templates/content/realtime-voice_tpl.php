<?php
echo '<applet code="avoir.realtime.voice.VoiceApplet.class" width="500" height="100">';
echo '	<param name="archive" value="'.$this->voiceURL.'/voice-client.jar"/> ';
echo '	<param name="userName" value="' . $this->userName . '"/>';
echo '  <param name="userLevel" value="' . $this->userLevel . '"/>';
echo '  <param name="localdirectory" value="/tmp/"/>';
echo '  <param name="voiceURL" value="'.$this->voiceURL.'"/>';
echo "</applet> ";
?>
