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
$h3->str = ' Section Manager  '.$objIcon->getAddIcon($this->uri(array('action' => 'addsection')));
//counter for records
$cnt = 1;
//get the pages
$arrSections = $this->_objSections->getRootNodes();

//Get cms type
$cmsType = 'treeMenu';

//setup the table headings
$table->startHeaderRow();
$table->addHeaderCell('#');
$table->addHeaderCell('Section Name');
$table->addHeaderCell('Published');
//$table->addHeaderCell('Reorder');
//$table->addHeaderCell('Order');
$table->addHeaderCell('Access');
$table->addHeaderCell('Section ID');
//$table->addHeaderCell('Order');
$table->addHeaderCell('&nbsp;');

$table->endHeaderRow();   

$rowcount = 0;

//setup the tables rows  and loop though the records
foreach($arrSections as $section)
{
  $link->link = $section['title'];
  //edit link
	$link->href = $this->uri(array('action' => 'viewsection', 'id' => $section['id']));

	$oddOrEven = ($rowcount == 0) ? "even" : "odd";
	
    $tableRow = array();
	  $tableRow[]=$cnt++;
    $tableRow[]=$link->show();
    
    //publish
    $link->href = $this->uri(array('action' => 'sectionpublish', 'id' => $section['id']));
    $link->link = $this->_objUtils->getCheckIcon($section['published']);
    
    $tableRow[]=$link->show();
   // $table->addCell('up down');
    //$table->addCell($section['ordering']);
	$tableRow[]=$this->_objUtils->getAccess($section['access']);
	$tableRow[]=$section['id'];
	$tableRow[]=$this->_objSections->getOrderingLink($section['id']);
	//$table->addCell($section['catid']);
	//$tableRow[]=$this->_objCategories->getCatCount($section['id']);
	//$table->addCell($section['created']);
	
	//delete link
	$objIcon->setIcon('delete');
	$link->href = $this->uri(array('action' => 'sectiondelete', 'id' => $section['id']));
    $link->link = $objIcon->show();
	$tableRow[] = $link->show();
  	$table->addRow($tableRow, $oddOrEven);
	$rowcount = ($rowcount == 0) ? 1 : 0;
	
	
}


//print out the page
print $h3->show();
print $table->show();


?>
