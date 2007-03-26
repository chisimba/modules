<!-- START FILE MANAGER FILE CHOOSER CODE-->
<script type="text/javascript">

	function openWindow(theURL,winName,features) { 
	  newwindow=window.open(theURL,winName,features);
       if (window.focus) {
       	newwindow.focus()
	   }
    } 

    /*
     * Function that is called by Java applet when user wants to select an image.
     */
    function openImageFileChooser() {
		openWindow('<?php echo str_replace('amp;', '', $this->uri(array('action'=>'selectrealtimeimagewindow', 'name'=>'selectimage'), 'filemanager')); ?>','new','toolbar=no, menubar=no, width=600, height=400, resizable=yes, scrollbars=auto, toolbar=no top=200 screenY=200 left=300 screenY=300');
    }

	/*
	 * Function that is called by file manager image chooser popup when a file has been selected.
	 */    
    function callFunctionFromParent()
	{
	    var imageURL = document.getElementById('hidden_selectimage').value;
	    document.getElementById("whiteboardapplet").imageSelected(imageURL);
	}
</script>
<div style="display:none;">
<?php
$objSelectFile = $this->getObject('selectrealtimeimage', 'filemanager');
$objSelectFile->name = 'selectimage';
$this->setVar('pageSuppressXML', TRUE);
echo $objSelectFile->show();
?>
</div>
<!-- END FILE MANAGER FILE CHOOSER CODE-->

<?php
$this->objLanguage = $this->getObject('language', 'language');
$this->objFeaturebox = $this->newObject('featurebox', 'navigation');

$whiteboard = '<applet id="whiteboardapplet" width="660" height="600" code="avoir.realtime.whiteboard.client.WhiteboardApplet.class">';
$whiteboard .= '    <param name="archive" value="'.$this->whiteboardURL.'/whiteboard-client.jar"/> ';
$whiteboard .= '    <param name="userName" value="' . $this->userName . '"/>';
$whiteboard .= '    <param name="userLevel" value="' . $this->userLevel . '"/>';
$whiteboard .= '    <param name="port" value="1981"/>';
$whiteboard .= "</applet> ";

$voiceTitle = $this->objLanguage->languageText('mod_realtime_voice', 'realtime');
$voice = '<applet code="avoir.realtime.voice.VoiceApplet.class" width="300" height="120">';
$voice .= '	<param name="archive" value="'.$this->voiceURL.'/voice-client.jar"/>';
$voice .= ' <param name="userName" value="' . $this->userName . '"/>';
$voice .= ' <param name="userLevel" value="' . $this->userLevel . '"/>';
$voice .= ' <param name="voiceURL" value="'.$this->voiceURL.'"/>';
$voice .= ' <param name="realtimeControllerURL" value="'.$this->realtimeControllerURL.'"/>';
$voice .= "</applet> ";

$voiceBox = $this->objFeaturebox->show($voiceTitle, $voice);

$objBlocks = $this->newObject('blocks', 'blocks');
$chatBlock = $objBlocks->showBlock('contextchat', 'messaging');

$objLayer = $this->newObject('layer', 'htmlelements');
$objLayer->str = $voiceBox.$chatBlock;
$objLayer->border = '; width: 320px; float: left';
$layer1 = $objLayer->show();

$objLayer->str = $whiteboard;
$objLayer->border = '; width: 660px; float: right';
$objLayer->align = 'center';
$layer2 = $objLayer->show();

echo $layer1.$layer2;
?>