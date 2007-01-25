<?php  
// Show the heading
$objHeading =& $this->getObject('htmlheading','htmlelements');
$objHeading->type=1;

// Show the add link
$objLink =& $this->getObject('link','htmlelements');
$objLink->link($this->uri(array(
 		'module'=>'faqadmin',
		'action'=>'add',
)));
$iconAdd = $this->getObject('geticon','htmlelements');
$iconAdd->setIcon('add');
$iconAdd->alt = $objLanguage->languageText("mod_faqadmin_addcategory", "faqadmin");
$iconAdd->align=false;
$objLink->link = $iconAdd->show();
 		
// Add the Icon to the heading
$objHeading->str =$objLanguage->languageText("mod_faqadmin_heading","faqadmin").' '.$objLink->show();
//$objHeading->str =$objLanguage->languageText("mod_faqadmin_heading")." : ".$contextTitle;
echo $objHeading->show();

// Create table for categories.
$objTable =& $this->newObject('htmltable','htmlelements');
$objTable->width='';
$objTable->cellpadding='5';
$objTable->cellspacing='2';
// Add the table header.
$objTable->startHeaderRow();
$objTable->addHeaderCell($objLanguage->languageText("mod_faqadmin_category","faqadmin"), 200);
$objTable->addHeaderCell($objLanguage->languageText("mod_faqadmin_noitems","faqadmin"), 200);
$objTable->addHeaderCell($objLanguage->languageText("mod_faqadmin_action","faqadmin"), 100);
$objTable->endHeaderRow();
$index = 0;
foreach ($categories as $item) {
    // Create the edit link.
	$objEditLink=&$this->getObject('link','htmlelements');
	$objEditLink->link($this->uri(array(
		'module'=>'faqadmin',
		'action'=>'edit',
		'id'=>$item['id']
	)));
    $iconEdit=&$this->getObject('geticon','htmlelements');
    $iconEdit->setIcon('edit');
    $iconEdit->alt=$objLanguage->languageText("word_edit");
    $iconEdit->align=false;
  	$objEditLink->link = $iconEdit->show();
    // Create the delete link.
	$objConfirm=&$this->newObject('confirm','utilities');
    $iconDelete=$this->getObject('geticon','htmlelements');
    $iconDelete->setIcon('delete');
    $iconDelete->alt=$objLanguage->languageText("word_delete");
    $iconDelete->align=false;
	$objConfirm->setConfirm(
    	$iconDelete->show(),
		$this->uri(array(
	    	'module'=>'faqadmin',
		  	'action'=>'deleteConfirm',
		  	'id'=>$item["id"]
		)),
        $objLanguage->languageText('phrase_suredelete')
    );
    // Add a row to the table.
   	/*
    // Create add icon                               
    $objAddLink = $this->getObject('link', 'htmlelements');
    $objAddLink->link($this->uri(array('action'=>'add','category'=>$item['id']), 'faq'));
    $objAddIcon =& $this->getObject('geticon', 'htmlelements');
    $objAddIcon->setIcon('add');
    $objAddIcon->alt = $this->objLanguage->languageText('faq_sayitadd');
    $objAddLink->link = $objAddIcon->show();
	*/
    // Count no items in category
    $this->objFaqEntries =& $this->getObject('dbfaqentries', 'faq');
    $list=$this->objFaqEntries->listAll($item['contextid'], $item['id']);
    $count=count($list);
    // Create link to category
    $categoryLink =& $this->getObject('link', 'htmlelements');
    $categoryLink->link($this->uri(array('action'=>'view','category'=>$item['id']), 'faq'));
    $categoryLink->link = $item['categoryid'];
    $categoryLink->title = $this->objLanguage->languageText('mod_faqadmin_viewcategory','faqadmin');
	// Create the actions
    $ncaction = /*$objAddLink->show();*/"";
    $action = /*$objAddLink->show()."&nbsp;".*/$objEditLink->show()."&nbsp;".$objConfirm->show();
    //echo $objEditLink->show();
    // This does a check so that the not categorised item can never be deleted.
    if ($item['categoryid'] == 'Not Categorised' && $item['userid'] == 'admin') {
     	$row = array($categoryLink->show().'&nbsp;'.'<b>'.'*'.'</b>', $count, $ncaction);
    } 
	else { 
    	$row = array($categoryLink->show(), $count, $action);
    }  
    $objTable->addRow($row, $index%2?'even':'odd');
    $index++;
}
// Show the table.
echo($objTable->show());

$addLink = new link($this->uri(array('action'=>'add')));
$addLink->link = $objLanguage->languageText('mod_faqadmin_addcategory','faqadmin');

//$returnToFaqLink = new link($this->uri(NULL, 'faq'));
//$returnToFaqLink->link = $objLanguage->languageText('mod_faqadmin_returntofaq');

echo '<p>'.$addLink->show()./*' / '.$returnToFaqLink->show().*/'</p>';
?>
