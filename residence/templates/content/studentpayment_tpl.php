<?
$cssLayout2 = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout2->setNumColumns(1);

$right =& $this->getObject('blocksearchbox','residence');
$right = $right->show($this->getParam('module'));

$left =& $this->getObject('leftblock');
$left = $left->show();

$this->financialaid =& $this->getObject('dbfinancialaid');
$table1 =& $this->newObject('htmltable','htmlelements');
$table =& $this->newObject('htmltable','htmlelements');

$table1->cellspacing = 2;
$table1->cellpadding = 2;

$table->cellspacing = 2;
$table->cellpadding = 2;

$details = "<p><b>Payment Details of ".ucfirst($stdinfo[0]->FSTNAM)."  ".ucfirst($stdinfo[0]->SURNAM)." for $paymentYear</b></p>";
$idnumber = $stdinfo[0]->STDNUM;

$payments 	= $this->financialaid->getAccountInformation('STDNUM',$idnumber);
//$payments 	= $this->financialaid->getAccountInformationHistory('STDNUM',$idnumber);
$loan = $this->financialaid->getAccountInformationHistory('STDNUM',$idnumber);

if($payments==null)
{
$payments 	= $this->financialaid->getAccountInformationHistory('STDNUM',$idnumber);
}else{
$payments 	= $this->financialaid->getAccountInformation('STDNUM',$idnumber);
}

/*echo '<pre>'; 
print_r($payments);
die;*/

/*
  			[AMT] => -300
            [DOCNUM] => ER044563
            [DOCSRC] => ELECTRONIC PAYMENT
            [DTEYMD] => 20041213
            [STDNUM] => 2512237
            [TRNCDE] => 47
*/
/*
if(is_array($account)){
	foreach($account as $acc=>$accinfo){
		$previousyear = $acc['accountYear'] - 1;
		$thisyear = $acc['accountYear'];
		$table1->startRow();	
		$table1->addCell("<b>Amount Carried Over from : $previousyear","100%");
		$table1->addCell(number_format($acc['amountCarriedOver'],2));
		$table1->endRow();
	
		$table1->startRow();
		$table1->addCell(' &nbsp;');
		$table1->endRow();

		$table1->startRow();
		$table1->addCell("<b>Balance for : $thisyear");
		$table1->addCell(number_format($acc['currentAmount'],2));
		$table1->endRow();
	}

	$table1->startRow();
	$table1->addCell(' &nbsp;');
	$table1->endRow();
	
}

//$this is only needed in the financial aid module.
$table1->startRow();
$table1->addCell(' &nbsp;');
$newPayment = new link();
$newPayment->href= $this->uri(array('action'=>'newpayment','id'=>$idnumber));
$newPayment->link = 'New Payment';

$sponsor = new link();
$sponsor->href=$this->uri(array('action'=>'sponsor','id'=>$idnumber));
$sponsor->link = "Show Sponsor(s)";
$table1->addCell($newPayment->show());
$table1->addCell($sponsor->show());
$table1->endRow();
*/

if(is_array($payments)){
	$table->startHeaderRow();
	$table->addHeaderCell('Date Of Payment ');
	$table->addHeaderCell('Amount Paid  &nbsp;&nbsp;&nbsp;  ');
	$table->addHeaderCell('Paid By         ');
	$table->endHeaderRow();
	$total = 0;
	foreach($payments as $pay=>$info){
	//echo '<pre>';
	//print_r($info->DOCSRC);
	//die;
		$table->startRow();
		$table->addCell($info->DTEYMD);
		$table->addCell(number_format($info->AMT,2));
		$table->addCell($info->DOCSRC);
		$table->endRow();
		$total += $info->AMT;		
		
	}
	$table->startRow();
	$table->addCell("<b>Total Paid</b>");
	$table->addCell(number_format($total,2));
	$table->endRow();
}

$table->startRow();
$table->addCell(' ');
$table->endRow();

$table->startRow();
$table->addCell(" ");
$table->addCell('');
$table->endRow();

if(is_array($studyYears)){
	$table->startRow();
	$table->addCell('Other years of Study ');
	$links = "";
	foreach($studyYears as $years){
		
		$link = new link();
		$link->href = $this->uri(array('action'=>'payment','id'=>$idnumber,'year'=>$years['yearOfStudy']));
		$link->link = $years['yearOfStudy'];
		$links .=$link->show()."  ";

	}

	$table->addCell($links,$width=null, $valign="top", $align=null, $class=null, "colspan=2");
	$table->endRow();		

}



$application = new link();
if($this->getParam('module') === "financialaid"){
	$application->href=$this->uri(array('action'=>'finapplication','id'=>$idnumber));
	$application->link="Click here for Financial Aid Application(s)";
}
else{
	$application->href=$this->uri(array('action'=>'ok'));
	$application->link="&nbsp;";
}
$this->leftNav = $this->getObject('layer','htmlelements');
$this->leftNav->id = "leftnav";
$this->leftNav->str=$left;
//echo $this->leftNav->addToLayer();

$this->rightNav = $this->getObject('layer','htmlelements');
$this->rightNav->id = "rightnav";
$this->rightNav->str = $right;
//echo $this->rightNav->addToLayer();

$leftSideColumn2 = $right;//$right

// Add Left column
$cssLayout2->setLeftColumnContent($leftSideColumn2);
//Output the content to the page



$content = $details." ".$table1->show().$table->show()." ".$application->show();

$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'ok','id'=>$idnumber)));
$objForm->setDisplayType(2);

$ok= new button('ok');
$ok->setToSubmit();
$ok->setValue('OK');

$objForm->addToForm($content);
$objForm->addToForm($ok);


$this->contentNav = $this->getObject('layer','htmlelements');
$this->contentNav->id = "content";
$this->contentNav->str = $objForm->show();
$this->contentNav->height="7400px";

$cssLayout2->setMiddleColumnContent($objForm->show());
//echo $this->contentNav->addToLayer();

echo $cssLayout2->show();



?>
