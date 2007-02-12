<?php
	/**
	* default template for the ajax chat module
	*/
	
	$this->objHeading->type = 2;
	$this->objHeading->str = $this->objUser->fullName();
	echo $this->objHeading->show();
	
	
	
	//add link
	
	
	//add icon
	$this->objIcon->setIcon('add');
	$this->objIcon->align=false;
	$this->objIcon->alt = $objLanguage->languageText("mod_ajaxchat_createroom");
	$add_icon = $this->objIcon->show();
	
	$username = $this->objUser->username();
	$userId = $this->objUser->userId($username);
	
	
	$this->objLink = new link($this->uri(array('action'=>'createroom','userid'=>$userId)));
	$this->objLink->link = $add_icon;
	$add_link = $this->objLink->show();
	//heading for rooms
	
	$this->objHeading->type = 2;
	$this->objHeading->str = $this->objLanguage->languageText('phrase_availablerooms').' '.$add_link;
	
	echo $this->objHeading->show();
	
	//table for list of rooms
	
	$table =& $this->newObject('htmltable','htmlelements');
	
	
	//list of contexts/rooms
	//retrieving all commitees
	$context_codes =  $this->objManGroups->userContextCodes($userId);
	
	//user defined rooms
	$rooms = $this->objChat->getRooms($userId);
	
	//lobby
	$lobbys =  $this->objChat->getLobby();
	
	
	
	
	//list of user created rooms
	
	//$table->border = 1;
	$table->startHeaderRow();
	
	$table->addHeaderCell($this->objLanguage->languageText('word_name'));
	$table->addHeaderCell($this->objLanguage->languageText('word_description'));
	$table->addHeaderCell($this->objLanguage->languageText('phrase_createdby'));
	$table->addHeaderCell($this->objLanguage->languageText('phrase_datecreated'));
	
	$table->endHeaderRow();
	$date_format = $this->newObject('simplecal','datetime');
	
	
	
	
	if($this->objUser->userId() != '1')
	{
		foreach($lobbys as $lobby)
		{
		
		
			$table->row_attributes = 'onmouseover="this.className=\'tbl_ruler\';" onmouseout="this.className=\'none\'; "';
			
			$this->objLink = new link($this->uri(array('action'=>'login','room'=>$lobby['name'])));
			$this->objLink->link = $lobby['name'];
			
			$table->startRow();
						
			
			
			$table->addCell($this->objLink->show());
			$table->addCell($lobby['description']);
				
			
			$created_by = $this->objUser->username($lobby['created_by']);
			$table->addCell($created_by);
				
				
			$date_created = $date_format->formatDate($lobby['date_created']);
			$table->addCell($date_created);
		}
	}
	
	
	//contexts
	for($i = 0;$i < count($context_codes); $i++)
	{
		$table->startRow();
		
		$table->row_attributes = 'onmouseover="this.className=\'tbl_ruler\';" onmouseout="this.className=\'none\'; "';
		
		$contexts = $this->objContext->getContextDetails($context_codes[$i]);
		
		$this->objLink = new link($this->uri(array('action'=>'login','room'=>$contexts['title'])));
		$this->objLink->link = $contexts['title'];
		
		
		
		$table->addCell($this->objLink->show());
		$table->addCell($contexts['about']);
			
		$created_by = $this->objUser->username($contexts['userid']);
		$table->addCell($created_by);
			
			
		$date_created = $date_format->formatDate($contexts['dateCreated']);
		$table->addCell($date_created);
			
		
	}
	
	
	foreach($rooms as $room)
	{
		
		
		$this->objLink = new link($this->uri(array('action'=>'login','room'=>$room['name'])));
		$this->objLink->link = $room['name'];
		
		$table->row_attributes = 'onmouseover="this.className=\'tbl_ruler\';" onmouseout="this.className=\'none\'; "';
		
		$table->startRow();
					
		
		$table->addCell($this->objLink->show());
		$table->addCell($room['description']);
			
		
		$created_by = $this->objUser->username($room['created_by']);
		$table->addCell($created_by);
			
			
		$date_created = $date_format->formatDate($room['date_created']);
		$table->addCell($date_created);
	} 
	
	
	
	
				
			
		
		
	
	
	
	
	
	
	echo $table->show();
?>
