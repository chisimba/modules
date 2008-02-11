<?php
	//Language Items	
	$notestsLabel = $this->objLanguage->languageText('mod_eportfolio_norecords', 'eportfolio');
	$linkAdd = '';
    	$objHeading =& $this->getObject('htmlheading','htmlelements');
   	$objHeading->type=1;
    // Show the heading
    $objHeading->str =$objUser->getSurname ().$objLanguage->languageText("phrase_eportfolio_userinformation", 'eportfolio');
    echo $objHeading->show();
    	$objHeading =& $this->getObject('htmlheading','htmlelements');
   	$objHeading->type=2;
//view name
    // Show the heading
    $objHeading->str =$objLanguage->languageText("mod_eportfolio_title", 'eportfolio');
	echo "<br/>";    
	echo $objHeading->show();
	
	
    echo "<br/>";
    // Create a table object
    $userTable =& $this->newObject("htmltable","htmlelements");
    $userTable->border = 0;
    $userTable->cellspacing='12';
    $userTable->cellpadding='12';
    $userTable->width = "50%";
    // Add the table heading.
    $userTable->startRow();
    $userTable->addHeaderCell("<b>".$objLanguage->languageText('word_title', 'system')."</b>");
    $userTable->addHeaderCell("<b>".$objLanguage->languageText('word_surname', 'system')."</b>");
    $userTable->addHeaderCell("<b>".$objLanguage->languageText('phrase_othernames', 'eportfolio')."</b>");
    $userTable->endRow();	
    
    // Step through the list of addresses.
    $class = 'even';
    if (!empty($user)) {
    	$i = 0;
       $class = ($class == (($i++%2) == 0)) ? 'even':'odd';
    // Display each field for addresses
        $userTable->startRow();
        $userTable->addCell($user['title'], "", NULL, NULL, $class, '');
	$userTable->addCell($user['surname'], "", NULL, NULL, $class, '');
        $userTable->addCell($user['firstname'], "", NULL, NULL, $class, '');
        // Show the edit link
        $iconEdit = $this->getObject('geticon','htmlelements');
        $iconEdit->setIcon('edit');
        $iconEdit->alt = $objLanguage->languageText("mod_eportfolio_edit",'eportfolio');
        $iconEdit->align=false;
        $objLink =& $this->getObject("link","htmlelements");
        $objLink->link($this->uri(array(
                    'module'=>'eportfolio',
                'action'=>'userdetails'                
            )));

              $objLink->link = $iconEdit->show();
        $linkEdit = $objLink->show();        

        $userTable->addCell($linkEdit, "", NULL, NULL, $class, '');
        $userTable->endRow();

  
} else {
    $userTable->startRow();
    $userTable->addCell($notestsLabel, '', '', '', 'noRecordsMessage', 'colspan="4"');
    $userTable->endRow();
}
	echo $userTable->show();
//end view name

