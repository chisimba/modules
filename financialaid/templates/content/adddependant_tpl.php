<?
$right =& $this->getObject('blocksearchbox','studentenquiry');
$right = $right->show($this->getParam('module','studentenquiry'));

$left =& $this->getObject('financialaidleftblock');
$left = $left->show();

$table =& $this->newObject('htmltable','htmlelements');
$table->cellspacing = 2;
$table->cellpadding = 2;

$idNum = new textinput('idNum');
$surname = new textinput('surname');
$firstname = new textinput('firstname');
$relationship = new textinput('relationship');
$straddress = new textinput('straddress');
$suburb = new textinput('suburb');
$city = new textinput('city');
$postcode = new textinput('postcode');
$spouse = new textinput('spouse');
$occupation = new textinput('occupation');
$employerDetails = new textinput('employerdetails');
$employerTelNo = new textinput('employertelno');


$relationship = new dropdown('saCitizen');
$relationship->addOption('1','Father');
$relationship->addOption('2','Mother');
$relationship->addOption('3','Guardian');
$relationship->addOption('4','Spouse');

$maritalSts = new dropdown('maritalstatus');
$maritalSts->addOption('1','Married');
$maritalSts->addOption('2','Single');
$maritalSts->addOption('3','Divorced');

$ok= new button('next');
$ok->setToSubmit();
$ok->setValue('Next');

$cancel= new button('cancel');
$cancel->setToSubmit();
$cancel->setValue('Cancel');


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
$table->addCell('Relationship');
$table->addCell($relationship->show());
$table->endRow();

$table->startRow();
$table->addCell('Home Address');
$table->addCell($straddress->show());
$table->endRow();

$table->startRow();
$table->addCell('Suburb');
$table->addCell($suburb->show());
$table->endRow();

$table->startRow();
$table->addCell('City');
$table->addCell($city->show());
$table->endRow();

$table->startRow();
$table->addCell('Postcode');
$table->addCell($postcode->show());
$table->endRow();

$table->startRow();
$table->addCell('Marital Status');
$table->addCell($maritalSts->show());
$table->endRow();

$table->startRow();
$table->addCell('Spouse');
$table->addCell($spouse->show());
$table->endRow();

$table->startRow();
$table->addCell('Occupation');
$table->addCell($occupation->show());
$table->endRow();

$table->startRow();
$table->addCell('Employer\'s Details');
$table->addCell($employerDetails->show());
$table->endRow();

$table->startRow();
$table->addCell('Employer\'s Tel Number');
$table->addCell($employerTelNo->show());
$table->endRow();



$table->startRow();
$table->addCell($ok->show());
$table->addCell($cancel->show());
$table->endRow();


$content = "<center>".$details."  ".$table->show()."</center>";

$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'adddependant')));
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
