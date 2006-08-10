<?php

	/**
	 * 
	 *
	 * @version $Id$
	 * @copyright 2004 
	 **/
	echo("ICQ # = ".$_POST['icq']."<br/>");
	echo("Text = ".$_POST['text']."<br/>");

	echo "<a href=\"" . 
		$this->uri(array(
	    	'module'=>'instantmessaging',
			'action'=>'showusers',
		))	
	. "\">" . "Return to IM" . "</a>";

?>