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

$table = new htmltable();
$table->cellspacing = '2';
$table->cellpadding = '5';

//setup the table headings


$h3 = $this->getObject('htmlheading', 'htmlelements');
$objLayer = $this->newObject('layer', 'htmlelements');

$h3->str = $objIcon->show().'&nbsp;'. $this->objLanguage->languageText('mod_rimfhe_', 'rimfhe', 'Graduating Masters Students Information');

$objLayer->str = $h3->show();
$objLayer->border = '; float:left; align: left; margin:0px; padding:0px;';
$header = $objLayer->show();

$display = '<p>'.$header.'</p><hr />';

//Show Header
echo $display;

$rowcount = 0;

//setup the tables rows  and loop though the records
if ( count($arrDisplayMasters) > 0) {	
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
?>
