<?php
echo '<applet width="800" height="600" code="avoir.realtime.whiteboard.client.WhiteboardApplet.class">';
echo '    <param name="archive" value="'.$this->whiteboardURL.'/whiteboard-client.jar"/> ';
echo '    <param name="userName" value="' . $this->userName . '"/>';
echo '    <param name="userLevel" value="' . $this->userLevel . '"/>';
echo '    <param name="port" value="1981"/>';
echo "</applet> ";
?>