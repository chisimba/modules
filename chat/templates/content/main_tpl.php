<?php 
    // Load classes.
	$this->loadClass("form","htmlelements");
	$this->loadClass("textinput","htmlelements");
	$this->loadClass("button","htmlelements");
	// Display error if neccessary.
	if ($error != "") {
		echo "<span class=\"error\">";
		echo $error;
		echo "</span>";
	    echo "<br />";
	}
    // Display available chat rooms.
	echo "<h3>".$objLanguage->languageText('chat_available_rooms').":</h3>";
	echo "<table>";
	foreach ($list as $element) {
		echo "<tr>";
		echo "<td>";
		echo "<b>" . $element[0] . "</b>";
		echo "</td>";
		echo "<td>";
		echo "<a href=\"" . 
			$this->uri(array(
				'module'=>'chat',
				'action'=>'join',
				'context'=>$element[1]
			))
		. "\">".$objLanguage->languageText('mod_chat_join')."</a>";		
		echo "</td>";
		echo "<tr>";
	}
	echo "</table>";
    // Display Create chat room form.
	echo "<h3>".$objLanguage->languageText('chat_create_room').":</h3>";
	$form = new form("createForm", 
		$this->uri(array(
	    	'module'=>'chat',
			'action'=>'createContext'
		))
	);
	$form->setDisplayType(3);
	$form->addToForm($objLanguage->languageText('chat_new_room').":");
	$form->addToForm(new textinput("newcontext",""));
	$button = new button("submit", $objLanguage->languageText("chat_create_room"));
	$button->setToSubmit();
	$form->addToForm($button);
	echo $form->show();
?>
