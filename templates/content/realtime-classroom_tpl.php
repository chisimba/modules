<?php
$this->objLanguage = $this->getObject('language', 'language');
$this->objFeaturebox = $this->newObject('featurebox', 'navigation');

$whiteboard = '<applet width="600" height="600" code="avoir.realtime.whiteboard.client.WhiteboardApplet.class">';
$whiteboard .= '    <param name="archive" value="'.$this->whiteboardURL.'/whiteboard-client.jar"/> ';
$whiteboard .= '    <param name="userName" value="' . $this->userName . '"/>';
$whiteboard .= '    <param name="userLevel" value="' . $this->userLevel . '"/>';
$whiteboard .= '    <param name="port" value="1981"/>';
$whiteboard .= "</applet> ";

$voiceTitle = $this->objLanguage->languageText('mod_realtime_voice', 'realtime');
$voice = '<applet code="avoir.realtime.voice.VoiceApplet.class" width="320" height="100">';
$voice .= '	<param name="archive" value="'.$this->voiceURL.'/voice-client.jar"/> ';
$voice .= ' <param name="userName" value="' . $this->userName . '"/>';
$voice .= ' <param name="userLevel" value="' . $this->userLevel . '"/>';
$voice .= ' <param name="localdirectory" value="/tmp/"/>';
$voice .= ' <param name="voiceURL" value="'.$this->voiceURL.'"/>';
$voice .= "</applet> ";

$voiceBox = $this->objFeaturebox->show($voiceTitle, $voice);

$objBlocks = $this->newObject('blocks', 'blocks');
$chatBlock = $objBlocks->showBlock('contextchat', 'messaging');

$objLayer = $this->newObject('layer', 'htmlelements');
$objLayer->str = $voiceBox.$chatBlock;
$objLayer->border = '; width: 27%; float: left';
$layer1 = $objLayer->show();

$objLayer->str = $whiteboard;
$objLayer->border = '; width: 78%;';
$objLayer->align = 'center';
$layer2 = $objLayer->show();

$objLayer->str = '';
$objLayer->border = '; clear: both;';
$layer3 = $objLayer->show();

echo $layer1.$layer2.$layer3;
?>