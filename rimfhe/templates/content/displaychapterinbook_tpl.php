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
$objIcon->alt = 'Add New Chapter In a Book';
$objIcon->title = 'Add Chapter In a Book';

$link = new link($this->uri(array('action'=>'Chapter In a Book')));
$link->link = $objIcon->show();

$addlink = new link($this->uri(array('action'=>'Chapter In a Book')));
$addlink->link = 'Add New Chapter In a Book';

/*
 *End New Stuf
 */

$table = new htmltable();
$table->cellspacing = '2';
$table->cellpadding = '5';

//setup the table headings


$h3 = $this->getObject('htmlheading', 'htmlelements');
$objLayer = $this->newObject('layer', 'htmlelements');

$h3->str =$this->objLanguage->languageText('word_text', 'rimfhe', 'Chapter in a Books Details');

$objLayer->str = $h3->show();
$objLayer->border = '; float:left; align: left; margin:0px; padding:0px;';
$header = $objLayer->show();

$display = '<p>'.$header.'&nbsp;&nbsp;&nbsp; '.$link->show().'</p><hr />';

//Show Header
echo $display;

//Set up fields heading
$table->startHeaderRow();
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_booktitle2', 'rimfhe'));
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_isbn', 'rimfhe'));
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_editors','rimfhe'));	
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_publisher', 'rimfhe'));
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_chaptertitle', 'rimfhe'));
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_authors', 'rimfhe'));
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_chapterfirstpageno', 'rimfhe'));
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_chapterlastpageno', 'rimfhe'));
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
	$tableRow[] = $entirebook['bookeditors'];	
	$tableRow[] = $entirebook['publisher'];
	$tableRow[] = $entirebook['chaptertitle'];
	$tableRow[] = $entirebook['authorname'];
	$tableRow[] = $entirebook['chapterfirstpageno'];
	$tableRow[] = $entirebook['chapterlastpageno'];
	$tableRow[] = $entirebook['pagetotal'];
	$tableRow[] = $entirebook['peerreviewed'];
		
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
?>
