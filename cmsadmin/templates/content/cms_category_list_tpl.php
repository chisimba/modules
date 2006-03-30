<?php
/**
 * This template will list all the sections 
 */

//initiate objects
$table = & $this->newObject('htmltable', 'htmlelements');
$h3 = &$this->newObject('htmlheading', 'htmlelements');
$link = & $this->newObject('link', 'htmlelements');
$objIcon = & $this->newObject('geticon', 'htmlelements');


//create a heading 
$h3->str = ' Category Manager  '.$objIcon->getAddIcon($this->uri(array('action' => 'addcategory')));
//counter for records
$cnt = 1;
//get the pages
$arrCategories = $this->_objCategories->getCategories();
//


//setup the table headings
$table->startHeaderRow();
$table->addHeaderCell('#');
$table->addHeaderCell('Category Name');
$table->addHeaderCell('Published');
$table->addHeaderCell('Order');
$table->addHeaderCell('Access');
$table->addHeaderCell('Section');
$table->addHeaderCell('Category ID');
$table->addHeaderCell('#Active');

$table->endHeaderRow();   

//setup the tables rows  and loop though the records
foreach($arrCategories as $arrCategory)
{
	$link->link = $arrCategory['title'];
	$link->href = $this->uri(array('action' => 'addsection', 'mode' => 'edit', 'id' => $arrCategory['id']));
	
	$table->startRow();
    $table->addCell($cnt++);
    $table->addCell($link->show());
    $table->addCell($arrCategory['published']);
    $table->addCell($arrCategory['ordering']);
	$table->addCell($this->_objUtils->getAccess($arrCategory['access']));
	
	$link->link = $this->_objSections->getMenuText($arrCategory['sectionid']);
	$link->href = $this->uri(array('action' => 'addsection', 'mode' => 'edit', 'id' => $arrCategory['sectionid']));
	
	$table->addCell($link->show());
	$table->addCell($arrCategory['id']);
	//$table->addCell($this->_objCategories->getCatCount($section['id']));
	//$table->addCell($section['created']);

  	$table->endRow();
	
	
}


//print out the page
print $h3->show();
print $table->show();


?>