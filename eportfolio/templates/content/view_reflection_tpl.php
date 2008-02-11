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
	            'action'=>'add_reflection'
	        )));

         $objLink->link =  $iconAdd->show();
	     $linkAdd = $objLink->show();
    // Show the heading
    $objHeading =& $this->getObject('htmlheading','htmlelements');
    $objHeading->type=1;
    $objHeading->str =$objUser->getSurname ().$objLanguage->languageText("mod_eportfolio_reflectionList", 'eportfolio').'&nbsp;&nbsp;&nbsp;'.$linkAdd;
    echo $objHeading->show();

	$list = $this->objDbReflectionList->getByItem($userId);
    echo "<br/>";
    // Create a table object
    $table =& $this->newObject("htmltable","htmlelements");
    $table->border = 0;
    $table->cellspacing='12';
    $table->cellpadding='12';
    $table->width = "100%";
    // Add the table heading.
    $table->startRow();
    $table->addHeaderCell("<b>".$objLanguage->languageText("mod_eportfolio_rationaleTitle",'eportfolio')."</b>");
    $table->addHeaderCell("<b>".$objLanguage->languageText("mod_eportfolio_creationDate",'eportfolio')."</b>");
    $table->addHeaderCell("<b>".$objLanguage->languageText("mod_eportfolio_shortdescription",'eportfolio')."</b>");
    $table->endRow();
    
    // Step through the list of addresses.
    $class = 'even';
    if (!empty($list)) {
    	$i = 0;
    foreach ($list as $item) {
       $class = ($class == (($i++%2) == 0)) ? 'even':'odd';
    // Display each field for activities
        $table->startRow();
        $table->addCell($item['rationale'], "", NULL, NULL, $class, '');
        $table->addCell($this->objDate->formatDate($item['creation_date']), "", NULL, NULL, $class, '');
        $table->addCell($item['shortdescription'], "", NULL, NULL, $class, '');
        
        // Show the edit link
        $iconEdit = $this->getObject('geticon','htmlelements');
        $iconEdit->setIcon('edit');
        $iconEdit->alt = $objLanguage->languageText("mod_eportfolio_edit",'eportfolio');
        $iconEdit->align=false;
        $objLink =& $this->getObject("link","htmlelements");
        $objLink->link($this->uri(array(
                    'module'=>'eportfolio',
                'action'=>'editreflection',
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
                    'action'=>'deletereflection',
                    'id'=>$item["id"]
                )),
            $objLanguage->languageText('mod_eportfolio_suredelete','eportfolio'));
			
            //echo $objConfirm->show();
        $table->addCell($linkEdit. $objConfirm->show(), "", NULL, NULL, $class, '');
        $table->endRow();



    }
	unset($item);
   
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
