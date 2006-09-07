<?
$right =& $this->getObject('blocksearchbox','residence');
$right = $right->show($this->getParam('module'));

$left =& $this->getObject('leftblock');
$left = $left->show();

$details = "<p><b>Search By Average Marks Range</p></b>";
$table =& $this->newObject('htmltable','htmlelements');

$years = new dropdown('resultyear');
$years->addOption('','');
$date = date("Y");
$start = date("Y") - 15;
for($i = 0; $i < 16; $i ++){
	$years->addOption($start,$start);
	$start++;
}

$mark1 = new textinput('mark1',null,null,5);
$mark2 = new textinput('mark2',null,null,5);

$table->startRow();
$table->addCell('Marks Between');
$table->addCell($mark1->show());
$table->addCell('and');
$table->addCell($mark2->show());
$table->endRow();

$table->startRow();
$table->addCell('year');
$table->addCell($years->show());
$table->endRow();

$ok= new button('ok');
$ok->setToSubmit();
$ok->setValue('OK');

$cancel= new button('cancel');
$cancel->setToSubmit();
$cancel->setValue('cancel');

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
$objForm->setAction($this->uri(array('action'=>'markrange')));
$objForm->setDisplayType(2);

$objForm->addToForm($content);

$this->contentNav = $this->getObject('layer','htmlelements');
$this->contentNav->id = "content";
$this->contentNav->str = $objForm->show();
//$this->contentNav->height="300px";
echo $this->contentNav->addToLayer();







?>
