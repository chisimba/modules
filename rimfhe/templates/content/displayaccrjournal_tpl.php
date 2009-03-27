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
$objIcon->alt = 'Add New Journal Articles';
$objIcon->title = 'Add Journal Articles';

$link = new link($this->uri(array('action'=>'DOE Accredoted Journal Articles')));
$link->link = $objIcon->show();

$addlink = new link($this->uri(array('action'=>'DOE Accredoted Journal Articles')));
$addlink->link = 'Add New Journal Articles';

/*
 *End New Stuf
 */

$table = new htmltable();
$table->cellspacing = '2';
$table->cellpadding = '5';

//setup the table headings
$table->startHeaderRow();

$h3 = $this->getObject('htmlheading', 'htmlelements');
$objLayer = $this->newObject('layer', 'htmlelements');

$h3->str =$this->objLanguage->languageText('mod_rimfhe_pgheadingdisplayjournal', 'rimfhe');

$objLayer->str = $h3->show();
$objLayer->border = '; float:left; align: left; margin:0px; padding:0px;';
$header = $objLayer->show();

$display = '<p>'.$header.'&nbsp;&nbsp;&nbsp; '.$link->show().'</p><hr />';

//Show Header
echo $display;

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

$rowcount = 0;

//setup the tables rows  and loop though the records
if ( count($arrJournal) > 0) {	
	
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
    echo  '<div class="noRecordsMessage">'.$objLanguage->languageText('mod_rimfhe_norecord', 'rimfhe').'</div>';
	
}
echo $table->show();
echo '<p>'.'&nbsp;'.'</p>';
echo '<p>'.$addlink->show().'</p>';
echo '<p>'.'&nbsp;'.'</p>';
?>
