<?
$right =& $this->getObject('blocksearchbox','residence');
$right = $right->show($this->getParam('module'));

$left =& $this->getObject('leftblock');
$left = $left->show();

$table =& $this->newObject('htmltable','htmlelements');

$details = "<p><b>Students Sponsored by $sponsorname</b></p>";
//$details = $sponsorname;
if(is_array($sponsoredStudents)){
	$table->startHeaderRow();
	$table->addHeaderCell('Student Name ');
	$table->addHeaderCell('Amount');
	$table->addHeaderCell('Year');
	$table->endHeaderRow();
	
	foreach($sponsoredStudents as $value){
		$table->startRow();
		$table->addCell($value['name']." ".$value['surname']);
		$table->addCell($value['amountSponsored']);
		$table->addCell($value['yearOfSponsorship']);
		$table->endRow();		
			
	}
}



$content = "<center>".$details." ".$table->show();

$this->leftNav = $this->getObject('layer','htmlelements');
$this->leftNav->id = "leftnav";
$this->leftNav->str=$left;
echo $this->leftNav->addToLayer();

$this->rightNav = $this->getObject('layer','htmlelements');
$this->rightNav->id = "rightnav";
$this->rightNav->str = $right;
echo $this->rightNav->addToLayer();

$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'sponsorlist')));
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

/*$this->contentNav = $this->getObject('layer','htmlelements');
$this->contentNav->id = "content";
$this->contentNav->str = $content;
//$this->contentNav->height="300px";
echo $this->contentNav->addToLayer();
*/
?>
