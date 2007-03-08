<?php

$this->setLayoutTemplate('layout_tpl.php');
   echo '<applet width="100" height="600" code="avoir.realtime.whiteboard.client.WhiteboardApplet.class">';
   echo '    <param name="archive" value="'.$whiteboardUrl.'/whiteboard-client.jar"/> ';
   echo '    <param name="userName" value="' . $userName . '"/>';
   echo '    <param name="userLevel" value="' . $userLevel . '"/>';
   echo '    <param name="port" value="1981"/>';
   echo "</applet> ";
?>