<?

$right =& $this->getObject('blocksearchbox','residence');
$right = $right->show($this->getParam('module'));

$left =& $this->getObject('leftblock');
$left = $left->show();

$details = "<p><b>Course Details of ".ucfirst($studentdetails[0]['FSTNAM'])."  ".ucfirst($studentdetails[0]['SURNAM'])."  for $resultsYear </b></p>";
$idnumber = $studentNumber;

$this->financialaid =& $this->getObject('dbfinancialaid');

$details .= "<p><b>".$stdinfo[0]['value']." Student";

$table =& $this->newObject('htmltable','htmlelements');
$table->cellspacing = 2;
$table->cellpadding = 2;

$results = $marksarr;
if(is_array($results)){
	$table->startRow();
	$table->addCell('Course Code');
	$table->addCell('Assessment Type');
	$table->addCell('Assessment Date');
	$table->addCell('Mark');
	$table->endRow();
	
	//$results = $stdinfo;
	$dev = count($results);
	
	foreach($results as $values=>$key){
	$ave +=  $key['FNLMRK'];
	}
	
	foreach($results as $values=>$key){
				//print_r($key);die;
				$table->startRow();
				$table->addCell($key['SBJCDE']);
				$assessmentType = $key['STYTYP'];
				//$this->financialaid->getLookupInfo($mark['assessmentTypeID']);
				$table->addCell($key['STYTYP']);
				$table->addCell($key['YEAR']);
				$table->addCell($key['FNLMRK']);
				$table->endRow();
				
				
				
				
				
	
		
		$table->startRow();
		$table->addCell('<b>'.$values['SBJCDE']);
		$ave = number_format(($ave/$dev),2);
		$table->addCell('<b>Average Mark');
		$table->addCell('  ');
		$table->addCell("<b>$ave");
		$table->endRow();
		
		
	}

		$table->startRow();
		$table->addCell("&nbsp;");
		$table->endRow();
		$table->startRow();
		$table->addCell('<b>Student Averege');
		$table->addCell('  ');
		//$stdave = number_format(($stdave/$j),2);
		//$table->addCell(' ');
		//$table->addCell($stdave);
		$table->endRow();
}

if(is_array($studyYears)){
	$table->startRow();
	$table->addCell('Other years of Study ');
	$links = "";
	foreach($studyYears as $years){
		
		$link = new link();
		$link->href = $this->uri(array('action'=>'results','id'=>$idnumber,'year'=>$years['yearOfStudy']));
		$link->link = $years['yearOfStudy'];
		$links .=$link->show()."  ";

	}

	$table->addCell($links,$width=null, $valign="top", $align=null, $class=null, "colspan=2");
	$table->endRow();		

}

$this->leftNav = $this->getObject('layer','htmlelements');
$this->leftNav->id = "leftnav";
$this->leftNav->str=$left;
echo $this->leftNav->addToLayer();

$this->rightNav = $this->getObject('layer','htmlelements');
$this->rightNav->id = "rightnav";
$this->rightNav->str = $right;
echo $this->rightNav->addToLayer();

$content = "<center>".$details." ".$table->show();

$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'ok','id'=>$idnumber)));
$objForm->setDisplayType(2);

$ok= new button('ok');
$ok->setToSubmit();
$ok->setValue('OK');

$objForm->addToForm($content);
$objForm->addToForm($ok);


$this->contentNav = $this->getObject('layer','htmlelements');
$this->contentNav->id = "content";
$this->contentNav->str = $objForm->show();
//$this->contentNav->height="300px";
echo $this->contentNav->addToLayer();


?>
