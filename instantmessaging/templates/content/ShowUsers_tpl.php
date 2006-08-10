<?php 
    $objLink=&$this->newObject('link','htmlelements');
    $icon=&$this->newObject('geticon','htmlelements');
?>
<div id="instantmessaging_main" class="instantmessaging_height">
<?php
	// Display a list of logged on users.
    echo "<h3>".$objLanguage->languageText('mod_instantmessaging_users','instantmessaging')."</h3>";
	if (!empty($users)) {
		foreach ($users as $user) {
			/*
			//Get country flag.
			$ip=$user['ipAddress'];
			$countryCode = $this->objIpToCountry->getCountryIP($ip);
	        $country = $this->objIpToCountry->getCountryNameByIp($ip);
			$flag = $this->objIpToCountry->getCountryFlagByIp($ip);
	        // Build the flag image.
			$flag='<img src="'.$flag.'" alt="'.$country.'" title="'.$country.'" >';
	        // Display the flag.
			echo $flag."&nbsp;";
			*/
	        // Display the user's name.
	        echo "<b>" . stripslashes($objUser->fullname($user['userId'])) ."</b>&nbsp;";
	        // Display the IM button.
			echo "<a href=\"#\" onclick=\"javascript:window.open('". 
				$this->uri(array(
			    	'module'=>'instantmessaging',
					'action'=>'sendMessage',
					'recipientId'=>$user['userid']
				))	
				."', 'IMSend', 'width=350, height=150, scrollbars=1');\">";
			$icon->setIcon('im');
			$icon->alt = "Instant Messaging";
	        $icon->extra = ' align="absmiddle"';
			echo $icon->show();
			echo "</a>";
	        echo '<br/>';
		}
	}
    if (false && $temporary) {
    	// Display a list of courses.
        echo "<h3>".ucfirst($objLanguage->code2Txt('mod_instantmessaging_contexts',array('contexts'=>'')))."</h3>";
        //$objDbContext =& $this->getObject('dbcontext','context');
        //$contexts = $objDbContext->getListOfContext();
		$objManageGroups =& $this->getObject('managegroups','contextgroups');
		$contexts = $objManageGroups->userContexts($this->objUser->userId());
		if (!empty($contexts)) {
	        foreach ($contexts as $context) {
	            // Display the user's name.
	            echo "<b>" . $context['title'] ."</b>&nbsp;";
	            // Display the IM button.
	    		echo "<a href=\"#\" onclick=\"javascript:window.open('". 
	    			$this->uri(array(
	    		    	'module'=>'instantmessaging',
	    				'action'=>'sendMessage',
	    				'recipientId'=>$context['contextCode'],
	                    'recipientType'=>'context'
	    			))	
	    			."', 'IMSend', 'width=350, height=200, scrollbars=1');\">";
	    		$icon->setIcon('im');
	    		$icon->alt = "Instant Messaging";
	            $icon->extra = ' align="absmiddle"';
	    		echo $icon->show();
	    		echo "</a>";
	            echo '<br/>';
	        }
		}
    }
?>
</div>
<div id="instantmessaging_footer">
<?php
    /*
    echo "<a href=\"".
    $this->uri(array(
      	'module'=>'instantmessaging',
    'action'=>'icq'
    )) 
    ."\">ICQ</a>";
    */
    // Display the enbale/disable IM button.
	if (!isset($_SESSION['pageSuppressIM'])) {
		echo "<a href=\"".
			$this->uri(array(
		    	'module'=>'instantmessaging',
				'action'=>'disable'
			)) 
			."\">";
		$icon = $this->getObject('geticon','htmlelements');
		$icon->setIcon('online');
		$icon->alt = "IM is Enabled";
		$icon->extra = ' align="absmiddle"';
		echo $icon->show();
		echo "</a> &nbsp;";
	}
	else {
		echo "<a href=\"".
			$this->uri(array(
		    	'module'=>'instantmessaging',
				'action'=>'enable'
			)) 
			."\">";
		$icon = $this->getObject('geticon','htmlelements');
		$icon->setIcon('offline');
		$icon->alt = "IM is Disabled";
		$icon->extra = ' align="absmiddle"';
		echo $icon->show();
		echo "</a> &nbsp; ";
	}
    // Display the close window button.    
    $icon->setIcon('close');
	$icon->alt=$this->objLanguage->languageText("im_closewindow");
    $icon->align = "absmiddle";
	echo '<a href="javascript:;" onClick="window.close();">'.$icon->show().'</a> &nbsp;';
	// Display the options link
	$objPopup =& $this->newObject('windowpop', 'htmlelements');
	$objPopup->set('window_name','IMOPTIONS');
	$objPopup->set('location', $this->uri(array('action'=>'options'), 'instantmessaging'));	
	$imIcon =& $this->getObject('geticon', 'htmlelements');
	$imIcon->setIcon('options');
	$imIcon->alt = $this->objLanguage->languageText('mod_instantmessaging_options');
	$objPopup->set('linktext', $imIcon->show());
	$objPopup->set('width', '500');
	$objPopup->set('height', '150');
	$objPopup->set('left', '400');
	$objPopup->set('top', '300');
	echo $objPopup->show(); 
?>
</div>