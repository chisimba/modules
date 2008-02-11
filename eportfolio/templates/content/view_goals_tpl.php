<?php
	//Language Items	
	$notestsLabel = $this->objLanguage->languageText('mod_eportfolio_norecords', 'eportfolio');
	$linkAdd = '';
	    // Show the add link
	    $iconAdd = $this->getObject('geticon','htmlelements');
	    $iconAdd->setIcon('add');
	    $iconAdd->alt = $objLanguage->languageText("mod_eportfolio_add", 'eportfolio');
	    $iconAdd->align=false;
	    $objLink =& $this->getObject('link','htmlelements');
	    $objLink->link($this->uri(array(
	                'module'=>'eportfolio',
	            'action'=>'add_goals'
	        )));

         $objLink->link =  $iconAdd->show();
	     $linkAdd = $objLink->show();
    // Show the heading
    $objHeading =& $this->getObject('htmlheading','htmlelements');
    $objHeading->type=1;
    $objHeading->str = $objUser->getSurname ().$objLanguage->languageText("mod_eportfolio_goalList", 'eportfolio').'&nbsp;&nbsp;&nbsp;'.$linkAdd;
    echo $objHeading->show();

	$goalsList = $this->objDbGoalsList->getByItem($userId);
    echo "<br/>";
    // Create a table object
    $table =& $this->newObject("htmltable","htmlelements");
    $table->border = 0;
    $table->cellspacing='12';
    $table->cellpadding='12';
    $table->width = "100%";
    // Add the table heading.
    $table->startRow();
    $table->addHeaderCell("<b>".$objLanguage->languageText("mod_eportfolio_Goals",'eportfolio')."</b>");
    $table->endRow();
    
    // Step through the list of addresses.
    $class = 'even';
    if (!empty($goalsList)) {
    	$i = 0;
	echo"<ol type='1'>";
    foreach ($goalsList as $item) {
/*if (!empty(item['parent']) then */
       $class = ($class == (($i++%2) == 0)) ? 'even':'odd';

    // Display each field for activities
        $table->startRow();
	//echo"<li>";
        $table->addCell("<li>".$item['shortdescription']."</li>", "", NULL, NULL, $class, '');
        
        // Show the edit link
        $iconEdit = $this->getObject('geticon','htmlelements');
        $iconEdit->setIcon('edit');
        $iconEdit->alt = $objLanguage->languageText("mod_eportfolio_edit",'eportfolio');
        $iconEdit->align=false;
        $objLink =& $this->getObject("link","htmlelements");
        $objLink->link($this->uri(array(
                    'module'=>'eportfolio',
                'action'=>'editgoals',
                'id' => $item["id"]
            )));
            //if( $this->isValid( 'edit' ))
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
                    'action'=>'deletegoals',
                    'id'=>$item["id"]
                )),
            $objLanguage->languageText('mod_eportfolio_suredelete','eportfolio'));
			
            //echo $objConfirm->show();
        $table->addCell($linkEdit. $objConfirm->show(), "", NULL, NULL, $class, '');
//echo"<li>";
        $table->endRow();



    }
	unset($item);
	echo"</ol>";
   
} else {
    $table->startRow();
    $table->addCell($notestsLabel, '', '', '', 'noRecordsMessage', 'colspan="5"');
    $table->endRow();
}
    	echo $table->show();
	$mainlink = new link($this->uri(array('module'=>'eportfolio','action'=>'main')));
	$mainlink->link = 'ePortfolio home';
	echo '<br clear="left" />'.$mainlink->show();
?>
