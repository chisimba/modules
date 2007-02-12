<?php


	/**
	* template to show the chat window
	*
	*/
	//$this->setSession('room_entered','hello');
	//echo "room ".$this->getSession('room_entered');
	
	
	
	
	
			
	
	//$this->appendArray
	
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
	header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
	header("Cache-Control: no-cache, must-revalidate" ); 
	header("Pragma: no-cache" );
	header("Content-Type: text/xml; charset=utf-8");
	
	$this->loadClass('link','htmlelements');
	
	$this->appendArrayVar('headerParams', $script); // Send JS to header
	
	//$room = $this->getParam('room');
	$room = $this->getSession('room');
	$id = $this->getSession('id');
	
	

	//xajax_getChatText(\'Lobby\');
	//'onLoad="xajax_getChatText(\'Lobby\');"
	
	 //xajax_sendChat(\'Entered the room\');
	$lobby = 'Lobby';
	
	
	//setInterval(\'xajax_focus()\',2000);
	
	//setInterval(\'xajax_getChatText()\',1000);
	$this->setVar('bodyParams', ' onLoad = " intervals(); return false;"');
	
	
	
	$script = '<script language="JavaScript" >
			   		function intervals()
					{
						xajax_usersList();
						setInterval(\'xajax_getChatText()\',5000);
						setInterval(\'scroll()\',500);
						setInterval(\'xajax_loggedIn()\',20000);
						setInterval(\'xajax_usersList()\',60000);
						document.main_chat.message.focus();
						
						
					}
			  </script>';
			  
	$this->appendArrayVar('headerParams', $script); // Send JS to header		  
	
	//table_div
	$div_table =& $this->newObject('htmltable','htmlelements');
	
	//table2
	$table =& $this->newObject('htmltable','htmlelements');
	$this->loadClass('textarea','htmlelements');
	
	$this->loadClass('textinput','htmlelements');
	
	
	
	$script = '<script language="JavaScript" >
			   		function scroll()
					{
						parent.document.getElementById(\'div_chat\').scrollTop = parent.document.getElementById(\'div_chat\').scrollHeight ;
					}
			  </script>';
				
	//echo $script;	
	$this->appendArrayVar('headerParams', $script); // Send JS to header
	
	$form = new form('main_chat');
	
	$send_url = $this->uri(array('action'=>'send'));
	$name = "paul";
	
	//$onSubmit = "";
	
	$extra = ' onSubmit = "xajax_sendChat(document.main_chat.message.value);
			   document.main_chat.message.value = \' \';
			   document.main_chat.message.focus();
			  
			   return false;"';//sprintf(' onClick ="javascript: %s"', $onClick );
	
	$form->extra = $extra;
	
	
	$room = $this->objChat->getSessionVariable('room');
	
	
	$this->objIcon->setIcon('chat/leaveroom');
	$this->objIcon->alt = $this->objLanguage->languageText('phrase_leaveroom');
	//$this->objIcon->extra = ' onClick = "alert(\'hello\'); return false;"';
	
	$leave_link = new link($this->uri(array('action'=>'leaveroom','room'=>$room)));
	//$leave_link->extra = ' onClick = "xajax_logout();"';
	$leave_link->link = $this->objIcon->show();
	
	
	$this->objHeading->type = 2;
	$this->objHeading->str = $this->objLanguage->languageText('word_room').': '.$room.' '.$leave_link->show();
	
	echo $this->objHeading->show();
	
	
	//div for displaying the chat window
	$chat_div = '<div id="div_chat" class="wrapperDarkBkg" style="height: 400px; width: 700px; overflow: auto; border: 1px solid #555555;">
		 </div>';
		 
	$users_div = '<div id="div_users" class="wrapperDarkBkg" style="height: 400px; width: 200px; overflow: auto;  border: 1px solid #555555;">
		 </div>';
		 
		 
		 
		 
	
	$div_table->startRow();
	$div_table->addCell($chat_div);
	//$div_table->addCell('','3%');
	$div_table->addCell($users_div);
	$div_table->endRow();	 
		 
		
	$this->objButton->setValue($this->objLanguage->languageText('word_send'));
	$this->objButton->setToSubmit();
	
	//$this->objButton->extra = 'onClick = "return false;"';
	
	$send_button = $this->objButton->show();
	
	//refresh button
	
	$objRefresh = new button('refresh');
	
	$objRefresh->setValue($this->objLanguage->languageText('word_clear'));
	
	$extra = ' onClick = "xajax_refreshChat();"';//sprintf(' onClick ="javascript: %s"', $onClick );
	
	$objRefresh->extra = $extra;
	
	$refresh_button = $objRefresh->show();
	
	
	
	
	
	//table for displaying text area and buttons
	$table->startRow();
	
	//message area
	//$message = new textarea('message','',4,60);
	$message = new textinput('message','',50,60);
	
	//$message->extra = ' onkeyup = "xajax_sendChat(this.value);"';//sprintf(' onClick ="javascript: %s"', $onClick );
	$table->addCell($message->show(),'10%');
	
	//send chat url
	
	//send button
	
	
	
	
	$table->addCell($send_button.' | '.$refresh_button);
	
	$table->endRow();
	
	
	$form->addToForm($div_table->show());
	$form->addToForm($table->show());
	
	echo $form->show();
	
?>	
