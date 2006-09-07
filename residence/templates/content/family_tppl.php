<?
$right =& $this->getObject('blocksearchbox','residence');
$right = $right->show($this->getParam('module'));

$left =& $this->getObject('leftblock');
$left = $left->show();

$details = "<center><p><b>Add Family Member</b></p>";

$table =& $this->newObject('htmltable','htmlelements');

$idnumber = new textinput('spIdnumber');
$name = new textinput('spName');
$surname = new textinput('spSurname');
$streetAddress = new textinput('spStreetAddress');
$suburb = new textinput('spSuburb');
$town = new textinput('spTown');
$code = new textinput('spCode');
$telephone = new textinput('spTelephone');
$cellphone = new textinput('spCellphone');
$email = new textinput('spEmail');

$table->startRow();
$table->addCell('Id Number',"70%");
$table->addCell($idnumber->show());
$table->endRow();

$table->startRow();
$table->addCell('Name',"70%");
$table->addCell($name->show());
$table->endRow();

$table->startRow();
$table->addCell('Surname');
$table->addCell($surname->show());
$table->endRow();

$table->startRow();
$table->addCell('Street Address');
$table->addCell($streetAddress->show());
$table->endRow();

$table->startRow();
$table->addCell('Suburb');
$table->addCell($suburb->show());
$table->endRow();

$table->startRow();
$table->addCell('Town');
$table->addCell($town->show());
$table->endRow();

$table->startRow();
$table->addCell('Code');
$table->addCell($code->show());
$table->endRow();

$table->startRow();
$table->addCell('Telephone');
$table->addCell($telephone->show());
$table->endRow();

$table->startRow();
$table->addCell('Cellphone');
$table->addCell($cellphone->show());
$table->endRow();

$table->startRow();
$table->addCell('Email');
$table->addCell($email->show());
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
$objForm->setAction($this->uri(array('action'=>'newsponsor')));
$objForm->setDisplayType(2);

$objForm->addToForm($content);

$this->contentNav = $this->getObject('layer','htmlelements');
$this->contentNav->id = "content";
$this->contentNav->str = $objForm->show();
//$this->contentNav->height="300px";
echo $this->contentNav->addToLayer();


?>