//Start Address View
	    // Show the add link
	    $iconAdd = $this->getObject('geticon','htmlelements');
	    $iconAdd->setIcon('add');
	    $iconAdd->alt = $objLanguage->languageText("mod_eportfolio_add", 'eportfolio');
	    $iconAdd->align=false;
	    $objLink =& $this->getObject('link','htmlelements');
	    $objLink->link($this->uri(array(
	                'module'=>'eportfolio',
	            'action'=>'add_address',
	        )));

         $objLink->link =  $iconAdd->show();
	     $linkAdd = $objLink->show();

    // Show the heading
    $objHeading->str =$objLanguage->languageText("mod_eportfolio_heading", 'eportfolio').'&nbsp;&nbsp;&nbsp;'.$linkAdd;
    echo $objHeading->show();

	$addressList = $this->objDbAddressList->getByItem($userId);
    echo "<br/>";
    // Create a table object
    $addressTable =& $this->newObject("htmltable","htmlelements");
    $addressTable->border = 0;
    $addressTable->cellspacing='12';
    $addressTable->cellpadding='12';
    $addressTable->width = "100%";
    // Add the table heading.
    $addressTable->startRow();
    $addressTable->addHeaderCell("<b>".$objLanguage->languageText("mod_eportfolio_contypes",'eportfolio')."</b>");
    $addressTable->addHeaderCell("<b>".$objLanguage->languageText("mod_eportfolio_streetno",'eportfolio')."</b>");
    $addressTable->addHeaderCell("<b>".$objLanguage->languageText("mod_eportfolio_streetname",'eportfolio')."</b>");
    $addressTable->addHeaderCell("<b>".$objLanguage->languageText("mod_eportfolio_locality",'eportfolio')."</b>");
    $addressTable->addHeaderCell("<b>".$objLanguage->languageText("mod_eportfolio_city",'eportfolio')."</b>");
    $addressTable->addHeaderCell("<b>".$objLanguage->languageText("mod_eportfolio_postcode",'eportfolio')."</b>");
    $addressTable->addHeaderCell("<b>".$objLanguage->languageText("mod_eportfolio_postaddress",'eportfolio')."</b>");
    $addressTable->endRow();
    
    // Step through the list of addresses.
    $class = 'even';
    if (!empty($addressList)) {
    	$i = 0;
    foreach ($addressList as $addressItem) {
       $class = ($class == (($i++%2) == 0)) ? 'even':'odd';
    // Display each field for addresses
        $addressTable->startRow();
        $addressTable->addCell($addressItem['type'], "", NULL, NULL, $class, '');
        $addressTable->addCell($addressItem['street_no'], "", NULL, NULL, $class, '');
        $addressTable->addCell($addressItem['street_name'], "", NULL, NULL, $class, '');
        $addressTable->addCell($addressItem['locality'], "", NULL, NULL, $class, '');
        $addressTable->addCell($addressItem['city'], "", NULL, NULL, $class, '');
        $addressTable->addCell($addressItem['postcode'], "", NULL, NULL, $class, '');
        $addressTable->addCell($addressItem['postal_address'], "", NULL, NULL, $class, '');    
        // Show the edit link
        $iconEdit = $this->getObject('geticon','htmlelements');
        $iconEdit->setIcon('edit');
        $iconEdit->alt = $objLanguage->languageText("mod_eportfolio_edit",'eportfolio');
        $iconEdit->align=false;
        $objLink =& $this->getObject("link","htmlelements");
        $objLink->link($this->uri(array(
                    'module'=>'eportfolio',
                'action'=>'editaddress',
                'id' => $addressItem["id"]
            )));
              $objLink->link = $iconEdit->show();
        $linkEdit = $objLink->show();        


        // Show the delete link
        $iconDelete = $this->getObject('geticon','htmlelements');
        $iconDelete->setIcon('delete');
        $iconDelete->alt = $objLanguage->languageText("mod_eportfolio_delete",'eportfolio');
        $iconDelete->align=false;

        $objConfirm =& $this->getObject("link","htmlelements");

        $objConfirm=&$this->newObject('confirm','utilities');
            $objConfirm->setConfirm(
                $iconDelete->show(),
                $this->uri(array(
                        'module'=>'eportfolio',
                    'action'=>'deleteConfirm',
                    'id'=>$addressItem["id"]
                )),
            $objLanguage->languageText('mod_eportfolio_suredelete','eportfolio'));
			
            //echo $objConfirm->show();
        $addressTable->addCell($linkEdit. $objConfirm->show(), "", NULL, NULL, $class, '');
        $addressTable->endRow();



    }
	unset($addressItem);
	} else {
	    $addressTable->startRow();
	    $addressTable->addCell($notestsLabel, '', '', '', 'noRecordsMessage', 'colspan="8"');
	    $addressTable->endRow();
	}
    	echo $addressTable->show();

