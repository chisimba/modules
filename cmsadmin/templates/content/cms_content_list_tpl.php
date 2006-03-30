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
$table->addHeaderCell('Reorder');
$table->addHeaderCell('Order');
$table->addHeaderCell('Access');
$table->addHeaderCell('Section');
$table->addHeaderCell('Category');
$table->addHeaderCell('Author');
$table->addHeaderCell('Date');
$table->endHeaderRow();   

//setup the tables rows  and loop though the records
foreach($arrPages as $page)
{
	
	$table->startRow();
    $table->addCell($cnt++);
    
    
    
    $link->link = $page['title'];
	$link->href = $this->uri(array('action' => 'addcontent', 'mode' => 'edit', 'id' => $page['id']));
    
	$table->addCell($link->show());
	$table->addCell($this->_objFrontPage->isFrontPage($page['id']));
    $table->addCell($page['published']);
    $table->addCell('up down');
    $table->addCell($page['ordering']);
	$table->addCell($this->_objUtils->getAccess($page['access']));
	$table->addCell($this->_objSections->getMenuText($page['sectionid']));
	$table->addCell($this->_objCategories->getMenuText($page['catid']));
	$table->addCell($page['created_by']);
	$table->addCell($page['created']);

  	$table->endRow();
	
	
}


//print out the page
print $h3->show();
print $table->show();


?>