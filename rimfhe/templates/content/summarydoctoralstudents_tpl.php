<?php
// This template displays summary details for Graduating Doctoral Students

//Load HTMl Objet Classes

$objH = $this->newObject('htmlheading', 'htmlelements');
$link =  $this->newObject('link', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$objLayer =$this->newObject('layer','htmlelements');
$this->loadClass('htmltable', 'htmlelements');


//Define the for  Graduates by Department
$table = new htmltable();
$table->cellspacing = '2';
$table->cellpadding = '5';

//Define the for  Graduates by Faculty
$table2 = new htmltable();
$table2->cellspacing = '2';
$table2->cellpadding = '5';

//setup the table headings
$h3 = $this->getObject('htmlheading', 'htmlelements');
$objLayer = $this->newObject('layer', 'htmlelements');
$h3->str =$this->objLanguage->languageText('word_text', 'rimfhe', 'Graduating Doctoral Students Summary Page');

$objLayer->str = $h3->show();
$objLayer->border = '; float:left; align: left; margin:0px; padding:0px;';
$header = $objLayer->show();

$display = '<p>'.$header.'</p><hr />';

//Show Header
echo $display;

$rowcount = 0;


if ( count($arrDeptSummary) > 0) {	
//Description for $table 
	$tableHeader = $this->objLanguage->languageText('mod_doraclstudents_summary', 'rimfhe', 'Total number of Doctoral Students by Department');
	
//Set up fields heading
	$table->startHeaderRow();
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Deaprtment'));
	$table->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Number of graduate Doctoral Students'));
		
	$table->endHeaderRow();
	
	foreach($arrDeptSummary as $arrDeptSummaries) {
	 //Set odd even row colour
	$oddOrEven = ($rowcount == 0) ? "even" : "odd";
	$tableRow = array();
	
	$tableRow[] = $arrDeptSummaries['deptschoool'];
	$tableRow[] = $arrDeptSummaries['countthesis'];
			
	$table->addRow($tableRow, $oddOrEven);
	
	$rowcount = ($rowcount == 0) ? 1 : 0;
	}

	//Description for $table2 
	$table2Header = $this->objLanguage->languageText('mod_doraclstudents_summary', 'rimfhe', 'Total number of Doctoral Students by Faculty');
	//Set up fields heading for $table2
	$table2->startHeaderRow();
	$table2->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Faculty'));
	$table2->addHeaderCell($this->objLanguage->languageText('word_title', 'rifhme', 'Number of graduate Doctoral Students'));
		
	$table->endHeaderRow();
	
	foreach($arrFacultySummary as $arrFacultySummaries) {
	 //Set odd even row colour
	$oddOrEven = ($rowcount == 0) ? "even" : "odd";
	$tableRow = array();
	
	$tableRow[] = $arrFacultySummaries['faculty'];
	$tableRow[] = $arrFacultySummaries['countthesis'];
			
	$table2->addRow($tableRow, $oddOrEven);
	
	$rowcount = ($rowcount == 0) ? 1 : 0;
	}

	$mastersTotalCount=$totalCount;
	
	$table3Header = $this->objLanguage->languageText('mod_doraclstudents_summary', 'rimfhe', 'Total number of Doctoral Students For the University');

	$totalDoctoralStuds= '<span style="color:red;font-size:12px;">'.$table3Header.'</span>:&nbsp;&nbsp;<strong>'.$mastersTotalCount.'</strong><br />';

	$table4Header = $this->objLanguage->languageText('mod_doraclstudents_summary', 'rimfhe', 'Total Output for the Unversity');
	
	$totalUnitOutPut = '<span style="color:red;font-size:12px;">'.$table4Header.'</span>:&nbsp;&nbsp;<strong>'.($mastersTotalCount*3).'</strong><br /><br />';
}
else{	echo  '<div class="noRecordsMessage">'.$objLanguage->languageText('mod_rimfhe_norecord', 'rimfhe', 'No record has been entered').'</div>';
	
}
//Header for $table

echo '<span style="color:red;font-size:12px;">'.$tableHeader.'<br /></span>';

echo $table->show();

echo '<br /><br />';

echo '<span style="color:red;font-size:12px;">'.$table2Header.'<br /></span>';

echo $table2->show();

echo '<br /><br />';

echo $totalDoctoralStuds;
echo '<br />';

echo $totalUnitOutPut;
?>
