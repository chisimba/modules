<?php	
$this->loadClass('link', 'htmlelements');
	

	//Show countries visitors came from
	$this->objH =& $this->getObject('htmlheading', 'htmlelements');
	$this->objH->type=3; //Heading <h3>
	//$this->objH->str=($objLanguage->code2Txt("mod_homepage_linkstohomepages"));
    $this->objH->str=$objLanguage->languageText('mod_homepage_listofallhomepages', 'homepage');
	echo $this->objH->show();
	
	//Showing the links to homepages of users that have homepages
    $homePageListTable = $this->getObject('htmltable', 'htmlelements');
    
    $homePageListTable->startHeaderRow();
    $homePageListTable->cellpadding = '5';
    $homePageListTable->cellspacing = '1';
    $homePageListTable->addHeaderCell($objLanguage->languageText('mod_homepage_wordhomepage', 'homepage'));
    $homePageListTable->addHeaderCell($objLanguage->languageText('word_hits'));
    
    $homePageListTable->endHeaderRow();
    
    foreach ($listHomePages as $homePage)
    {
        $homePageListTable->startRow();
        $homepageLink = new link($this->uri(array('action'=>'viewhomepage', 'userId'=>$homePage['userid'])));
        $homepageLink->link = $homePage['firstname'].' '.$homePage['surname'];
        
        $homePageListTable->addCell($homepageLink->show());
        $homePageListTable->addCell($homePage['visitors']);
        
        $homePageListTable->endRow();
    }
    
    echo $homePageListTable->show();
    
    $returnLink = new link ($this->uri(NULL));
    $returnLink->link = $objLanguage->languageText('mod_homepage_returntohomepages', 'homepage');
    echo '<p>'.$returnLink->show().'</p>';
    
	
	

?>