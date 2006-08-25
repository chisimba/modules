<?

//var_dump($employment);
$right =& $this->getObject('blocksearchbox');
$right = $right->show($this->getParam('module'));


$left =& $this->getObject('leftblock');
$left = $left->show();

$this->leftNav = $this->getObject('layer','htmlelements');
$this->leftNav->id = "leftnav";
$this->leftNav->str=$left;
echo $this->leftNav->addToLayer();

$this->rightNav = $this->getObject('layer','htmlelements');
$this->rightNav->id = "rightnav";
$this->rightNav->str = $right;
echo $this->rightNav->addToLayer();


$ok= new button('ok');
$ok->setToSubmit();
$ok->setValue('OK');

$cancel= new button('cancel');
$cancel->setToSubmit();
$cancel->setValue('cancel');

$title = $this->studentinfo->getLookupInfo($stdinfo[0]['titleID']);

$header = & $this->newObject('htmlheading','htmlelements');
$header1 = & $this->newObject('htmlheading','htmlelements');

$header->str = "Employment Details of ".ucfirst($title[0]['value'])."  ".ucfirst($stdinfo[0]['name'])."  ".ucfirst($stdinfo[0]['surname']);

$table =& $this->newObject('htmltable','htmlelements');
$table1 =& $this->newObject('htmltable','htmlelements');

$table->cellpadding=2;
$table->cellspacing=2;

$employertel = new textinput('txtEmployertel');
$employerdetails = new textarea('txtEmployerdetails');
$jobtitle = new textinput('txtJobtitle');
$content = "";

if(is_array($employment)){
	
	$table->startHeaderRow();	
	$table->addHeaderCell('Employment Year');
	$table->addHeaderCell('Job Title');
	$table->addHeaderCell("Emplyer's Telephone");
	$table->endHeaderRow();
	
	foreach($employment as $emp){
		$table->startRow();
		$table->addCell($emp['employmentyear']);
		$table->addCell($emp['jobtitle']);
		$table->addCell($emp['employertelephone']);
		$table->endRow();
		//echo $emp." <br>";
		//var_dump($employment);
	}

	$table->startRow();
	$table->addCell($ok->show());
	$table->addCell($cancel->show());
	$table->endRow();

	$objForm = new form('theform1');
	$objForm->setAction($this->uri(array('action'=>'info','id'=>$this->getParam('id'))));
	$objForm->setDisplayType(2);

	$objForm->addToForm($table->show());
	$content = $objForm->show();
}


$header1->str = "Add New Employment";

$table1->startRow();
$table1->addCell('Job Title');
$table1->addCell($jobtitle->show());
$table1->endRow();

$table1->startRow();
$table1->addCell('Employer Details');
$table1->addCell($employerdetails->show());
$table1->endRow();


$table1->startRow();
$table1->addCell('Employer Telephone');
$table1->addCell($employertel->show());
$table1->endRow();

/*
$ok= new button('ok');
$ok->setToSubmit();
$ok->setValue('OK');

$cancel= new button('cancel');
$cancel->setToSubmit();
$cancel->setValue('cancel');
*/
$table1->startRow();
$table1->addCell($ok->show());
$table1->addCell($cancel->show());
$table1->endRow();



$objForm1 = new form('theform');
$objForm1->setAction($this->uri(array('action'=>'parttime','id'=>$this->getParam('id'))));
$objForm1->setDisplayType(2);

$objForm1->addToForm($table1);

$content = $header->show()."  ".$content."  ".$header1->show()."  ".$objForm1->show();

$this->contentNav = $this->getObject('layer','htmlelements');
$this->contentNav->id = "content";
$this->contentNav->str = $content;
//$this->contentNav->height="300px";
echo $this->contentNav->addToLayer();

?>
