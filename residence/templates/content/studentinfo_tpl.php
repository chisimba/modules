<?
$cssLayout2 = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout2->setNumColumns(3);

$right =& $this->getObject('blocksearchbox','residence');
$right = $right->show();

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
//echo $this->leftNav->addToLayer();


$this->rightNav = $this->getObject('layer','htmlelements');
$this->rightNav->id = "rightnav";
$this->rightNav->str = $right;
//echo $this->rightNav->addToLayer();


$leftSideColumn2 = $right;//$right

// Add Left column
$cssLayout2->setRightColumnContent($leftSideColumn2);
//Output the content to the page
//echo $cssLayout2->show();


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
$objForm->setAction($this->uri(array('action'=>'ok','id'=>$idnumber)));
$objForm->setDisplayType(2);

$ok= new button('ok');
$ok->setToSubmit();
$ok->setValue('OK');

$objForm->addToForm($content);
$objForm->addToForm($ok);

$appTabBox = & $this->newObject('tabbox','financialaid');
$appTabBox->tabName = 'Studentinfo';

$studentNumber = $studentdetails[0]->STDNUM;



$tabletab =& $this->newObject('htmltable','htmlelements');
		//student number
				$tabletab->startRow($rowClass);
				$tabletab->addCell('<p>'.'Student Id'.'</p>');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->STDNUM);
				$tabletab->endRow($rowClass);
		
	
		//ACALNG name
				$tabletab->startRow($rowClass);
				$tabletab->addCell('<p>'.'ACALNG'.'</p>');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->ACALNG);
				$tabletab->endRow($rowClass);
		//ATRNY name
				$tabletab->startRow($rowClass);
				$tabletab->addCell('<p>'.'ATRNY'.'</p>');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->ATRNY);
				$tabletab->endRow($rowClass);
		
		//HMELNG name
				$tabletab->startRow($rowClass);
				$tabletab->addCell('<p>'.'HMELNG'.'</p>');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->HMELNG);
				$tabletab->endRow($rowClass);

		//PRVREGYR name
				$tabletab->startRow($rowClass);
				$tabletab->addCell('<p>'.'PRVREGYR'.'</p>');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->PRVREGYR);
				$tabletab->endRow($rowClass);
		//STDSTS name
				$tabletab->startRow($rowClass);
				$tabletab->addCell('<p>'.'STDSTS'.'</p>');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->STDSTS);
				$tabletab->endRow($rowClass);
		//STDACCBAL name
				$tabletab->startRow($rowClass);
				$tabletab->addCell('<p>'.'STDACCBAL'.'</p>');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->STDACCBAL);
				$tabletab->endRow($rowClass);
		//STDACCSTS name
				$tabletab->startRow($rowClass);
				$tabletab->addCell('<p>'.'STDACCSTS'.'</p>');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->STDACCSTS);
				$tabletab->endRow($rowClass);
		//MARSTS name
				$tabletab->startRow($rowClass);
				$tabletab->addCell('<p>'.'MARSTS'.'</p>');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->MARSTS);
				$tabletab->endRow($rowClass);
		//ATRNY name
				$tabletab->startRow($rowClass);
				$tabletab->addCell('<p>'.'ATRNY'.'</p>');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->ATRNY);
				$tabletab->endRow($rowClass);
		//DBTPCKDTE name
				$tabletab->startRow($rowClass);
				$tabletab->addCell('<p>'.'DBTPCKDTE'.'</p>');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->DBTPCKDTE);
				$tabletab->endRow($rowClass);
		//DBTPCKSTS name
				$tabletab->startRow($rowClass);
				$tabletab->addCell('<p>'.'DBTPCKSTS'.'</p>');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->DBTPCKSTS);
				$tabletab->endRow($rowClass);
		//DRVCDE name
				$tabletab->startRow($rowClass);
				$tabletab->addCell('<p>'.'DRVCDE'.'</p>');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->DRVCDE);
				$tabletab->endRow($rowClass);
		//IGNRHMS name
				$tabletab->startRow($rowClass);
				$tabletab->addCell('<p>'.'IGNRHMS'.'</p>');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->IGNRHMS);
				$tabletab->endRow($rowClass);
		//DRVCDE name
				$tabletab->startRow($rowClass);
				$tabletab->addCell('<p>'.'DRVCDE'.'</p>');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->DRVCDE);
				$tabletab->endRow($rowClass);
		//INI name
				$tabletab->startRow($rowClass);
				$tabletab->addCell('<p>'.'INI'.'</p>');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->INI);
				$tabletab->endRow($rowClass);
		//LNKNUM name
				$tabletab->startRow($rowClass);
				$tabletab->addCell('<p>'.'LNKNUM'.'</p>');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->LNKNUM);
				$tabletab->endRow($rowClass);
		//VDATE name
				$tabletab->startRow($rowClass);
				$tabletab->addCell('<p>'.'VDATE'.'</p>');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->VDATE);
				$tabletab->endRow($rowClass);
