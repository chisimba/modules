<?php
/**
 * This template outputs the front page manager
 * for the cms module
 * 
 * 
 */

//initiate objects
$table = & $this->newObject('htmltable', 'htmlelements');
$h3 = &$this->newObject('htmlheading', 'htmlelements');
$link = & $this->newObject('link', 'htmlelements');
$objIcon = & $this->newObject('geticon', 'htmlelements');


//create a heading 
$h3->str = ' Front Page Manager  '.$objIcon->getAddIcon($this->uri(array('action' => 'addcontent' , 'frontpage' => 'true')));
//counter for records
$cnt = 1;
//get the pages
$arrCategories = $this->_objCategories->getCategories();
//


//setup the table headings
$table->startHeaderRow();
$table->addHeaderCell('#');
$table->addHeaderCell('Title');
$table->addHeaderCell('Published');
//$table->addHeaderCell('Order');
$table->addHeaderCell('Access');
$table->addHeaderCell('Section');
$table->addHeaderCell('Category');
//$table->addHeaderCell('#Active');

$table->endHeaderRow();   

$rowcount = 0;
//var_dump($files);
//setup the tables rows  and loop though the records
foreach($files as $file)
{
    $arrFile = $this->_objContent->getContentPage($file['content_id']);
	$link->link = $arrFile['title'];
	$link->href = $this->uri(array('action' => 'addcategory', 'mode' => 'edit', 'id' => $arrFile['id']));

	$oddOrEven = ($rowcount == 0) ? "even" : "odd";
	
    $tableRow = array();
    $tableRow[]=$cnt++;
    $tableRow[]=$link->show();
    $tableRow[]=$this->_objUtils->getCheckIcon($arrFile['published'], FALSE);
   // $table->addCell($arrCategory['ordering']);
	$tableRow[]=$this->_objUtils->getAccess($arrFile['access']);
	
	$link->link = $this->_objSections->getMenuText($arrFile['sectionid']);
	$link->href = $this->uri(array('action' => 'addsection', 'mode' => 'edit', 'id' => $arrFile['sectionid']));
	
	$tableRow[]=$link->show();
	$tableRow[]=$this->_objCategories->getMenuText($arrFile['catid']);
	//$table->addCell($this->_objCategories->getCatCount($section['id']));
	//$table->addCell($section['created']);

  	
	$table->addRow($tableRow, $oddOrEven);
	 $rowcount = ($rowcount == 0) ? 1 : 0;
	
}


//print out the page
print $h3->show();
print $table->show();
?>