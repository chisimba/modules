<?
$right =& $this->getObject('blocksearchbox','residence');
$right = $right->show($this->getParam('module'));

$details = "<p><b>Details of ".ucfirst($studentdetails[0]->FSTNAM)."  ".ucfirst($studentdetails[0]->SURNAM)."</b></p>";
$idnumber = $studentdetails[0]->STDNUM;
$table =& $this->newObject('htmltable','htmlelements');
//print_r($studentdetails);
$left =& $this->getObject('leftblock');
$left = $left->show();

$this->financialaid =& $this->getObject('dbfinancialaid');

//var_dump($student);

if(is_array($stdlookup)){
	for($i = 0;$i < count($stdlookup);$i++){
		$table->startRow();
		$table->addCell($stdlookup[$i]['lookupType'],'70%');
		$table->addCell($stdlookup[$i]['value']);		
		$table->endRow();
	}
}

if(is_array($student)){
	$std = $this->financialaid->getLookupInfo($student[0]['studyTypeID']);
	$table->startRow();
	$table->addCell('Study Type');
	$link = "";
	if($std[0]['value'] === "Part Time"){
		$link = new link();
		$link->href=$this->uri(array('action'=>'parttime','id'=>$this->getParam('id')));
		$link->link="Part Time";
		$table->addCell($link->show());
	}
	else{
		$table->addCell($std[0]['value']);
	}

	
	$table->endRow();
}

if(!is_null($studentaddress))
$values = $studentaddress;
$num = count($studentaddress);
$num =$num -1;

//if(is_array($studentaddress) and count($studentaddress) > 0){
	//var_dump($stdaddress);
	//foreach($studentaddress as $values){
		//print_r($values);
		//die;
		$table->startRow();
		$table->addCell('Street Address');
		$table->addCell($values[$num]->AD1);
		$table->endRow();

		$table->startRow();
		$table->addCell('Suburb');
		$table->addCell($values[$num]->AD2);
		$table->endRow();

		$table->startRow();
		$table->addCell('City');
		$table->addCell($values[$num]->town);
		$table->endRow();

		$table->startRow();
		$table->addCell('Telephone');
		$table->addCell($values[$num]->PHNNUM);
		$table->endRow();

		$table->startRow();
		$table->addCell('Cellphone');
		$table->addCell($values[$num]->CELLNUM);
		$table->endRow();
		
		$table->startRow();
		$table->addCell('email');
		$table->addCell($studentdetails[0]->STDNUM.'@uwc.ac.za');
		$table->endRow();
		
		$contactType = $this->financialaid->getLookupInfo($values['contactTypeID']);
			
		$table->startRow();
		$table->addCell('Address Type');
		//switch
		switch($values[$num]->ADRTYP)
		{
		case 'R':
			$table->addCell("<b>".'Regional'."</b>");
			break;
		case 'F':
			$table->addCell("<b>".'Farm'."</b>");
			break;
		case null:
			$table->addCell("<b>".'Unknown'."</b>");
			break;
		}
		$table->endRow();

	//}
//}
/*
else{
$table->startRow();
$table->addCell('Address Type');
}
*/
/*
$types = array('35','36','37');
//$contactType = $this->financialaid->getLookupInfo($values['contactTypeID']);
//$table->startRow();
//$table->addCell(' ');
$addresstype = $this->getParam('address');
$datype = "";
for($i = 0; $i < 3; $i++){
	if($types[$i] != $addresstype){
		$link = new link();
		$link->href=$this->uri(array('action'=>'info','id'=>$idnumber,'address'=>$types[$i]));
		
		$contactType = $this->financialaid->getLookupInfo($types[$i]);
		$link->link= $contactType[0]->value;
		$datype .="<br>".$link->show()."</br>"; 
	}
}
$table->addCell("<b>".$datype."<b>");
$table->endRow();
*/

$this->leftNav = $this->getObject('layer','htmlelements');
$this->leftNav->id = "leftnav";
$this->leftNav->str=$left;
echo $this->leftNav->addToLayer();

$this->rightNav = $this->getObject('layer','htmlelements');
$this->rightNav->id = "rightnav";
$this->rightNav->str = $right;
echo $this->rightNav->addToLayer();

$link = new link();
if($this->getParam('module') === "financialaid"){
	$link->href=$this->uri(array('action'=>'nextofkin','id'=>$idnumber));
	$link->link="Show Family Member(s)";
}
if($this->getParam('module') === "residence"){
	$link->href=$this->uri(array('action'=>'resapp','id'=>$idnumber));
	$link->link="Click here for Application(s) for Residence";	
}
$content = "<center>".$details." ".$table->show()." ".$link->show()."</center>";

$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'enquiry','id'=>$idnumber)));
$objForm->setDisplayType(2);

$ok= new button('ok');
$ok->setToSubmit();
$ok->setValue('OK');

$objForm->addToForm($content);
$objForm->addToForm($ok);


$this->contentNav = $this->getObject('layer','htmlelements');
$this->contentNav->id = "content";
$this->contentNav->str = $objForm->show();
//$this->contentNav->height="300px";
echo $this->contentNav->addToLayer();

?>
