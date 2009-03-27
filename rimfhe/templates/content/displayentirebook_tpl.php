<?php

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
$objIcon->alt = 'Add New Book/Monogragh';
$objIcon->title = 'Add New Book/Monogragh';

$link = new link($this->uri(array('action'=>'Entire Book/Monogragh')));
$link->link = $objIcon->show();

$addlink = new link($this->uri(array('action'=>'Entire Book/Monogragh')));
$addlink->link = 'Add New Book/Monogragh';

/*
 *End New Stuf
 */

$table = new htmltable();
$table->cellspacing = '2';
$table->cellpadding = '5';

//setup the table headings


$h3 = $this->getObject('htmlheading', 'htmlelements');
$objLayer = $this->newObject('layer', 'htmlelements');

$h3->str = $this->objLanguage->languageText('mod_rimfhe_pgheadingdisplayentirebook', 'rimfhe');

$objLayer->str = $h3->show();
$objLayer->border = '; float:left; align: left; margin:0px; padding:0px;';
$header = $objLayer->show();

$display = '<p>'.$header.'&nbsp;&nbsp;&nbsp; '.$link->show().'</p><hr />';

//Show Header
echo $display;

//Set up fields heading
$table->startHeaderRow();
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_title', 'rimfhe'));
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_isbn', 'rimfhe'));
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_publisher', 'rimfhe'));
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_authors', 'rimfhe'));
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_firstchapterpageno', 'rimfhe'));
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_lastchapterpageno', 'rimfhe'));
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_pagetotal', 'rimfhe'));
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_peer', 'rimfhe'));	
$table->endHeaderRow();

$rowcount = 0;

//setup the tables rows  and loop though the records
if ( count($arrDisplayBooks) > 0) {	
	
	foreach($arrDisplayBooks as $entirebook) {
	 //Set odd even row colour
	$oddOrEven = ($rowcount == 0) ? "even" : "odd";
	$tableRow = array();
	
	$tableRow[] = $entirebook['booktitle'];
	$tableRow[] = $entirebook['isbn'];
	$tableRow[] = $entirebook['publishinghouse'];
	$tableRow[] = $entirebook['authorname'];
	$tableRow[] = $entirebook['firstchapterpageno'];
	$tableRow[] = $entirebook['lastchapterpageno'];
	$tableRow[] = $entirebook['totalpages'];
	$tableRow[] = $entirebook['peerreviewed'];
		
	$table->addRow($tableRow, $oddOrEven);
	
	$rowcount = ($rowcount == 0) ? 1 : 0;
	}	
}
else{
    echo  '<div class="noRecordsMessage">'.$objLanguage->languageText('mod_rimfhe_norecord', 'rimfhe' ).'</div>';	
}

echo $table->show();
echo '<p>'.'&nbsp;'.'</p>';
echo '<p>'.$addlink->show().'</p>';
?>