//-----------------------------------------------end student info
$tabletaba =& $this->newObject('htmltable','htmlelements');
$payments 	= $this->financialaid->getAccountInformation('STDNUM',$studentNumber);
//$payments 	= $this->financialaid->getAccountInformationHistory('STDNUM',$idnumber);
$loan = $this->financialaid->getAccountInformationHistory('STDNUM',$studentNumber);

if($payments==null)
{
$payments 	= $this->financialaid->getAccountInformationHistory('STDNUM',$studentNumber);
}else{
$payments 	= $this->financialaid->getAccountInformation('STDNUM',$studentNumber);
}
if(is_array($payments)){
	$tabletaba->startHeaderRow();
	$tabletaba->addHeaderCell('Date Of Payment ');
	$tabletaba->addHeaderCell('Amount Paid  &nbsp;&nbsp;&nbsp;  ');
	$tabletaba->addHeaderCell('Paid By         ');
	$tabletaba->endHeaderRow();
	$total = 0;
	foreach($payments as $pay=>$info){
	//echo '<pre>';
	//print_r($info->DOCSRC);
	//die;
		$tabletaba->startRow();
		$tabletaba->addCell($info->DTEYMD);
		$tabletaba->addCell(number_format($info->AMT,2));
		$tabletaba->addCell($info->DOCSRC);
		$tabletaba->endRow();
		$total += $info->AMT;		
		
	}
	$tabletaba->startRow();
	$tabletaba->addCell("<b>Total Paid</b>");
	$tabletaba->addCell(number_format($total,2));
	$tabletaba->endRow();
}

$tabletaba->startRow();
$tabletaba->addCell(' ');
$tabletaba->endRow();

$tabletaba->startRow();
$tabletaba->addCell(" ");
$tabletaba->addCell('');
$tabletaba->endRow();
//----------------------------------------------------student payment 

$tabletabb =& $this->newObject('htmltable','htmlelements');
$results = $this->financialaid->getSTCRS($studentNumber);
 $marksarr= $this->financialaid->getMarks('STDNUM',$studentNumber);
$results= $marksarr ;
if(is_array($results)){
	$tabletabb->startRow();
	$tabletabb->addCell('Course Code');
	$tabletabb->addCell('Assessment Type');
	$tabletabb->addCell('Assessment Date');
	$tabletabb->addCell('Mark');
	$tabletabb->endRow();
	
	//$results = $stdinfo;
	$dev = count($results);
	
	foreach($results as $values=>$key){
	$ave +=  $key->FNLMRK;
	}
	
	foreach($results as $values=>$key){
				//print_r($key);die;
				$tabletabb->startRow();
				$tabletabb->addCell($key->SBJCDE);
				$assessmentType = $key->STYTYP;
				//$this->financialaid->getLookupInfo($mark['assessmentTypeID']);
				$tabletabb->addCell($key->STYTYP);
				$tabletabb->addCell($key->YEAR);
				$tabletabb->addCell($key->FNLMRK);
				$tabletabb->endRow();
				
				
				
				
				
	
		
		$tabletabb->startRow();
		$tabletabb->addCell('<b>'.$values->SBJCDE.'</b>');
		$ave = number_format(($ave/$dev),2);
		$tabletabb->addCell('<b>Average Mark'.'</b>');
		$tabletabb->addCell('  ');
		$tabletabb->addCell('<b>'.$ave.'</b>');
		$tabletabb->endRow();
		
		
	}

		$tabletabb->startRow();
		$tabletabb->addCell("&nbsp;");
		$tabletabb->endRow();
		$tabletabb->startRow();
		$tabletabb->addCell('<b>'.'Student Averege'.'</b>');
		$tabletabb->addCell('  ');
		//$stdave = number_format(($stdave/$j),2);
		//$table->addCell(' ');
		//$table->addCell($stdave);
		$tabletabb->endRow();
}else{}



$appTabBox->addTab('Student Info', 'Student Info', $Display=$tabletab->show());
$appTabBox->addTab('Payment Info', 'Payment Info', $Display=$tabletaba->show());
$appTabBox->addTab('Course Info', 'Course Info', $Display=$tabletabb->show());
$appTabBox->addTab('Residence Info', 'Residence Info', $Display=$tabletabb->show());


$cssLayout2->setMiddleColumnContent($objForm->show().'<p>'.$appTabBox->show().'</p>');


echo $cssLayout2->show();



//echo $this->contentNav->addToLayer();

?>
