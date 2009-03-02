<?php
// Thi template displays the registered staff members

//Load HTMl Objet Classes
$table =  $this->newObject('htmltable', 'htmlelements');
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

$table = new htmltable();
$table->cellspacing = '2';
$table->cellpadding = '5';

$h3 = $this->getObject('htmlheading', 'htmlelements');
$objLayer = $this->newObject('layer', 'htmlelements');

$h3->str = $objIcon->show().'&nbsp;'. $this->objLanguage->languageText('word_text', 'rimfhe', 'Registererd Staff Members');

$objLayer->str = $h3->show();
$objLayer->border = '; float:left; align: left; margin:0px; padding:0px;';
$header = $objLayer->show();


$display = '<p>'.$header.$headShow.'</p><hr />';
//Show Header
echo $display;

$rowcount = 0;

//setup the tables rows  and loop though the records
if (count($arrDisplayStaff) > 0) {

	//setup the table headings
	$table->startHeaderRow();
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Serial #'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Surname'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Initials'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Names'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Title'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Rank'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Appointment'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Department'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Faculty'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Staff #'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Email'));
	$table->endHeaderRow();

	//Loop through $arrDisplayStaff and set data in rows
	foreach($arrDisplayStaff as $staffmember) {
	    //Set odd even row colour
	 $oddOrEven = ($rowcount == 0) ? "even" : "odd";

	//Setuo table rows
	$tableRow = array();
	$tableRow[] = $staffmember['puid'];
	$tableRow[] = $staffmember['surname'];
	$tableRow[] = $staffmember['initials'];
	$tableRow[] = $staffmember['firstname'];
	$tableRow[] = $staffmember['tiltle'];
	$tableRow[] = $staffmember['rank'];
	$tableRow[] = $staffmember['appointmenttype'];
	$tableRow[] = $staffmember['department'];
	$tableRow[] = $staffmember['faculty'];
	$tableRow[] = $staffmember['staffnumber'];
	$tableRow[] = $staffmember['email'];
	
	//add to table
	$table->addRow($tableRow, $oddOrEven);
	
	    $rowcount = ($rowcount == 0) ? 1 : 0;
	}
}
else{
	//When no data has been entered
    echo  '<div class="noRecordsMessage">'.$objLanguage->languageText('mod_rimfhe_norecord', 'rimfhe', 'No record has been entered').'</div>';
	
}
 echo $table->show();

?>
