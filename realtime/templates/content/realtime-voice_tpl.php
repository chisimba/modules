<?php
   echo '      <applet code="avoir.realtime.voice.audio.applet.VoiceApplet.class" width="400" height="200">';
   echo '         <param name="archive" value="voice-client.jar,jmf.jar,swing-worker.jar"> ';
   echo '         <param name="userName" value="' . $userName . '">';
   echo '         <param name="localdirectory" value="/tmp/">';
   echo '         <param name="realtimeModuleBase" value="'.$appUrl.'">';
   echo "\n         </param></param></param></param>\n";
   echo "      </applet> ";
?>
