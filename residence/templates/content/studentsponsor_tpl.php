<?
$right =& $this->getObject('blocksearchbox','residence');
$right = $right->show($this->getParam('module'));

$left =& $this->getObject('leftblock');
$left = $left->show();

$details = "<p><b> ".ucfirst($stdinfo[0]['name'])."  ".ucfirst($stdinfo[0]['surname'])." Sponsor(s)  for $sponsorYear </p>";
$idnumber = $stdinfo[0]['personID'];
$this->financialaid =& $this->getObject('dbfinancialaid');

//echo $details;

$table =& $this->newObject('htmltable','htmlelements');
$table->cellspacing = 2;
$table->cellpadding = 2;

//var_dump($sponsors);
$objForm= "";
$ok = "";
$add = false;
//var_dump($studyYears);
if($sponsors === false){
	$details = "<p><b> ".ucfirst($stdinfo[0]['name'])."  ".ucfirst($stdinfo[0]['surname'])." does not have any sponsor(s) for $sponsorYear </p>";
	$details .= "<p>Do you want to assign sponsor(s) to this student?</p></b>";
	$table->startRow();
	$ok= new button('add_ok');
	$ok->setToSubmit();
	$ok->setValue('OK');
	
}
else{

	if(is_array($sponsors)){
		$table->startHeaderRow();
		$table->addHeaderCell('Sponsor');
		$table->addHeaderCell('Amount Sponsored');
		$table->addHeaderCell('Sponsorship Year');
		$table->endHeaderRow();
		
		$total = 0;

		foreach($sponsors as $values){
			$table->startRow();
			$sponsor = $this->financialaid->getSponsorName($values['sponsorPersonid']);
			$table->addCell($sponsor);
			$table->addCell(number_format($values['amountSponsored'],2));
			$table->addCell($values['yearOfSponsorship']);
			$table->endRow();
			$total += $values['amountSponsored'];
		}
		
		$table->startRow();
		$table->addCell('<b>TOTAL SPONSORSHIP');
		$table->addCell("<b>".number_format($total,2));
		$table->endRow();

	}
	
	$objForm = new form('theform');
	$objForm->setAction($this->uri(array('action'=>'sponsor','id'=>$idnumber)));
	
	$content = "<center>".$details."  ".$table->show();
	$objForm->addToForm($content);

	$ok= new button('ok');
	$ok->setToSubmit();
	$ok->setValue('OK');

	$add= new button('add_ok');
	$add->setToSubmit();
	$add->setValue('Assign New Sponsor');
	
	$objForm->addToForm($ok);
	
}


if(is_array($studyYears)){
	$table->startRow();
	$table->addCell('Other years of Study ');
	$links = "";
	foreach($studyYears as $years){
		
		$link = new link();
		$link->href = $this->uri(array('action'=>'sponsor','id'=>$idnumber,'year'=>$years['yearOfStudy']));
		$link->link = $years['yearOfStudy'];
		$links .=$link->show()."  ";

	}

	$table->addCell($links,$width=null, $valign="top", $align=null, $class=null, "colspan=2");
	$table->endRow();		

}

$cancel= new button('cancel');
$cancel->setToSubmit();
$cancel->setValue('cancel');
	
$table->startRow();
$table->addCell($ok->show());
if($add)$table->addCell($add->show());
$table->addCell($cancel->show());
$table->endRow();

$content = "<center>".$details." ".$table->show();

$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'sponsor','id'=>$idnumber)));
$objForm->setDisplayType(2);

$objForm->addToForm($content);

$this->leftNav = $this->getObject('layer','htmlelements');
$this->leftNav->id = "leftnav";
$this->leftNav->str=$left;
echo $this->leftNav->addToLayer();

$this->rightNav = $this->getObject('layer','htmlelements');
$this->rightNav->id = "rightnav";
$this->rightNav->str = $right;
echo $this->rightNav->addToLayer();

$this->contentNav = $this->getObject('layer','htmlelements');
$this->contentNav->id = "content";
$this->contentNav->str = $objForm->show();
//$this->contentNav->height="300px";
echo $this->contentNav->addToLayer();

?>
