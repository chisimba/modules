<?php
/**
 * 
 *
 * @version $Id$
 * @copyright 2004 
 **/
	echo $objLanguage->languageText("chat_you_have_left") . "<br/>";
	echo "<a href=\"" . 
		$this->uri(array(
		   	'module'=>'chat',
		)) . "\">" . $objLanguage->languageText("chat_return") . "</a>";				    
	echo "<br/>";
	echo "<a href=\"" . 
		$this->uri(array(), '_default') . "\">" . $objLanguage->languageText("chat_return_to_main_menu") . "</a>";				    
?>