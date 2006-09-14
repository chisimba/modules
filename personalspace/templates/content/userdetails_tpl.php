<?php
	//WTF? - Repeated shit

	echo("<h3>User Details</h3>");
	$table =& $this->getObject("htmltable","htmlelements");
	$table->border = 0;
	$table->width = Null;	
	$table->startRow();
	$table->addCell("<b>". $objLanguage->languageText("phrase_fullname") ."</b>");
    $table->addCell($objUser->fullName());
	$table->endRow();
	$table->startRow();
    $table->addCell("<b>". $objLanguage->languageText("word_username") ."</b>");
	$table->addCell($objUser->userName());
	$table->endRow();
	$table->startRow();
	// Number of logins
	$data = $objUser->lookupData($objUser->userName());
	$table->addCell("<b>". $objLanguage->languageText("phrase_numberoflogins") ."</b>");
	$table->addCell($data['logins']);
	$table->endRow();
	$table->startRow();
	// Current course
	$table->addCell("<b>". $objLanguage->languageText("phrase_currentcourse") ."</b>");
	$this->objDbContext = &$this->getObject('dbcontext','context');
	$contextCode = $this->objDbContext->getContextCode();
	if ($contextCode == null) {
		$table->addCell($objLanguage->code2Txt("phrase_notloggedin"));
	}
	else {
		$contextRecord = $this->objDbContext->getContextDetails($contextCode);
		$contextTitle = $contextRecord['title'];
		$table->addCell($contextTitle);
	}
	$table->endRow();
	echo $table->show();
	echo "<a href=\"" . 
		$this->uri(array(
			'action'=>'Edit',
			'userId'=>$objUser->userId()
		),
		'useradmin'
		)	
	. "\">". $objLanguage->languageText("phrase_updatedetails") ."</a>" . "<br/>";	
?>