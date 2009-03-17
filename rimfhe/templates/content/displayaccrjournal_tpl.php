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

$h3->str = $objIcon->show().'&nbsp;'. $this->objLanguage->languageText('mod_rimfhe_pgheadingdisplayjournal', 'rimfhe');

$objLayer->str = $h3->show();
$objLayer->border = '; float:left; align: left; margin:0px; padding:0px;';
$header = $objLayer->show();

$display = '<p>'.$header.'</p><hr />';

//Show Header
echo $display;

$rowcount = 0;

//setup the tables rows  and loop though the records
if ( count($arrJournal) > 0) {	
//Set up fields heading
	$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_journalname', 'rimfhe'));
	$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_categorey', 'rimfhe'));
	$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_atitcletitle', 'rimfhe'));
	$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_year', 'rimfhe'));
	$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_volume', 'rimfhe'));
	$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_firstpage', 'rimfhe'));
	$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_lastpage', 'rimfhe'));
	$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_pagetotal', 'rimfhe'));
	$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_authors', 'rimfhe'));
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
	
	}	
}
else{
    echo  '<div class="noRecordsMessage">'.$objLanguage->languageText('mod_rimfhe_norecord', 'rimfhe', 'No record has been entered').'</div>';
	
}
 echo $table->show();

?>
