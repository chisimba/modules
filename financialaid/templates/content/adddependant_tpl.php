<?
$right =& $this->getObject('blocksearchbox','studentenquiry');
$right = $right->show($this->getParam('module','studentenquiry'));

$left =& $this->getObject('financialaidleftblock');
$left = $left->show();

$table =& $this->newObject('htmltable','htmlelements');
$table->cellspacing = 2;
$table->cellpadding = 2;
$appnum = $this->getParam('appnum');

$appnumfield = new textinput("appnum", $appnum,  "hidden", NULL);
$firstname = new textinput('firstname');
$relationship = new textinput('relationship');
$dependantReason = new textinput('dependantreason');
$hasIncome = new dropdown('hasIncome');
$hasIncome->addOption('1', $objLanguage->languagetext('word_yes'));
$hasIncome->addOption('0', $objLanguage->languagetext('word_no'));
$incomeType = new textinput('incomeType');
$incomeAmount = new textinput('incomeAmount');
$category = new textinput('category');

$addbut= new button('add');
$addbut->setToSubmit();
$addbut->setValue($objLanguage->languagetext('word_add'));

$cancelbut= new button('cancel');
$cancelbut->setToSubmit();
$cancelbut->setValue($objLanguage->languagetext('word_cancel'));


$table->startRow();
$table->addCell($appnumfield->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_firstname','financialaid'));
$table->addCell($firstname->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_howrelated','financialaid'));
$table->addCell($relationship->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_dependantreason','financialaid'));
$table->addCell($dependantReason->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_category','financialaid'));
$table->addCell($category->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_hasincome','financialaid'));
$table->addCell($hasIncome->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_incometype','financialaid'));
$table->addCell($incomeType->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_incomeamount','financialaid'));
$table->addCell($incomeAmount->show());
$table->endRow();


$table->startRow();
$table->addCell($addbut->show());
$table->addCell($cancelbut->show());
$table->endRow();



$content = "<center>".$details."  ".$table->show()."</center>";

$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'savedependant')));
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
