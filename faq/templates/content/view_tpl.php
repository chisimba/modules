<?php
?>
<!--<div style="padding:10px;">-->
<?php
    // Load classes.
	$this->loadHTMLElement("form");
	$this->loadHTMLElement("textinput");
	$this->loadHTMLElement("dropdown");
	$this->loadHTMLElement("button");
	$this->loadHTMLElement("link");
    $this->loadHTMLElement("hiddeninput");
    $this->loadHTMLElement("label");
//
    //Use to check for admin user:
    $isAdmin = $this->objUser->isAdmin();

    //Use to check for lecturer in context:
    $isLecturer = false;
    if($contextId != 'root'){
        $userPKId=$this->objUser->PKId($this->objUser->userId());
        $objGroups=$this->getObject('groupAdminModel','groupadmin');
        $groupid=$objGroups->getLeafId(array($contextId,'Lecturers'));
        if($objGroups->isGroupMember( $userPKId, $groupid )){
            $isLecturer = true;
        }
    }
//
    // Display error string if neccessary.
	if ($error != "") {
		echo "<span class=\"error\">";
		echo $error;
		echo "</span>";
	    echo "<br/>";
	}

    // Add an entry if not displaying "All Categories".
//	if ($categoryId != "All Categories") {
  //      if (sAdmin || $isLecturer) {
            // Add an entry.
    		$addLink = "<a href=\"" .
    	               $this->uri(array(
    		    		'module'=>'faq',
    		   			'action'=>'add',
    					'category'=>$categoryId
    		))
    		. "\">";
    		$icon = $this->getObject('geticon','htmlelements');
    		$icon->setIcon('add');
    		$icon->alt = "Add";
    		$icon->align=false;
    		$addLink .= $icon->show();
    		//echo "&nbsp;".$objLanguage->languageText("faq_addnewentry");
    		$addLink .= "</a>";
/*
        } else {
            $addLink = NULL;
        }
	} else {
        $addLink = NULL;
    }
*/
	echo "<h1>" .
		$objLanguage->languageText("phrase_faq") .
		//" : " .
		//$contextTitle .
        ' '.$addLink.
		"</h1>";

	// Category Form.
	$form = new form("category", $this->uri(array('module'=>'faq','action'=>'changeCategory')));
    $form->method = 'GET';
	$form->setDisplayType(3);
    $moduleHiddenInput = new hiddeninput('module', 'faq');
    $form->addToForm($moduleHiddenInput->show());
    $actionHiddenInput = new hiddeninput('action', 'changeCategory');
    $form->addToForm($actionHiddenInput->show());
    $label = new label($objLanguage->languageText("faq_category","faq") . " : ", 'input_category');
	$form->addToForm($label->show());
	$dropdown = new dropdown('category');
	$dropdown->addOption("All Categories","All Categories");
	foreach ($categories as $item) {
		$dropdown->addOption($item["id"],$item["categoryid"]);
	}
	$dropdown->setSelected($categoryId);
	$form->addToForm($dropdown);
	$form->addToForm("&nbsp;");
	$button = new button("submit", $objLanguage->languageText("word_go"));
	$button->setToSubmit();
	$form->addToForm($button);
	echo $form->show();
	echo "<br/>";

	if (!empty($list)) {
		// List the questions as href links to link to the main body of the FAQ.
		$index = 1;
	    // show using an ordered list
	    echo '<ol>';
	  	foreach ($list as $element) {
			echo "<li><a href=\"#".$index."\">";
			echo /*$index . " : " . */ nl2br($element["question"]);
			echo "</a></li>";
			$index++;
		}
	    echo '</ol>';
		echo "<br/>";
	}

    // List the questions and answers.
	$index = 1;
	$found = false;
  	foreach ($list as $element) {
        // Anchor tag for link to top of page.
		echo "<a id=\"".$index."\"></a>";
		$found = true;
?>
		<!--<div style="background-color: #008080; padding:5px;">-->
		<!--<div style="background-color: #000080; padding:5px;">-->
        <div class="wrapperDarkBkg">
<?php
		echo "<b>" . $index . " : " . "</b>" . nl2br($element["question"]);
?>
		<!--<div style="background-color: #FFFFFF; padding:5px;">-->
        <div class="wrapperLightBkg">
<?php
	  	echo "<p>";
		echo "<b>" . "</b>" . nl2br($element["answer"]);
	  	echo "</p>";
		//echo $objLanguage->languageText("faq_postedby") . " : " . $objUser->fullname($element["userId"]) . "&nbsp;" . $element["dateLastUpdated"] . "<br/>";
		echo "&nbsp;";
        if ($isAdmin || $isLecturer) {
            // Edit an entry.
    		$icon = $this->getObject('geticon','htmlelements');
    		$icon->setIcon('edit');
    		$icon->alt = "Edit";
    		$icon->align=false;
    		echo "<a href=\"" .
                    $this->uri(array(
    		    		'module'=>'faq',
    		   			'action'=>'edit',
    					'category'=>$categoryId,
    					'id' => $element["id"]
    		))
    		. "\">".$icon->show()."</a>";
    		echo "&nbsp;";
            // Delete an entry.
            $objConfirm=&$this->newObject('confirm','utilities');
    		$icon = $this->getObject('geticon','htmlelements');
    		$icon->setIcon('delete');
    		$icon->alt = "Delete";
    		$icon->align=false;
            $objConfirm->setConfirm(
                $icon->show(),
            	$this->uri(array(
            	    'module'=>'faq',
            		'action'=>'deleteConfirm',
            		'category'=>$categoryId,
            		'id'=>$element["id"]
            	)),
                $objLanguage->languageText('faq_suredelete','faq'));
            echo $objConfirm->show();
        }
?>
		</div>
		</div>
		<!--</div>-->
<?php
        echo "<br/>";
        echo "<br/>";
		$index++;
  	}
    // If no entries then display message.
	if (!$found) {
		echo "<div class=\"noRecordsMessage\">" . $objLanguage->languageText("faq_noentries","faq") . "</div>";
	}

	if ($isAdmin || $isLecturer) {
		// Add an entry.
		echo "<a href=\"" .
			$this->uri(array(
				'module'=>'faq',
				'action'=>'add',
				'category'=>$categoryId
			))
		. "\">".$objLanguage->languageText("faq_addnewentry","faq")."</a>";
	}

    // Show link to manage categories and edit categories if user is admin or lecturer
    if ($isAdmin || $isLecturer) {
        // Create manage category link
        $manageCategoriesLink = new link ($this->uri(NULL, 'faqadmin'));
        $manageCategoriesLink->link = $objLanguage->languageText("faq_managecategories","faq");
        // Create edit category link
        //$editCategoryLink = new link ($this->uri(array('action'=>'edit','id'=>$categoryId), 'faqadmin'));
    	//$editCategoryLink->link = 'Edit Category';
        if($categoryId != 'All Categories'){
       		echo '<p>'.$manageCategoriesLink->show()./*"&nbsp;"."<b>".'/'."</b>"."&nbsp;".$editCategoryLink->show().*/'</p>';
        }
		else {
        	echo '<p>'.$manageCategoriesLink->show().'</p>';
        }
    } 
?>
