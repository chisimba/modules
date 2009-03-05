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

$h3->str = $objIcon->show().'&nbsp;'. $this->objLanguage->languageText('word_text', 'rimfhe', 'Entire Books Details');

$objLayer->str = $h3->show();
$objLayer->border = '; float:left; align: left; margin:0px; padding:0px;';
$header = $objLayer->show();

$display = '<p>'.$header.$headShow.'</p><hr />';

//Show Header
echo $display;

$rowcount = 0;

//setup the tables rows  and loop though the records
if ( count($arrDisplayBooks) > 0) {	
//Set up fields heading
	$table->startHeaderRow();
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Title of Book/<br />Monograph'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'ISBN Number'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Publishing <br />House'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Authors'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'First Chapter<br /> Page #'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Last Chapter<br /> Page #'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Total Number <br /> of Pages'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Evidence of<br />Peer Review'));	
	$table->endHeaderRow();
	
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
    echo  '<div class="noRecordsMessage">'.$objLanguage->languageText('mod_rimfhe_norecord', 'rimfhe', 'No record has been entered').'</div>';
	
}
 echo $table->show();

?>


