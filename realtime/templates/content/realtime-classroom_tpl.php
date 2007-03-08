<?php
$this->objLanguage = $this->getObject('language', 'language');
$this->objFeaturebox = $this->newObject('featurebox', 'navigation');

$whiteboard = '<applet width="400" height="600" code="avoir.realtime.whiteboard.client.WhiteboardApplet.class">';
$whiteboard .= '    <param name="archive" value="'.$this->whiteboardURL.'/whiteboard-client.jar"/> ';
$whiteboard .= '    <param name="userName" value="' . $this->userName . '"/>';
$whiteboard .= '    <param name="userLevel" value="' . $this->userLevel . '"/>';
$whiteboard .= '    <param name="port" value="1981"/>';
$whiteboard .= "</applet> ";

$voiceTitle = $this->objLanguage->languageText('mod_realtime_voice', 'realtime');
$voice = '<applet code="avoir.realtime.voice.VoiceApplet.class" width="500" height="100">';
$voice .= '	<param name="archive" value="'.$this->voiceURL.'/voice-client.jar"/> ';
$voice .= ' <param name="userName" value="' . $this->userName . '"/>';
$voice .= ' <param name="userLevel" value="' . $this->userLevel . '"/>';
$voice .= ' <param name="localdirectory" value="/tmp/"/>';
$voice .= ' <param name="voiceURL" value="'.$this->voiceURL.'"/>';
$voice .= "</applet> ";

$voiceBox = $this->objFeaturebox->show($voiceTitle, $voice);

$cssLayout=&$this->newObject('csslayout','htmlelements');
$cssLayout->setNumColumns(3);

$objBlocks = $this->newObject('blocks', 'blocks');
$chatBlock = $objBlocks->showBlock('contextchat', 'messaging');

$cssLayout->setLeftColumnContent($chatBlock);
$cssLayout->setMiddleColumnContent($whiteboard);
$cssLayout->setRightColumnContent($voiceBox);

echo $cssLayout->show();
?>