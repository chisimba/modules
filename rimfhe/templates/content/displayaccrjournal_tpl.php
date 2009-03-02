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
$table->startHeaderRow();

$h3 = $this->getObject('htmlheading', 'htmlelements');
$objLayer = $this->newObject('layer', 'htmlelements');

$h3->str = $objIcon->show().'&nbsp;'. $this->objLanguage->languageText('word_text', 'rimfhe', 'List of DOE Accredited Journal Articles');

$objLayer->str = $h3->show();
$objLayer->border = '; float:left; align: left; margin:0px; padding:0px;';
$header = $objLayer->show();

$display = '<p>'.$header.$headShow.'</p><hr />';

//Show Header
echo $display;

$rowcount = 0;

//setup the tables rows  and loop though the records
if ( count($arrJournal) > 0) {	
//Set up fields heading
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Name of Journal'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Categroy'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Title of Article'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Year of<br /> Publication'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Vlume'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'First Page #'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Last Page #'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Total Pages'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Authors'));
	$table->endHeaderRow();
	
	foreach($arrJournal as $journal) {
	 //Set odd even row colour
	$oddOrEven = ($rowcount == 0) ? "even" : "odd";
	$tableRow = array();
	
	$tableRow[] = $journal['journalname'];
	$tableRow[] = $journal['journalcategory'];
	$tableRow[] = $journal['articletitle'];
	$tableRow[] = $journal['publicationyear'];
	$tableRow[] = $journal['volume'];
	$tableRow[] = $journal['firstpageno'];
	$tableRow[] = $journal['lastpageno'];
	$tableRow[] = $journal['pagetotal'];
	$tableRow[] = $journal['authorname'];
	
	$table->addRow($tableRow, $oddOrEven);
	
	$rowcount = ($rowcount == 0) ? 1 : 0;
	 echo $title.'<br />';

	}	
}
else{
    echo  '<div class="noRecordsMessage">'.$objLanguage->languageText('mod_rimfhe_norecord', 'rimfhe', 'No record has been entered').'</div>';
	
}
 echo $table->show();

?>


