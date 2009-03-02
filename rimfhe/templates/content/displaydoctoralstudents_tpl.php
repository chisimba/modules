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

$h3->str = $objIcon->show().'&nbsp;'. $this->objLanguage->languageText('word_text', 'rimfhe', 'Graduating Doctoral Student Information');

$objLayer->str = $h3->show();
$objLayer->border = '; float:left; align: left; margin:0px; padding:0px;';
$header = $objLayer->show();

$display = '<p>'.$header.$headShow.'</p><hr />';

//Show Header
echo $display;

$rowcount = 0;

//setup the tables rows  and loop though the records
if ( count($arrDisplayDoctoral) > 0) {	
//Set up fields heading
	$table->startHeaderRow();
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Surname'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Initials.
 Number'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'First Names'));	
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Gender'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Student<br /> Registration<br />Number'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Department'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Faculty'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', ' Title of Thesis'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Name of Supervisors'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Degree'));	
	$table->endHeaderRow();
	
	foreach($arrDisplayDoctoral as $doctoralstudents) {
	 //Set odd even row colour
	$oddOrEven = ($rowcount == 0) ? "even" : "odd";
	$tableRow = array();
	
	$tableRow[] = $doctoralstudents['surname'];
	$tableRow[] = $doctoralstudents['initials'];
	$tableRow[] = $doctoralstudents['firstname'];	
	$tableRow[] = $doctoralstudents['gender'];
	$tableRow[] = $doctoralstudents['regnumber'];
	$tableRow[] = $doctoralstudents['deptschoool'];
	$tableRow[] = $doctoralstudents['faculty'];
	$tableRow[] = $doctoralstudents['thesistitle'];
	$tableRow[] = $doctoralstudents['supervisorname'];
	$tableRow[] = $doctoralstudents['degree'];
		
	$table->addRow($tableRow, $oddOrEven);
	
	$rowcount = ($rowcount == 0) ? 1 : 0;
	}	
}
else{
    echo  '<div class="noRecordsMessage">'.$objLanguage->languageText('mod_rimfhe_norecord', 'rimfhe', 'No record has been entered').'</div>';
	
}
 echo $table->show();

?>