//End Address View
//Start Contacts View
	    // Show the add link
	    $iconAdd = $this->getObject('geticon','htmlelements');
	    $iconAdd->setIcon('add');
	    $iconAdd->alt = $objLanguage->languageText("mod_eportfolio_add", 'eportfolio');
	    $iconAdd->align=false;
	    $objLink =& $this->getObject('link','htmlelements');
	    $objLink->link($this->uri(array(
	                'module'=>'eportfolio',
	            'action'=>'add_contact'
	        )));

	    $objLink->link =  $iconAdd->show();
	    $linkAdd = $objLink->show();
    // Show the heading
    $objHeading->str =$objLanguage->languageText("mod_eportfolio_contact", 'eportfolio').'&nbsp;&nbsp;&nbsp;'.$linkAdd;
    echo $objHeading->show();

	$contactList = $this->objDbContactList->getByItem($userId);
	$emailList = $this->objDbEmailList->getByItem($userId);
	
    echo "<br/>";
    // Create a table object
    $contactTable =& $this->newObject("htmltable","htmlelements");
    $contactTable->border = 0;
    $contactTable->cellspacing='12';
    $contactTable->cellpadding='12';
    $contactTable->width = "100%";
    // Add the table heading.
    $contactTable->startRow();
    $contactTable->addHeaderCell("<b>".$objLanguage->languageText("mod_eportfolio_contypes",'eportfolio')."</b>");
    $contactTable->addHeaderCell("<b>".$objLanguage->languageText("mod_eportfolio_contacttypes",'eportfolio')."</b>");
    $contactTable->addHeaderCell("<b>".$objLanguage->languageText("mod_eportfolio_countrycode",'eportfolio')."</b>");
    $contactTable->addHeaderCell("<b>".$objLanguage->languageText("mod_eportfolio_areacode",'eportfolio')."</b>");
    $contactTable->addHeaderCell("<b>".$objLanguage->languageText("mod_eportfolio_contactnumber",'eportfolio')."</b>");
    $contactTable->endRow();
    
    // Step through the list of addresses.
    $class = 'even';
    if (!empty($contactList)) {
    	$i = 0;
    foreach ($contactList as $contactItem) {
       $class = ($class == (($i++%2) == 0)) ? 'even':'odd';
    // Display each field for addresses
        $contactTable->startRow();
        $contactTable->addCell($contactItem['type'], "", NULL, NULL, $class, '');
	$contactTable->addCell($contactItem['contact_type'], "", NULL, NULL, $class, '');
        $contactTable->addCell($contactItem['country_code'], "", NULL, NULL, $class, '');
        $contactTable->addCell($contactItem['area_code'], "", NULL, NULL, $class, '');
        $contactTable->addCell($contactItem['id_number'], "", NULL, NULL, $class, '');
        // Show the edit link
        $iconEdit = $this->getObject('geticon','htmlelements');
        $iconEdit->setIcon('edit');
        $iconEdit->alt = $objLanguage->languageText("mod_eportfolio_edit",'eportfolio');
        $iconEdit->align=false;
        $objLink =& $this->getObject("link","htmlelements");
        $objLink->link($this->uri(array(
                    'module'=>'eportfolio',
                'action'=>'editcontact',
                'id' => $contactItem["id"]
            )));

              $objLink->link = $iconEdit->show();
        $linkEdit = $objLink->show();        


        // Show the delete link
        $iconDelete = $this->getObject('geticon','htmlelements');
        $iconDelete->setIcon('delete');
        $iconDelete->alt = $objLanguage->languageText("mod_eportfolio_delete",'eportfolio');
        $iconDelete->align=false;

        $objConfirm =& $this->getObject("link","htmlelements");

        $objConfirm=&$this->newObject('confirm','utilities');
            $objConfirm->setConfirm(
                $iconDelete->show(),
                $this->uri(array(
                        'module'=>'eportfolio',
                    'action'=>'deletecontact',
                    'id'=>$contactItem["id"]
                )),
            $objLanguage->languageText('mod_eportfolio_suredelete','eportfolio'));
			

        $contactTable->addCell($linkEdit. $objConfirm->show(), "", NULL, NULL, $class, '');
        $contactTable->endRow();



    }
	unset($contactItem);
   
} else {
    $contactTable->startRow();
    $contactTable->addCell($notestsLabel, '', '', '', 'noRecordsMessage', 'colspan="5"');
    $contactTable->endRow();
}
	echo $contactTable->show();
