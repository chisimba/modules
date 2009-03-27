<?php
// Thi template displays the registered staff members

//Load HTMl Objet Classes

$objH = $this->newObject('htmlheading', 'htmlelements');
$link =  $this->newObject('link', 'htmlelements');
$objIcon =  $this->newObject('geticon', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$objLayer =$this->newObject('layer','htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('htmltable', 'htmlelements');
$objLayer = $this->newObject('layer', 'htmlelements');

$authortable =  $this->newObject('htmltable', 'htmlelements');

/*****
 *New Stuff Added
 */
$objIcon = $this->newObject('geticon', 'htmlelements');

$objIcon->setIcon('edit');
$objIcon->alt = 'Edit';
$objIcon->title = 'Edit';
$editIcon = $objIcon->show();

$objIcon->setIcon('add');
$objIcon->align = 'top';
$objIcon->alt = 'Add New Graduating Masters Student';
$objIcon->title = 'Add New Graduating Masters Student';

$link = new link($this->uri(array('action'=>'Graduating Masters Student')));
$link->link = $objIcon->show();

$addlink = new link($this->uri(array('action'=>'Graduating Masters Student')));
$addlink->link = 'Add New Graduating Masters Student';

//link to Graduating Masters Students Summary Page
$objMastersStudSummary = new link($this->uri(array('action'=>'Graduating Masters Students Summary')));
$objMastersStudSummary->link='View Masters Students Output Summary';

/*
 *End New Stuf
 */

	
$table = new htmltable();
$table->cellspacing = '2';
$table->cellpadding = '5';

//setup the table headings

$h3 = $this->getObject('htmlheading', 'htmlelements');
$objLayer = $this->newObject('layer', 'htmlelements');

$h3->str =$this->objLanguage->languageText('mod_rimfhe_', 'rimfhe', 'Graduating Masters Students Information');

$objLayer->str = $h3->show();
$objLayer->border = '; float:left; align: left; margin:0px; padding:0px;';
$header = $objLayer->show();

$display = '<p>'.$header.'&nbsp;&nbsp;&nbsp; '.$link->show().'</p><hr />';

//Show Header
echo $display;

//Set up fields heading
$table->startHeaderRow();
$table->addHeaderCell($this->objLanguage->languageText('word_surname', 'system'));
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_initials', 'rimfhe'));
$table->addHeaderCell($this->objLanguage->languageText('phrase_firstname', 'system'));	
$table->addHeaderCell($this->objLanguage->languageText('word_gender', 'system', 'Gender'));
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_studentnumber', 'rimfhe'));
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_department', 'rimfhe'));
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_faculty', 'rimfhe'));
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_thesistitle', 'rimfhe'));
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_supervisor', 'rimfhe'));
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_degree', 'rimfhe'));	
$table->endHeaderRow();

$rowcount = 0;

//setup the tables rows  and loop though the records
if ( count($arrDisplayMasters) > 0) {	

	foreach($arrDisplayMasters as $mastersstudents) {

	 //Set odd even row colour
	$oddOrEven = ($rowcount == 0) ? "even" : "odd";
	$tableRow = array();
	
	$tableRow[] = $mastersstudents['surname'];
	$tableRow[] = $mastersstudents['initials'];
	$tableRow[] = $mastersstudents['firstname'];	
	$tableRow[] = $mastersstudents['gender'];
	$tableRow[] = $mastersstudents['regnumber'];
	$tableRow[] = $mastersstudents['deptschoool'];
	$tableRow[] = $mastersstudents['faculty'];
	$tableRow[] = $mastersstudents['thesistitle'];
	$tableRow[] = $mastersstudents['supervisorname'];
	$tableRow[] = $mastersstudents['degree'];
		
	$table->addRow($tableRow, $oddOrEven);
	
	$rowcount = ($rowcount == 0) ? 1 : 0;
	}	
}
else{
    echo  '<div class="noRecordsMessage">'.$objLanguage->languageText('mod_rimfhe_norecord', 'rimfhe', 'No record has been entered').'</div>';
	
}
 echo $table->show();
echo '<p>'.'&nbsp;'.'</p>';
echo '<p>'.$addlink->show().'</p>';
echo '<p>'.$objMastersStudSummary->show().'</p>';
?>
