<?php
/**
 * This template will show all content together with its sections and categories
 */

//initiate objects
$table = & $this->newObject('htmltable', 'htmlelements');
$h3 = &$this->newObject('htmlheading', 'htmlelements');
$objIcon = & $this->newObject('geticon', 'htmlelements');
$link = & $this->newObject('link', 'htmlelements');

//create a heading 
$h3->str = ' Content Items Manager '.$objIcon->getAddIcon($this->uri(array('action' => 'addcontent')));;
//counter for records
$cnt = 1;
//get the pages
$arrPages = $this->_objContent->getContentPages();
//


//setup the table headings
$table->startHeaderRow();
$table->addHeaderCell('#');
$table->addHeaderCell('Title');
$table->addHeaderCell('Front Page');
$table->addHeaderCell('Published');
//$table->addHeaderCell('Reorder');
//$table->addHeaderCell('Order');
$table->addHeaderCell('Access');
$table->addHeaderCell('Section');
//$table->addHeaderCell('Category');
$table->addHeaderCell('Author');
$table->addHeaderCell('Date');
$table->endHeaderRow();   

$rowcount = 0;

//setup the tables rows  and loop though the records
foreach($arrPages as $page)
{
	 $oddOrEven = ($rowcount == 0) ? "odd" : "even";
	//$table->startRow();
    $tableRow = array();
	$tableRow[]=$cnt++;
    
    
    
    $link->link = $page['title'];
	$link->href = $this->uri(array('action' => 'addcontent', 'mode' => 'edit', 'id' => $page['id']));
    
	$tableRow[]=$link->show();
	$tableRow[]=$this->_objUtils->getCheckIcon($this->_objFrontPage->isFrontPage($page['id']), FALSE);
	
	//the publish link
	$link->href = $this->uri(array('action' => 'contentpublish', 'id' => $page['id']));
    $link->link = $this->_objUtils->getCheckIcon($page['published']);
    
    $tableRow[]= $link->show(); //$this->_objUtils->getCheckIcon($page['published'], TRUE);
  //  $table->addCell('up down');
    //$table->addCell($page['ordering']);
	$tableRow[]=$this->_objUtils->getAccess($page['access']);
	$tableRow[]=$this->_objSections->getMenuText($page['sectionid']);
	//$tableRow[]=$this->_objCategories->getMenuText($page['catid']);
	$tableRow[]=$this->_objUser->fullname($page['created_by']);
	$tableRow[]=$this->_objUtils->formatShortDate($page['created']);

  	//$table->endRow();
  	$table->addRow($tableRow, $oddOrEven);
	 $rowcount = ($rowcount == 0) ? 1 : 0;
	
}


//print out the page
print $h3->show();
print $table->show();


?>