//End Contact View
//Start Email View	
    echo "<br/>";
	    $iconAdd = $this->getObject('geticon','htmlelements');
	    $iconAdd->setIcon('add');
	    $iconAdd->alt = $objLanguage->languageText("mod_eportfolio_add", 'eportfolio');
	    $myobjLink =& $this->getObject("link","htmlelements");
	    $myobjLink->link($this->uri(array(
	                'module'=>'eportfolio',
	            'action'=>'add_email'
	        )));
            $myobjLink->link =  $iconAdd->show();
	    $emailLinkAdd = $myobjLink->show();

    // Create a heading for emails
$objHeading->str =$objLanguage->languageText("mod_eportfolio_emailList", 'eportfolio').'&nbsp;&nbsp;&nbsp;'.$emailLinkAdd;
    echo $objHeading->show();
    // Create a table object for emails
    $emailTable =& $this->newObject("htmltable","htmlelements");
    $emailTable->border = 0;
    $emailTable->cellspacing='12';
    $emailTable->cellpadding='12';
    $emailTable->width = "50%";
    // Add the table heading.
    $emailTable->startRow();
    $emailTable->addHeaderCell("<b>".$objLanguage->languageText("mod_eportfolio_contypes",'eportfolio')."</b>");
    $emailTable->addHeaderCell("<b>".$objLanguage->languageText("mod_eportfolio_email",'eportfolio')."</b>");
    $emailTable->endRow();
    
    // Step through the list of addresses.
    $class = 'even';
    if (!empty($emailList)) {
    	$i = 0;
    foreach ($emailList as $emailItem) {
       $class = ($class == (($i++%2) == 0)) ? 'even':'odd';
    // Display each field for addresses
        $emailTable->startRow();
        $emailTable->addCell($emailItem['type'], "", NULL, NULL, $class, '');
	$emailTable->addCell($emailItem['email'], "", NULL, NULL, $class, '');
        // Show the edit link
        $iconEdit = $this->getObject('geticon','htmlelements');
        $iconEdit->setIcon('edit');
        $iconEdit->alt = $objLanguage->languageText("mod_eportfolio_edit",'eportfolio');
        $iconEdit->align=false;
        $objLink =& $this->getObject("link","htmlelements");
        $objLink->link($this->uri(array(
                    'module'=>'eportfolio',
                'action'=>'editemail',
                'id' => $emailItem["id"]
            )));

              $objLink->link = $iconEdit->show();
        $linkEdit = $objLink->show();        


        // Show the delete link
        $iconDelete = $this->getObject('geticon','htmlelements');
        $iconDelete->setIcon('delete');
        $iconDelete->alt = $objLanguage->languageText("mod_eportfolio_delete",'eportfolio');
        $iconDelete->align=false;

        $objConfirm =& $this->getObject("link","htmlelements");

        $objConfirm=&$this->newObject('confirm','utilities');
            $objConfirm->setConfirm(
                $iconDelete->show(),
                $this->uri(array(
                        'module'=>'eportfolio',
                    'action'=>'deleteemail',
                    'myid'=>$emailItem["id"]
                )),
            $objLanguage->languageText('mod_eportfolio_suredelete','eportfolio'));
			

        $emailTable->addCell($linkEdit. $objConfirm->show(), "", NULL, NULL, $class, '');
        $emailTable->endRow();



    }
	unset($emailItem);
   
} else {
    $emailTable->startRow();
    $emailTable->addCell($notestsLabel, '', '', '', 'noRecordsMessage', 'colspan="5"');
    $emailTable->endRow();
}
	
    	echo $emailTable->show();
