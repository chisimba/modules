<?
$right =& $this->getObject('blocksearchbox','residence');
$right = $right->show($this->getParam('module'));

$left =& $this->getObject('leftblock');
$left = $left->show();

$this->financialaid =& $this->getObject('dbfinancialaid');
$table =& $this->newObject('htmltable','htmlelements');
$table->cellspacing = 2;
$table->cellpadding = 2; 

$details = "<p><b>Assign Sponsor to ".ucfirst($stdinfo[0]['name'])."  ".ucfirst($stdinfo[0]['surname'])." </p>";
$idnumber = $stdinfo[0]['personID'];


$list = "";
if(is_array($sponsorlist)){
	$drop = new dropdown('sponsor');
	$drop->addOption('  ','  ');
	foreach($sponsorlist as $sponsor){
		$drop->addOption($sponsor['personID'],$sponsor['name']."  ".$sponsor['surname']);
	}
$list = $drop->show();
}

$amount = new textinput('amount');
$sponsoryear = new textinput('sponsorshipyear',date("Y"));

$ok= new button('assignsponsor');
$ok->setToSubmit();
$ok->setValue('OK');

$cancel= new button('cancel');
$cancel->setToSubmit();
$cancel->setValue('cancel');

$table->startRow();
$table->addCell('Sponsor');
$table->addCell($list);
$table->endRow();

$table->startRow();
$table->addCell('Amount');
$table->addCell($amount->show());
$table->endRow();

$table->startRow();
$table->addCell('Year');
$table->addCell($sponsoryear->show());
$table->endRow();

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

$content = "<center>".$details."  ".$table->show();

$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'sponsor','id'=>$idnumber)));
$objForm->setDisplayType(2);

$objForm->addToForm($content);

$this->contentNav = $this->getObject('layer','htmlelements');
$this->contentNav->id = "content";
$this->contentNav->str = $objForm->show();
//$this->contentNav->height="300px";
echo $this->contentNav->addToLayer();


?>
