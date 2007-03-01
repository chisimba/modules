<?php
   echo '      <applet code="avoir.realtime.voice.VoiceApplet.class" width="500" height="200">';
   echo '         <param name="archive" value="'.$voiceUrl.'/voice-client.jar,'.$voiceUrl.'/swing-worker.jar"/> ';
   echo '         <param name="userName" value="' . $userName . '"/>';
   echo '         <param name="localdirectory" value="/tmp/"/>';
   echo '         <param name="realtimeModuleBase" value="'.$realtimeUrl.'"/>';
   echo "      </applet> ";
?>
