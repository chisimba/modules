	<!--<div style="background-color: #008080; padding:5px;">-->
	<!--<div style="background-color: #000080; padding:5px;">-->
	<!--<div style="background-color: #FFFFFF; padding:5px;">-->
<div style="background-color: #FF9900; padding:5px;"><!--height:140px;-->
<div style="background-color: #FFEFDF; padding:5px;"><!--height:130px;-->
<?php 
	//$this->loadClass("tabbedbox","htmlelements");
	//$tabbedbox = new tabbedbox();
	//$tabbedbox->addTabLabel("<b>" . $objLanguage->languageText("word_from") . "</b> : " . $sender);
	//$tabbedbox->addBoxContent($text);
	//echo $tabbedbox->show();
    // Display message.
	if (is_null($senderId)) {
		// System notification
    	$tabLabel = $sender;
	}
	else {
		// Ordinary message
	    $tabLabel = "<b>" . $objLanguage->languageText("word_from",'instantmessaging') . "</b> : " . $sender;
	}
    $boxContent = $text;
	echo "<div class=\"im-box\">\n";
    echo "<h5>". $tabLabel."</h5>\n";
    echo "<div class=\"im-box-content\">";
    echo $boxContent;
    echo "\n</div>";
    echo "\n</div>";
	echo "<br/>";
    // Display reply button if not a system notification message
	if (!is_null($senderId)) {
		echo "<a href=\"" . $this->uri(array(
		    	'module'=>'instantmessaging',
				'action'=>'sendMessage',
				'recipientId'=>$senderId,
				'messageId'=>$messageId
			)) . "\">" . $objLanguage->languageText("word_reply",'instantmessaging') . "</a>";
		echo "&nbsp;";
	}
    // Display close window button.
	$icon=&$this->newObject('geticon','htmlelements');
	$icon->setIcon('close');
	$icon->alt=$this->objLanguage->languageText("im_closewindow",'instantmessaging');
    $icon->align = "absmiddle";
	$icon->extra=' onclick="window.close()" ';
	echo $icon->show();
?>
</div>
</div>
	<!--</div>-->
	<!--</div>-->
	<!--</div>-->