//End Email View
//Demographics view
	$demographicsList = $this->objDbDemographicsList->getByItem($userId);
	if (empty($demographicsList)) {
	    // Show the add link
	    $iconAdd = $this->getObject('geticon','htmlelements');
	    $iconAdd->setIcon('add');
	    $iconAdd->alt = $objLanguage->languageText("mod_eportfolio_add", 'eportfolio');
	    $iconAdd->align=false;
	    $objLink =& $this->getObject('link','htmlelements');
	    $objLink->link($this->uri(array(
	                'module'=>'eportfolio',
	            'action'=>'add_demographics'
	        )));

        $objLink->link =  $iconAdd->show();
	     $linkAdd = $objLink->show();
	    // Show the heading
	    $objHeading->str =$objLanguage->languageText("mod_eportfolio_demographics", 'eportfolio').'&nbsp;&nbsp;&nbsp;'.$linkAdd;
	echo $objHeading->show();
	} else {
	    // Show the heading
	    $objHeading->str =$objLanguage->languageText("mod_eportfolio_demographics", 'eportfolio');
	echo $objHeading->show();

	}
    	

    echo "<br/>";
    // Create a table object
    $demographicsTable =& $this->newObject("htmltable","htmlelements");
    $demographicsTable->border = 0;
    $demographicsTable->cellspacing='12';
    $demographicsTable->cellpadding='12';
    $demographicsTable->width = "50%";
    // Add the table heading.
    $demographicsTable->startRow();
    $demographicsTable->addHeaderCell("<b>".$objLanguage->languageText("mod_eportfolio_contypes",'eportfolio')."</b>");
    $demographicsTable->addHeaderCell("<b>".$objLanguage->languageText("mod_eportfolio_birth",'eportfolio')."</b>");
    $demographicsTable->addHeaderCell("<b>".$objLanguage->languageText("mod_eportfolio_nationality",'eportfolio')."</b>");
    $demographicsTable->endRow();
    
    // Step through the list of addresses.
    $class = 'even';
    if (!empty($demographicsList)) {
    	$i = 0;
    foreach ($demographicsList as $demographicsItem) {
       $class = ($class == (($i++%2) == 0)) ? 'even':'odd';
    // Display each field for Demographics
        $demographicsTable->startRow();
        $demographicsTable->addCell($demographicsItem['type'], "", NULL, NULL, $class, '');
        $demographicsTable->addCell($this->objDate->formatDate($demographicsItem['birth']), "", NULL, NULL, $class, '');
        $demographicsTable->addCell($demographicsItem['nationality'], "", NULL, NULL, $class, '');
        // Show the edit link
        $iconEdit = $this->getObject('geticon','htmlelements');
        $iconEdit->setIcon('edit');
        $iconEdit->alt = $objLanguage->languageText("mod_eportfolio_edit",'eportfolio');
        $iconEdit->align=false;
        $objLink =& $this->getObject("link","htmlelements");
        $objLink->link($this->uri(array(
                    'module'=>'eportfolio',
                'action'=>'editdemographics',
                'id' => $demographicsItem["id"]
            )));
        $objLink->link = $iconEdit->show();
        $linkEdit = $objLink->show();        


        // Show the delete link
        $iconDelete = $this->getObject('geticon','htmlelements');
        $iconDelete->setIcon('delete');
        $iconDelete->alt = $objLanguage->languageText("mod_eportfolio_delete",'eportfolio');
        $iconDelete->align=false;

        $objConfirm =& $this->getObject("link","htmlelements");

        $objConfirm=&$this->newObject('confirm','utilities');
            $objConfirm->setConfirm(
                $iconDelete->show(),
                $this->uri(array(
                        'module'=>'eportfolio',
                    'action'=>'deletedemographics',
                    'id'=>$demographicsItem["id"]
                )),
            $objLanguage->languageText('mod_eportfolio_suredelete','eportfolio'));
			

        $demographicsTable->addCell($linkEdit. $objConfirm->show(), "", NULL, NULL, $class, '');
        $demographicsTable->endRow();



    }
	unset($demographicsItem);
   
} else {
    $demographicsTable->startRow();
    $demographicsTable->addCell($notestsLabel, '', '', '', 'noRecordsMessage', 'colspan="6"');
    $demographicsTable->endRow();
}
    	echo $demographicsTable->show();

//End Demographics view
  	
	$mainlink = new link($this->uri(array('module'=>'eportfolio','action'=>'main')));
	$mainlink->link = 'ePortfolio home';
	echo '<br clear="left" />'.$mainlink->show();
?>
