<?

$right =& $this->getObject('blocksearchbox','residence');
$this->objUser =& $this->getObject('user','security');
$right = $right->show($this->getParam('module'));

$left =& $this->getObject('leftblock');
$left = $left->show();
$table =& $this->getObject('sorttable','htmlelements');
$table->width = '100%';
$table->cellpadding = 5;
$table->cellspacing = 2;


$title = $this->financialaid->getLookupInfo($stdinfo[0]['titleID']);

//$details = "<p><b>Family Member(s) of ".ucfirst($title[0]['value'])."  ".ucfirst($stdinfo[0]['name'])." ".ucfirst($stdinfo[0]['surname'])."</b></p>";

$header = & $this->newObject('htmlheading','htmlelements'); 
$header->str = "Family Member(s) of ".ucfirst($title[0]['value'])."  ".ucfirst($stdinfo[0]['name'])." ".ucfirst($stdinfo[0]['surname']);

$addnew = new link();
$addnew->href = $this->uri(array('action'=>'newfamily','id'=>$stdinfo[0]['personID']));
$addnew->link = "New Entry ";


if(is_array($stdfamily)){

	$table->startHeaderRow();
	//$table->addHeaderCell('Title');
	$table->addHeaderCell('Name');
	//$table->addHeaderCell('Surname');
	$table->addHeaderCell('Relationship');
	$table->addHeaderCell('Monthly Gross Income');
	$table->addHeaderCell( ' ');
	$table->endHeaderRow();	
	$id = $this->getParam('id');
	foreach($stdfamily as $family){
		
		$title = $this->financialaid->getLookupInfo($family['titleID']);
		$relationship = $this->financialaid->getLookupInfo($family['personTypeID']);
		$income = $this->financialaid->getLookupInfo($family['monthlygross']);
		
		$edit = new link();
		$edit->href=$this->uri(array('action'=>'editfamily', 'familyid'=>$family['personID'],'id'=>$id));
		$edit->link = "edit";

		$table->startRow();
		$table->addCell($title[0]['value']." ".$family['name']." ".$family['surname']);
		//$table->addCell();
		//$table->addCell();
		$table->addCell($relationship[0]['value']);
		$table->addCell($income[0]['value']);
		$table->addCell($edit->show());
		$table->endRow();		

	}
	
}
	
$ok= new button('ok');
$ok->setToSubmit();
$ok->setValue('OK');

$cancel= new button('cancel');
$cancel->setToSubmit();
$cancel->setValue('Cancel');

$table->startRow();
$table->addCell($ok->show());
$table->addCell($cancel->show());
$table->endRow();

$this->leftNav = $this->getObject('layer','htmlelements');
$this->leftNav->id = "leftnav";
$this->leftNav->str=$left;
echo $this->leftNav->addToLayer();
		
$this->rightNav = $this->getObject('layer','htmlelements');
$this->rightNav->id = "rightnav";
$this->rightNav->str = $right;
echo $this->rightNav->addToLayer();

$content = $header->show()."  ".$addnew->show().$table->show();

$objForm = new form('theform');
$objForm->setAction($this->uri(array()));
$objForm->setDisplayType(2);

$objForm->addToForm($content);

$this->contentNav = $this->getObject('layer','htmlelements');
$this->contentNav->id = "content";
$this->contentNav->str =$objForm->show();
$this->contentNav->height="300px";
echo $this->contentNav->addToLayer();


?>
