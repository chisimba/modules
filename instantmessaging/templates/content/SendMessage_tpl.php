	<!--<div style="background-color: #008080; padding:5px;">-->
	<!--<div style="background-color: #000080; padding:5px;">-->
	<!--<div style="background-color: #FFFFFF; padding:5px;">-->
<div style="background-color: #FF9900; padding:5px;"><!--height:140px;-->
<div style="background-color: #FFEFDF; padding:5px;"><!--height:130px;-->
<?php
    // If this is a reply then display the message being replied to.
	if ($reply == "yes") {
	 	//$this->loadClass("tabbedbox","htmlelements");
		//$tabbedbox = new tabbedbox();
		//$tabbedbox->addTabLabel("<b>" . $objLanguage->languageText("word_from") . "</b> : " . $recipient);
		//$tabbedbox->addBoxContent($text);
		//echo $tabbedbox->show();

        $tabLabel = "<b>" . $objLanguage->languageText("word_from",'instantmessaging') . "</b> : " . $recipient;
        $boxContent = $text;
        //
    	echo "<div class=\"im-box\">\n";
        echo "<h5>". $tabLabel."</h5>\n";
        echo "<div class=\"im-box-content\">";
        echo $boxContent;
        echo "\n</div>";
        echo "\n</div>";
	}
    // Load class.
	$this->loadClass("form","htmlelements");
	$this->loadClass("checkbox","htmlelements");
    // Display form.
	$form = new form("mainform",
		$this->uri(array(
	    	'module'=>'instantmessaging',
			'action'=>'sendMessageConfirm',
			'recipientId'=>$recipientId,
			'recipientType'=>$recipientType,
			'reply'=>$reply,
			'closeWindow'=>$closeWindow
		))
	);
	$form->setDisplayType(3);
	if ($reply == "yes") {
		$form->addToForm("<b>" . $objLanguage->languageText("im_replyto",'instantmessaging') . "</b> : " . $recipient . "<br/>");
	}
	else {
        switch($recipientType){
        	case 'user': 
        		$form->addToForm("<b>" . $objLanguage->languageText("im_sendto",'instantmessaging') . "</b> : " . $recipient . "<br/>");
        		break;
        	case 'context': 
        		$form->addToForm("<b>" . $objLanguage->languageText("im_sendtomembersof",'instantmessaging') . "</b> : " . $recipient . "<br/>");
                $checkbox1 =& new checkbox('lecturers',NULL,true);
                $checkbox2 =& new checkbox('students',NULL,true);
                $checkbox3 =& new checkbox('guests',NULL,true);
                $form->addToForm($checkbox1->show().ucfirst($objLanguage->languageText('mod_context_authors'))/*"Lecturers"*/."<br/>");
                $form->addToForm($checkbox2->show().ucfirst($objLanguage->languageText('mod_context_readonlys'))/*"Students"*/."<br/>");
                $form->addToForm($checkbox3->show()."Guests"."<br/>");
        		break;
        	case 'workgroup': 
        		$form->addToForm("<b>" . $objLanguage->languageText("im_sendtomembersof",'instantmessaging') . "</b> : " . $recipient . "<br/>");
        		break;
        	case 'buddies': 
        		$form->addToForm("<b>" . $objLanguage->languageText("im_sendtomembersof",'instantmessaging') . "</b> : " . $recipient . "<br/>");
        		break;
        	default:
        		;
        } // switch
	}
    // Display the message textarea.
	$this->loadClass("textarea","htmlelements");
	$form->addToForm(new textarea("text", "", 5, 35));
    $form->addToForm("<br/>");

    $form->addToForm("<table><tr><td width=\"80%\">");

    // Display the close window button.
	$icon=&$this->newObject('geticon','htmlelements');
	$icon->setIcon('close');
	$icon->alt=$this->objLanguage->languageText("im_closewindow",'instantmessaging');
    $icon->align = "absmiddle";
	$icon->extra=' onclick="window.close()" ';
	$form->addToForm($icon->show());

    $form->addToForm("</td><td width=\"20%\">");

    // Display the send button.    
	$this->loadClass("button","htmlelements");
	$button = new button("send", $objLanguage->languageText("word_send",'instantmessaging','instantmessaging'));
	$button->setToSubmit();
	$form->addToForm($button);
    
    $form->addToForm("</td></tr></table>");
    
	echo $form->show();
?>
</div>
</div>
	<!--</div>-->
	<!--</div>-->
	<!--</div>-->
