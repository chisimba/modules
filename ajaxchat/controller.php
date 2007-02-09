<?php
	// security check - must be included in all scripts
	if (!$GLOBALS['kewl_entry_point_run'])
	{
		die("You cannot view this page directly");
	}
	// end security check
	
	/**
	* The ajax chat controller manages
	* the different actions for the ajax chat module
	* @author Shafeek Naidoo
	* @version v1.0
	* @copyright 2006, University of the Western Cape & AVOIR Project
	* @license GNU GPL
	* @package ajaxchat
	**/
	
	class ajaxchat extends controller
	{
		
		
		/**
		* @var object $objUser
		*/
		var $objUser;                       
		
		/**
		* @var object $objLanguage
		*/
		var $objLanguage; 
		
		
		/**
		* @var object $objIcon
		*/
		var $objIcon; 
		
		/**
		* @var object $objHeading
		*/
		var $objHeading;
		
		/**
		* @var object $objLink
		*/
		var $objLink;
		
		/**
		* @var object $objButton
		*/
		var $objButton;
		
		/**
		* @var object $objContext
		*/
		var $objContext;
		
		/**
		* @var object $objManGroup
		*/
		var $objManGroups;
		
		/**
		* @var object $objManGroup
		*/
		var $objChat;
		
		
		function init()
		{
			$this->objUser =& $this->getObject('user','security');
			
			$this->objLanguage =& $this->getObject('language','language');
			
			$this->objHeading =& $this->getObject('htmlheading','htmlelements');
			
			$this->objLink =& $this->getObject('link','htmlelements');
			
			$this->objButton =& $this->getObject('button','htmlelements');
			
			$this->objIcon =& $this->newObject('geticon','htmlelements');
			
			$this->objContext =& $this->getObject('dbcontext','context');
			
			$this->objManGroups =& $this->newObject('managegroups','contextgroups');
			
			$this->objChat =& $this->getObject('dbajaxchat');
			
			$this->loadClass('xajax', 'htmlelements');
			$this->loadClass('xajaxresponse', 'htmlelements');
		}
		
		function dispatch($action)
		{
			
			//$this->setLayoutTemplate("main_layout_tpl.php");
			
			switch($action)
			{
				case Null:
					
				case 'main':
					//making sure user is not in room
					$this->setSession('room_entered','0');
					$this->objChat->leaveRoom();
					
					return "main_page_tpl.php";
					
				case 'createroom':
					return "create_room_tpl.php";
					
				case 'login':
					$this->setSession('room',$this->getParam('room'));
					$this->setSession('room_entered','1');
					$this->objChat->enter_room();
					$this->nextAction('chat');
					
				case 'chat':
					
					
					if(!$this->getSession('room_entered'))
					{
						return "error_tpl.php";
					}else{
						$this->ajax_sendChat();
					
						return "chat_tpl.php";
					}
					
				case 'send':
					//$this->setSession('room',$this->getParam('room'));
					$this->ajax_sendChat();
					break;
					
				case 'leaveroom':
					$this->objChat->leaveRoom();
					$this->setSession('startLogonList','');
					
					$this->nextAction('main');
					break;
				
			}
			
		}
		
		
		function ajax_sendChat()
		{
			 // Instantiate Class - Parameter MUST be the URL with the current action
			 $xajaxSend = new xajax($this->uri(array('action'=>'send'), NULL, NULL, FALSE, TRUE));
			 $xajaxSend->registerFunction(array($this,"sendChat")); // Register another function in this controller
			 $xajaxSend->registerFunction(array($this,"getChatText")); // Register another function in this controller
			 $xajaxSend->registerFunction(array($this,"loggedIn")); // Register another function in this controller
			 $xajaxSend->registerFunction(array($this,"refreshChat")); // Register another function in this controller
			 $xajaxSend->registerFunction(array($this,"focus")); // Register another function in this controller
			 $xajaxSend->registerFunction(array($this,"usersList")); // Register another function in this controller
			 $xajaxSend->registerFunction(array($this,"logout")); // Register another function in this controller
			 
			 
			 $xajaxSend->processRequests(); // XAJAX method to be called
        
			 $this->appendArrayVar('headerParams', $xajaxSend->getJavascript()); // Send JS to header
        
			
			 
			 
			 return 'chat_tpl.php';
			 
		}
		
		/**
		* function to send the chat 
		* @param $message
		*/
		
		function sendChat($message)
		{
			
			
			//get room name
			$room = $this->getSession('room');
			
			$this->setSession('post','1');
			
			//retrieving last record id
			/*$records = $this->objChat->lastChat($room);
			
			
			foreach($records as $record)
			{
				$id_old = $record['id'];
			}
			*/
			 
			$this->setId();
			//insert the message into the database
			$id = $this->objChat->insertChatMessage($message,$room);
			
			
			//$this->setSession('id_old',$id_old);
			$this->setSession('id_current',$id);
			
			//$this->setVarByRef('id',$id);
			//$this->setVarByRef('room',$room);
			
			//get message for room
			//$message = $this->objChat->getMessage($room,$id);
			
			$objResponse = new xajaxResponse();
			
			$response = $this->prepare_response($message);
			
			$objResponse->addAppend('div_chat', 'innerHTML', $response);
			
			
			return $objResponse->getXML();
			
			
			
		}
		
		
		
		/**
		* function to get the chat text entered
		* @param room
		* @param id -- id of last entered message 
		*/
		function getChatText()
		{
			
			
			
			$room = $this->getRoom();
			
			$room = $this->getSession('room');
						
			
			$message = $this->objChat->getMessages($room);
			
			 
			$objResponse = new xajaxResponse();
			 
			$response = $this->prepare_response($message);
			
			$objResponse->addAppend('div_chat', 'innerHTML', $response);
			
			
			return $objResponse->getXML();
		}
		
		/**
		* function to get the chat text entered
		* @param room
		* @param id -- id of last entered message 
		*/
		function setId()
		{
			//retrieving last record id
			$room = $this->getSession('room');
			$records = $this->objChat->lastChat($room);
			
			
			foreach($records as $record)
			{
				$id_old = $record['id'];
			}
			 
			
			
			
			$this->setSession('id_old',$id_old);
			
		}
		
		
		
		
		
		/**
		* function to return the name of the chat room
		*
		* @return room
		*/
		function getRoom()
		{
			
			return $this->getSession('room');
			
		}
		 
		/**
		* function to display who has logged in
		* 
		*/
		function loggedIn()
		{
			$objResponse = new xajaxResponse();
			
			$loggedUsers = $this->objChat->getLoggedOn();
			 
			foreach($loggedUsers as $user)
			{
				$response = '('.$user['enter_time'].'): '.$user['user_name'].' has entered the room<br>';
			}
			
			
			$objResponse->addAppend('div_chat', 'innerHTML', $response);
			/* 
			$response2 = ' ';
			//for user list
			foreach($loggedUsers as $user)
			{
				$response2 .= '<b>'.$user[0]['user_name'].'</b><br>';
			}
			
			
			
			
			$objResponse->addAppend('div_users', 'innerHTML',$response); */
			
			return $objResponse->getXML();
		}
		
		
		/**
		* function to refresh the user's list div
		*
		*/
		function usersList()
		{
			
			$objResponse = new xajaxResponse();
			
			$users = $this->objChat->getLoggedOnList();
			
			$message = " ";
			
			
			foreach($users as $user)
			{
				$message .= '<b>'.$user['user_name'].'</b><br>';
				
				
			}
			
			$objResponse->addAssign('div_users', 'innerHTML', $message);
			
			return $objResponse->getXML();
			
		}
		
		/**
		* function to refresh chat div
		*
		*/
		function refreshChat()
		{
			$objResponse = new xajaxResponse();
			$objResponse->addClear('div_chat', 'innerHTML');
			return $objResponse->getXML();
		}
		
		function focus()
		{
			$objResponse = new xajaxResponse();
			$response = '<script language="JavaScript" >
						parent.document.getElementById(\'div_chat\').scrollTop = parent.document.getElementById(\'div_chat\').scrollHeight ;
						</script>';
			$objResponse->addScript($response);
			return $objResponse->getXML();
			
		}
		
		
		/**
		* function to display a message when the user logs out
		* 
		*/
		function logout()
		{
			$objResponse = new xajaxResponse();
			
			$response = $this->objUser->fullName().' left the room.';
			
			$this->objChat->updateUsers();
			
			$room = $this->getRoom();
			
			$users = $this->objChat->loggedout($room);
			
			$objResponse->addAppend('div_chat', 'innerHTML', $response);
			
			return $objResponse->getXML();
		}
		
		/**
		* function to prepare the message from the database
		* @param array list of messages
		*/
		function prepare_response($messages)
		{
			$final = " ";
			
			foreach($messages as $message)
			{
				if($message['message'] == " ")
				{
					$final = " ";
				}else{
					$final .= $message['user_name'].' @ ('.date('H:i').'): '.$message['message'].'<br>';
				}
				
			}
			
			return $final;
		}
		
		
		
		
		
		
		
		
		
		
		
	}
?>
