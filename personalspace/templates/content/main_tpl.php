<?php
	// Display - heading (Personal Space)
	echo '<h1>'.$objLanguage->languageText('mod_personalspace_heading', 'personalspace').'</h1>';
    // Display - secondary heading (User Details)
	echo("<h3>".$objLanguage->languageText("mod_personalspace_userdetails", 'personalspace')."</h3>");

    echo '<div class="wrapperLightBkg" style="border: 1px solid #c0c0c0;">';

	$table =& $this->newObject("htmltable","htmlelements");
	$table->border = 0;
	$table->width = Null;
    $table->cellpadding = 2;
	$table->startRow();
	// Display - full name
	$table->addCell("<b>". $objLanguage->languageText("phrase_fullname") ."</b>");
    $table->addCell($objUser->fullName());
	$table->endRow();
	$table->startRow();
    // Display - username
	$table->addCell("<b>". $objLanguage->languageText("word_username") ."</b>");
	$table->addCell($objUser->userName());
	$table->endRow();
	$table->startRow();
	// Display - number of logins
	$data = $objUser->lookupData($objUser->userName());
	$table->addCell("<b>". $objLanguage->languageText("phrase_numberoflogins") ."</b>");
	$table->addCell($data['logins']);
	$table->endRow();
	$table->startRow();
	// Display - current course
	$table->addCell("<b>". $objLanguage->code2Txt('phrase_currentcourse')."</b>");
	$this->objDbContext = &$this->getObject('dbcontext','context');
	$contextCode = $this->objDbContext->getContextCode();
	if ($contextCode == null) {
		$table->addCell($objLanguage->code2Txt('phrase_notloggedin'));
	}
	else {
		$contextRecord = $this->objDbContext->getContextDetails($contextCode);
		$contextTitle = $contextRecord['title'];
		$table->addCell($contextTitle);
	}
	$table->endRow();

	echo $table->show();

	// Display - update user
	echo '<p align="center"><a href="' .
		$this->uri(NULL, 'userdetails')
		. "\">". $objLanguage->languageText("phrase_updatedetails") ."</a>";

	// Display - change password.
	//$objSqlUsers =& $this->getObject('sqlusers','security');     #############
	//if (!$objSqlUsers->isLDAPUser($this->objUser->userId())) {   #############
		echo " / <a href=\"" .
			$this->uri(array(
				'action'=>'changepassword'
				//'action'=>'Edit',
				//'userId'=>$objUser->userId()
			), 'userdetails'
			)
		. "\">"."Change Password"."</a>"."</p>";
	//}


	// End Wrapper
	echo '</div>';

?>

<?php
    if ($email) {
        // Display emails.
    	echo("<h3>".$objLanguage->languageText('mod_personalspace_email', 'personalspace')."</h3>");
    	// Calculate # of emails and # of unread emails.
    	$count = 0;
    	$unread = 0;
        if($emails != FALSE){
            foreach ($emails as $email) {
                $count++;
                if ($email['read_email']!='1') {
                    $unread++;
                }
            }
            // Calculate upper bound.
            if ($count < 5) {
                $to = $count;
            }
            else {
                $to = 5;
            }
            // Display emails.
            if ($count > 0) {
                echo
                    "1 "
                    .$objLanguage->languageText('word_to')
                    ." "
                    .$to
                    ." "
                    .$objLanguage->languageText('word_of')
                    ." "
                    .$count
                    ." "
                    .$objLanguage->languageText('word_messages')
                    ." ("
                    .$unread
                    ." "
                    .$objLanguage->languageText('word_unread')
                    .")";
            }
        }
        $table =& $this->newObject("htmltable","htmlelements");
        $table->border = 0;
        $table->cellspacing = 1;
        $table->cellpadding = 5;
        $table->width = "100%";
        $table->startHeaderRow();
        $table->addHeaderCell("<b>".$objLanguage->languageText('mod_personalspace_subject', 'personalspace')."</b>","40%","top");
        $table->addHeaderCell("<b>".$objLanguage->languageText('mod_personalspace_sender', 'personalspace')."</b>","40%","top");
        $table->addHeaderCell("<b>".$objLanguage->languageText('mod_personalspace_date', 'personalspace')."</b>","20%","top");
        $table->endHeaderRow();
    	if ($count > 0) {
    		$index = 0;
    		foreach ($emails as $email) {
                $name = $this -> objDbEmail -> getName($email['sender_id']);
    			if ($index < 5) {
    				if ($email['read_email']!='1') {
    				    $tdClass = 'emailNew';
    				}
    				else {
    				    $tdClass = 'emailOld';
    				}
    				$table->startRow();
    				$cell = "<a href=\"".
    					$this->uri(array(
    						//'module'=>'email',
    						'action'=>'read',
    						'emailId'=>$email['email_id']
    					), 'email')
    				."\">" . $email['subject'] . "</a>";
    				$table->addCell($cell, null, "top", null, $tdClass);
    				$table->addCell($name, null, "top", null, $tdClass);
    				$table->addCell($email['date_sent'], null, "top", null, $tdClass);
    				$table->endRow();
    			}
    			$index++;
    		}
    	}
    	else {
    		$table->startRow();
            //<span class="noRecordsMessage"> ... </span>
    		$table->addCell("<span class=\"noRecordsMessage\">".$objLanguage->languageText('mod_personalspace_noemails', 'personalspace')."</span>", null, "center", "center", null, "colspan='3'");
    		$table->endRow();
    	}
    	echo $table->show();
    	// Show Inbox.
    	echo "<p align=\"center\"><a href=\"" .
    		$this->uri(array(
    		),
    		'email'
    		)
    	. "\">";
    	$icon = $this->getObject('geticon','htmlelements');
    	$icon->setIcon('inbox');
    	$icon->alt = $objLanguage->languageText('mod_personalspace_inbox', 'personalspace');
    	//$icon->align=false;
    	echo $icon->show();
    	echo "&nbsp;".$objLanguage->languageText('mod_personalspace_inbox', 'personalspace')."</a>" . "&nbsp;/&nbsp;";
    	// Show Compose Message.
    	echo "<a href=\"" .
    		$this->uri(array(
    			'action'=>'compose'
    		),
    		'email'
    		)
    	. "\">";
    	$icon = $this->getObject('geticon','htmlelements');
    	$icon->setIcon('notes');
    	$icon->alt = $objLanguage->languageText('mod_personalspace_compose', 'personalspace');
    	//$icon->align=false;
    	echo $icon->show();
    	echo "&nbsp;".$objLanguage->languageText('mod_personalspace_compose', 'personalspace')."</a>" . "</p>";
    }

    if ($homepage) {
        // Header
        $objHeading = $this->getObject('htmlheading', 'htmlelements');
        $objHeading->str = $objLanguage->languageText('mod_personalspace_homepage', 'personalspace');
        echo $objHeading->show();

        $objHomepage =& $this->getObject('dbhomepages', 'homepage');
        echo $objHomepage->show();
    }
?>