<?php
	echo("<h3>EMail</h3>");
	//print_r($emails);
	$count = 0;
	$unread = 0;
	foreach ($emails as $email) {
		$count++;
		if ($email['folder']=='new') {
			$unread++;
		}
	}
	if ($count < 5) {
		$to = $count;
	}
	else {
	    $to = 5;
	}
	if ($count > 0) {
		echo "1 to ".$to." of ".$count." Messages (".$unread." Unread)";
	}
	else {
		echo "You have no emails.";
	}
	$table =& $this->getObject("htmltable","htmlelements");
	$table->border = 0;
	$table->cellspacing = 1;
	$table->cellpadding = 5;	
	$table->width = Null;	
	$table->startRow();
	$table->addCell("<b>Subject</b>",null,"top",null,'emailHeading');
	$table->addCell("<b>Sender</b>",null,"top",null,'emailHeading');
	$table->addCell("<b>Date</b>",null,"top",null,'emailHeading');
	$table->endRow();
	$count = 0;
	foreach ($emails as $email) {
		if ($count < 5) {
			if ($email['folder']=='new') {
			    $tdClass = 'emailNew';
			}
			else {
			    $tdClass = 'emailOld';
			}
			$table->startRow();
			$table->addCell($email['subject'], null, "top", null, $tdClass);
			$table->addCell($email['fullname'], null, "top", null, $tdClass);
			$table->addCell($email['date'], null, "top", null, $tdClass);
			$table->endRow();		    
		}
		$count++;
	}
	echo $table->show();
	echo "<a href=\"" . 
		$this->uri(array(
		),
		'email'
		)	
	. "\">";
	$icon = $this->getObject('geticon','htmlelements');
	$icon->setIcon('inbox');
	$icon->alt = "Inbox";
	$icon->align=false;
	echo $icon->show();
	echo "&nbsp;Inbox</a>" . "&nbsp;";	
	echo "<a href=\"" . 
		$this->uri(array(
			'action'=>'new'
		),
		'email'
		)	
	. "\">";
	$icon = $this->getObject('geticon','htmlelements');
	$icon->setIcon('notes');
	$icon->alt = "Compose";
	$icon->align=false;
	echo $icon->show();
	echo "&nbsp;Compose</a>" . "&nbsp;";	
?>