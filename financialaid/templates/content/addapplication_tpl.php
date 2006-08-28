<?
$right =& $this->getObject('blocksearchbox','studentenquiry');
$right = $right->show($this->getParam('module','studentenquiry'));

$left =& $this->getObject('financialaidleftblock');
$left = $left->show();

$table =& $this->newObject('htmltable','htmlelements');
$table->cellspacing = 2;
$table->cellpadding = 2;

$appNum = new textinput('appnum');
$stdNum = new textinput('stdnum');
$idNum = new textinput('idNum');
$surname = new textinput('surname');
$firstname = new textinput('firstname');
$gender = new dropdown('gender');
$gender->addOption('Male',$objLanguage->languagetext('word_male'));
$gender->addOption('Female',$objLanguage->languagetext('word_female'));

$SACitizen = new dropdown('saCitizen');
$SACitizen->addOption('1',$objLanguage->languagetext('word_yes'));
$SACitizen->addOption('0',$objLanguage->languagetext('word_no'));

$maritalSts = new textinput('maritalsts');
$supportingSelf = new dropdown('supportingself');
$supportingSelf->addOption('1',$objLanguage->languagetext('word_yes'));
$supportingSelf->addOption('0',$objLanguage->languagetext('word_no'));

$addbut= new button('add');
$addbut->setToSubmit();
$addbut->setValue($objLanguage->languagetext('word_add'));

$cancelbut= new button('cancel');
$cancelbut->setToSubmit();
$cancelbut->setValue($objLanguage->languagetext('word_cancel'));


$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_appnumber','financialaid'));
$table->addCell($appNum->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_stdnum2','financialaid'));
$table->addCell($stdNum->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_idnumber','financialaid'));
$table->addCell($idNum->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_surname','financialaid'));
$table->addCell($surname->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_firstnames','financialaid'));
$table->addCell($firstname->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_gender','financialaid'));
$table->addCell($gender->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_sacitizen','financialaid'));
$table->addCell($SACitizen->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_supportingself','financialaid'));
$table->addCell($supportingSelf->show());
$table->endRow();

$table->startRow();
$table->addCell($addbut->show());
$table->addCell($cancelbut->show());
$table->endRow();



$content = "<center>".$details."  ".$table->show()."</center>";

$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'saveapplication')));
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
