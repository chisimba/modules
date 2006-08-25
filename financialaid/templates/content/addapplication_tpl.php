<?
$right =& $this->getObject('blocksearchbox','studentenquiry');
$right = $right->show($this->getParam('module','studentenquiry'));

$left =& $this->getObject('financialaidleftblock');
$left = $left->show();

$table =& $this->newObject('htmltable','htmlelements');
$table->cellspacing = 2;
$table->cellpadding = 2;

$stdNum = new textinput('stdnum');
$idNum = new textinput('idNum');
$surname = new textinput('surname');
$firstname = new textinput('firstname');
$gender = new textinput('firstname');
$SACitizen = new dropdown('saCitizen');
$SACitizen->addOption('yes','Yes');
$SACitizen->addOption('no','No');

$marticalSts = new textinput('firstname');
$supportingSelf = new dropdown('firstname');
$supportingSelf->addOption('yes','Yes');
$supportingSelf->addOption('no','No');

$ok= new button('next');
$ok->setToSubmit();
$ok->setValue('Next');

$cancel= new button('cancel');
$cancel->setToSubmit();
$cancel->setValue('Cancel');

$table->startRow();
$table->addCell('Student Number');
$table->addCell($stdNum->show());
$table->endRow();

$table->startRow();
$table->addCell('ID Number');
$table->addCell($idNum->show());
$table->endRow();

$table->startRow();
$table->addCell('Surname');
$table->addCell($surname->show());
$table->endRow();

$table->startRow();
$table->addCell('First Names');
$table->addCell($firstname->show());
$table->endRow();

$table->startRow();
$table->addCell('Are you a SA citizen?');
$table->addCell($SACitizen->show());
$table->endRow();

$table->startRow();
$table->addCell('Have you been supporting yourself financially for a period longer than 3 years?');
$table->addCell($supportingSelf->show());
$table->endRow();

$table->startRow();
$table->addCell($ok->show());
$table->addCell($cancel->show());
$table->endRow();


$content = "<center>".$details."  ".$table->show()."</center>";

$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'addnextofkin')));
$objForm->setDisplayType(2);

$objForm->addToForm($content);


// Create an instance of the css layout class
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(3);
$cssLayout->setLeftColumnContent($left);
$cssLayout->setRightColumnContent($right);
$cssLayout->setMiddleColumnContent($objForm->show());

echo $cssLayout->show();


?>
