<?php
	// Load classes
	$this->loadClass('form', 'htmlelements');
	$this->loadClass('textinput', 'htmlelements');
    $this->loadClass("button","htmlelements");

    // Display the search form
	$form = new form('searchform', $this->uri(array('action'=>'searchlog')));
    $form->displayType = 2;
    $form->addToForm($objLanguage->languageText('mod_chat_search'));
    $textinput = new textinput('searchterm');
    $form->addToForm($textinput->show());
    $button=new button();
    $button->setToSubmit();
    $button->setValue($this->objLanguage->languageText('word_go'));
    $form->addToForm($button->show());
    echo $form->show();

	$icon = $this->getObject('geticon','htmlelements');
    /**
    * Function to parse the entry
    * @param string The text for the entry
    * @param object The icon object
    * @return string The parsed entry
    */
	function parseEntry($entry, $icon)
	{
		$entry = stripslashes($entry);
		$result = "";
		$i = 0;
		while($i < strlen($entry)){
            // Convert [smilexx] to an icon
			if ($entry[$i] == "[") {
			    $i++;
				$smiley = "";
				while($i < strlen($entry) && $entry[$i]!="]"){
					$smiley .= $entry[$i];
					$i++;
				} // while
				if ($i >= strlen($entry)) {
				    break;
				}
				$i++;
				$icon->setIcon("smileys/" . $smiley);
				$icon->align=false;
				$result .= $icon->show();
			}
			else {
				$result .= $entry[$i];
				$i++;
			}
		} // while
		return $result;
	}
	//$iconFolder = $objConfig->defaultIconFolder();
	echo "<h3>".$objLanguage->languageText('mod_chat_viewlogheading')." $context</h3>";
    // Display the posts.
	$line = 0;
	foreach ($content as $entry) {
		if (($line % 2)==0) {
			echo "<div class=\"even\">";
		}
		else {
			echo "<div class=\"odd\">";
		}
		echo strftime("%c",$entry["timestamp"]) . " : ";
        // Is this a system post?
		if ($entry["username"]=="") {
			echo $entry["content"] . "<br />";
		}
        // Is this a public post?
		else if ($entry["recipient"]=="All") {
			echo "<b>[" . $entry["username"] . "]</b>&nbsp;" . parseEntry($entry["content"],$icon) . "<br/>";
		}
        // This is a private post
		else {
			echo "<b>[" . $entry["username"] . ":" . $entry["recipient"] . "]</b>&nbsp;" . parseEntry($entry["content"],$icon) . "<br/>";
		}
		echo "</div>";
		$line++;
	}
?>