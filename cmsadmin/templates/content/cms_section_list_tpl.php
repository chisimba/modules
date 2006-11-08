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
$table->addHeaderCell('Access');
$table->addHeaderCell('Order');


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
  $tableRow[]=$this->_objUtils->getAccess($section['access']);
	$tableRow[]=$this->_objSections->getOrderingLink($section['id']);
	
	//delete link
	$objIcon->setIcon('delete');
	$link->href = $this->uri(array('action' => 'sectiondelete', 'id' => $section['id']));
  $link->link = $objIcon->show();
  //Create delete icon 
  $delArray = array('action' => 'deletesection', 'confirm'=>'yes', 'id'=>$section['id']);
  $deletephrase = $this->objLanguage->languageText('mod_cmsadmin_confirmdelsection', 'cmsadmin');
  $delIcon = $objIcon->getDeleteIconWithConfirm($section['id'], $delArray,'cmsadmin',$deletephrase);

  //edit icon
  $editIcon = $objIcon->getEditIcon($this->uri(array('action' => 'addsection', 'id' => $section['id'])));
	$tableRow[] = $editIcon.'&nbsp;'.$delIcon;
  $table->addRow($tableRow, $oddOrEven);
	$rowcount = ($rowcount == 0) ? 1 : 0;
	
	
}


//print out the page
print $h3->show();
print $table->show();


?>
