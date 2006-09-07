<?

$right =& $this->getObject('blocksearchbox','residence');
$right = $right->show($this->getParam('module'));

$left =& $this->getObject('leftblock');
$left = $left->show();

//$this->financialaid =& $this->getObject('dbfinancialaid','studentfinance');
$table =& $this->newObject('htmltable','htmlelements');

$table->cellspacing = 2;
$table->cellpadding = 2;

$details = "<p><b>New Payment for ".ucfirst($stdinfo[0]['name'])."  ".ucfirst($stdinfo[0]['surname'])."</b></p>";
$idnumber = $stdinfo[0]['personID'];
//echo $details;

$amount = new textinput('amount');
$dateofpayment = new textinput('dateofpayment',date("Y-m-d"));
$paymentby = new textinput('paymentMadeBy');

$ok= new button('ok');
$ok->setToSubmit();
$ok->setValue('OK');

$cancel= new button('cancel');
$cancel->setToSubmit();
$cancel->setValue('cancel');

$table->startRow();
$table->addCell('Amount',"70%");
$table->addCell($amount->show());
$table->endRow();

$table->startRow();
$table->addCell('Date Of Payment');
$table->addCell($dateofpayment->show());
$table->endRow();

$table->startRow();
$table->addCell('Payment Made By');
$table->addCell($paymentby->show());
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
$objForm->setAction($this->uri(array('action'=>'pay','id'=>$idnumber)));
$objForm->setDisplayType(2);

$objForm->addToForm($content);
//$objForm->addToForm($ok);

$this->contentNav = $this->getObject('layer','htmlelements');
$this->contentNav->id = "content";
$this->contentNav->str = $objForm->show();
//$this->contentNav->height="300px";
echo $this->contentNav->addToLayer();


?>
